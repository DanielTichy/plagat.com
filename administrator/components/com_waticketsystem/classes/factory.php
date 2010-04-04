<?php
/**
 * @version		$Id: factory.php 66 2009-03-31 14:18:46Z webamoeba $
 * @package		wats
 * @package		classes
 * @license		GNU/GPL
 */

/**
 *
 * @static
 */
class WFactory {

    /**
	 * Gets teh globally available WConfig
	 *
	 * @return JTable
	 */
    function &getConfig() {
	    return WConfig::getInstance();
	}

}

?>