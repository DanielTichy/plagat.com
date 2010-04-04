<?php 
/**
 * advanced.php
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

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_wafl'.DS.
             'models'.DS.'advanced.php';

jimport('joomla.application.component.controller');

/**
 * Advanced Siruna Controller
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
class WaflControllerAdvanced extends JController
{

    /**
     * constructor
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
     * Logic that handles the save button of the form
     *
     * @access public
     * @return void
     */
    function save()
    {
        $model =& WaflModelAdvanced::getWAFLAdvancedInstance();
        $result = $model->save();
        $this->_redirect();
        if ($result !== true) {
            global $mainframe; 
            $mainframe->enqueueMessage($result[0], $result[1]);
        }
    }
    
    /**
     * Logic that handles the cancel button of the form
     *
     * @access public
     * @return void
     */
    function restore()
    {
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
}
?>
