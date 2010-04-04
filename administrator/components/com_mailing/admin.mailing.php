<?php
/*
* @name ULTIMATE MAILING LISTS
* Created By Chris Michaelides <info@axxis.gr>
* http://www.axxis.gr
* @copyright AXXIS Internet Solutions Copyright (C) 2009  www.Axxis.gr / All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die();

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
require_once(JPATH_COMPONENT.DS.'controller.php');
//require_once(JPATH_COMPONENT.DS.'tables/cbactivity.php');
global $defaultgroup;

		$db	=& JFactory::getDBO();
		$query = "SELECT * FROM #__mailing_conf WHERE id=1";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$defaultgroup = $rows[0]->defaultgroup;
		
		$url = JURI::Base();
		$url = str_replace("administrator/", "", $url);

$controller = new mailinglistController();
if ($defaultgroup) $controller->registerDefaultTask('listEmails'); else $controller->registerDefaultTask('listGroups');
$task = JRequest::getCmd('task');
$act = JRequest::getCmd('act');
//echo "ACT:$act TASK:$task";

switch($act)
{
	case "emails":
	switch($task) {
		case "saveemail":
		  $controller->execute($task);
		break;
		case "deleteemail":
		  $controller->execute($task);
		break;
		case "batch":
		  $controller->execute($task);
		break;
		case "savebatch":
		  $controller->execute($task);
		break;
		case "sendtocontact":
		  $controller->execute($task);
		break;
		default:
		  $controller->execute('listEmails');
		break;
	}
	break;

	case "groups":
	switch($task) {
		case "sendtogroup":
		  $controller->execute($task);
		break;
		case "addusers":
		  $controller->execute($task);
		break;
		default:
      $controller->execute('listGroups');
		break;
	}
	break;

	case "message":
	switch($task) {
		case "sendit":
		  $controller->execute($task);
		break;
		default:
		  $controller->execute('sendMessage');
		break;
	}
	break;

	case "success":
	switch($task) {
		default:
      $controller->execute('listSuccess');
		break;
	}
	break;

	case "fail":
	switch($task) {
		default:
      $controller->execute('listFail');
		break;
	}
	break;

	case "maintenance":
	switch($task) {
		case "do_maintenance":
		  $controller->execute($task);
		break;
		default:
      $controller->execute('maintenance');
		break;
	}
	break;

}
if (!$act) {
//echo "<br/>I will execute with no act now...";
$controller->execute($task);
}
$controller->redirect();

?>