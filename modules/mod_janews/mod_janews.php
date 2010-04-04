<?php 
/*------------------------------------------------------------------------
# JA Rutile for Joomla 1.5 - Version 1.0 - Licence Owner JA122250
# ------------------------------------------------------------------------
# Copyright (C) 2004-2008 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once (JPATH_SITE .DS.'modules'.DS.'mod_janews'.DS.'helper.php');

$catorsec				= 	trim( $params->get( 'catorsec' ));
$catid 					= 	trim( $params->get( 'catid' ));
$showheadline 			= 	intval (trim( $params->get( 'showheadline', 0 )));
$cols					= 	intval (trim( $params->get( 'cols', 2 ) ));
$ordering					= 	trim( $params->get( 'ordering', 'created desc' ) );
$showcontentfrontpage = 	intval (trim( $params->get( 'showcontentfrontpage', 1 ) ));
$mainframe =& JFactory::getApplication('site');

	// If the template has a css override use it
JHTML::stylesheet('',modJaNewsHelper::getFile('ja.news.css','modules/mod_janews/ja.news/','templates/'.$mainframe->getTemplate().'/css/'));

if ($cols > 0) {
	if ($catid) {
    $catids = preg_split('/[\n,]|<br \/>/', $catid);
	}else {
		$catids = modJaNewsHelper::getAllCatIds($catorsec);
	}
  $introitems 				= 	intval (trim( $params->get( 'introitems', 1 ) ));
  $linkitems 				= 	intval (trim( $params->get( 'linkitems', 0 ) ));
	//Get data
	$contents = $themes = array();
	for ($i = 0; $i < count($catids); $i ++) {
	  $temp = split(':',$catids[$i]);
	  if(isset($temp[0])) $catid = $temp[0];
	  if($catid) {
  	  $rows = modJaNewsHelper::getList($catorsec, $catid, $linkitems + $introitems, $ordering,$showcontentfrontpage);
  	  if(count($rows)) {
        $contents[] = $rows;
        $themes[] = isset($temp[1])? $temp[1]:'';
      }
    }
  }
//print_r($catids);

	$path = JModuleHelper::getLayoutPath('mod_janews', 'blog');
	if (file_exists($path)) {
		require($path);
	}
}
?>