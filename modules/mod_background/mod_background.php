<?php
/**
* @version 1.0 $ 13-10-2008 15:26:10
* @package Background
* @copyright (C) 2008 Joomla4more (http://www.joomla4more.com)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/**
This module used code from the mod_random_image (standard joomla module) by stingrey */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$image = modBackgroundHelper::getBackgroundImage($params);
require(JModuleHelper::getLayoutPath('mod_background'));