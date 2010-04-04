<?php
 
// no direct access
defined('_JEXEC') or die('Restricted access');
 


// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php'); 


// Require specific controller if requested
if($controller =  JRequest::getVar('controller') ) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}
 
$doc =& JFactory::getDocument();
$doc->addStyleSheet('components/com_perchadownloadsattach/css/style.css');
$doc->addScript('components/com_perchadownloadsattach/js/jquery.corner.js');
$doc->addScript('components/com_perchadownloadsattach/js/init.js');


// Create the controller
$classname	= 'perchadownloadsattachController';
$controller = new $classname();

$config =& JComponentHelper::getParams('com_perchadownloadsattach'); 
//echo "URL:: ".$url_upload;
// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();

?>
