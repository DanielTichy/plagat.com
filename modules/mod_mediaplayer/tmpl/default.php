<?php 
/**
* @version 1.1.1 $
* @package Media Player
* @copyright (C) 2007 Daniel Gutierrez Oroncuy
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

echo 
_PlayerDIV_ . "
<script type='text/javascript'>
	var s1 = new SWFObject('" . _URLfiles_ . "player.swf','ply','" . _WIDTH_ . "','" . _HEIGHT_ . "','9','#FFFFFF');
	s1.addParam('allowfullscreen','true');
	s1.addParam('allowscriptaccess','always');
	s1.addParam('wmode','opaque');
	s1.addParam('flashvars','file=" . 
	$MediaPlayer . _BACK_ . _FRONT_ . _LIGHT_ . _CBAR_ . 
	_SKIN_ . _LOGO_ . _ICONS_ .  _VOLUME_ . 
	_AUTOSTART_. _REPEAT_ . _SHUFFLE_ . _STRETCH_ . _BUFFER_ .
	_IMAGE_ . _STREAMING_ . _PLUGINS_ .
	"');
	s1.write('mediaplayer');
</script>";
?>