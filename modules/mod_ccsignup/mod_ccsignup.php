<?php
/**
 * Constant Contact Newsletter Sign Up Module
 * Module Version 1.0 - Joomla! Version 1.5
 * Author: Michael Babcock
 * info@trinitronic.com
 * http://trinitronic.com
 * Copyright (c) 2009 TriniTronic. All Rights Reserved. 
 * License: GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.html
 * mod_ccsignup is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );
 
//$items = modCCSignupHelper::getItems( $params );
require( JModuleHelper::getLayoutPath( 'mod_ccsignup' ) );
?>

