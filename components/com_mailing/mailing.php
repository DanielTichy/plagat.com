<?php
/*
* @name ULTIMATE MAILING LISTS
* Created By Chris Michaelides <info@axxis.gr>
* http://www.axxis.gr
* @copyright AXXIS Internet Solutions Copyright (C) 2008  www.Axxis.gr / All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die();

require_once(JPATH_COMPONENT.DS.'frontcontroller.php');
$controller = new subscriptionController();
$controller->registerDefaultTask('s_list');
$task = JRequest::getCmd('task');
$act = JRequest::getCmd('act');
//echo "ACT:$act TASK:$task";
$controller->newgrloadLanguage(JPATH_COMPONENT);

global $defaultgroup;

		$db	=& JFactory::getDBO();
		$query = "SELECT * FROM #__mailing_conf WHERE id=1";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$defaultgroup = $rows[0]->defaultgroup;
		//$password = $rows[0]->verikey;

		$url = JURI::Base();

switch($act)
{
	case "subscribe":
  	$controller->execute('s_list');
	break;

	case "unsubscribe":
  	$controller->execute('s_list');
	break;
}
if (!$act) {
//echo "<br/>I will execute with no act now...$task";
$controller->execute('s_list');
}
$controller->redirect();
?>