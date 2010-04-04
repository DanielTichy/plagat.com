<?php
/**
 * wafl.php 
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Tables
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

defined('_JEXEC') or die();

/**
 * WAFL table handler
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Tables
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
class TableWafl extends JTable
{
    /** @var int Primary key */
    var $id = null;

    /** @var int Reference to module */
    var $module_id = null;

    /** @var boolean Reference to module */
    var $published = 0;
    
    /** @var int Order */
    var $ordering = 0;  

    var $title = null;

    var $position = null;
    
    var $module = null;

    /**
     * Constructor
     *
     * @param database &$db Database object
     */
    function __construct(&$db)
    {
        parent::__construct('#__modules_wafl', 'id', $db);
    }

    /**
     * Returns a record from the jos_modules_wafl table.
     *
     * @param int $mod_id The module id
     *
     * @access private
     * @return The row
     */
     
    function _doesRecordExist($mod_id)
    {
        $db    = &$this->getDBO();
        $query = "SELECT ". $db->nameQuote('id')." FROM "
        .$db->nameQuote('#__modules_wafl')
        . 'WHERE ' . $db->nameQuote('module_id')
        . '=' . $db->Quote($mod_id);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result === null) {
            return false;
        }
        return $result;
    }

    /**
     * Returns if a module is mobile enabled
     *
     * @param int $mod_id The module id
     *
     * @access public
     * @return boolean
     */
    function isMobileEnabled($mod_id)
    {
        $this->reset();
        $db =&$this->getDBO();
        $query = 'SELECT '.$db->nameQuote('published').' FROM '
        . $db->nameQuote('#__modules_wafl')
        . ' WHERE ' .$db->NameQuote('module_id').' = '. $db->Quote($mod_id);
        $db->setQuery($query);
        return $db->loadResult();
    }

    /**
     * Adds a new record in the jos_modules_wafl table
     *
     * @param int $module_id The module id
     * @param int $published The published parameter
     *
     * @access public
     * @return Boolean
     */
    function insert($module_id, $published)
    {
        $this->reset();
        $bools = false;
        if ($this->_doesRecordExist($module_id) === false) {
            $this->set('id', $this->getNextOrder());
            $this->set('ordering', $module_id);
            $this->set('module_id', $module_id);
            $this->set('published', $published);
            $this->set('title', null);
            $this->set('position', null);
            $this->set('module', null);
            if ($this->check()) {
                //store translated into 'UPDATE' instead of 'INSERT'
                $ret = $this->_db->insertObject(
                    $this->_tbl, $this, $this->_tbl_key
                );
                if (!$ret) {
                    echo $this->getError()."<br />";
                    $bools = false;
                } else {
                    $bools = true;
                }
            }
        }
        return $bools;
    }

    /**
     * Deletes a record with a given module_id
     *
     * @param int $module_id The module id
     *
     * @access public
     * @return Boolean True if successful
     */
    function deleteByModuleId($module_id) 
    {
        //should always be true
        $id = $this->_doesRecordExist($module_id);
        if ($id !== false) {
            return $this->delete($id);
        }
        return false;
    }
    /**
     * Checks if all the 'not null' values of the record
     * are defined before storing the record.
     *
     * @access public
     * @return boolean
     */
    function check()
    {
        return true;
    }
    

}
?>

