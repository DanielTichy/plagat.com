<?php
/**
* @version $Id: mosbanner.php, v0.3 2007/09/17 17:59:22 $
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @Author: keep - http://joomla.blog.hu - based on: Tobbworld.de - http://www.tobbworld.de
*
*
**/

/** Ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$_MAMBOTS->registerFunction( 'onPrepareContent', 'newBanner' );

function newBanner( $published, &$row, &$params, $page=0 ) {
  global $database;

  // Load the Bot's params (all)
  $query = "SELECT id FROM #__mambots WHERE element = 'mosbanner' AND folder = 'content'";
  $database->setQuery( $query );
  $id = $database->loadResult();
  $mambot = new mosMambot( $database );
  $mambot->load( $id );
  $botparams =& new mosParameters( $mambot->params );   // Use "$botparms" to avoid overwritting "$params" (from Module)



  if ( !strpos($row->text,"{mosbanner}") ) {
  	if (preg_match('{mosbanner:id=\d+(,\d+)*}', $row->text)) {
		if ($published) {  // Bot Published => replace {mosbanner:id=?} with banner
			$mosaddbanner_entrytext = $row->text;		
			if (preg_match_all('/{mosbanner:id=\d+(,\d+)*}/', $mosaddbanner_entrytext, $mosaddbanner_matches, PREG_PATTERN_ORDER) > 0) {
				foreach ($mosaddbanner_matches[0] as $mosaddbanner_match) {
					$mosaddbanner_output = "";
					$mosaddbanner_match = str_replace("{mosbanner:id=", "", $mosaddbanner_match);
					$mosaddbanner_match = str_replace("}", "", $mosaddbanner_match);
					$mosaddbanner_params = array();
					$mosaddbanner_params = explode(",", $mosaddbanner_match);
				
					$mosaddbanner_entrytext = preg_replace("/{mosbanner:id=\d+(,\d+)*}/", viewBannerWithId($mosaddbanner_params[array_rand($mosaddbanner_params)]), $mosaddbanner_entrytext, 1);
					$row->text = str_replace ("{mosbanner}", "", $row->text);					
				}
				$row->text = $mosaddbanner_entrytext;
			
			}
		} else {           // Bot Published => remove {mosbanner:id=?}
		  $row->text = preg_replace ("/{mosbanner:id=\d+(,\d+)*}/", "", $row->text);
		}
	}
  	if (preg_match('{mosbanner:cid=\d+(,\d+)*}', $row->text)) {
		if ($published) {  // Bot Published => replace {mosbanner:cid=?} with banner
			$mosaddbanner_entrytext = $row->text;		
			if (preg_match_all('/{mosbanner:cid=\d+(,\d+)*}/', $mosaddbanner_entrytext, $mosaddbanner_matches, PREG_PATTERN_ORDER) > 0) {
				foreach ($mosaddbanner_matches[0] as $mosaddbanner_match) {
					$mosaddbanner_output = "";
					$mosaddbanner_match = str_replace("{mosbanner:cid=", "", $mosaddbanner_match);
					$mosaddbanner_match = str_replace("}", "", $mosaddbanner_match);
					$mosaddbanner_params = array();
					$mosaddbanner_params = explode(",", $mosaddbanner_match);
					
					$database->setQuery( "SELECT * FROM #__banner WHERE cid IN($mosaddbanner_match) ORDER BY rand() LIMIT 1" );
					$numrows = $database->loadResult();
					if ($numrows === null) {
						echo $database->stderr( false );
						return;
					}
					$database->loadObject( $banner );
									
					$mosaddbanner_entrytext = preg_replace("/{mosbanner:cid=\d+(,\d+)*}/", viewBannerWithId($banner->bid), $mosaddbanner_entrytext, 1);
					$row->text = str_replace ("{mosbanner}", "", $row->text);					
				}
				$row->text = $mosaddbanner_entrytext;
			
			}
		} else {           // Bot Published => remove {mosbanner:cid=?}
		  $row->text = preg_replace ("/{mosbanner:cid=\d+(,\d+)*}/", "", $row->text);
		}
	}	
  } else {
    if ($published) {  // Bot Published => replace {mosbanner} with banner
      $row->text = str_replace ("{mosbanner}", viewbanner2 (), $row->text);
    } else {           // Bot Published => remove {mosbanner}
      $row->text = str_replace ("{mosbanner}", "", $row->text);
    }
  } 

  return true;
}

/* Code from banners.php compenent
   change:
     - return method - show banner really on location from {mosbanner}
*/
function viewbanner2() {
	global $database, $mosConfig_live_site;

	$database->setQuery( "SELECT count(*) AS numrows FROM #__banner WHERE showBanner=1" );

	$numrows = $database->loadResult();
	if ($numrows === null) {
		echo $database->stderr( false );
		return;
	}

	if ($numrows > 1) {
		mt_srand( (double) microtime()*1000000 );
		$bannum = mt_rand( 0, --$numrows );
	} else {
		$bannum = 0;
	}

	$database->setQuery( "SELECT * FROM #__banner WHERE showBanner=1 LIMIT $bannum,1" );

	$banner = null;
	if ($database->loadObject( $banner )) {
		$database->setQuery( "UPDATE #__banner SET impmade=impmade+1 WHERE bid='$banner->bid'" );
		if(!$database->query()) {
			echo $database->stderr( true );
			return;
		}
		$banner->impmade++;

		if ($numrows > 0) {
			// Check if this impression is the last one and print the banner
			if ($banner->imptotal == $banner->impmade) {
				$query = "INSERT INTO #__bannerfinish (cid, type, name, impressions, clicks, imageurl, datestart, dateend)
					VALUES ('$banner->cid', '$banner->type', '$banner->name', '$banner->impmade', '$banner->clicks', '$banner->imageurl', '$banner->date', now())";
				$database->setQuery($query);
				if(!$database->query()) {
					die($database->stderr(true));
				}

				$query="DELETE FROM #__banner WHERE bid=$banner->bid";
				$database->setQuery($query);
				if(!$database->query()) {
					die($database->stderr(true));
				}
			}

			if (trim( $banner->custombannercode )) {
				return $banner->custombannercode;
			} else if (eregi( "(\.bmp|\.gif|\.jpg|\.jpeg|\.png)$", $banner->imageurl )) {
				$imageurl = "$mosConfig_live_site/images/banners/$banner->imageurl";
				$banner1 = "<a href=\"".sefRelToAbs("index.php?option=com_banners&amp;task=click&amp;bid=$banner->bid")."\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt=\"Advertisement\" /></a>";
			return $banner1;
			} else if (eregi("\.swf$", $banner->imageurl)) {
				$imageurl = "$mosConfig_live_site/images/banners/".$banner->imageurl;
				$banner2= "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" border=\"5\">
						<param name=\"movie\" value=\"$imageurl\"><embed src=\"$imageurl\" loop=\"false\" pluginspage=\"http://www.macromedia.com/go/get/flashplayer\" type=\"application/x-shockwave-flash\"></embed></object>";
			return $banner2;
			}
		}
	} else {
		return "";
	}
}

function viewBannerWithId( $bannerId ) {
	global $database, $mosConfig_live_site;

	$database->setQuery( "SELECT * FROM #__banner WHERE bid=$bannerId" );
	$numrows = $database->loadResult();
	if ($numrows === null) {
		echo $database->stderr( true );
		return;
	}
	$banner = null;
	if ($database->loadObject( $banner )) {
		$database->setQuery( "UPDATE #__banner SET impmade=impmade+1 WHERE bid='$banner->bid'" );
		if(!$database->query()) {
			echo $database->stderr( true );
			return;
		}
		$banner->impmade++;

		if ($numrows > 0) {
			// Check if this impression is the last one and print the banner
			if ($banner->imptotal == $banner->impmade) {
				$query = "INSERT INTO #__bannerfinish (cid, type, name, impressions, clicks, imageurl, datestart, dateend)
					VALUES ('$banner->cid', '$banner->type', '$banner->name', '$banner->impmade', '$banner->clicks', '$banner->imageurl', '$banner->date', now())";
				$database->setQuery($query);
				if(!$database->query()) {
					die($database->stderr(true));
				}

				$query="DELETE FROM #__banner WHERE bid=$banner->bid";
				$database->setQuery($query);
				if(!$database->query()) {
					die($database->stderr(true));
				}
			}

			if (trim( $banner->custombannercode )) {
				return $banner->custombannercode;
			} else if (eregi( "(\.bmp|\.gif|\.jpg|\.jpeg|\.png)$", $banner->imageurl )) {
				$imageurl = "$mosConfig_live_site/images/banners/$banner->imageurl";
				$banner1 = "<a href=\"".sefRelToAbs("index.php?option=com_banners&amp;task=click&amp;bid=$banner->bid")."\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt=\"Advertisement\" /></a>";
			return $banner1;
			} else if (eregi("\.swf$", $banner->imageurl)) {
				$imageurl = "$mosConfig_live_site/images/banners/".$banner->imageurl;
				$banner2= "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" border=\"5\">
						<param name=\"movie\" value=\"$imageurl\"><embed src=\"$imageurl\" loop=\"false\" pluginspage=\"http://www.macromedia.com/go/get/flashplayer\" type=\"application/x-shockwave-flash\"></embed></object>";
			return $banner2;
			}
		}
	} else {
		return "";
	}
}

?>