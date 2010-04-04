<?php 
/**
* @version 1.1.1 $id constants.php
* @package Media Player
* @copyright (C) 2007 Daniel Gutierrez Oroncuy
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
//URLs Media player
$url = 	JURI::base().'modules/mod_mediaplayer/';
define( '_URLfiles_', $url . 'files/' );
define( '_URLmedia_', $url . 'media/' );
define( '_URLskins_', $url . 'skins/' );
//Constant Div Player
$link = JHTML::link('http://www.macromedia.com/go/getflashplayer', JText::_('GET_THE_PLAYER'), '');
define( '_PlayerDIV_', '<div id="mediaplayer">' . $link . ' ' . JText::_('TO_SEE_THIS_PLAYER'). ' </div>' );
//Constant Var
define( '_FILE_'	, $file );
//Design
define( '_WIDTH_'	, $width );
define( '_HEIGHT_'	, $height );
define( '_BACK_'	, '&backcolor=' 	. $backcolor );
define( '_LIGHT_'	, '&lightcolor=' 	. $lightcolor );
define( '_FRONT_'	, '&frontcolor=' 	. $frontcolor );
define( '_CBAR_' 	, '&controlbar='	. $cbar );
if($image!='')
{
	define( '_IMAGE_' , '&image=' . $image );
}
//Skins
if($skin == '')
{
	define( '_SKIN_'	, '' );
}
else
{
	define( '_SKIN_'	, '&skin=' 		. _URLskins_ . $skin );
}
//Logo
if($logo == '')
{
	define( '_LOGO_'	, '' );
}
else
{
	define( '_LOGO_'	, '&logo=' 		. $logo );
}
//Icons
if($icons == 0)
{
	define( '_ICONS_'	, '' );
}
else
{
	define( '_ICONS_'	, '&icons=false' );
}
//Playlist params
define( '_PLAYLIST_', '&playlist=' 		. $playlist );
define( '_PSIZE_'	, '&playlistsize=' 	. $psize );
define( '_ITEM_'	, '&item=' 			. $item );
//Volume
define( '_VOLUME_'	, '&volume=' 		. $volume );
//Autostart
if ($autostart == 0)
{
	define( '_AUTOSTART_','&autostart=true' );
}
else
{
	define( '_AUTOSTART_','' );
}
//Repeat
define( '_REPEAT_'	, '&repeat='     	. $repeat );
//Random
if($shuffle == 0)
{
	define( '_SHUFFLE_'	, '&shuffle=true' );
}
else
{
	define( '_SHUFFLE_'	, '' );
}
//Stretch & Buffer
define( '_STRETCH_' , '&stretching=' 	. $stretch );
define( '_BUFFER_'	, '&bufferlength=' 	. $buffer );
if( $streaming == 0 )
{
	define('_STREAMING_' , '&streamer=' . _URLfiles_ . 'xmoov.php' );
}
else
{
	define('_STREAMING_' , '' );
}
define( '_PLUGINS_' , $plugins );