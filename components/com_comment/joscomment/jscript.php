<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
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

//require_once('utils.php');

class JOSC_jscript {
	
	function insertJavaScript($path)
	{
	$html = "\n<script type='text/javascript'>\n";
/*	$html .= ' var _JOOMLACOMMENT_MSG_DELETE = \'' . htmlspecialchars(_JOOMLACOMMENT_MSG_DELETE, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_MSG_DELETEALL = \'' . htmlspecialchars(_JOOMLACOMMENT_MSG_DELETEALL, ENT_QUOTES) . '\';';
	$html .= ' var _JOOMLACOMMENT_WRITECOMMENT = \'' . htmlspecialchars(_JOOMLACOMMENT_WRITECOMMENT, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_SENDFORM = \'' . htmlspecialchars(_JOOMLACOMMENT_SENDFORM, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_EDITCOMMENT = \'' . htmlspecialchars(_JOOMLACOMMENT_EDITCOMMENT, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_EDIT = \'' . htmlspecialchars(_JOOMLACOMMENT_EDIT, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_FORMVALIDATE = \'' . htmlspecialchars(_JOOMLACOMMENT_FORMVALIDATE, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_FORMVALIDATE_CAPTCHA = \'' . htmlspecialchars(_JOOMLACOMMENT_FORMVALIDATE_CAPTCHA, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_FORMVALIDATE_CAPTCHA_FAILED = \'' . htmlspecialchars(_JOOMLACOMMENT_FORMVALIDATE_CAPTCHA_FAILED, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_FORMVALIDATE_EMAIL = \'' . htmlspecialchars(_JOOMLACOMMENT_FORMVALIDATE_EMAIL, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_ANONYMOUS = \'' . htmlspecialchars(_JOOMLACOMMENT_ANONYMOUS, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_BEFORE_APPROVAL = \'' . htmlspecialchars(_JOOMLACOMMENT_BEFORE_APPROVAL, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_REQUEST_ERROR = \'' . htmlspecialchars(_JOOMLACOMMENT_REQUEST_ERROR, ENT_QUOTES) . '\';';
*/    
    $html .= " var _JOOMLACOMMENT_MSG_DELETE 	= \"" . _JOOMLACOMMENT_MSG_DELETE . "\";";
    $html .= " var _JOOMLACOMMENT_MSG_DELETEALL = \"" . _JOOMLACOMMENT_MSG_DELETEALL . "\";";
	$html .= " var _JOOMLACOMMENT_WRITECOMMENT 	= \"" . _JOOMLACOMMENT_WRITECOMMENT . "\";";
    $html .= " var _JOOMLACOMMENT_SENDFORM 		= \"" . _JOOMLACOMMENT_SENDFORM . "\";";
    $html .= " var _JOOMLACOMMENT_EDITCOMMENT 	= \"" . _JOOMLACOMMENT_EDITCOMMENT . "\";";
    $html .= " var _JOOMLACOMMENT_EDIT 			= \"" . _JOOMLACOMMENT_EDIT . "\";";
    $html .= " var _JOOMLACOMMENT_FORMVALIDATE 	= \"" . _JOOMLACOMMENT_FORMVALIDATE . "\";";
    $html .= " var _JOOMLACOMMENT_FORMVALIDATE_CAPTCHA = \"" . _JOOMLACOMMENT_FORMVALIDATE_CAPTCHA . "\";";
    $html .= " var _JOOMLACOMMENT_FORMVALIDATE_CAPTCHA_FAILED = \"" . _JOOMLACOMMENT_FORMVALIDATE_CAPTCHA_FAILED . "\";";
    $html .= " var _JOOMLACOMMENT_FORMVALIDATE_EMAIL = \"" . _JOOMLACOMMENT_FORMVALIDATE_EMAIL . "\";";
    $html .= " var _JOOMLACOMMENT_ANONYMOUS 	= \"" . _JOOMLACOMMENT_ANONYMOUS . "\";";
    $html .= " var _JOOMLACOMMENT_BEFORE_APPROVAL = \"" . _JOOMLACOMMENT_BEFORE_APPROVAL . "\";";
    $html .= " var _JOOMLACOMMENT_REQUEST_ERROR = \"" . _JOOMLACOMMENT_REQUEST_ERROR . "\";";
    $html .= " var _JOOMLACOMMENT_MSG_NEEDREFRESH = \"" . _JOOMLACOMMENT_MSG_NEEDREFRESH . "\";";
	$html .= "\n</script>\n";
    $ifnocache = JOSC_utils::insertToHead($html);
    $ifnocache .= JOSC_utils::insertToHead("\n<script type='text/javascript' src='$path/jscripts/client.js'></script>\n");
	return $ifnocache;
	}

}

?>
