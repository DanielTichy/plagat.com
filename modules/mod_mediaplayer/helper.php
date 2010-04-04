<?php
/**
* @version 1.0 $id Helper.php
* @package Flash Media Player
* @copyright (C) 2007 Daniel Gutierrez Oroncuy
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*****************************************************************************
* Integracion del script creado por Jeroen Wijering www.jeroenwijering.com	 *
* Módulo Desarrollado por Daniel Gutierrez www.gutierrez.nu					 *
*****************************************************************************
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modMediaPlayerHelper 
{
	function getMediaPlayer($playing)
	{
		if($playing==0)
		{
			$playlistParams = _URLmedia_ . _FILE_;
		}
		else
		{
			$playlistParams = _URLfiles_ . _FILE_ . _PLAYLIST_ . _PSIZE_ . _ITEM_;			
		}
		return $playlistParams;
	}	

}