<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Percha Component
 * @copyright Copyright (C) Cristian Grañó Reder www.percha.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */


// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 *  
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class perchadownloadsattachViewControlpanel extends JView
{
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		
		global $mainframe;

		$db =& JFactory::getDBO();


 
		JToolBarHelper::Preferences( 'com_perchadownloadsattach' );
                JToolBarHelper::custom( 'help_unit', 'help ', 'icon over', 'Help', false, false );
                 

		parent::display($tpl);
	}
}
