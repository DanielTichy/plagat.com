<?php
/**
* @version		$Id: helper.php 10079 2008-02-28 13:39:08Z ircmaxell $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modLatestNewsHelperPlusDate
{
	function getList(&$params)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');

		$count		= (int) $params->get('count', 5);
		$catid		= trim( $params->get('catid') );
		$secid		= trim( $params->get('secid') );
		$show_front	= $params->get('show_front', 1);
		$aid		= $user->get('aid', 0);

		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('shownoauth');

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		/*
		$show_date	= $params->get( 'show_date', 0 );
		$show_date_type	= $params->get( 'show_date_type', 0 );
		
		$thumb_embed 		= intval( $params->get( 'thumb_embed', 0 ) );
		$thumb_width 		= intval( $params->get( 'thumb_width', 32 ) );
		$thumb_height 		= intval( $params->get( 'thumb_height', 32 ) );
		$limit 		= intval( $params->get( 'limit', 200 ) );
		$loadorder 			= intval( $params->get( 'loadorder', 1 ) );
		
		$show_more_in 			= intval( $params->get( 'show_more_in', 0 ) );
		$show_more_type 			= intval( $params->get( 'show_more_type', 0 ) );
		$show_date_in_introtext 			= intval( $params->get( 'show_date_in_introtext', 0 ) );
		
		$image_path = $params->get( 'image_path', 'images/stories' );
		*/

		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;

		// User Filter
		switch ($params->get( 'user_id' ))
		{
			case 'by_me':
				$where .= ' AND (created_by = ' . (int) $userId . ' OR modified_by = ' . (int) $userId . ')';
				break;
			case 'not_me':
				$where .= ' AND (created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
				break;
		}

		// Ordering
		switch ($params->get( 'ordering' ))
		{
			case 'm_dsc':
				$ordering		= 'a.modified DESC, a.created DESC';
				break;
			case 'c_dsc':
			default:
				$ordering		= 'a.created DESC';
				break;
		}

		if ($catid)
		{
			$ids = explode( ',', $catid );
			JArrayHelper::toInteger( $ids );
			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
		}
		if ($secid)
		{
			$ids = explode( ',', $secid );
			JArrayHelper::toInteger( $ids );
			$secCondition = ' AND (s.id=' . implode( ' OR s.id=', $ids ) . ')';
		}

		// Content Items only
		$query = 'SELECT a.*, ' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
			' FROM #__content AS a' .
			($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
			' WHERE '. $where .' AND s.id > 0' .
			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
			($catid ? $catCondition : '').
			($secid ? $secCondition : '').
			($show_front == '0' ? ' AND f.content_id IS NULL ' : '').
			' AND s.published = 1' .
			' AND cc.published = 1' .
			' ORDER BY '. $ordering;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		$i		= 0;
		$lists	= array();
		foreach ( $rows as $row )
		{
			$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			$lists[$i]->text = htmlspecialchars( $row->title );
			$lists[$i]->id = htmlspecialchars( $row->id );
			$lists[$i]->introtext = htmlspecialchars( $row->introtext );
			$lists[$i]->created = $row->created;
			$lists[$i]->images = $row->images;
			$i++;
		}

		return $lists;
	}

	function lnd_showThumb($row_images,$row_image,$params,$row_itemid,$row_id){
		$thumb_embed = intval( $params->get( 'thumb_embed', 0 ) );
		$thumb_width = intval( $params->get( 'thumb_width', 32 ) );
		$thumb_height = intval( $params->get( 'thumb_height', 32 ) );
		$aspect = intval( $params->get( 'aspect', 0 ) );

		$default_image_path = "/modules/mod_latestnewsplusdate/latestnewsplusdate/default.gif";
		if ($thumb_embed == 1) 
		{
			echo '<a href="index.php?option=com_content&amp;task=view&amp;id=' . 
			$row_id .'&amp;Itemid=' . $row_itemid .'">';
							
			if (!empty($row_images))
			{
					$img = "/images/stories/" . strtok($row_images,"|\r\n");
					$class="";
					$extra = ' align="left" alt="article thumbnail" ';
					modLatestNewsHelperPlusDate::lnd_thumb_size($img, $thumb_width, $thumb_height, $image, $extra, $class, $aspect);
					
					echo  $image;
						
			}
			else if ($row_image !="")
			{
				echo '<img src="'.$mainframe->getBasePath( 0, true ).'/images/stories/' . $row_image .' " width="' . $thumb_width . '" height="' . $thumb_height .'" style="float: left;" alt="article image" />';
			}
			else {
							
				$img = $default_image_path;
				$class="";
				$extra = ' align="left" alt="article thumbnai" ';
						   	
				modLatestNewsHelperPlusDate::lnd_thumb_size($img, $thumb_width, $thumb_height, $image, $extra, $class, $aspect);
				echo $image;
			}
			echo '</a>';
		}
	
	}
	
	function lnd_thumb_size($file, $wdth, $hgth, &$image, &$xtra, $class, $aspect)
	{
			global $mainframe;

			if($class!='') $xtra .= ' class="'.$class.'"';
			
			// Find the extension of the file
			$ext = substr(strrchr(basename(JPATH_SITE.$file), '.'), 1);
			$thumb = str_replace('.'.$ext, '_lnd_thumb.'.$ext, $file);
			$image = '';
			$image_path = JPATH_SITE.$thumb;

			$image_site = $mainframe->getBasePath( 0, true ).$thumb;
			$found = false;

			if (file_exists($image_path))
			{
				$size = '';
				$wx = $hy = 0;
				if (function_exists( 'getimagesize' ))
				{
					$size = @getimagesize( $image_path );
					if (is_array( $size ))
					{
						$wx = $size[0];
						$hy = $size[1];
						$size = 'width="'.$wx.'" height="'.$hy.'"';
					}
		    	}
		    	if ($wx == $wdth && $hy == $hgth)
		    	//if ( $wx == $wdth )
		    	{
						$found = true;
						$image= '<img src="'.$image_site.'" '.$size.$xtra.' />';
					}
			}
		
			if (!$found)
			{
				$size = '';
				$wx = $hy = 0;
				$size = @getimagesize( JPATH_SITE.$file );
				if (is_array( $size ))
				{
					$wx = $size[0];
					$hy = $size[1];
				}
				
				modLatestNewsHelperPlusDate::lnd_calcsize($wx, $hy, $wdth, $hgth, $aspect);
				switch ($ext)
				{
					case 'jpg':
					case 'jpeg':
					case 'png':
						modLatestNewsHelperPlusDate::lnd_thumbIt(JPATH_SITE.$file,$image_path,$ext,$wdth,$hgth);
						$size = 'width="'.$wdth.'" height="'.$hgth.'"';
						$image= '<img  src="'.$image_site.'" '.$size.$xtra.' />';
						break;
		
					case 'gif':
						if (function_exists("imagegif")) {
							modLatestNewsHelperPlusDate::lnd_thumbIt(JPATH_SITE.$file,$image_path,$ext,$wdth,$hgth);
							$size = 'width="'.$wdth.'" height="'.$hgth.'"';
							$image= '<img src="'.$image_site.'" '.$size.$xtra.' />';
							break;
		        		}
						
					default:
						$size = 'width="'.$wdth.'" height="'.$hgth.'"';
						$image= '<img src="'.$mainframe->getBasePath( 0, true ).$file.'" '.$size.$xtra.' />';
						break;
				}
			}
	}
	
	function lnd_thumbIt ($file, $thumb, $ext, &$new_width, &$new_height) 
	{
			$img_info = getimagesize ( $file );
			$orig_width = $img_info[0];
			$orig_height = $img_info[1];
			
			if($orig_width<$new_width || $orig_height<$new_height)
			{
				$new_width = $orig_width;
				$new_height = $orig_height;
			}
			
			switch ($ext) {
				case 'jpg':
				case 'jpeg':
					$im  = imagecreatefromjpeg($file);
					$tim = imagecreatetruecolor ($new_width, $new_height);
					modLatestNewsHelperPlusDate::lnd_ImageCopyResampleBicubic($tim, $im, 0,0,0,0, $new_width, $new_height, $orig_width, $orig_height);
					imagedestroy($im);
		
					imagejpeg($tim, $thumb, 75);
					imagedestroy($tim);
					break;
	
				case 'png':
					$im  = imagecreatefrompng($file);
					$tim = imagecreatetruecolor ($new_width, $new_height);
					modLatestNewsHelperPlusDate::lnd_ImageCopyResampleBicubic($tim, $im, 0,0,0,0, $new_width, $new_height, $orig_width, $orig_height);
					imagedestroy($im);
	
					imagepng($tim, $thumb, 9);
					imagedestroy($tim);
					break;
	
				case 'gif':
					if (function_exists("imagegif")) {
						$im  = imagecreatefromgif($file);
						$tim = imagecreatetruecolor ($new_width, $new_height);
						modLatestNewsHelperPlusDate::lnd_ImageCopyResampleBicubic($tim, $im, 0,0,0,0, $new_width, $new_height, $orig_width, $orig_height);
						imagedestroy($im);
	
						imagegif($tim, $thumb, 75);
						imagedestroy($tim);
	    			}
					break;
	
				default:
					break;
			}
	}
	
	function lnd_ImageCopyResampleBicubic (&$dst_img, &$src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) 
	{
			if ($dst_w==$src_w && $dst_h==$src_h) {
				$dst_img = $src_img;
				return;
			}
	  		ImagePaletteCopy ($dst_img, $src_img);
			$rX = $src_w / $dst_w;
			$rY = $src_h / $dst_h;
			$w = 0;
			for ($y = $dst_y; $y < $dst_h; $y++) 
			{
				$ow = $w; $w = round(($y + 1) * $rY);
				$t = 0;
				for ($x = $dst_x; $x < $dst_w; $x++) 
				{
					$r = $g = $b = 0; $a = 0;
					$ot = $t; $t = round(($x + 1) * $rX);
					for ($u = 0; $u < ($w - $ow); $u++) 
					{
						for ($p = 0; $p < ($t - $ot); $p++) 
						{
							$c = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $ot + $p, $ow + $u));
							$r += $c['red'];
	          				$g += $c['green'];
	          				$b += $c['blue'];
	          				$a++;
	        			}
					}
					if(!$a) $a = 1;
					ImageSetPixel ($dst_img, $x, $y, ImageColorClosest ($dst_img, $r / $a, $g / $a, $b / $a));
				}
			}
	}
	
	function lnd_calcsize($srcx, $srcy, &$forcedwidth, &$forcedheight, $aspect) 
	{
			if ($forcedwidth > $srcx)  $forcedwidth = $srcx;
			if ($forcedheight > $srcy) $forcedheight = $srcy;
			if ( $forcedwidth <=0 && $forcedheight > 0) {
				$forcedwidth = round(($forcedheight * $srcx) / $srcy);
			}else if ( $forcedheight <=0 && $forcedwidth > 0) {
				$forcedheight = round(($forcedwidth * $srcy) / $srcx);
			}else if ( $forcedwidth/$srcx>1 && $forcedheight/$srcy>1) {
				//May not make an image larger!
				$forcedwidth = $srcx;
				$forcedheight = $srcy;
			}
			else if ( $forcedwidth/$srcx<1 && $aspect) {
				//$forcedheight = round(($forcedheight * $forcedwidth) /$srcx);
				$forcedheight = round( ($srcy/$srcx) * $forcedwidth );
				$forcedwidth = $forcedwidth;
			}
	}
	
	function lnd_limittext($txt,$limit)
	{
		    $len=strlen($txt);
		    if ($len <= $limit)
		        return $txt;
		    else
		    {
		        $txt = substr($txt,0,$limit);
		        $pos = strrpos($txt," ");
		        if($pos >0)
				{
			        $txt = substr($txt,0,$pos);
			    	if (($tpos =strrpos($txt,"<")) >  strrpos($txt,">") && $tpos>0)
			    	{
				  		$txt = substr($txt,0,$tpos-1);
				  	}
				}
		        return $txt . "...";
		    }
	}

	function unhtmlentities($string)
	{
	    // replace numeric entities
	    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
	    $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
	    // replace literal entities
	    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
	    $trans_tbl = array_flip($trans_tbl);
	    return strtr($string, $trans_tbl);
	}	
}
