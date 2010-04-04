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


// Require the base controller

require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'helpers/Image.php' );  
 
$document = &JFactory::getDocument();

$document->addStyleSheet(  JURI::root().'administrator/components/com_perchadownloadsattach/css/style.css');
 
// Require specific controller if requested
if($controller = JRequest::getWord('controller', 'controlpanel')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}
 

// Create the controller
$classname	= 'perchadownloadsattachController'.$controller;
//echo $classname;

if(isset($_GET["section"])) {
//echo JPATH_COMPONENT.DS.'controllers'.DS.  $_GET["section"] .'.php';
require_once  JPATH_COMPONENT.DS.'controllers'.DS.  $_GET["section"] .'.php';
$classname	= 'perchadownloadsattachController'.$_GET["section"]; 
}else
{
    
}
  

$controller	= new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task', 'unidades' ) );

// Redirect if set by the controller
$controller->redirect(); 
