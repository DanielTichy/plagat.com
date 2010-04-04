<?php
/**
 * wafl.php
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Models
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://www.wafl.ugent.be
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport('joomla.application.component.model');
 
/**
 * WAFL Model
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Models
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://www.wafl.ugent.be
 */
class WaflModelWafl extends JModel
{
    /**
     * modules_wafl data array
     *
     * @var array
     */
    var $_data;
 
    /**
     * Constructor
     *
     */
    function __construct()
    {
         global $mainframe;
         global $option;
         parent::__construct();
         // Get the pagination request variables
         $limit = $mainframe->getUserStateFromRequest(
             'global.list.limit', 'limit', $mainframe->getCfg('list_limit')
         );
         $limitstart = $mainframe->getUserStateFromRequest(
             $option.'limitstart', 'limitstart', 0
         );
         // set the state pagination variables
         $this->setState('limit', $limit);
         $this->setState('limitstart', $limitstart);
    }
    
    /**
     * Returns the query
     * 
     * @access private
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
        $orderby    = $this->_buildContentOrderBy();
        $query = ' SELECT #__modules_wafl.id, #__modules_wafl.module_id
        , #__modules_wafl.published, #__modules_wafl.ordering
        , #__modules.title, #__modules.position, #__modules.module' 
        . ' FROM #__modules_wafl '
        . 'INNER JOIN #__modules ON #__modules.id = #__modules_wafl.module_id'
        . $orderby;
        return $query;
    }
    
    /**
     * Returns the orderby part of the query
     * 
     * @access private
     * @return string The query to be used to sort the modules in the databank
     */
    function _buildContentOrderBy()
    {
        global $mainframe, $option;

        $filter_order        = $mainframe->getUserStateFromRequest(
            $option.'filter_order', 'filter_order', 'a.ordering', 'cmd'
        );
        $filter_order_Dir    = $mainframe->getUserStateFromRequest(
            $option.'filter_order_Dir', 'filter_order_Dir', '', 'word'
        );
        if ($filter_order == 'a.ordering') {
            $orderby     = ' ORDER BY #__modules_wafl.ordering '.$filter_order_Dir;
        } else {
            $orderby     = ' ORDER BY #__modules_wafl'.$filter_order.
            ' '.$filter_order_Dir.' , ordering ';
        }

        return $orderby;
    }  
 
    /**
     * Retrieves the admin data
     * 
     * @access public
     * @return array Array of objects containing the data from the database
     */
    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data )) {
            $query = $this->_buildQuery();
            $limitstart = $this->getState('limitstart');
            $limit = $this->getState('limit');
            $this->_data = $this->_getList($query, $limitstart, $limit);
        }
        return $this->_data;
    }

    /**
     * Returns a sorted list of modules that are enabled for mobile devices
     *
     * @access public
     * @return array Full record (module objects).
     */
    function getMobileModulesByOrder()
    {
        $db    =& $this->_db;
        $query = 'SELECT ' . $db->nameQuote("#__modules.module") . ','
        . $db->nameQuote("#__modules.title") 
        . ' FROM ' . $db->nameQuote('#__modules')
        . ' INNER JOIN '
        . $db->nameQuote('#__modules_wafl')
        . ' ON ' . $db->nameQuote("#__modules.id")
        .'='
        . $db->nameQuote("#__modules_wafl.module_id")
        . ' WHERE ' . $db->nameQuote('#__modules_wafl.published') . '=1' 
        . ' ORDER BY ' . $db->nameQuote('#__modules_wafl.ordering');

        $db->setQuery($query);
        
        if (!$db->query()) {
            echo $db->stderr();
            $msg = 'my_application ' . $db->getErrorNum()
            . ' ' . $db->getErrorMsg();
            error_log($msg);
            return false;
        }
        
        $ids = $db->loadObjectList();
        $modules = array();
        $i = 0;
        
        foreach ($ids as $id) {
            $custom = substr($id->module, 0, 4) == 'mod_' ?  0 : 1;
            $name = $custom ? $id->title : substr($id->module, 4);
            $module =& JModuleHelper::getModule($name, $id->title);
            
            if (!(null === $module)) {
                $modules[$i] = $module;
                $i++;
            }
        }
        
        return $modules;
    }

    /**
     * Synchronizes the jos_modules table with the jos_modules_wafl table.
     * First of all, this method will retrieve all the module ids from
     * the jos_modules table. Then it will check if there's an entry
     * for all of these ids in our jos_modules_wafl table.
     * If there is a module without an entry, a new entry will be made
     * in this table with the published parameter set to 'one'.
     *
     * @access public
     * @return array Array( "added" => number of modules added,
     *  "deleted" => number of modules deleted )
     */

    function checkModules()
    {
        $table =& JTable::getInstance('wafl', 'Table');
        $db    =& $this->_db;
        
        $query = 'SELECT '
        . $db->nameQuote('module_id')
        . ' FROM '.$db->nameQuote('#__modules_wafl');
        $db->setQuery($query);
        if (!$db->query()) {
            echo $db->stderr();
            // Log a possible error
            $msg = 'my_application ' . $db->getErrorNum()
            . ' ' . $db->getErrorMsg();
            error_log($msg);
            return false;
        }
        $array_wafl = $db->loadResultArray();
        
        $query = 'SELECT '
        . $db->nameQuote('id')
        . ' FROM '.$db->nameQuote('#__modules');
        $db->setQuery($query);
        if (!$db->query()) {
            echo $db->stderr();
            // Log a possible error
            $msg = 'my_application ' . $db->getErrorNum()
            . ' ' . $db->getErrorMsg();
            error_log($msg);
            return false;
        }
        $array_modules = $db->loadResultArray();
        
        $toDelete = array_diff($array_wafl, $array_modules);
        $toAdd = array_diff($array_modules, $array_wafl);
        
        $i2 = 0;
        foreach ($toAdd as &$value) {
            if ($table->insert($value, 1) === true) {
                $i2++;
            }
        }
        
        $j2 = 0;
        
        foreach ($toDelete as &$value) {
            if ($table->deleteByModuleId($value) === true) {
                $j2++;
            }
        }
        $output = array("added" => $i2,"deleted" => $j2);
        return $output;  
    }

    /**
     * This method is a proxy method for checkModules. 
     * It checks if there has been records deleted or added.
     *
     * @access public
     * @return boolean True if table has been updated
     */
    function isUpdated() 
    {
        $array = $this->checkModules();
        if ($array["added"] > 0 || $array["deleted"] > 0) {
            return true;
        }
        return false;    
        
    }
    
    /**
     * Sets all the published flags to false
     *
     * @access public
     * @return boolean True if successful
     */
    function clearDatabase()
    {
        $db    =& $this->_db;
        $params = 0;
        $update = 'UPDATE '. $db->nameQuote('#__modules_wafl')
        .' SET '.$db->nameQuote('published')
        . ' = ' .$db->Quote($params);
        $db->setQuery($update);
        if (!$db->query()) {
            echo $db->stderr();
            // Log a possible error
            $msg = 'my_application ' . $db->getErrorNum() . ' ' . $db->getErrorMsg();
            error_log($msg);
            return false;
        }
        return true;
    }

    /**
     * Sets all the published flags to true
     *
     * @access public
     * @return boolean True if successful
     */
    function setAllEnabled()
    {
        $db    =& $this->_db;
        $params = 1;
        $update = 'UPDATE '. $db->nameQuote('#__modules_wafl')
        .' SET '.$db->nameQuote('published')
        . ' = ' .$db->Quote($params);
        $db->setQuery($update);
        if (!$db->query()) {
            echo $db->stderr();
            // Log a possible error
            $msg = 'my_application ' . $db->getErrorNum() . ' ' . $db->getErrorMsg();
            error_log($msg);
            return false;
        }
        return true;
    }
    
    /**
     * Get a pagination object
     *
     * @access public
     * @return JPagination The Pagination object
     */
    function getPagination()
    {
        if (empty($this->_pagination)) {
            // import the pagination library
            jimport('joomla.html.pagination');
            // prepare the pagination values
            $total = $this->getTotal();
            $limitstart = $this->getState('limitstart');
            $limit = $this->getState('limit');
            // create the pagination object
            $this->_pagination = new JPagination($total, $limitstart,
                                                  $limit);
        }
        return $this->_pagination;
    }
    
    /**
     * Get number of items
     *
     * @access public
     * @return integer Number of total items saved in jos_modules_wafl
     */
    function getTotal()
    {
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }
    
    /**
     * Changes an object his order in a certain direction
     *
     * @param int $direction How many places it has to move up or down
     * 
     * @access public
     * @return boolean True is succesful
     */
    function move($direction)
    {
        $row =& $this->getTable();
        $cid = JRequest::getVar('cid', array(), '', 'array');
        if (!$row->load($cid[0])) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    /**
     * Method to move a categorie
     *
     * @param array $order The array with order values
     * @param array $cid   The array with the key values
     *
     * @access    public
     * @return    boolean    True on success
     */
    function saveorder($order, $cid = array())
    {
        $row =& $this->getTable();

        // update ordering values
        for ($i=0; $i < count($cid); $i++) {
            $row->load((int) $cid[$i]);
            
            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }        

        return true;
    }  

 
}

