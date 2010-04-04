<?php
/*----------------------------------------------------------------------
# Youjoomla Default Settings - April 2007
# ----------------------------------------------------------------------
# Copyright (C) 2007 You Joomla. All Rights Reserved.
# Designed by: You Joomla
# License: Commercial
# Website: http://www.youjoomla.com
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted index access' );

//START COLLAPSING THAT MODULE:)
$left = $this->countModules( 'left' );
$right = $this->countModules( 'right' );
if ( $left  &&  $right  ) {
	$content  = 'content';
	$left  = 'left';
	$right = 'right';
	$mainbody  = 'mainbody';
	$wrap    = 'wrap';
    $insidewrap='insidewrap';
	$landrwrap='landrwrap';
	}elseif ( $left) {
	$content  = 'content_L';
	$left  = 'left_L';
	$mainbody  = 'mainbody_L';
	$wrap    = 'wrapblank';
	$insidewrap='insidewrapblank_left';
	}elseif ( $right) {
	$content  = 'content_R';
	$right = 'right_R';
	$mainbody  = 'mainbody_R';
	$wrap    = 'wrap';
	$insidewrap='insidewrapblank_right';
	$landrwrap='landrwrap_R';
	} else {
	$content = 'content_R';
	$mainbody  = 'mainbody_L';
	$wrap    = 'wrapblank';
	$landrwrap='landrwrap';
	$insidewrap='insidewrapblank';
	}




//START COLLAPSING TOP:)
$top = 0;
if ($this->countModules('user1')) $top++;
if ($this->countModules('user2')) $top++;
if ($this->countModules('user3')) $top++;
if ($this->countModules('user4')) $top++;
if ( $top == 4 ) {
	$topwidth = '25%';
} else if ($top == 3) {
	$topwidth = '33.3%';
} else if ($top == 2) {
	$topwidth = '50%';		
} else if ($top == 1) {
	$topwidth = '100%';
}
//START COLLAPSING TOP:)
$bottom = 0;
if ($this->countModules('user5')) $bottom++;
if ($this->countModules('user6')) $bottom++;
if ($this->countModules('user7')) $bottom++;
if ($this->countModules('user8')) $bottom++;
if ( $bottom == 4 ) {
	$bottomwidth = '25%';
} else if ($bottom == 3) {
	$bottomwidth = '33.3%';
} else if ($bottom == 2) {
	$bottomwidth = '50%';		
} else if ($bottom == 1) {
	$bottomwidth = '100%';
}
$bgimg = 0;
if ($this->countModules('user1')) $bgimg++;
if ($this->countModules('user2')) $bgimg++;
if ($this->countModules('user3')) $bgimg++;
if ($this->countModules('user4')) $bgimg++;
if ( $bgimg > 0 ) {
	$img = 'bg1';
	} else if ($bgimg < 1) {
	$img = 'bg2';
	}
?>