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
/**
 * @package OnlineBabylon
 */
class onlineScreens {
		function &createTemplate() {
		global $option, $mosConfig_absolute_path;
		require_once( $mosConfig_absolute_path . '/includes/patTemplate/patTemplate.php' );
		$tmpl =& patFactory::createTemplate( $option, true, false );
		$tmpl->setRoot( dirname( __FILE__ ) . '/tmpl' );
		return $tmpl;
	}
	/**
	 * A simple message
	 */
	function onlineb() {
        $tmpl =& onlineScreens::createTemplate();
        $tmpl->setAttribute( 'body', 'src', 'onlinebabylon.html' );

        $tmpl->displayParsedTemplate( 'form' );
	}
}
?>
