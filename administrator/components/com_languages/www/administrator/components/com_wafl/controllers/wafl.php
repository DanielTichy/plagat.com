<?php 
/**
 * wafl.php
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Controllers
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
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

/**
 * WAFL Controller
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Controllers
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
class WaflControllerWafl extends JController
{

    /**
     * constructor (registers additional tasks to methods)
     *
     * @return void
     */

    function __construct()
    {
        parent::__construct();
    }


    /**
     * Method to display the view
     *
     * @access public
     * @return void
     */
    function display()
    {
        parent::display();
    }

    /**
     * Enable a module for mobile viewing
     *
     * @return void
     */
    function publish()
    {
        global $option;

        $cid = JRequest::getVar('cid', array(), '', 'array');
        $publish = 1;
        $waflTable =& JTable::getInstance('wafl', 'Table');
        $waflTable->publish($cid, $publish);
        
        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  
        
        $this->_redirect();
    }

    /**
     * disable a module for mobile viewing
     *
     * @access public
     * @return void
     */
    function unpublish()
    {
        global $option;

        $cid = JRequest::getVar('cid', array(), '', 'array');
        $publish = 0;
        $waflTable =& JTable::getInstance('wafl', 'Table');
        $waflTable->publish($cid, $publish);
          
        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  
         
        $this->_redirect();
    }
    
    /**
     * Change the published field of the selected modules
     * 
     * @access public
     * @return void
     */
    function changeSelected()
    {
        global $option;

        $cid = JRequest::getVar('cid', array(), '', 'array');
        $waflTable =& JTable::getInstance('wafl', 'Table');
        foreach ($cid as $id) {
            $waflTable->load($id);
            $publish = !$waflTable->published;
            $waflTable->publish($id, $publish);
        }
        
        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  
        
        $this->_redirect();
    }
    /**
     * Clear the database
     *
     * @access public
     * @return void
     */
    function clear()
    {
        global $option;
 
        $this->getModel()->clearDatabase(); 
        
        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  
        
        $this->_redirect();
    }

    /**
     * Enable all the modules
     *
     * @access public
     * @return void
     */
    function enableAll()
    {
        global $option;

        $this->getModel()->setAllEnabled(); 
        
        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  
        
        $this->_redirect();
    }
    
    /**
     * private function that handles the redirect
     *
     * @access private
     * @return void
     */  
    private function _redirect()
    {
        global $option;
        $this->setRedirect(
            'index.php?option=' .$option.'&view='
            . JRequest::getVar('view', JREQUEST_ALLOWRAW).'&c='
            .JRequest::getVar('c', JREQUEST_ALLOWRAW)
        );
    }
    
    /**
     * Moves an object one place up
     *
     * @access public
     * @return void
     */  
    function orderup()
    {
        $model = $this->getModel('Wafl');
        $model->move(-1);
        
        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  

        $this->_redirect();
    }

    /**
     * Moves the object one place down
     *
     * @access public
     * @return void
     */  
    function orderdown()
    {
        $model = $this->getModel('Wafl');
        $model->move(1);
        
        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  

        $this->_redirect();
    }
    
    /**
     * Method to save order
     *
     * @access public
     * @return void
     */
    function saveorder()
    {
        $cid     = JRequest::getVar('cid', array(), 'post', 'array');
        $order   = JRequest::getVar('order', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('Wafl');
        $model->saveorder($order, $cid);
        
        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  

        $msg = JText::_('Nieuwe volgorde opgeslagen');
        $this->_redirect();
    } 
    
}
?>
