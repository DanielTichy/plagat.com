<?php
/*
// "AllVideos" Plugin by JoomlaWorks for Joomla! 1.5.x - Version 3.1
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr
// Designed and developed by the JoomlaWorks team
// *** Last update: September 14th, 2009 ***
*/

// Set flag that this is a parent file
define('_JEXEC',1);

define('DS',DIRECTORY_SEPARATOR);
define('JPATH_BASE', '..'.DS.'..'.DS.'..'.DS.'..');

// Includes
require_once (JPATH_BASE.DS.'includes'.DS.'defines.php');
require_once (JPATH_BASE.DS.'includes'.DS.'framework.php');

// API
$mainframe= &JFactory::getApplication('site');
$document = &JFactory::getDocument();

// Assign paths
$sitePath = JPATH_SITE;
$siteUrl  = substr(JURI::root(), 0, -1);

// Define error handling
$nogo = JText::_('Sorry, download unavailable or wrong file path set!<br /><br />Please contact the administrator of this site.<br /><br /><a href="javascript:history.go(-1);">Return</a>');

// block any attempt to explore the filesystem - check if images are included in the "images" folder
$ref_com_content = $siteUrl.'/'.substr($_GET['file'],0,strlen('images/'));
$check_com_content = $siteUrl."/images/";

$ref_com_k2 = $siteUrl.'/'.substr($_GET['file'],0,strlen('media/k2/videos/'));
$check_com_k2 = $siteUrl."/media/k2/videos/";

if( isset($_GET['file']) && ($ref_com_content===$check_com_content || $ref_com_k2===$check_com_k2)){
	$getfile = $_GET['file'];
} else {
	$getfile = NULL;
}

if (!$getfile) {
	// go no further if filename not set
	echo $nogo;
} else {
	// define the pathname to the file
	$filepath = $sitePath.DS.$getfile;
	// check that it exists and is readable
	if (file_exists($filepath) && is_readable($filepath)) {
		// get the file's size and send the appropriate headers
		$size = filesize($filepath);
		header('Content-Type: application/force-download');
		header('Content-Length: '.$size);
		header('Content-Disposition: attachment; filename="'.basename($getfile).'"');
		header('Content-Transfer-Encoding: binary');
		// open the file in binary read-only mode
		// suppress error messages if the file can't be opened
		$file = @ fopen($filepath, 'rb');
		if ($file) {
			// stream the file and exit the script when complete
			fpassthru($file);
			exit;
		} else {
			echo $nogo;
		}
	} else {
		echo $nogo;
	}
}
