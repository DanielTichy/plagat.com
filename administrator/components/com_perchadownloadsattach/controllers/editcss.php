<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Percha Component
 * @copyright Copyright (C) Cristian Grañó Reder www.percha.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */


// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class perchadownloadsattachControllerEditcss extends  perchadownloadsattachController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		 
		  
	}
	/**
	 * display the edit form
	 * @return void
	 */
	function display()
	{
		
		JRequest::setVar( 'view', 'editcss' );

		$task = JRequest::getVar( 'task' );
		//JRequest::setVar( 'section', 'categoria' ); 
		//JRequest::setVar('hidemainmenu', 1);
                
		parent::display();
	}
/**
	 * display the edit form
	 * @return void
	 */
	function apply()
	{

		JRequest::setVar( 'view', 'editcss' );

                $editcss = JRequest::getVar( 'editcss' );

               

                $mosConfig_absolute_path = JPATH_BASE."/../";
                $filename = $mosConfig_absolute_path ."components/com_perchadownloadsattach/css/style.css";

                $mystring = fopen($filename, "wb");
                $handle = fopen($filename, "wb");
                $newstring =  $editcss;
                $numbytes = fwrite($handle, $newstring);
                fclose($handle);


                 echo '<dl id="system-message"><dt class="message">'. JText::_( 'Message' )  .'</dt><dd class="message message fade">
                        <ul>
                                <li>'. JText::_( 'Save CSS Ok' )  .'</li>
                        </ul>
                </dd>
                </dl>'; 

		parent::display();
	}
        
 

	 
}
