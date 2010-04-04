<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/*
 * Copyright Copyright (C) 2007 Alain Georgette. All rights reserved.
 * Copyright Copyright (C) 2006 Frantisek Hliva. All rights reserved.
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * !JoomlaComment is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * !JoomlaComment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA  02110-1301, USA.
 */
    
    /* get bot function from the global setting */
    require_once(JPATH_SITE."/administrator/components/com_comment/class.config.comment.php");
    $null=null;
    $joscommentconfig = new JOSC_config(1,$null);
    $joscommentconfig->load();
    $joscplugin =  'pluginJosCommentJ15';

    /*
     * if func contain "::JOSCusereturn" -> do not use text concatenation but return value
     * usefull for onAfterDisplayContent
     */
    $joscbottag  = "::JOSCusereturn";
    $joscbotfunc = $joscommentconfig->_mambot_func;
    if (!strpos($joscbotfunc, $joscbottag)===false) {
    	$joscbotfunc = str_replace($joscbottag, '', $joscbotfunc );
    	$joscplugin .= "_UseReturn";
    }

	$mainframe->registerEvent(($joscbotfunc ? $joscbotfunc : 'onPrepareContent'), $joscplugin);
	unset($joscommentconfig);

function pluginJosCommentJ15( &$row, &$params, $page = 0 )
{   return pluginJosComment( true, $row, $params, $page );
}

function pluginJosCommentJ15_UseReturn( &$row, &$params, $page = 0 )
{   return pluginJosComment_UseReturn( true, $row, $params, $page );
}

function pluginJosComment($published, &$row, &$params, $page = 0)
{
	if (!$published) return true;

	global $option;
	require(JPATH_SITE."/administrator/components/com_comment/plugin/com_content/josc_com_content.php");
}

function pluginJosComment_UseReturn($published, &$row, &$params, $page = 0)
{
	if (!$published) return true;

	$joscplugintext = "";

	global $option;
	require(JPATH_SITE."/administrator/components/com_comment/plugin/com_content/josc_com_content.php");

	return $joscplugintext;
}

?>
