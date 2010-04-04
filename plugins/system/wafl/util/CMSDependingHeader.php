<?php
/**
 * CMSDependingHeader.php
 *
 * PHP version 5
 * 
 * In this file any header stuff can be defined that depends on the CMS.
 * This header will be included at the top of every WAFL-plugin file.
 * 
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Util
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://www.wafl.ugent.be
 */

defined('_JEXEC') or die('Stay away from my waffles!');

/**
 * Enhances plugin portability to a platform where DS is not defined.
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
} 
/**
 * Defining the WAFL directory (plugins/system/wafl) in terms of a Joomla! constant.
 */
if (!defined('WAFL_DIR')) {
    define('WAFL_DIR', JPATH_PLUGINS . DS . 'system' . DS . 'wafl');
}
?>