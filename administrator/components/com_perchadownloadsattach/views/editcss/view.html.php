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

jimport( 'joomla.application.component.view' );

/**
 * Hello View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class perchadownloadsattachViewEditcss extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		$mosConfig_absolute_path = JPATH_BASE."/../"; 

		$text =   JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Edit Css' ).': <small><small>[ ' . $text.' ]</small></small>','wrms_toolbar_title' );


                $user =& JFactory::getUser();
                $user_type = $user->get('usertype');
                if ($user_type == "Super Administrator")
                    {
                    JToolBarHelper::apply();
                    }
		//Parametros  
               // $folder = $url_upload. "/unidad_". $Unidad->id ;
 
		$path = $mosConfig_absolute_path ."components/com_perchadownloadsattach/css/style.css";

                if(file_exists($path)) { 
                        $fh = fopen($path, 'r');
                        $theData = fread($fh,  filesize($path));
                        fclose($fh);

                        
                }
                
		$this->assignRef('path',		$path);
                $this->assignRef('text',		$theData);

		parent::display($tpl);
	}
}
