<?php

/**
* @package 		FileManagement
* @copyright 	Copyright (C) 2009 DecryptWeb. All rights reserved.
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class FilemgmtViewFilemgmt extends JView 
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$task = JRequest::getVar('task');
		
		jimport('joomla.html.pane');
		JHTML::_('behavior.tooltip');
				
		JToolBarHelper::title( JText::_( 'File Upload Manager' ), 'generic.png' );

		JToolBarHelper::save();
		JToolBarHelper::deleteList();
		JToolBarHelper::help('filehelp',true);					
		
		//get data from model
		$model		= & $this->getModel();
		$fl     	= & $this->get('Data');	
		
		$this->assignRef('filelist' , $fl);	
		
		parent::display($tpl);
	}
}