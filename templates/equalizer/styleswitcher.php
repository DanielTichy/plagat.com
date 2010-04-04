<?php
/*----------------------------------------------------------------------
#Youjoomla Styleeswitch  - 
# ----------------------------------------------------------------------
# Copyright (C) 2007 You Joomla. All Rights Reserved.
# Designed by: You Joomla
# License: Copyright
# Website: http://www.youjoomla.com
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted index access' );
session_start();
$mystyles = array();


$mystyles['orange']['file'] = "orange.css";
$mystyles['blue']['file'] = "blue.css";
$mystyles['green']['file'] = "template_css.css"; // default

$mystyles['green']['label'] = '<img src="templates/'.$this->template.'/images/green.gif" alt="Green" title="Green" />';
$mystyles['orange']['label'] = '<img src="templates/'.$this->template.'/images/orange.gif" alt="Orange" title="Orange" />';

$mystyles['blue']['label'] = '<img src="templates/'.$this->template.'/images/blue.gif" alt="Blue" title="Blue" />';




if (isset($_GET['change_css']) && $_GET['change_css'] != "") {
    $_SESSION['css'] = $_GET['change_css'];
} else {
    $_SESSION['css'] = (!isset($_SESSION['css'])) ? $default_color : $_SESSION['css'];
}
switch ($_SESSION['css']) {
    case "green":
    $css_file = "green.css";
    break;
    case "orange":
    $css_file = "orange.css";
    break;
	case "blue":
    $css_file = "blue.css";
    break;
    default:
    $css_file = "template_css.css";
}



//
//function style_switcher() {
//while(list($key, $val) = each($mystyles)){
// echo "<a href='".$_SERVER['PHP_SELF']."?change_css=".$key."' title='".$val["label"]."'>".$val["label"]."</a>";
//}
//}
//FONT SWITCH

$myfont = array();

$myfont['big']['file'] = "14px"; // default
$myfont['medium']['file'] = "11px";
$myfont['small']['file'] = "9px";


$myfont['big']['label'] = '<img src="templates/'.$this->template.'/images/plus.gif" alt="Big" title="Big" />';
$myfont['medium']['label'] = '<img src="templates/'.$this->template.'/images/jednako.gif" alt="Medium" title="Medium" />';
$myfont['small']['label'] = '<img src="templates/'.$this->template.'/images/minus.gif" alt="Small" title="Small" />';






if (isset($_GET['change_font']) && $_GET['change_font'] != "") {
    $_SESSION['font'] = $_GET['change_font'];

} else {
    $_SESSION['font'] = (!isset($_SESSION['font'])) ? $default_font : $_SESSION['font'];

	
}
switch ($_SESSION['font']) {
    case "small":
    $css_font = "9px";
    break;
    case "medium":
    $css_font = "11px";
    break;
	case "big":
    $css_font = "14px";
    break;
    default:
    $css_font = "11px";
}

//WIDTH SWITCH


$mywidth = array();


$mywidth['wide']['file'] = "1000px";
$mywidth['narrow']['file'] = "776px";

$mywidth['wide']['label'] = '<img src="templates/'.$this->template.'/images/wide.gif" alt="Green" title="Wide" />';
$mywidth['narrow']['label'] = '<img src="templates/'.$this->template.'/images/narrow.gif" alt="Orange" title="Narrow" />';





if (isset($_GET['change_width']) && $_GET['change_width'] != "") {
    $_SESSION['width'] = $_GET['change_width'];


} else {
    $_SESSION['width'] = (!isset($_SESSION['width'])) ? $default_width : $_SESSION['width'];
}
switch ($_SESSION['width']) {
    case "wide":
    $css_width = "1000px";
	$layoutcss ="wide.css";
    break;
    case "narrow":
    $css_width = "776px";
	$layoutcss ="narrow.css";
    break;
    default:
    $css_width = "1000px";

}


?>