<?php
/**
 * @version		$Id: dbhelper.php 66 2009-03-31 14:18:46Z webamoeba $
 * @package		wats
 * @package		classes
 * @license		GNU/GPL
 */

/**
 *
 * @static
 */
class WDBHelper {

    /**
	 * Quotes an identifier
	 *
	 * @param $identifier string
	 * @param $db JDatabase
	 */
    function nameQuote($identifier, $db = null) {
	    // prepapre DBO
		if (is_object($db)) {
			$db =& $db;
        } else {
            $db =& JFactory::getDBO();
		}
		
		// split the identifier up into names
		$names = explode(".", $identifier);
		
		// build the return value
		foreach($names AS $position => $name) {
		    $names[$position] = $db->nameQuote($name);
		}
		
		return implode(".", $names);
	}

}

?>