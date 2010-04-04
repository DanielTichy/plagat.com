<?php
/**
* pPGallery (0.815_RC4) Content Plugin for Joomla! 1.5+
* WebGallery using prettyPhoto display-engine (a jQuery lightbox clone)
*  by Stphane Caron (http://www.no-margin-for-errors.com/projects/prettyPhoto-jquery-lightbox-clone)
* @author    cs
* @copyright This plugin is released under the GNU/GPL License
* @license   GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.event.plugin');

class plgContentPPGallery extends JPlugin {

function onPrepareContent(&$row, &$params, $limitstart) {
  global $mainframe;
  $plugin =& JPluginHelper::getPlugin('content', 'ppgallery');
  $pluginParams = new JParameter( $plugin->params );
 
  // regular expression search string
  $plgstring = $pluginParams->get('plgstring', 'ppgallery'); //plgString to be used e.g. for existing galleries
  $regexp = "~{".$plgstring."(?:\s?(.*?)?)?}(.*?){/".$plgstring."}~is";
  if ( !preg_match($regexp, $row->text) ) {
    return;
  }

  if (preg_match_all($regexp, $row->text, $matches, PREG_PATTERN_ORDER) > 0) {
    $doc =& JFactory::getDocument();
    $relpath = JURI::base(true); // relative path
    $abspath = JPATH_SITE;  //absolute path to J! install path
    $imgroot = "images";  // rootfolder for imgs in Folder-mode
    $csvfile = "ppgallery.txt"; // filename of img-data file for Folder-mode

    //for each found pPGallery
    $ppgallery = -1;
    foreach ($matches[0] as $match) { //[0] whole str, [1] params, [2] path or imgs
      $ppgallery++;
      $override = $matches[1][$ppgallery]; //1st!
      $twidth = plgContentPPGalleryGetOverrides($override, 'width', $pluginParams->get('width', 200));
      $theight= plgContentPPGalleryGetOverrides($override, 'height', $pluginParams->get('height', 200));
      $quality_jpg= plgContentPPGalleryGetOverrides($override, 'quality_j', $pluginParams->get('quality_jpg', 75));
      $quality_png= plgContentPPGalleryGetOverrides($override, 'quality_p', $pluginParams->get('quality_png', 6));
      $padd_v = plgContentPPGalleryGetOverrides($override, 'padding_v', $pluginParams->get('padd_v', 5));
      $padd_h = plgContentPPGalleryGetOverrides($override, 'padding_h', $pluginParams->get('padd_h', 5));
      $thbs_limit = plgContentPPGalleryGetOverrides($override, 'limit', $pluginParams->get('t_limit', ''));
      $thbs_only = plgContentPPGalleryGetOverrides($override, 't_only', $pluginParams->get('t_only', 0));
      $pre_txt = plgContentPPGalleryGetOverrides($override, 'prefix_txt', $pluginParams->get('pre_txt', ''));
      $lnk_pop = plgContentPPGalleryGetOverrides($override, 'link_popup', $pluginParams->get('lnk_pop', ''));
      $caption = plgContentPPGalleryGetOverrides($override, 'caption', $pluginParams->get('caption', 'none'));
      $fixed_w = plgContentPPGalleryGetOverrides($override, 'fixed_w', $pluginParams->get('fixed_w', '1'));
      $itemid = $row->id;

      // 0 = no thumbs only script for pP will be loaded 
      if ($thbs_limit != '0') {

        //IMG Mode: loads imgs from content <img>-tags
        if (preg_match_all('(<img.*?>)', $matches[2][$ppgallery], $img_tag) != 0) {
          $img_tag = $img_tag[0];

          //check and replace non existing imgs
          unset($img_chk);
          foreach ($img_tag as $chk) {
            $chk_file = plgContentPPGalleryGetAttr($chk, 'src');
            if (file_exists($chk_file)) {
              $img_chk[] = $chk;
            }
          $img_tag = $img_chk;
          }
          unset($imageset);
          $i = 0;
          foreach ($img_tag as $img) {
            $imageset[$i] -> img_path = dirname(plgContentPPGalleryGetAttr($img, 'src'));
            $imageset[$i] -> img_file = basename(plgContentPPGalleryGetAttr($img, 'src'));
            $imageset[$i] -> img_alt = plgContentPPGalleryGetAttr($img, 'alt');
            $imageset[$i] -> img_title = plgContentPPGalleryGetAttr($img, 'title');
            $i++ ;
          }
          $imgcount = count($imageset);
        } else {

          //Folder-Mode: loads all imgs from folder into array
          $imgfolder = $imgroot.DS.$matches[2][$ppgallery];
          $ipath  = $abspath.DS.$imgfolder;
          unset($imageset);
          $i = 0;
          if ($idir = opendir($ipath)) {
            while (($ifile = readdir($idir)) !== false) {
              if((substr(strtolower($ifile),-3) == 'jpg') || (substr(strtolower($ifile),-3) == 'gif') || (substr(strtolower($ifile),-3) == 'png')) {
                $imageset[$i] -> img_path = ($matches[2][$ppgallery] == '' ? rtrim($imgfolder, DS) : $imgfolder);
                $imageset[$i] -> img_file = $ifile;
                $imageset[$i] -> img_alt = plgContentPPGalleryGetCsv($ifile, $ipath.DS.$csvfile, 'alt');
                $imageset[$i] -> img_title = plgContentPPGalleryGetCsv($ifile, $ipath.DS.$csvfile, 'title');
                $i++;
                array_multisort($imageset, SORT_ASC, SORT_REGULAR);
              }
            }
            closedir($idir);
          }
          $imgcount = count($imageset); 
        }

        //content output...
        if($imgcount) {
          $content = "<span class=\"pp_gallery\">\n";
            if ($thbs_limit != '') { 
              $imgs_hidden = $imgcount;
              if ($imgcount > $thbs_limit) {
                $imgcount = $thbs_limit;
              }
            }

            for($a = 0; $a < $imgcount; $a++) {
              if($imageset[$a]->img_file != '') {

              //thumbs-size calculation, 0=w, 1=h
              $imagedata = getimagesize($imageset[$a]->img_path.DS.$imageset[$a]->img_file);
              $thb_w = $twidth;
              $thb_h = (int)($imagedata[1]*($thb_w/$imagedata[0]));
              if(($theight) AND ($thb_h > $theight)) {
                $thb_h = $theight;
                $thb_w = (int)($imagedata[0]*($thb_h/$imagedata[1]));
              }

              //create/check thumb folder in J!cache
              if (!JFolder::exists(JPATH_CACHE.DS.$imageset[$a]->img_path)) {
                JFolder::create(JPATH_CACHE.DS.$imageset[$a]->img_path, 0755);
              }
              // define thb-file-name
              if (substr(strtolower($imageset[$a]->img_file),-3) == 'jpg') {
                $thb_file = 'cache'.DS.$imageset[$a]->img_path.DS.$thb_w."x".$thb_h."_q".$quality_jpg."_t_".$imageset[$a]->img_file;
              }
              if (substr(strtolower($imageset[$a]->img_file),-3) == 'png') {
                $thb_file = 'cache'.DS.$imageset[$a]->img_path.DS.$thb_w."x".$thb_h."_q".$quality_png."_t_".$imageset[$a]->img_file;
              }
              if (substr(strtolower($imageset[$a]->img_file),-3) == 'gif') {
                $thb_file = 'cache'.DS.$imageset[$a]->img_path.DS.$thb_w."x".$thb_h."_t_".$imageset[$a]->img_file;
              }

              // a H R E F  tag
              $content .= '<span class="ppg_thbox'.$itemid.$ppgallery.'">';
                if ($caption == 'top') {$content .= '<span class="ppg_captop">'.$imageset[$a]->img_alt.'</span>';} // caption Top
                ($fixed_w ? $content .= '<span class="ppg_thb" style="margin-left: '.((($twidth-$thb_w)/2)+4).'px;">' : $content .= '<span class="ppg_thb">'); // +4 from css file for drop-shadow!
                if (!$thbs_only) {
                  $content .= '<a href="'.$relpath."/".$imageset[$a]->img_path."/".$imageset[$a]->img_file.'" rel="prettyPhoto[pgg'.$itemid.$ppgallery.']" title="';
                  if ($pre_txt) {$content .= $pre_txt.'<br /><br />';}
                  $content .= $imageset[$a]->img_title.'" target="_blank">';
                }

                // if thb with desired settings already exists, proceed with output, otherwise build/rebuild thb
                if(file_exists($thb_file)){
                } else {
                  list($img_w, $img_h) = getimagesize($imageset[$a]->img_path.DS.$imageset[$a]->img_file);

                  //load thumbs from cache folder into array
                  unset($thumbs);
                  $i = 0;
                  if ($tdir = opendir($abspath.DS.'cache'.DS.$imageset[$a]->img_path)) {
                    while (($tfile = readdir($tdir)) !== false) {
                      if((substr(strtolower($tfile),-3) == 'jpg') || (substr(strtolower($tfile),-3) == 'gif') || (substr(strtolower($tfile),-3) == 'png')) {
                        $thumbs[$i] -> img = substr($tfile,strpos($tfile,'_t_')+3);
                        $thumbs[$i] -> thb = $tfile;
                        $i++;
                      }
                    }
                    closedir($tdir);
                  }

                  //search for a thb (w/o the settings) in (thbs array) with existing img_name and delete it
                  $tfound = plgContentPPGallerySearchMultiArray($thumbs, 'img', $imageset[$a]->img_file);
                  //if a thb exists but with different settings -> delete before rebuilding a new one
                  if (is_file($abspath.DS.'cache'.DS.$imageset[$a]->img_path.DS.$thumbs[$tfound]->thb)) {
                    unlink($abspath.DS.'cache'.DS.$imageset[$a]->img_path.DS.$thumbs[$tfound]->thb );
                  }

                  //generate the thbs
                  if (substr(strtolower($thb_file),-3) == 'gif') {
                    $img = ImageCreateFromGIF($imageset[$a]->img_path.DS.$imageset[$a]->img_file);
                    $thb = ImageCreateTrueColor($thb_w, $thb_h);
                    imagecopyresampled($thb, $img, 0, 0, 0, 0, $thb_w, $thb_h , $img_w, $img_h);
                    ImageGIF($thb, $thb_file);
                  }
                  elseif (substr(strtolower($thb_file),-3) == 'jpg') {
                    $img = ImageCreateFromJPEG($imageset[$a]->img_path.DS.$imageset[$a]->img_file);
                    $thb = ImageCreateTrueColor($thb_w, $thb_h);
                    imagecopyresampled($thb, $img, 0, 0, 0, 0, $thb_w, $thb_h, $img_w, $img_h);
                    ImageJPEG($thb, $thb_file, $quality_jpg);
                  }
                  elseif (substr(strtolower($thb_file),-3) == 'png') {
                    $img = ImageCreateFromPNG($imageset[$a]->img_path.DS.$imageset[$a]->img_file);
                    $thb = ImageCreateTrueColor($thb_w, $thb_h);
                    imagecopyresampled($thb, $img, 0, 0, 0, 0, $thb_w, $thb_h, $img_w, $img_h);
                    ImagePNG($thb, $thb_file, $quality_png);
                  }
                }
                // I M G  tag - - -
                if ($caption == 'label'  && $imageset[$a]->img_alt != "") {$content .= '<span class="ppg_caplbl">'.$imageset[$a]->img_alt.'</span>';} // caption label
                $content .= '<img src="'.$relpath."/".$thb_file.'" alt="'.($imageset[$a]->img_alt == "" ? $imageset[$a]->img_file : $imageset[$a]->img_alt).'" width="'.$thb_w.'" height="'.$thb_h.'" title="';
                if (!$thbs_only && $lnk_pop) { 
                  $content .= $lnk_pop.'" />'; }
                else {
                 ($imageset[$a]->img_title == "" ? $content .= $imageset[$a]->img_file.'" />' : $content .= $imageset[$a]->img_title.'" />');
                }
                if (!$thbs_only) {$content .= '</a>';}
                $content .= '</span>';
                if ($caption == 'bottom') {$content .= '<span class="ppg_capbot">'.$imageset[$a]->img_alt.'</span>';} // caption bottom
                $content .= "</span>\n";
              }
            }
            // hidden a H R E F
            $content .="<span class=\"ppg_clr\"></span>\n</span>\n";
            if (($thbs_limit != '') && ($thbs_limit < $imgs_hidden)) {
              for($a = $thbs_limit; $a < $imgs_hidden; $a++) {
                if($imageset[$a]->img_file != '') {
                  $content .= '<span style="display: none"><a href="'.$relpath."/".$imageset[$a]->img_path."/".$imageset[$a]->img_file.'" rel="prettyPhoto[pgg'.$itemid.$ppgallery.']" title="';
                  if ($pre_txt) {$content .= $pre_txt.'<br /><br />';}
                  $content .= $imageset[$a]->img_title.'" target="_blank">';
                  $content .= '<img src="" alt="'.($imageset[$a]->img_alt == "" ? $imageset[$a]->img_file : $imageset[$a]->img_alt).'" />';
                  $content .= "</a></span>\n";
                }
              }
            }
        }
        //head declarations
        // c s s
        $css_thbox = '      .ppg_thbox'.$itemid.$ppgallery.' { float: left; ';
        if ($caption == 'none' || $caption == 'label') { $css_thbox .= 'height: '.($theight+12).'px; margin: 0 '.$padd_h.'px '.$padd_v.'px 0;';}
        if ($caption == 'top') { $css_thbox .= 'height: '.($theight+30).'px; margin: 0 '.$padd_h.'px '.$padd_v.'px 0;';}
        if ($caption == 'bottom') { $css_thbox .= 'height: '.($theight+10).'px; margin: 1.5em '.$padd_h.'px '.$padd_v.'px 0;';}
        ($fixed_w ? $css_thbox .= ' width: '.($twidth+10).'px; }' : $css_thbox .= ' }');
        $doc->addStyleDeclaration($css_thbox);

        //pP inline parameter overrides here..., when multiple instance with different settings of Gallery is possible (from v3.0, see below)
      }
      //remove {pp...} from content and replace with the gallery
      $row->text = str_replace( $matches[0][$ppgallery], $content, $row->text );
    }
    $doc->addStyleSheet($relpath.'/plugins/content/ppgallery/res/prettyPhoto.css');
    $doc->addStyleSheet($relpath.'/plugins/content/ppgallery/res/pPGallery.css');
    $doc->addScript($relpath.'/plugins/content/ppgallery/res/jquery.js" charset="utf-8');
    $doc->addScript($relpath.'/plugins/content/ppgallery/res/jquery.prettyPhoto.js" charset="utf-8');

    //gloal settings for prettyPhoto Parameters (pP settings per easch gallery will be possible with pP v3.0)
    if (!defined('onlyonce')){
      $ppparams = '';
      if (($ppani = $pluginParams->get('ppAni', 'normal')) != 'normal') $ppparams .= 'animationSpeed:"'.$ppani.'"';
      if (($ppopac = $pluginParams->get('ppOpac', 0.35)) != '0.35') ($ppparams != '' ? $ppparams .= ',opacity:'.$ppopac.'' : $ppparams .= 'opacity:'.$ppopac.'');
      if (!$pptitle = $pluginParams->get('ppTitle', true)) ($ppparams != '' ? $ppparams .= ',showTitle:false' : $ppparams .= 'showTitle:false');
      if (!$ppresize = $pluginParams->get('ppResize', true)) ($ppparams != '' ? $ppparams .= ',allowresize:false' : $ppparams .= 'allowresize:false');
      if ($pphidef = $pluginParams->get('ppHidef', false)) ($ppparams != '' ? $ppparams .= ',hideflash:true' : $ppparams .= 'hideflash:true');
      if (($ppsep = $pluginParams->get('ppSep', '/')) != '/') ($ppparams != '' ? $ppparams .= ',counter_separator_label:" '.$ppsep.'"' : $ppparams .= 'counter_separator_label:" '.$ppsep.'"');
      if (($pptheme = $pluginParams->get('ppTheme', 'light_rounded')) != 'light_rounded') ($ppparams != '' ? $ppparams .= ',theme:"'.$pptheme.'"' : $ppparams .= 'theme:"'.$pptheme.'"');
      $noconflict = $pluginParams->get('noconflict', 1);

      $head = "<script type='text/javascript' charset='utf-8'> //rc4 \n";
      if ($noconflict) {$head .= "      jQuery.noConflict();\n";}
      $head .= '      jQuery(document).ready(function($) {
      $("a[rel^=\"prettyPhoto\"]").prettyPhoto({'.$ppparams.'});
      });
      </script>';
      $doc->addCustomTag($head);
      define('onlyonce', true);
    }
  }
}
}

// - s u b - f u n c t i o n s - - -

function plgContentPPGalleryGetOverrides($override, $attribute, $default = null) {
  $matches = array();
  preg_match_all('/(\w+)(\s*=\s*\".*?\")/s', htmlspecialchars_decode($override), $matches); // html..decode: some editors repl spec.chars with entities;
  $count = count($matches[1]);
  for ($i = 0; $i < $count; $i++) {
    if (strtolower($matches[1][$i]) == strtolower($attribute)) {
      $value = ltrim($matches[2][$i], " \n\r\t=");
      $value = trim($value, '""');
      if (strtolower($attribute) == 'src') ltrim($value, DS);
      return $value;
    }
  }
  return $default;
}

function plgContentPPGallerySearchMultiArray ($array, $index, $value) { //thumbs, img, filename
  if(is_array($array) && count($array)>0) { 
      foreach(array_keys($array) as $key) {
        $tempkey[$key] = $array[$key]->$index;
          if ($tempkey[$key] == $value) {
              $arrkey = $key;
         }
      }
    }
  return $arrkey;
}

function plgContentPPGalleryGetAttr($tag, $attr) {
  preg_match('#' . $attr . '\s?=\s?"(.*?)"#', $tag, $attr_value);
  if (isset($attr_value[1])) return $attr_value[1];
  return '';
}

function plgContentPPGalleryGetCsv($i_file, $csv, $attr) {
  if(file_exists($csv)){
    $handle = fopen ($csv, "r");
    while (($data = fgetcsv ($handle, 1000, ",")) !== FALSE ) {
      if ($i_file == $data[0]) {
        ($attr == 'alt' ? $attr_value = $data[1] : $attr_value = $data[2]);
      }
    }
    fclose ($handle);
    return $attr_value;
  }
}

//prettyPhoto Inline Parameter Overrides when multiple instance with different settings of Gallery is possible (from v3.0))
/* add for no conflict and comma issue..., Inline Parameter Overrides for prettyPhoto Engine
  unset($ppparams);
  if (($ppani = plgContentPPGalleryGetOverrides($override, 'ani', $pluginParams->get('ppAni', 'normal'))) != 'normal') $ppparams[$ppgallery] .= 'animationSpeed:"'.$ppani.'", ';
  if (($pppad = plgContentPPGalleryGetOverrides($override, 'pad', $pluginParams->get('ppPad', 40))) != '40') $ppparams[$ppgallery] .= 'padding:'.$pppad.', ';
  if (($ppopac = plgContentPPGalleryGetOverrides($override, 'opac', $pluginParams->get('ppOpac', 0.35))) != '0.35') $ppparams[$ppgallery] .= 'opacity:'.$ppopac.', ';
  if (!$pptitle = plgContentPPGalleryGetOverrides($override, 'title', $pluginParams->get('ppTitle', true))) $ppparams[$ppgallery] .= 'showTitle:false, ';
  if (!$ppresize = plgContentPPGalleryGetOverrides($override, 'resize', $pluginParams->get('ppResize', true))) $ppparams[$ppgallery] .= 'allowresize:false, ';
  if (($ppsep = plgContentPPGalleryGetOverrides($override, 'separator', $pluginParams->get('ppSep', '/'))) != '/') $ppparams[$ppgallery] .= 'counter_separator_label:" '.$ppsep.' ", ';
  if (($pptheme = plgContentPPGalleryGetOverrides($override, 'theme', $pluginParams->get('ppTheme', 'light_rounded'))) != 'light_rounded') $ppparams[$ppgallery] .= 'theme:"'.$pptheme.'", ';
  $head = '<script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
      $("a[rel^=\"prettyPhoto'.$ppgallery.'\"]").prettyPhoto({'.$ppparams[$ppgallery].'});
    });
  </script>';
  $doc->addCustomTag($head);
*/

?>