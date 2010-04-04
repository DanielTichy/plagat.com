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

jimport('joomla.application.component.controller');


class FilemgmtController extends JController
{
	function __construct( $default = array() )
	{		
		$default['default_task'] = 'show';
		global $data;
		$data = new FilemgmtModelFilemgmt();
		parent::__construct($default);
	}
	
	function display()
	{
		parent::display();
	}
	function show()
	{
		JRequest::setVar( 'view', 'filemgmt' );		
		JRequest::setVar( 'layout', 'default' );	
		parent::display();
	}

	function save()
	{				
		global $data;	
		$data->submitfile();		
	}	
	
	function remove()
	{				
		global $data;	
		$data->removefile();		
	}
}