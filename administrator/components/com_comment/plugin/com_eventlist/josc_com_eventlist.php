<?php
defined('_JEXEC')  or die('Restricted access');
/*
 * include the following instructions :

<!-- START joomlacomment INSERT -->
<div class="details" style="">
<?php
	global $option;
	require_once(JPATH_SITE."/administrator/components/com_comment/plugin/$option/josc_com_eventlist.php");
?>
</div>
<!-- END OF joomlacomment INSERT -->

 * 	
 *	
 *
 *  HTML part of the code can be changed ! according to the theme...
 */

	global $option;
	require_once(JPATH_SITE."/components/com_comment/joscomment/utils.php");

	$comObject = JOSC_utils::ComPluginObject($option,$row);
	echo JOSC_utils::execJoomlaCommentPlugin($comObject, $row, $params, true);
	unset($comObject);
?>
