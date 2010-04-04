<?php
/**
* @package com_OnlineBabylon
* @version 1.6.0
* @copyright Copyright  2008 by masih.ad@gmail.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
*/
/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
require_once( $mainframe->getPath( 'admin_html' ) );
$task = mosGetParam( $_REQUEST, 'task', '' );
switch ($task) {
	default:
		onlineScreens::onlineb();
		break;
}
?>
