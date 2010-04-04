<?php
/**
* @version		$Id: helper.php 10616 2008-08-06 11:06:39Z hackwar $
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
if (!class_exists('modJABulletin')) {
	jimport('dwp');
						
	class modJABulletin
	{
		function getMostRead(&$params)
		{
			global $mainframe;
	
			$db			=& JFactory::getDBO();
			$user		=& JFactory::getUser();
	
			$count		= intval($params->get('count', 5));
			$catid		= trim($params->get('catid'));
			$secid		= trim($params->get('secid'));
			$show_front	= $params->get('show_front', 1);
			$aid		= $user->get('aid', 0);
	
			$contentConfig = &JComponentHelper::getParams( 'com_content' );
			$access		= !$contentConfig->get('shownoauth');
	
			$nullDate	= $db->getNullDate();
			$date =& JFactory::getDate();
			$now  = $date->toMySQL();
	
			if ($catid) {
				$ids = explode( ',', $catid );
				JArrayHelper::toInteger( $ids );
				$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
			}
			if ($secid) {
				$ids = explode( ',', $secid );
				JArrayHelper::toInteger( $ids );
				$secCondition = ' AND (s.id=' . implode( ' OR s.id=', $ids ) . ')';
			}
	
			//Content Items only
			$query = 'SELECT a.*,' .
				' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
				' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
				' FROM #__content AS a' .
				' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' .
				' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
				' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
				' WHERE ( a.state = 1 AND s.id > 0 )' .
				' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )' .
				' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'.
				($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
				($catid ? $catCondition : '').
				($secid ? $secCondition : '').
				($show_front == '0' ? ' AND f.content_id IS NULL' : '').
				' AND s.published = 1' .
				' AND cc.published = 1' .
				' ORDER BY a.hits DESC';
			$db->setQuery($query, 0, $count);
			$rows = $db->loadObjectList();
	
			return $rows;
		}
		
		function getLatest(&$params)
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
	
			return $rows;
		}
			
		function parseList (&$rows, &$params) {
			$i		= 0;
			$showimg = $params->get('show_image', 1);
			$w = (int) $params->get('width', 0);
			$h = (int) $params->get('height', 0);
			$showdate = $params->get('show_date', 1);
			$lists	= array();
			foreach ( $rows as $row )
			{
				$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
				$lists[$i]->text = htmlspecialchars( $row->title );
				if ($showdate) {
					$lists[$i]->date = strtotime ($row->modified) ? $row->created:$row->modified;
				}
				if ($showimg) {
					$imgsrc = JAImageTools::getImgUrlFromText(DWP::SearchImageUrlLimitedString($row->introtext.$row->fulltext));
					if(!$imgsrc){
						$imgsrc = JAImageTools::getImgUrlFromText($row->introtext.$row->fulltext);
					}
					$imgsrc1 = JAImageTools::resizeImage($imgsrc, $w, $h);
					if ($imgsrc1) {
						if ($imgsrc1 == $imgsrc) {
							$width = $w?"width=\"$w\"":"";
							$height = $h?"height=\"$h\"":"";
							$lists[$i]->image = "<img src=\"$imgsrc1\" alt=\"{$lists[$i]->text}\" title=\"{$lists[$i]->text}\" $width $height />";
						} else {
							$lists[$i]->image = "<img src=\"$imgsrc1\" alt=\"{$lists[$i]->text}\" title=\"{$lists[$i]->text}\" />";
						}
					}
				}
				$i++;
			}
			return $lists;
		}
		
		function getList (&$params) {
			$type		=  $params->get('type', 'latest');
			switch ($type) {
				case 'latest':
					$rows = modJABulletin::getLatest ($params);
					break;
				case 'mostread':
					$rows = modJABulletin::getMostRead ($params);
					break;
			}
			if (isset ($rows)) return modJABulletin::parseList ($rows, $params);
			return null;		
		}
		
		function addStyleFiles () {
			global $mainframe;
			$filename = 'ja.bulletin.css';
			$tplpath = DS.'templates'.DS.$mainframe->getTemplate().DS.'css'.DS;
			$tplurl = '/templates/'.$mainframe->getTemplate().'/css/';
			$modurl = '/modules/mod_jabulletin/tmpl/';
			$cssurl = $tplurl;
			if(!file_exists(JPATH_SITE.$tplpath.$filename)){
				$cssurl = $modurl;
			}
			$cssurl = JURI::base().$cssurl;
			//JHTML::stylesheet ($filename, $cssurl);
			?>
				<script type="text/javascript">
				//<![CDATA[ 
					var links = document.getElementsByTagName ('link');
					var included = false;
					if (links.length) {
						for (var i=0;i<links.length;i++) {
							if (links[i].getAttribute('href').test('ja.bulletin.css')) {
								included = true;
								break;
							}
						}
					}
					if (!included) {
						var script = document.createElement('link');
						script.setAttribute('type', 'text/css');
						script.setAttribute('rel', 'stylesheet');
						script.setAttribute('href', '<?php echo $cssurl.$filename; ?>');
						document.getElementsByTagName("head")[0].appendChild(script);
					}
				//]]>
				</script>
			<?php
		}	
	}
}
if (!class_exists ('JAImageTools')) {
	class JAImageTools {
		function getImgUrlFromText ($text) {
			$regex = "/\<img.+src\s*=\s*\"([^\"]*)\"[^\>]*\>/";
			preg_match ($regex, $text, $matches);
			$images = (count($matches)) ? $matches : array();
			$image = count($images)>1?$images[1]:'';
			return $image;
		}	
		
		function resizeImage ( &$img, $width, $height ) {
			if(!$img) return '';
			if (!function_exists ('imagejpeg')) return $img;
			$img = str_replace(JURI::base(),'',$img);
			$img = rawurldecode($img);
			$imagesurl = (file_exists(JPATH_SITE .'/'.$img)) ? JURI::base().JAImageTools::jaResize($img,$width,$height) :  '';
			return $imagesurl;
		}

		function jaResize($image,$max_width,$max_height){
			$path = JPATH_SITE;
			$sizeThumb = getimagesize(JPATH_SITE.'/'.$image);
			$width = $sizeThumb[0];
			$height = $sizeThumb[1];
			if(!$max_width && !$max_height) {
				$max_width = $width;
				$max_height = $height;
			}else{
				if(!$max_width) $max_width = 1000;
				if(!$max_height) $max_height = 1000;
			}
			$x_ratio = $max_width / $width;
			$y_ratio = $max_height / $height;
			if (($width <= $max_width) && ($height <= $max_height) ) {
				$tn_width = $width;
				$tn_height = $height;
			} else if (($x_ratio * $height) < $max_height) {
				$tn_height = ceil($x_ratio * $height);
				$tn_width = $max_width;
			} else {
				$tn_width = ceil($y_ratio * $width);
				$tn_height = $max_height;
			}
			// read image
			$ext = strtolower(substr(strrchr($image, '.'), 1)); // get the file extension
			$rzname = strtolower(substr($image, 0, strpos($image,'.')))."_{$tn_width}_{$tn_height}.{$ext}"; // get the file extension
			$resized = $path.'/images/resized/'.$rzname;
			if(file_exists($resized)){
				$smallImg = getimagesize($resized);
				if (($smallImg[0] <= $tn_width && $smallImg[1] == $tn_height) ||
				($smallImg[1] <= $tn_height && $smallImg[0] == $tn_width)) {
					return "images/resized/".$rzname;
				}
			}
			if(!file_exists($path.'/images/resized/') && !mkdir($path.'/images/resized/',0755)) return '';
			$folders = explode('/',$image);
			$tmppath = $path.'/images/resized/';
			for($i=0;$i < count($folders)-1; $i++){
				if(!file_exists($tmppath.$folders[$i]) && !mkdir($tmppath.$folders[$i],0755)) return '';
				$tmppath = $tmppath.$folders[$i].'/';
			}
			switch ($ext) {
				case 'jpg':     // jpg
				$src = imagecreatefromjpeg(JPATH_SITE.'/'.$image);
				break;
				case 'png':     // png
				$src = imagecreatefrompng(JPATH_SITE.'/'.$image);
				break;
				case 'gif':     // gif
				$src = imagecreatefromgif(JPATH_SITE.'/'.$image);
				break;
				default:
			}
			$dst = imagecreatetruecolor($tn_width,$tn_height);
			//imageantialias ($dst, true);
			if (function_exists('imageantialias')) imageantialias ($dst, true);
			imagecopyresampled ($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
			imagejpeg($dst, $resized, 90); // write the thumbnail to cache as well...
			return "images/resized/".$rzname;
		}
	
	}
}