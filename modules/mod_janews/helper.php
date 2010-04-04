<?php
/*------------------------------------------------------------------------
# JA Rutile for Joomla 1.5 - Version 1.0 - Licence Owner JA122250
# ------------------------------------------------------------------------
# Copyright (C) 2004-2008 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');
if(!defined('_JA_NEWS_')){
	define ('_JA_NEWS_', 1);
	
	require_once (JPATH_SITE . '/components/com_content/helpers/route.php');

	class modJaNewsHelper
	{
		function replaceImage( &$row, $align, $autoresize, $maxchars, $showimage, $width = 0, $height = 0, $hiddenClasses = '' ) {
			global $database, $_MAMBOTS, $current_charset;
			$regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
			
			preg_match ($regex, $row->introtext, $matches);
			if(!count($matches)) preg_match ($regex, $row->fulltext, $matches);
			$images = (count($matches)) ? $matches : array();
			$image = '';
			if (count($images)) $image = trim($images[2]);
		  $align = $align?"align=\"$align\"":"";
			if ($image && $showimage) {
				if ($autoresize && function_exists('imagecreatetruecolor') 
					&& ($image1 = modJANewsHelper::processImage ( $image, $width, $height ))) {
						$image = "<img src=\"".$image1."\" alt=\"{$row->title}\" $align />";
				} else {
					$width = $width?"width=\"$width\"":"";
					$height = $height?"height=\"$height\"":"";
					$image = "<img src=\"".$image."\" alt=\"{$row->title}\" $width $height $align />";
				}
			} else $image = '';

			$regex1 = "/\<img[^\>]*>/";
			$row->introtext = preg_replace( $regex1, '', $row->introtext );
			$regex1 = "/<div class=\"mosimage\".*<\/div>/";
			$row->introtext = preg_replace( $regex1, '', $row->introtext );
			$row->introtext = trim($row->introtext);
			$row->introtext1 = $row->introtext;
			if ($maxchars && strlen ($row->introtext) > $maxchars) {
				$doc = JDocument::getInstance (); 
				if (function_exists('mb_substr')) {
				$row->introtext1 = SmartTrim::mb_trim($row->introtext, 0, $maxchars, $doc->_charset);
				} else {
				$row->introtext1 = SmartTrim::trim($row->introtext, 0, $maxchars);
				}
			}
			// clean up globals
			return $image;
		}

		function processImage ( &$img, $width, $height ) {
			if(!$img) return;
			$img = str_replace(JURI::base(),'',$img);
			$img = rawurldecode($img);
			$imagesurl = (file_exists(JPATH_SITE .'/'.$img)) ? modJANewsHelper::jaResize($img,$width,$height) :  '' ;
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

		function calModStyle ($cols) {
			$modules = array();
			switch ($cols) {
				case 0: return null;
				break;
				case 1: 
					$modules[0]['class'] = "";
					$modules[0]['width'] = "100%";
					$modules[0]['subwidth'] = "100%";
					break;
				case 2: 
					$modules[0]['class'] = "-left";
					$modules[0]['width'] = "49.9%";
					$modules[0]['subwidth'] = "95%";
					$modules[1]['class'] = "-right";
					$modules[1]['width'] = "49.9%";
					$modules[1]['subwidth'] = "95%";
					break;
				default: 

					$width1 = round(99.6/($cols-0.1), 2);
					$width2 = round((99.6 - $width1*($cols-2))/2, 2);

					for ($i=1; $i<$cols - 1; $i++){
						$modules[$i]['class'] = "-center";
						$modules[$i]['width'] = $width1."%";
						$modules[$i]['subwidth'] = "90%";
					}
					$modules[0]['class'] = "-left";
					$modules[0]['width'] = $width2."%";
					$modules[0]['subwidth'] = "95%";
					$modules[$cols - 1]['class'] = "-right";
					$modules[$cols - 1]['width'] = $width2."%";
					$modules[$cols - 1]['subwidth'] = "95%";
					break;
			}
			return $modules;
		}

		function unhtmlentities($string) 
		{
			$trans_tbl = array("&lt;" => "<", "&gt;" => ">", "&amp;" => "&");
			return strtr($string, $trans_tbl);
		}
		
		function getList($catorsec, $catid, $items, $ordering,$showcontentfrontpage=false)
		{
			global $mainframe;

			$db 	=& JFactory::getDBO();
			$user 	=& JFactory::getUser();
			$aid	= $user->get('aid', 0);

			$contentConfig	= &JComponentHelper::getParams( 'com_content' );
			$noauth			= !$contentConfig->get('shownoauth');

			jimport('joomla.utilities.date');
			$date = new JDate();
			$now = $date->toMySQL();

			$nullDate = $db->getNullDate();

			// query to determine article count
			$query = 'SELECT a.*,cc.description as catdesc, cc.title as cattitle,s.description as secdesc, s.title as sectitle,' .
				' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
				' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
				' CASE WHEN CHAR_LENGTH(s.alias) THEN CONCAT_WS(":", s.id, s.alias) ELSE s.id END as secslug'.
				' FROM #__content AS a' .
				' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
				' INNER JOIN #__sections AS s ON s.id = a.sectionid';
			$query .=	' WHERE a.state = 1 ' .
				($noauth ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
				' AND (a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' ) ' .
				' AND (a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )' .
				(($catorsec)?"\n AND cc.id=" . (int) $catid:' AND s.id=' . (int) $catid) .
				' AND cc.section = s.id' .
				' AND cc.published = 1' .
				' AND s.published = 1';
			if(!$showcontentfrontpage) {
				$query .=	' AND a.id not in (SELECT content_id FROM #__content_frontpage )';
			}		
				
			//$query .= ' ORDER BY a.created DESC' ;
			$query .= ' ORDER BY a.'.$ordering;
			
			$db->setQuery($query, 0, $items);
			$rows = $db->loadObjectList();
  		global $mainframe;
  		JPluginHelper::importPlugin('content');
  		$dispatcher	   =& JDispatcher::getInstance();
  		$params 	   =& $mainframe->getParams('com_content');
  		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
  
  		for($i=0;$i<count($rows);$i++) {
  		  $rows[$i]->text = $rows[$i]->introtext;
  		  $results = $dispatcher->trigger('onPrepareContent', array (& $rows[$i], & $params, $limitstart));
   		  $rows[$i]->introtext = $rows[$i]->text;
      }
			return $rows;
		}

		function getHLNews($items) {
			$db 	=& JFactory::getDBO();
			$contentConfig	= &JComponentHelper::getParams( 'com_content' );
			$noauth			= !$contentConfig->get('shownoauth');
			$user		=& JFactory::getUser();
			$aid	= $user->get('aid', 0);

			jimport('joomla.utilities.date');
			$date = new JDate();
			$now = $date->toMySQL();

			$nullDate = $db->getNullDate();
			$query = "SELECT a.*,"
					. "\n CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(':', a.id, a.alias) ELSE a.id END as slug,"
					. "\n CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(':', cc.id, cc.alias) ELSE cc.id END as catslug"
					. "\n FROM #__content AS a"
					. "\n INNER JOIN #__content_frontpage AS f ON f.content_id = a.id"
					. "\n INNER JOIN #__categories AS cc ON cc.id = a.catid"
					. "\n WHERE ( a.state = 1 AND a.sectionid > 0 "
					. ' AND (a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' ) ' 
					. ' AND (a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )' 
					. ($noauth ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid : '')
					. "\n AND cc.published = 1 )"
					. "\n ORDER BY f.ordering ASC";
			$db->setQuery($query, 0, $items);
			$news = $db->loadObjectList();

  		global $mainframe;
  		JPluginHelper::importPlugin('content');
  		$dispatcher	   =& JDispatcher::getInstance();
  		$params 	   =& $mainframe->getParams('com_content');
  		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
  
  		for($i=0;$i<count($news);$i++) {
   		  $news[$i]->text = $news[$i]->introtext;
 		    $results = $dispatcher->trigger('onPrepareContent', array (& $news[$i], & $params, $limitstart));
   		  $news[$i]->introtext = $news[$i]->text;
      }

			return $news;
		}

		function getFile($name, $modPath, $tmplPath) {
			if (file_exists(JPATH_SITE.DS.$tmplPath.$name)) {
				return $tmplPath.$name;
			}
			return $modPath.$name;
		}

		function getAllCatIds ($catorsec) {
			$db 	=& JFactory::getDBO();
			if($catorsec){
				$query = "SELECT id FROM #__categories WHERE published = 1;";
			}
			else{
				$query = "SELECT id FROM #__sections WHERE published = 1;";
			}
			$db->setQuery($query);
			$c = $db->loadObjectList();
			$catids = array();
			foreach($c as $_c) $catids[] = $_c->id;
			return $catids;
		}

		function getAllCatIdsBySection ($sectionid) {
			$db 	=& JFactory::getDBO();
			$query = "SELECT id FROM #__categories WHERE section = $sectionid;";

			$db->setQuery($query);
			$c = $db->loadObjectList();
			$catids = array();
			foreach($c as $_c) $catids[] = $_c->id;
			return $catids;
		}
		
		function getModuleParameters($modid) {
			$db 	=& JFactory::getDBO();
			$query = "SELECT params FROM #__modules WHERE id = $modid;";
			$db->setQuery($query);
			return $db->loadResult();
    }

		function getItemid($sectionid) {
			$contentConfig	= &JComponentHelper::getParams( 'com_content' );
			$noauth			= !$contentConfig->get('shownoauth');
			$user		=& JFactory::getUser();
			$aid	= $user->get('aid', 0);
			$db 	=& JFactory::getDBO();
			$query = "SELECT id FROM #__menu WHERE `link` like '%option=com_content%view=section%id=$sectionid%'"
					. ' AND published = 1'
					. ($noauth ? ' AND access <= ' .(int) $aid : '');
					
			$db->setQuery($query);
			return $db->loadResult();
    }
	}
  	
  class SmartTrim {    
    /*
      $hiddenClasses: Class that have property display: none or invisible.
    */
    function mb_trim($strin, $pos=0, $len=10000, $hiddenClasses = '', $encoding='utf-8' ) {
			mb_internal_encoding($encoding);
      $strout = trim($strin);
      
      $pattern = '/(<[^>]*>)/';
      $arr = preg_split($pattern, $strout, -1, PREG_SPLIT_DELIM_CAPTURE);
      $left = $pos;
      $length = $len;
      $strout = '';
      for($i=0;$i<count($arr);$i++) {
        $arr[$i] = trim($arr[$i]);
        if ($arr[$i] == '') continue;
        if ($i % 2 == 0) {
          if($left > 0) {
            $t = $arr[$i]; 
            $arr[$i] = mb_substr($t, $left);
            $left -= (mb_strlen ($t) - mb_strlen ($arr[$i]));
          }
          
          if ($left <= 0) {
            if ($length > 0) {
              $t = $arr[$i];
              $arr[$i] = mb_substr($t, 0, $length);
              $length -= mb_strlen ($arr[$i]);
              if ($length <= 0) {
                $arr[$i] .= '...';
              }
              
            } else {
              $arr[$i] = '';
            }
          }
        }else{
          if (SmartTrim::isHiddenTag ($arr[$i], $hiddenClasses)) {
            if ($endTag = SmartTrim::getCloseTag($arr, $i)){
              while ($i<$endTag) $strout .= $arr[$i++]."\n";
            }
          }
        }
        $strout .= $arr[$i]."\n";
      }
      //echo $strout;  
      return SmartTrim::toString($arr, $len);
    }

    function trim($strin, $pos=0, $len=10000, $hiddenClasses = '' ) {
      $strout = trim($strin);
      
      $pattern = '/(<[^>]*>)/';
      $arr = preg_split($pattern, $strout, -1, PREG_SPLIT_DELIM_CAPTURE);
      $left = $pos;
      $length = $len;
      $strout = '';
      for($i=0;$i<count($arr);$i++) {
        $arr[$i] = trim($arr[$i]);
        if ($arr[$i] == '') continue;
        if ($i % 2 == 0) {
          if($left > 0) {
            $t = $arr[$i]; 
            $arr[$i] = substr($t, $left);
            $left -= (strlen ($t) - strlen ($arr[$i]));
          }
          
          if ($left <= 0) {
            if ($length > 0) {
              $t = $arr[$i];
              $arr[$i] = substr($t, 0, $length);
              $length -= strlen ($arr[$i]);
              if ($length <= 0) {
                $arr[$i] .= '...';
              }
              
            } else {
              $arr[$i] = '';
            }
          }
        }else{
          if (SmartTrim::isHiddenTag ($arr[$i], $hiddenClasses)) {
            if ($endTag = SmartTrim::getCloseTag($arr, $i)){
              while ($i<$endTag) $strout .= $arr[$i++]."\n";
            }
          }
        }
        $strout .= $arr[$i]."\n";
      }
      //echo $strout;  
      return SmartTrim::toString($arr, $len);
    }
    
    function isHiddenTag ($tag, $hiddenClasses='') {
      //By pass full tag like img
      if (substr($tag, -2)=='/>') return false;
      if (in_array(SmartTrim::getTag($tag), array('script','style'))) return true;
      if (preg_match('/display\s*:\s*none/', $tag)) return true;
      if ($hiddenClasses && preg_match('/class\s*=[\s"\']*('.$hiddenClasses.')[\s"\']*/', $tag)) return true;
    }
    
    function getCloseTag ($arr, $openidx) {
      $tag = trim($arr[$openidx]);
      if(!$openTag = SmartTrim::getTag($tag)) return 0;
      
      $endTag = "</$openTag>";
      $endidx = $openidx+1;
      $i=1;
      while ($endidx<count($arr)) {
        if (trim($arr[$endidx]) == $endTag) $i--;
        if (SmartTrim::getTag($arr[$endidx])==$openTag) $i++;
        if ($i == 0) return $endidx;
        $endidx++;
      }
      return 0;
    }
    
    function getTag ($tag) {
      if (preg_match ('/\A<([^\/>]*)\/>\Z/', trim($tag), $matches)) return ''; //full tag
      if (preg_match ('/\A<([^ \/>]*)([^>]*)>\Z/', trim($tag), $matches)) {
        //echo "[".strtolower($matches[1])."]";
        return strtolower($matches[1]);
      }
      //if (preg_match ('/<([^ \/>]*)([^\/>]*)>/', trim($tag), $matches)) return strtolower($matches[1]);
      return '';
    }
    
    function toString ($arr, $len) {
      $i = 0;
      $stack = new JAStack();
      $length = 0;
      while ($i<count($arr)) {
        $tag = trim($arr[$i++]); 
        if ($tag == '') continue;
        if (SmartTrim::isCloseTag ($tag)){
          if ($ltag = $stack->getLast()){
            if ('</'.SmartTrim::getTag($ltag).'>' == $tag) $stack->pop();
            else $stack->push($tag);
          }
        } else if (SmartTrim::isOpenTag ($tag)) {
          $stack->push ($tag);
        } else if (SmartTrim::isFullTag ($tag)) {
          //echo "[TAG: $tag, $length, $len]\n";
          if ($length < $len)
            $stack->push ($tag);
        } else {
          $length += strlen ($tag);
          $stack->push ($tag);     
        }
      }
      
      return $stack->toString();
    }

    function isOpenTag ($tag) {
      if (preg_match ('/\A<([^\/>]+)\/>\Z/', trim($tag), $matches)) return false; //full tag
      if (preg_match ('/\A<([^ \/>]+)([^>]*)>\Z/', trim($tag), $matches)) return true;
      return false;
    }
    
    function isFullTag ($tag) {
      //echo "[Check full: $tag]\n";
      if (preg_match ('/\A<([^\/>]*)\/>\Z/', trim($tag), $matches)) return true; //full tag
      return false;
    }
    
    function isCloseTag ($tag) {
      if (preg_match ('/<\/(.*)>/', $tag)) return true;
      return false;
    }    
  }
  
  class JAStack {
    var $_arr = null;
    function JAStack() {
      $this->_arr = array();
    }
    
    function push ($item) {
      $this->_arr[count($this->_arr)] = $item;
    }
    function pop () {
      if (!$c = count($this->_arr)) return null;
      $ret = $this->_arr[$c-1];
      unset ($this->_arr[$c-1]);
      return $ret;
    }
    function getLast() {
      if (!$c = count($this->_arr)) return null;
      return $this->_arr[$c-1];
    }
    function toString() {
      $output = '';
      foreach ($this->_arr as $item) {
        $output .= $item."\n";      	
      }
      return $output;
    }
  }	
}
