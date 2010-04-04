<?php
/*------------------------------------------------------------------------------
// DWP Customization for Joomla 1.5 - Version 1.0 - Licence dwp.jim@hotmail.com
// Customization for plagat.com - including code to all files where usage DWP: 
// jimport('dwp')
-------------------------------------------------------------------------------*/

// Include dwp config file
include_once (JPATH_SITE . DS .'dwpconfig.php');

global $DwpCfg;
$DwpCfg = new DwpConfig();

class DWP{	
	function DwpConf(){
		global $DwpCfg;
		return $DwpCfg;
	}
	
	function JaNewsBlockItemTitleLength(){
		return DWP::DwpConf()->JaNewsBlockItemTitleLength;
	}
	
	function SearchImageUrlLimitedString($html){
		global $DwpCfg;
		return substr($html, 0, DWP::DwpConf()->JaBulletingSearchImageFromFirstChars);	
	}
	
	function ImageAsLink($image, $link = ''){		if($image == '')			return "";					global $DwpCfg;		if(DWP::DwpConf()->GenerateImageAsLink && $link && !DWP::imageIsLink($image))			return "<a href=\"". $link ."\" class=\"dwpImageLink\">". $image ."</a>";		else								return $image;	}
	
	function HpImageAsLink($image, $link = ''){		if($image == '')			return "";				global $DwpCfg;		if(DWP::DwpConf()->GenerateHpImageAsLink && $link && !DWP::imageIsLink($image))			return "<a href=\"". $link ."\" class=\"dwpHpImageLink\">". $image ."</a>";		else								return $image;	}	
	
	function imageIsLink($image){
		return (strpos($image, "<a href") != false);
	}
	
}
?>