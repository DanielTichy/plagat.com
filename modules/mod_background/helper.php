<?php
/**
* @version 1.0 $ 13-10-2008 15:26:10
* @package Background
* @copyright (C) 2008 Joomla4more (http://www.joomla4more.com)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class modBackgroundHelper
{	
	function getBackgroundImage(&$params)
	{
		// get the parameters
		$color 		= $params->get('color', '#ffffff');
		$image		= $params->get('image');
		$random 	= $params->get('random');
		$folder		= $params->get('folder', 'images/stories');
		$add_css 	= $params->get('additional_css');

		// prepare some variables
		$the_array 	= array();
		$the_image 	= array();
		
		// use random image
		if ($random == 1) {
		
			// if folder doesnt contain slash to start, add
			if ( strpos($folder, '/') !== 0 ) {	
				$folder = '/'. $folder;
			}
			
			// construct absolute path to directory
			$abspath_folder = JPATH_BASE.DS.$folder;

			// check if directory exists
			if (is_dir($abspath_folder)) {
				if ($handle = opendir($abspath_folder)) {
					while (false !== ($file = readdir($handle))) {
						if ($file != '.' && $file != '..' && $file != 'CVS' && $file != 'index.html' ) {
							$the_array[] = $file;
						}
					}
				}
				closedir($handle);
	
			foreach ($the_array as $img) {
					if (!is_dir($abspath_folder .'/'. $img)) {
						if (eregi('jpg', $img) || eregi('png', $img) || eregi('gif', $img) ) {
							$the_image[] = $img;
						}
					}
				}
				
				if (!$the_image) {
					return "No images";
				} else {
					$i 				= count($the_image);
					$random 		= mt_rand(0, $i - 1);
					$image		 	= $the_image[$random];
					$abspath_image	= $abspath_folder . '/'. $image;
					}
				}
		}
		// no random image
		else {
			$folder = "images/stories";
		}
			$livesite  = JURI::base();

			// make the CSS output
			$output =  "<style type=\"text/css\"> body { background-color:" . $color . " !important;";
			if (!($image == -1 && random == 0)) {
				$output .= "background-image: url(" . $livesite . $folder . '/' . $image . ") !important;";
				// if add_css contains <br /> remove them
				if (stristr($add_css, '<br />') == TRUE) {
					$add_css = str_replace("<br />", "", $add_css); 
				}
				$output .= $add_css;
			}
			$output .= " }</style>";
			 
			// echo the CSS output
			return $output;
	}
}