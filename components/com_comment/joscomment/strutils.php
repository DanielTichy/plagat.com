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

class JOSC_strutils {

    function str_fill($len, $filler)
    {
	$result = "";
	for ($i = 0; $i < $len; $i++)
	$result .= $filler;
	return $result;
    }

    function php_compat_str_ireplace($search, $replace, $subject, &$count)
    {
	if (is_string($search) && is_array($replace)) {
	    user_error('Array to string conversion', E_USER_NOTICE);
	    $replace = (string) $replace;
	}
	if (!is_array($search)) {
	    $search = array ($search);
	}
	$search = array_values($search);
	if (!is_array($replace)) {
	    $replace_string = $replace;

	    $replace = array ();
	    for ($i = 0, $c = count($search); $i < $c; $i++) {
		$replace[$i] = $replace_string;
	    }
	}
	$replace = array_values($replace);
	$length_replace = count($replace);
	$length_search = count($search);
	if ($length_replace < $length_search) {
	    for ($i = $length_replace; $i < $length_search; $i++) {
		$replace[$i] = '';
	    }
	}
	$was_array = false;
	if (!is_array($subject)) {
	    $was_array = true;
	    $subject = array ($subject);
	}
	$count = 0;
	foreach ($subject as $subject_key => $subject_value) {
	    foreach ($search as $search_key => $search_value) {
		$segments = explode(strtolower($search_value), strtolower($subject_value));
		$count += count($segments) - 1;
		$pos = 0;
		foreach ($segments as $segment_key => $segment_value) {
		    $segments[$segment_key] = substr($subject_value, $pos, strlen($segment_value));
		    $pos += strlen($segment_value) + strlen($search_value);
		}
		$subject_value = implode($replace[$search_key], $segments);
	    }

	    $result[$subject_key] = $subject_value;
	}
	if ($was_array === true) {
	    return $result[0];
	}
	return $result;
    }

}

if (!function_exists('str_ireplace')) {
    function str_ireplace($search, $replace, $subject, $count = null)
    {
	return JOSC_strutils::php_compat_str_ireplace($search, $replace, $subject, $count);
    }
}

?>