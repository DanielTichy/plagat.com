<?php

/**
* Gavick Embed Media v.1.0
* @package Joomla!
* @Copyright (C) 2008 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.5 $
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

class gkEmbedMediaHelper{
	
	var $module_unique_id, $url, $width, $height, $type, $yt_related, $yt_border, $yt_style, $yt_color1, $yt_color2, $gv_fs, $veoh_fs, $veoh_bg;
	
	/**
		VARIABLES VALIDATION
	**/
	
	function validateVariables(&$params){
		$this->module_unique_id = $params->get('module_unique_id','gk_news_show-1');
        $this->url = $params->get('url','');
		$this->height = $params->get('height', 0);
		$this->width = $params->get('width', 0);
       	$this->type = $params->get('type', 'youtube');
        // YouTube advanced configuration
		$this->yt_related = $params->get('yt_related', 1);
		$this->yt_border = $params->get('yt_border', 1);
        $this->yt_style = $params->get('yt_style', 0);
        $this->yt_color1 = $params->get('yt_color1', '0x000000');
		$this->yt_color2 = $params->get('yt_color2', '0x000000');
		// Google video player
		$this->gv_fs = $params->get('gv_fs', 1);
		// Veoh player
		$this->veoh_fs = $params->get('veoh_fs', 1);
		$this->veoh_bg = $params->get('veoh_bg', '#000000');
		$this->veoh_autoplay = $params->get('veoh_autoplay', 1);
		// Daily motion player
		$this->dm_size = $params->get('dm_size', 1);
		$this->dm_fs = $params->get('dm_fs', 1);
		$this->dm_bg = $params->get('dm_bg', '000000');
		$this->dm_glow = $params->get('dm_glow', '000000');
		$this->dm_fg = $params->get('dm_fg', 'FFFFFF');
		$this->dm_special = $params->get('dm_special', '000000');
		$this->dm_autoplay = $params->get('dm_autoplay', 1);
		$this->dm_related = $params->get('dm_related', 1);
		// Revver player
		$this->rev_fs = $params->get('rev_fs', 1);
		// Viddler player
		$this->vid_autoplay = $params->get('vid_autoplay', 1);
		$this->vid_fs = $params->get('vid_fs', 1);
		$this->vid_player = $params->get('vid_player', 1);
		// other player
		$this->other = $params->get('other','');
		// fix for 0x0 size
		if($this->width == 0) $this->width = 480;
		if($this->height == 0) $this->height = 320;
	}
	
	function renderLayout(){
		echo '<div id="'.$this->module_unique_id.'" style="position: relative; z-index: 0;">';
		require(JModuleHelper::getLayoutPath('mod_gk_embed_media', $this->type));
		echo '</div>';
	}
}

?>