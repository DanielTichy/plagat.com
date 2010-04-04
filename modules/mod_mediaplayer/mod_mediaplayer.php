<?php
/**
* @version 1.1.1 $
* @package Media Player
* @copyright (C) 2007 Daniel Gutierrez Oroncuy
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// No direct access
defined('_JEXEC') or die('Restricted access');
// Include the mediaplayer functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$playing	= 	$params->get( 'playing' );
$file		=	$params->get( 'file' );
//Design
$width		=	$params->get( 'width' );
$height		=	$params->get( 'height' );
$backcolor	=	$params->get( 'backcolor' );
$lightcolor	=	$params->get( 'lightcolor' );
$frontcolor	=	$params->get( 'frontcolor' );
$cbar		=	$params->get( 'cbar' );
$image		=	$params->get( 'image' );
$skin		=	$params->get( 'skin' );
$logo		=	$params->get( 'logo' );
$icons		=	$params->get( 'icons' );
$playlist	=	$params->get( 'playlist' );
$psize		= 	$params->get( 'psize' );
//Behaviour
$volume 	= 	$params->get( 'volume' );
$item		=	$params->get( 'item' );
$autostart 	= 	$params->get( 'autostart' );
$repeat 	= 	$params->get( 'repeat' );
$shuffle 	= 	$params->get( 'shuffle' );
$stretch	=	$params->get( 'stretch' );
$buffer		=	$params->get( 'buffer' );
$streaming 	=	$params->get( 'streaming' );
$plugins	= 	$params->get( 'plugins' );

//Call swfobject.js
$script = JHTML::script( 'modules/mod_mediaplayer/files/swfobject.js', false, false);
$GLOBALS['mainframe']->addCustomHeadTag($script);

require_once (dirname(__FILE__).DS.'constants.php');

$MediaPlayer = modMediaPlayerHelper::getMediaPlayer($playing);

require(JModuleHelper::getLayoutPath('mod_mediaplayer'));
