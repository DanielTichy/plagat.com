<?php
/**
 * Mod_Namedays 
 * @date 2008-11-11
 * @author Vytautas Krivickas webmaster@vytux.com
 * @based on Roman Náhlovský web@renat.sk module
 * @Copyright (C) 2008
 * @ All rights reserved
 * @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version joomla 1.5.X
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
$moduleclass_sfx    = $params->get( 'moduleclass_sfx' );
$label_pre_yesterday= $params->get( 'label_pre_yesterday', '') ;
$label_yesterday    = $params->get( 'label_yesterday', '') ;
$label_today        = $params->get( 'label_today', '') ;
$label_tomorrow     = $params->get( 'label_tomorrow', '') ;
$label_post_tomorrow= $params->get( 'label_post_tomorrow', '') ;
$nameday_list       = $params->get( 'nameday_list', '') ;
$show_pre_yesterday = $params->get( 'show_pre_yesterday', '') ;
$show_yesterday     = $params->get( 'show_yesterday', '') ;
$show_today         = $params->get( 'show_today', '') ;
$show_tomorrow      = $params->get( 'show_tomorrow', '') ;
$show_post_tomorrow = $params->get( 'show_post_tomorrow', '') ;
$align              = $params->get( 'align', 'center' );
$width              = $params->get( 'width', '' );
$height             = $params->get( 'height', '' );
$label_post         = $params->get( 'label_post', '' );
$label_pre          = $params->get( 'label_pre', '' );
$br_pre_yesterday   = $params->get( 'br_pre_yesterday', '<br />') ;
$br_yesterday       = $params->get( 'br_yesterday', '<br />') ;
$br_today           = $params->get( 'br_today', '<br />') ;
$br_tomorrow        = $params->get( 'br_tomorrow', '<br />') ;
$br_post_tomorrow   = $params->get( 'br_post_tomorrow', '<br />') ;
$seperator          = $params->get( 'seperator', ' ') ;
$createdby          = $params->get( 'createdby', '0') ;
$date_format        = $params->get( 'date_format', 'd') ;

switch ($createdby) {
  case "0" :
    $createdby = "<font size=1><center>Made by: <a href=http://www.vytux.com>Vytux!</a></font></center>";
  break;
  case "1" :
    $createdby = "";
  break;
  default:
    $createdby = "";
  break;
}

switch ($nameday_list) {
	case "0" :
		require('modules/mod_namedays/date/names_at_AT.php');
		break;
	case "1" :
		require('modules/mod_namedays/date/names_cz_CZ.php');
		break;
    case "2" :
        require('modules/mod_namedays/date/names_fi_FI.php');
		break;
	case "3" :
		require('modules/mod_namedays/date/names_fr_FR.php');
		break;  
    case "4" :
		require('modules/mod_namedays/date/names_de_DE.php');
		break;
	case "5" :
		require('modules/mod_namedays/date/names_hu_HU.php');
		break;
    case "6" :
        require('modules/mod_namedays/date/names_it_IT.php');
		break;
	case "7" :
		require('modules/mod_namedays/date/names_lv_LV.php');
		break;
	case "8" :
		require('modules/mod_namedays/date/names_lt_LT.php');
		break;
	case "9" :
		require('modules/mod_namedays/date/names_pl_PL.php');
		break;
    case "10" :
		require('modules/mod_namedays/date/names_sk_SK.php');
		break;
	case "11" :
		require('modules/mod_namedays/date/names_se_SE.php');
		break;  
	default:
		break;
}

switch ($br_pre_yesterday) {
	case "0" :
		$br_pre_yesterday = "<br />";
		break;
	case "1" :
		$br_pre_yesterday = "";
		break;
	default:
		$br_pre_yesterday = "";
		break;
}
switch ($br_yesterday) {
	case "0" :
		$br_yesterday = "<br />";
		break;
	case "1" :
		$br_yesterday = "";
		break;
	default:
		$br_yesterday = "";
		break;
}
switch ($br_today) {
	case "0" :
		$br_today = "<br />";
		break;
	case "1" :
		$br_today = "";
		break;
	default:
		$br_today = "";
		break;
}
switch ($br_tomorrow) {
	case "0" :
		$br_tomorrow = "<br />";
		break;
	case "1" :
		$br_tomorrow = "";
		break;
	default:
		$br_tomorrow = "";
		break;
}
switch ($br_post_tomorrow) {
	case "0" :
		$br_post_tomorrow = "<br />";
		break;
	case "1" :
		$br_post_tomorrow = "";
		break;
	default:
		$br_post_tomorrow = "";
		break;
}

  // Get Current Datetime with correct TimeZone 
  $myJDate = JFactory::getDate();
  $myJUser =& JFactory::getUser();  
  
  // If not logged in use site TimeZone else use the logged in users TimeZone
  if ($myJUser->guest) {
    $JApp =& JFactory::getApplication();
	$myJDate->setOffset($JApp->getCfg('offset'));
  } else {
    $myJDate->setOffset($myJUser->getParam('timezone'));
  }
  
  // Set $date to the 'local' time as defined above
  $date = $myJDate->toUnix(true);
  
  // Setup the 5 days dates
  $day1 = mktime(0, 0, 0, date("m", $date), date("d", $date)-2, date("Y", $date));
  $day2 = mktime(0, 0, 0, date("m", $date), date("d", $date)-1, date("Y", $date));
  $day3 = mktime(0, 0, 0, date("m", $date), date("d", $date)  , date("Y", $date));
  $day4 = mktime(0, 0, 0, date("m", $date), date("d", $date)+1, date("Y", $date));
  $day5 = mktime(0, 0, 0, date("m", $date), date("d", $date)+2, date("Y", $date));
  
  // include the template for display
  require(JModuleHelper::getLayoutPath('mod_namedays'))
?>
