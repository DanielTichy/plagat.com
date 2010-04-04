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

$mainframe =& JFactory::getApplication('site');
JHTML::stylesheet('',modJANewsHelper::getFile('ja.news.fp.css','modules/mod_janews_fp/ja.news/','templates/'.$mainframe->getTemplate().'/css/'));
	// If the template has a css override use it

  //Show frontpage
  $numberofheadlinenews = $params->get('numberofheadlinenews',0);
  
  $bigmaxchar = $params->get('bigmaxchars',0);
  $bigitems = $params->get('bigitems',0);
  $bigshowimage = $params->get('bigshowimage',0);
  $bigimg_w = $params->get('bigimg_w',0);
  $bigimg_h = $params->get('bigimg_h',0);
  $imgalign = '';
  
  $smallmaxchar = $params->get('smallmaxchars',0);
  $smallitems = $numberofheadlinenews - $bigitems;
  $smallshowimage = $params->get('smallshowimage',0);
  $smallimg_w = $params->get('smallimg_w',0);
  $smallimg_h = $params->get('smallimg_h',0);
    
  $fp_layout = trim($params->get('fp_layout','default_fp'));
  if (strpos($fp_layout, '.php') > 0) $fp_layout = substr($fp_layout, 0, -4);
  $rows = modJANewsHelper::getHLNews($numberofheadlinenews);

  if (count($rows)) {
  	$path = JModuleHelper::getLayoutPath('mod_janews_fp', $fp_layout);
  	if (file_exists($path)) {
  		require($path);
  	}
  }
?>