<?php

/**
 * view.html.php
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Views
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
 
jimport('joomla.application.component.view');
 
/**
 * WAFL View
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Views
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
class WaflViewWafl extends JView
{
    /**
     * Wafl view display method
     * 
     * @param string $tpl The template to display
     * 
     * @return void
     */
    function display($tpl = null)
    {
        global $mainframe, $option;
        //set toolbar
        JHTML::_(
            'stylesheet', 'toolbarlogo.css', 
            'administrator/components/com_wafl/views/'
        ); 
        JToolBarHelper::title(JText::_('Admin Panel'), 'logo.png');
        include_once JPATH_COMPONENT.DS.'views'.DS.'wafl'.DS.'wafl.php';
        WaflToolbarWafl::setWaflToolbar();

        $filter_order 
            = $mainframe->getUserStateFromRequest(
                $option.'filter_order', 'filter_order', 'a.ordering', 'cmd'
            );
        $filter_order_Dir 
            = $mainframe->getUserStateFromRequest(
                $option.'filter_order_Dir', 'filter_order_Dir', '', 'word'
            );

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        $this->assignRef('lists', $lists);
        
        // Get data from the model
        $items =& $this->get('Data');      
        $pagination =& $this->get('Pagination');
     
        // push data into the template
        $this->assignRef('items', $items);     
        $this->assignRef('pagination', $pagination);
         
        parent::display($tpl);
    }
}

