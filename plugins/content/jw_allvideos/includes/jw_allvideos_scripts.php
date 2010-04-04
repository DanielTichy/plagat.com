<?php
/*
// "AllVideos" Plugin by JoomlaWorks for Joomla! 1.5.x - Version 3.1
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr
// Designed and developed by the JoomlaWorks team
// *** Last update: September 14th, 2009 ***
*/

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

ob_start ("ob_gzhandler"); 
header("Content-type: text/javascript; charset: UTF-8"); 
header("Cache-Control: must-revalidate"); 
header("Expires: ".gmdate("D, d M Y H:i:s", time() + 60 * 60)." GMT");

// Includes
include(dirname( __FILE__ ).DS."players".DS."wmvplayer".DS."silverlight.js");
echo "\n\n";
include(dirname( __FILE__ ).DS."players".DS."wmvplayer".DS."wmvplayer.js");
echo "\n\n";
include(dirname( __FILE__ ).DS."players".DS."quicktimeplayer".DS."AC_QuickTime.js");
echo "\n\n";
include(dirname( __FILE__ ).DS."jw_allvideos.js");
echo "\n\n";

ob_flush();
