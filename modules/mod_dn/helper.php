<?php
###################################################################################################
#  Display News  1.5.12 - March -2009 by bkomraz1@gmail.com
#  http://joomlacode.org/gf/project/display_news/
#  Based on Display News - Latest 1-3 [07 June 2004] by Rey Gigataras [stingrey]   www.stingrey.biz  mambo@stingrey.biz
#  @ Released under GNU/GPL License : http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
###################################################################################################

defined('_JEXEC') or die('Restricted access');

// loads module function file
require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php'); 
require_once( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'category.php'); 
require_once( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'section.php'); 
require_once( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'content.php'); 

class modDisplayNewsHelper {

	var $css_type = null;


   // Function to filter html code and special characters from text
   function dn_filter( $text ) {
      $text = strip_tags($text, /* exclude */ "<img>");
//      $text = preg_replace("'<script[^>]*>.*?</script>'si","",$text);
      $text = preg_replace("@<script[^>]*?>.*?</script>@si","",$text);
      $text = preg_replace('/{.+?}/','',$text);
      $text = preg_replace('/(( )|(&nbsp;))+/',' ',$text);
      // $text = preg_replace('/&amp;/',' ',$text);
      $text = preg_replace('/&quot;/',' ',$text);
      // $text = htmlspecialchars($text);
      return $text;
   }

   //  Function required to create set of Names, '' added
   function dn_set_name( &$param ) {
      if ($param <> NULL) {
         $paramA = explode(",", $param);
         $a = "0";
         foreach ($paramA as $paramB) {
            $paramB = trim($paramB);
            $paramB = "'".addslashes($paramB)."'";
            $paramA[$a] = $paramB;
            $a++;
         }
         $param = implode(",", $paramA);
      }
   }
   //---------------------------------------------------------------------
   //  Functinality to allow text_hover to be blank by use if special character "#" entered
   //  If not then space added to the end of the variables
   function dn_hovertext( &$text ) {
      if ($text == "#") {
         $text = "";
      } else {
         $text .= " ";
      }
   }
   //---------------------------------------------------------------------


   // Function that limits title, intro or full to specified character or word length
	function dn_limit( &$text, $limit_type, $length_chars, $href ) {
	
		if ( $limit_type < 2 || !$length_chars ) {
			return 0;
		}
		
		$titletext = "";
		$visiblelen = 0;
		$triger = 0;
		for($i=0;$i<strlen($text);$i++)
		{
		    $char=substr($text,$i,1);
			
			if ($char == "<" || $char == ">") {
				$triger ++;
			}

			if ( !$triger && ( ($limit_type == 2) || ($char == " ")) ) {
				$visiblelen ++;
			}
			
			if ($visiblelen > $length_chars) {
				break;
			}
			
			// UTF Lenght. should be added to string "as is"
			// 0xC0-0xFD
			if ((ord($char)>= 192) && (ord($char)<=253) ) {
				$titletext = $titletext . $char;
				for ($j=$i+1;$j<strlen($text);$j++) {
					$char=substr($text,$j,1);
					if ((ord($char)>= 128) && (ord($char)<=191) ) {
						$titletext = $titletext . $char;
						$i=$j;
					}
					else {
						break;
					}
				}
			} else {
				$titletext = $titletext . $char;
			}
			
			
			if ($triger == 2) {
				$triger = 0;
			}
			
			
		}
		     
		if ($visiblelen > $length_chars) {
			$titletext = chop($titletext);
			$titletext .= strlen($href) ? '<a href="'.$href.'">...</a>' : '...';
			$title = $titletext;
			$text = $title;
			return 1;
		} else {
			$title = $titletext;
			$text = $title;
			return 0;
		}

   }
   /**---------------------------------------------------------------------**/
   //  Functinality to convert Set Name to Set Id to be used by show_more
   function dn_name_id( $id, $name ) {
      global $database;
      if ( strchr($name, ",") ) {
         $id = "";
      } else {
         $database->setQuery("SELECT a.id"
         ."\n  FROM #__sections AS a"
         ."\n  WHERE a.name = '$name'");
         $id = $database->loadResult();
      }
      return $id;
   }
   //---------------------------------------------------------------------
   //---------------------------------------------------------------------

	function dn_style_convert( $id ) {

		if ( ! $this->css_type )
			return $id;

		$convert = array(
			"dn-title_auto" 		=> array("content" => "contentpagetitle", 	"table" => "sectiontableheader", "latestnews" => ""),
			"dn-whole" 				=> array("content" => "contentpaneopen", 	"table" => "contentpane", 	     "latestnews" => ""),
			"dn-module_title" 		=> array("content" => "", 					"table" => "sectiontableheader", "latestnews" => ""),
			"dn-module_description" => array("content" => "contentdescription", "table" => "contentdescription", "latestnews" => ""),
			"dn-each" 				=> array("content" => "", 					"table" => "sectiontableentry",  "latestnews" => ""),
			"dn-section" 			=> array("content" => "", 					"table" => "", 	                 "latestnews" => ""),
			"dn-category" 			=> array("content" => "", 					"table" => "", 	                 "latestnews" => ""),
			"dn-date" 				=> array("content" => "createdate", 		"table" => "", 	                 "latestnews" => ""),
			"dn-author" 			=> array("content" => "small", 				"table" => "", 	                 "latestnews" => "small"),
			"dn-head" 				=> array("content" => "contentheading", 	"table" => "", 	                 "latestnews" => ""),
			"dn-title" 				=> array("content" => "contentpagetitle", 	"table" => "", 	                 "latestnews" => ""),
			"dn-hits" 				=> array("content" => "", 					"table" => "", 	                 "latestnews" => ""),
			"dn-introtext" 			=> array("content" => "contentpaneopen", 	"table" => "", 	                 "latestnews" => ""),
			"dn-introtext-link" 	=> array("content" => "", 					"table" => "", 	                 "latestnews" => ""),
			"dn-fulltext" 			=> array("content" => "contentpaneopen", 	"table" => "", 	                 "latestnews" => ""),
			"dn-read_more" 			=> array("content" => "readon", 			"table" => "readon",             "latestnews" => "readon"),
			"dn" 					=> array("content" => "", 					"table" => "", 	                 "latestnews" => "latestnews"), //  <ul
			"dn" 					=> array("content" => "", 					"table" => "", 	                 "latestnews" => "latestnews"), //  <ol
			"arrow-dn" 				=> array("content" => "", 					"table" => "", 	                 "latestnews" => "latestnews"), // echo "<li ""), }
			"list-dn" 				=> array("content" => "", 					"table" => "", 	                 "latestnews" => "latestnews"), // echo "<li ""),  }
			"dn-more" 				=> array("content" => "", 					"table" => "", 	                 "latestnews" => ""),
			"dn-module_link" 		=> array("content" => "", 					"table" => "", 	                 "latestnews" => "")
		);

		$result = $convert[$id][$this->css_type];
		return $result ? $result : "";

	}

	function dn_style( $id, $k = "" ) {
		$dnstyle=modDisplayNewsHelper::dn_style_convert($id)."${k}" ;
		return $dnstyle ? ( "class='${dnstyle}$this->moduleclass_sfx'" ) : "";
	}


function main(&$params)
{


global $mosConfig_lang, $mosConfig_mbf_content, $mainframe;

$website = "http://joomlacode.org/gf/project/display_news/";
$version = "Display News by BK 1.5.10";

echo "\n<!-- START '".$version."' -->\n";


$globalConfig = & $mainframe->getParams();

//----- Parameters - Criteria ( 19 ) ------------------------------------------
$set_count = $params->get('set_count' ) ;
if ( !$set_count ) {
	$set_count = 1000000000;
}

$show_tooltips  		= $params->get( 'show_tooltips', 0 );
$set_date_today 		= $params->get( 'set_date_today', 0 );
// 5-10      5 - older than 5 days, newly than 10 days
// 5 newly than 5 days old
$set_date_range 		= $params->get( 'set_date_range', 0 ); 

$publish_up_spec = "";
if ( substr($set_date_range, 0, 1) == "p" ) { 
		$set_date_newly = 0;
		$set_date_older = 0;
		$publish_up_spec = "p";
} else {
	$tokens = split("-", $set_date_range,2);
	if (count($tokens)==2) {
		$set_date_older = $tokens[0];
		$set_date_newly = $tokens[1];
	} else if ( count($tokens)==1 ) {
		$set_date_newly = $tokens[0];
		$set_date_older = 0;
	}
}

$set_date_month 		= $params->get( 'set_date_month', "" );
$set_date_year  		= $params->get( 'set_date_year', "");

$set_auto        		= $params->get( 'set_auto', 0 );
$set_auto_author 		= $params->get( 'set_auto_author', 0);

// FIXME show_frontpage -> num value
$show_frontpage 		= $params->get( 'show_frontpage', "y" ); 

$set_category_id_extra 	= $params->get( 'set_category_id_extra', 0);
$set_category_id 		= $params->get( 'set_category_id', 0);

$set_section_id_extra 	= $params->get( 'set_section_id_extra', 0);
$set_section_id 		= $params->get( 'set_section_id', 0);

$set_article_id 		= $params->get( 'set_article_id', 0);

$set_author_id 			= $params->get( 'set_author_id', 0);
$set_author_name 		= $params->get( 'set_author_name', "");

$minus_leading 			= $params->get( 'minus_leading', 0 );
$hide_current 			= $params->get( 'hide_current', 0 );
//---------------------------------------------------------------------

//----- Parameters - Display ( 19 ) ------------------------------------------
$this->css_type = $params->get('css_type', "content" );
if ( $this->css_type == "dedicated" ) {
	$this->css_type = "";
}
$show_image = $params->get('image', 0);
$image_align = $params->get('image_align', 0);
$image_size = $params->get('image_size', "");
$show_title_auto = $params->get('show_title_auto' , 0 );
$use_modify_date = $params->get('use_modify_date', 0);
$created = $use_modify_date ? "modified" : "created";
$show_more_auto = $params->get('show_more_auto', 0 );
//---------------------------------------------------------------------

//----- Parameters - Display Modifier ( 14 ) --------------------------------

$scroll_direction = $params->get('scroll_direction', "no");

$scroll_height = $params->get('scroll_height' , 100 );
$scroll_speed = $params->get('scroll_speed' , 1 );
$scroll_delay = $params->get('scroll_delay' , 30 );

$show_title_nextline = $params->get('show_title_nextline', 0 );

$filter_text = $params->get('filter_text' , 0 );
$length_limit_text = $params->get('length_limit_text' );

$filter_title = $params->get('filter_title' , 0 );
$length_limit_title = $params->get('length_limit_title' );

$format_date = $params->get('format_date', JText::_('DATE_FORMAT_LC1'));

$link_section = $params->get('link_section',   $globalConfig->get('link_section') );
$link_category = $params->get('link_category', $globalConfig->get('link_category') );

$link_titles = $params->get('link_titles', $globalConfig->get('link_titles') );
$link_text = $params->get('link_text' , 1 );
$format = $params->get('format', "%s %c<br>%t<br>%d - %a<br>%i");
$show_full_text = $params->get('show_full_text', 0);
$ordering = $params->get('ordering', "mostrecent");
$style = $params->get('style', "flat");

$show_readmore = $params->get('show_readmore', 2 );

$this->moduleclass_sfx = $params->get('moduleclass_sfx', "" );

//----- Parameters - Display Text ( 10 ) -------------------------------------
// Allows for multilingual customisation //
$text_module_description 	= $params->get('text_module_description');
$text_readmore 				= $params->get('text_readmore');
$text_more 					= $params->get('text_more', JText::_('More Articles...') );

$text_title_auto_pre 		= $params->get('text_title_auto_pre', "");
$text_title_auto_suf 		= $params->get('text_title_auto_suf', "");

$text_hover_section  		= $params->get('text_hover_section', "");
$text_hover_category 		= $params->get('text_hover_category', "");
$text_hover_title    		= $params->get('text_hover_title', "");

$text_hover_readmore 		= $params->get('text_hover_readmore', "");

$text_hover_more_section  	= $params->get('text_hover_more_section', "");
$text_hover_more_category 	= $params->get('text_hover_more_category', "");

$bottom_link_text = $params->get('bottom_link_text' );
$bottom_link_url = $params->get('bottom_link_url' );
//---------------------------------------------------------------------

//  Conflict Handler
$show_section = $show_category = $show_date = $show_title = $show_hits = $show_author = $show_text = $show_rating = 0;



      $pf =0;

      for ( $i=0; $i < strlen( $format); $i += 1 ) {
            if ( $format[$i] == "%" ) {
               $pf = 1;
            } else {
               if ( $pf==1 ) {
                  $pf = 0;
                  switch ( $format[$i] ) {
                  case "s":
                     $show_section = 1;
                     break;
                  case "c":
                     $show_category = 1;
                     break;
                  case "d":
                     $show_date = 1;
                     break;
                  case "t":
                     $show_title = 1;
                     break;
                  case "h":
                     $show_hits = 1;
                     break;
                  case "a":
                     $show_author = 1;
                     break;
                  case "i":
                     $show_text = 1;
                     break;
                  case "r":
                     $show_rating = 1;
                     break;
                  case "%":
                     break;
                  default:
                  } // switch
               } // if ( pf
            } // if ( $format[i] == "%" )
      } // for


$limit = "\n LIMIT $minus_leading, $set_count";

//
if ($set_section_id) {
   if ( $set_section_id_extra ) {
      $set_section_id_extra = $set_section_id.",".$set_section_id_extra;
   } else {
      $set_section_id_extra = $set_section_id;
   }
}

if ($set_category_id) {
   if ( $set_category_id_extra ) {
      $set_category_id_extra = $set_category_id.",".$set_category_id_extra;
   } else {
      $set_category_id_extra = $set_category_id;
   }
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------

//  Functinality to allow text_hover to be blank by use if special character "#" entered
//  If not then space added to the end of the variables
modDisplayNewsHelper::dn_hovertext( $text_hover_section );
modDisplayNewsHelper::dn_hovertext( $text_hover_category );
modDisplayNewsHelper::dn_hovertext( $text_hover_title );
modDisplayNewsHelper::dn_hovertext( $text_hover_readmore );
modDisplayNewsHelper::dn_hovertext( $text_hover_more_section );
modDisplayNewsHelper::dn_hovertext( $text_hover_more_category );
modDisplayNewsHelper::dn_hovertext( $text_title_auto_pre );
modDisplayNewsHelper::dn_hovertext( $text_title_auto_suf );
//---------------------------------------------------------------------

// $blog = $section_link_blog ? "blog" : "";

$view = JRequest::getCmd('view');

$db		=& JFactory::getDBO();

   
// If { set_auto = y } then Module will automatically determine section/category id of current page and use this to control what news is dsiplayed
if ($set_auto) {

   if ($view == "section") {
  		$temp				= JRequest::getString('id');
		$temp				= explode(':', $temp);
		$zsectionid         = $temp[0];
        $set_section_id_extra = $zsectionid;
   }

   if ($view == "category") {
  		$temp				= JRequest::getString('id');
		$temp				= explode(':', $temp);
		$zcategoryid        = $temp[0];
        $set_category_id_extra = $zcategoryid;
   }

   if ($view == "article") {
  		$temp				= JRequest::getString('id');
		$temp				= explode(':', $temp);
        $zcontentid         = $temp[0];

		$set_category_id_extra = & JTable::getInstance( 'content' );
        $set_category_id_extra->load( $zcontentid );
        $set_category_id_extra = $set_category_id_extra->catid;
   }
}

// If { set_auto_author = y } then Module will automatically determine Author id of current page and use this to control what news is dsiplayed
if ($set_auto_author) {

   if ($view == "article") {
  		$temp				= JRequest::getString('id');
		$temp				= explode(':', $temp);
        $zcontentid         = $temp[0];

		$result	= null;
		$query = "SELECT created_by_alias, created_by, title FROM #__content WHERE id = '$zcontentid'";
		$db->setQuery($query);
		$result = $db->loadObject();

		switch ( $set_auto_author ) {
		case 1: // by article author
			if ( $result->created_by_alias ) {
				$set_author_name = $result->created_by_alias;
			} else {
				$db->setQuery("SELECT name FROM #__users WHERE id = ".$result->created_by );
				$result = $db->loadObject();
				$set_author_name = $result->name;
			}
			break;

		case 2: // by article name
			$set_author_name = $result->title;
			break;
		 }
   } else {
      return;
   }
}

if ($view == "article") {
  		$temp				= JRequest::getString('id');
		$temp				= explode(':', $temp);
        $currcontentid      = $temp[0];
}

//---------------------------------------------------------------------

global $mainframe;
$config =& JFactory::getConfig();
$tzoffset = $config->getValue('config.offset');


//  Special variable used for management of different access levels
$access  = !$mainframe->getCfg( 'shownoauth' );

//  Handling required to create set Names, '' added
modDisplayNewsHelper::dn_set_name( $set_section_id );
modDisplayNewsHelper::dn_set_name( $set_category_id );
modDisplayNewsHelper::dn_set_name( $set_author_name );
//---------------------------------------------------------------------

//  Special Handling to get $set_date_month to work correctly
if ($set_date_month != "") {
   if ($set_date_month == "0") {
      $set_date_month = date( "m", time()+$tzoffset*60*60 );
   }
}
//---------------------------------------------------------------------

//  Special Handling to get $set_date_year to work correctly
if ($set_date_year != "") {
   if ($set_date_year == 0) {
      $set_date_year = date( "Y", time()+$tzoffset*60*60 );
   }
}

//---------------------------------------------------------------------

######################################################################################################################################

//  Main Query & Array
switch ( $ordering ) {
case "mostread":
   $order_by = "a.hits DESC";
   break;
case "ordering":
   $order_by = "a.ordering ASC";
   break;
case "frontpageordering":
   $order_by = "b.ordering ASC";
   break;
case "title":
   $order_by = "a.title ASC";
   break;
case "mostold":
   $order_by = "$created ASC";
   break;
case "random":
   $order_by = "RAND()";
   break;
case "rating":
   $order_by = "(v.rating_sum / v.rating_count) DESC, v.rating_count DESC";
   break;
case "voting":
   $order_by = "v.rating_count DESC, (v.rating_sum / v.rating_count) DESC";
   break;
case "mostrecent":
default:
   $order_by = "$created DESC";
}

$my =& JFactory::getUser();

$date =& JFactory::getDate();
$now = $date->toMySQL();
$nullDate = $db->getNullDate();

$query = "SELECT a.id ".
		', CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug'.
		', CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
		', a.attribs'.
($show_title ? ", a.title" : "" ).
($show_text  ? ", a.introtext as introtext" : "" ).
// (($show_text &&  $show_full_text) ? ", CONCAT(a.introtext, a.fulltext ) as text" : "" ).
( (($show_text && $show_full_text) || $show_readmore == 2 ) ? ", a.fulltext" : "" ).
                          ", a.sectionid ".
                          ", a.catid ".
($show_date   ? ", a.$created as created" : "" ).
($show_author ? ", created_by" : "" ).
($show_author ? ", a.created_by_alias" : "" ).
($show_hits   ? ", a.hits" : "" ).
($show_image ? ", a.images" : "" )
.($show_rating ? ",round( v.rating_sum / v.rating_count ) AS rating, v.rating_count" : "" )
.			                                        "\n FROM #__content AS a"
. ( ($show_frontpage == "n" || $show_frontpage == "only" ) ? "\n LEFT JOIN #__content_frontpage AS b ON b.content_id = a.id" : "" )
. ( ( $set_author_name || $show_author )            ? "\n JOIN #__users AS c ON c.id = a.created_by" : "" )
.			"\n INNER JOIN #__categories AS cc ON cc.id = a.catid"
.           "\n INNER JOIN #__sections AS d ON d.id = a.sectionid"
   . ( ($show_rating || $ordering == "rating" || $ordering == "voting" ) ?
								"\n LEFT JOIN #__content_rating AS v ON a.id = v.content_id" : "" )
   . 								"\n  WHERE (a.state = '1')"
   . ( ($publish_up_spec == "" )?	"\n  AND (a.publish_up = ".$db->Quote($nullDate)." OR a.publish_up <= ".$db->Quote($now)."  )" : "")
   . ( ($publish_up_spec == "p" )?	"\n  AND ( a.publish_up > ".$db->Quote($now)."  )" : "")
   . 								"\n  AND (a.publish_down = ".$db->Quote($nullDate)." OR a.publish_down >= ".$db->Quote($now)."  )"
   . 								"\n AND (d.published = '1')"
   . 								"\n AND (cc.published = '1')"
   . ($set_section_id_extra ? 					"\n   AND (a.sectionid IN ($set_section_id_extra) )" : '')
   . ($set_category_id_extra ? 					"\n   AND (a.catid IN ($set_category_id_extra) )" : '')
   . ($show_frontpage == "n" ? 					"\n  AND (b.content_id IS NULL)" : '')
   . ($show_frontpage == "only" ? 				"\n  AND (b.content_id = a.id)" : '')
   . ($set_article_id ? 					"\n  AND (a.id IN ($set_article_id) )" : '')
   . ($hide_current && $view == "article"? 			"\n  AND (a.id <> ($currcontentid) )" : '')
   . ($set_author_id ? 						"\n  AND (a.created_by IN ($set_author_id) )" : '')
   . ($set_author_name ? 					"\n  AND ((a.created_by_alias IN ($set_author_name)) OR
											((a.created_by_alias = '' ) AND ( c.name IN ($set_author_name))) )" : '')
   . ($set_date_newly ? 				"\n  AND (TO_DAYS(ADDDATE(NOW(), INTERVAL $tzoffset HOUR)) - TO_DAYS(ADDDATE($created, INTERVAL $tzoffset HOUR)) <= '$set_date_newly' )" : '')
   . ($set_date_older ? 				"\n  AND (TO_DAYS(ADDDATE(NOW(), INTERVAL $tzoffset HOUR)) - TO_DAYS(ADDDATE($created, INTERVAL $tzoffset HOUR)) >= '$set_date_older' )" : '')

   . ($set_date_today ? 					"\n   AND (TO_DAYS(ADDDATE(NOW(), INTERVAL $tzoffset HOUR)) = TO_DAYS(ADDDATE($created, INTERVAL $tzoffset HOUR)))" : '')
   . ($set_date_month ? 					"\n  AND ($set_date_month = MONTH(ADDDATE($created, INTERVAL $tzoffset HOUR)))" : '')
   . ($set_date_year ? 					"\n  AND ($set_date_year  = YEAR(ADDDATE($created, INTERVAL $tzoffset HOUR)))" : '')
   . ( isset($access) ? "\n AND a.access <= '".$my->get('gid')."'" : '' )
#******************************************#
//  This Controls the fact that this module displayes the Latest News first
   . "\n  ORDER BY $order_by"
#******************************************#
   . "\n $limit;"
;

$db =& JFactory::getDBO();



$db->setQuery( $query );

$rows = $db->loadObjectList();

######################################################################################################################################

//  Error checker, that tests whether any data has resulted from the query
//  If not an Error message is displayed
if ($rows <> NULL) {

	

// echo "\n <!-- A 'stingrey MOS-Solutions' module '".$website."' -->";

   //---------------------------------------------------------------------

   // If autotitle set to yes, displays an Auto Title preffix with the name of the section/category
   if ( $show_title_auto ) {
      if ($set_author_id) {
         $db->setQuery("SELECT a.name "
         ."\n  FROM #__users AS a"
         ."\n  WHERE a.id=".$set_author_id);
         $text_title_auto_mid = $db->loadResult();
         $title_text = $text_title_auto_pre.$text_title_auto_mid.$text_title_auto_suf;
      } else if ( $set_author_name ) {
         $text_title_auto_mid = $set_author_name;
         $title_text = $text_title_auto_pre.$text_title_auto_mid.$text_title_auto_suf;
      } else if ($view == "section") {
         $db->setQuery("SELECT a.name "
         ."\n  FROM #__sections AS a"
         ."\n  WHERE a.id=".$set_section_id_extra);
         $text_title_auto_mid = $db->loadResult();
         $title_text = $text_title_auto_pre.$text_title_auto_mid.$text_title_auto_suf;
      } else if ($view == "category") {
         $db->setQuery("SELECT a.name "
         ."\n  FROM #__categories AS a"
         ."\n  WHERE a.id=".$set_category_id_extra);
         $text_title_auto_mid = $db->loadResult();
         $title_text = $text_title_auto_pre.$text_title_auto_mid.$text_title_auto_suf;
      } else {
         $title_text = $text_title_auto_pre.$text_title_auto_suf;
      }
      echo "\n \n";
	  echo "<h3>";
      echo $title_text;
      echo "</h3>";
   }  //---------------------------------------------------------------------

   
   //Div that surrounds whole Module (excluding Title)
   echo "\n \n";

   echo "<div ".modDisplayNewsHelper::dn_style("dn-whole").">";

   if (strlen($text_module_description) > "0") {
      echo "\n \n";
      echo "<div ".modDisplayNewsHelper::dn_style("dn-module_description").">";
      echo "\n";
      echo $text_module_description;
      echo "\n </div>";
   }

   //---------------------------------------------------------------------

   
   // Activates scrolling text ability
   if ($scroll_direction != "no" ) {
      echo "\n \n";
      echo "<marquee behavior='scroll' align='center'  direction='".$scroll_direction."'  height='".$scroll_height."' scrollamount='".$scroll_speed."' scrolldelay='".$scroll_delay."' truespeed onmouseover=this.stop() onmouseout=this.start() >";
   }  //---------------------------------------------------------------------

	switch ($style) {
	case 'horiz':
	   echo '<table>';
       echo '<tr>';
	   break;
	case 'vert':
	   echo '<table>';
	   break;
	case 'flatarrow':
	   echo "\n<ul  ".modDisplayNewsHelper::dn_style("dn").">";
	   break;
    case 'flatlist':
       echo "\n<ol  ".modDisplayNewsHelper::dn_style("dn").">";
	default:
//	   echo '<div>';
	}

   
   // Start of Loop //
   $k = 0;
   foreach ($rows as $row) {
      echo "\n\t";

	$aparams = new JParameter($row->attribs);

	  // 
	$row->text = "";
	if ( $show_text ) {
		if ( $show_full_text ) {
		    if ($aparams->get('show_intro')) {
				$row->text .= $row->introtext;
			}
			$row->text .= $row->fulltext;
		} else {
			$row->text .= $row->introtext;
		}
	}
      
    switch ($style) {
	case 'horiz':
        echo "<td ".modDisplayNewsHelper::dn_style("dn-each", ( $this->css_type == "table" ) ? ($k+1) : "" )." valign=\"top\">";
        break;
    case 'vert':
      	echo "<tr><td ".modDisplayNewsHelper::dn_style("dn-each", ( $this->css_type == "table" ) ? ($k+1) : "" )." width=\"100%\">";
  	    break;
	case 'flatarrow':
      echo "<li ".modDisplayNewsHelper::dn_style("arrow-dn").">";
      break;
    case 'flatlist':
      echo "<li ".modDisplayNewsHelper::dn_style("list-dn").">";
      break;
	default:
	  	echo "<div ".modDisplayNewsHelper::dn_style("dn-each", ( $this->css_type == "table" ) ? ($k+1) : "" )." >";
	}


      
      // Start of Module Display for each News Item

      if ( $this->css_type == "table" ) {
	      $k = 1 - $k;
	  }
      
	if ( isset($mosConfig_jf_content) && $mosConfig_jf_content){
		  $row = JoomFish::translate( $row, 'content', $mosConfig_lang);
	}
      //---------------------------------------------------------------------


	// define the regular expression for the bot
      $regex = "{moscomment}";

       // perform the replacement
    if ( $show_text ) { 
		$row->text = str_replace( $regex, '', $row->text );
      
		// Removes instances of {mospagebreak} from being displayed
		$row->text = str_replace( '{mospagebreak}', '', $row->text );
	}
      //---------------------------------------------------------------------

	  $results = $mainframe->triggerEvent('onPrepareContent', array (&$row, &$aparams, 1));
      //---------------------------------------------------------------------
      
      // Loads the section information into variable $section
      if ( $show_section  || $show_category ) {
         $section =& JTable::getInstance( 'section' );
         $section->load( $row->sectionid );
         //  Mambelfish Support
         if( $mosConfig_mbf_content ) {
                      $section = JoomFish::translate( $section, 'sections', $mosConfig_lang);
         }
      }

      // loads the category inion into variable $category
      if ( $show_category ) {
	     $category = & JTable::getInstance( 'category' );
		 if ( $category ) {
	     $category->load( $row->catid );
		 $category->catslug = strlen( $category->alias ) ?  $category->id . ":" . $category->alias : $category->id;  
		 // CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug
         //  Mambelfish Support
         if( $mosConfig_mbf_content ) {
            $category = JoomFish::translate( $category, 'categories', $mosConfig_lang);
         }
		 }
      }
      //---------------------------------------------------------------------

      
      //To show date item created, date mambot called
      if ( ($show_date ) && (intval( $row->created ) <> NULL) ) {
         $create_date = JHTML::_('date', $row->created, $format_date );
      }
      //---------------------------------------------------------------------


      // Converts the user id number of the author to their name and loads this into the $author variable
      if ( $show_author ) {
      	 if ( $row->created_by_alias != '' )
			$author = $row->created_by_alias;
      	 else {
			$db->setQuery("SELECT a.name"
			."\n  FROM #__users AS a"
			."\n  LEFT JOIN #__content AS b ON a.id=b.created_by"
			."\n  WHERE a.id=".$row->created_by);
			$author = $db->loadResult();
		 }
      }
      // Will check to see if item uses a created by alias, if it does, it loads this into the $author variable
      // however, if the item only has a created by value, it converts the user id number of the author to their name and loads this into the $author variable
      //---------------------------------------------------------------------


      
      // Code for displaying of individual items Section

      $section_out = $category_out = $date_out = $author_out = $title_out = $hits_out = $rating_out = $text_out = $readmore_out = "";

      if ($show_section ) {
         // $section_out .= "\n\t\t";
         $section_out .=  "<span ".modDisplayNewsHelper::dn_style("dn-section").">";
         if ($link_section ) {
            if ($section->published == "1") {
               // $section_out .=  "\n\t\t\t";
               // $section_out .=  "<a href='".JRoute::_("index.php?option=com_content&task=${blog}section&id=$section->id".$_Itemid."")."' ".modDisplayNewsHelper::dn_style("dn-section")." ";
               $section_out .=  "<a href='".JRoute::_(ContentHelperRoute::getSectionRoute($section->id))."' ".modDisplayNewsHelper::dn_style("dn-section")." ";
               
               if ( $show_tooltips ) {
	               $section_out .=  "title='".$text_hover_section.$section->title."'";
	           }
               $section_out .=  ">".$section->title."</a>";
            } else {
               $section_out .=  $section->title;
            }
         } else {
            $section_out .=  $section->title;
         }
         $section_out .=  "</span>";
      }
      //---------------------------------------------------------------------

      
      // Code for displaying of individual items Category
      if ($show_category ) {
         $category_out .= "<span ".modDisplayNewsHelper::dn_style("dn-category").">";
         if ($link_category ) {
            if ($category->published == "1") {
               $category_out .= "<a href='".JRoute::_(ContentHelperRoute::getCategoryRoute($category->catslug, $section->id))."' ".modDisplayNewsHelper::dn_style("dn-category")." ";
               if ( $show_tooltips ) {
	               $category_out .= "title='".$text_hover_category.$category->title."'";
	           }
               $category_out .= ">".$category->title."</a>";
            } else {
               $category_out .= $category->title;
            }
         } else {
            $category_out .= $category->title;
         }
         $category_out .= "</span>";
      }  //---------------------------------------------------------------------

      
      // Code for displaying of individual items Date
      if ( (($show_date ) ) ) {
         $date_out .= "<span ".modDisplayNewsHelper::dn_style("dn-date").">";
         $date_out .= $create_date;
         $date_out .= "</span>";
      }
      //---------------------------------------------------------------------

      
      // Code for displaying of individual items Author
      if ( ($show_author ) )  {
         $author_out .= "<span ".modDisplayNewsHelper::dn_style("dn-author").">";
         $author_out .= $author;
         $author_out .= "</span>";
      }
      //---------------------------------------------------------------------

      
      // Code for displaying of individual items Title
      if ($show_title ) {
         $title = $row->title;
      	 $title = JFilterOutput::ampReplace( $title );
         if ($filter_title ) {
            $title = modDisplayNewsHelper::dn_filter( $title );
			modDisplayNewsHelper::dn_limit( $title,	$filter_title, $length_limit_title, "" );
         }
         if ($show_title_nextline ) {
            $length = strlen($title);
            if ($length > $length_chars_title) {
               $titlefirstline = strtok($title, " ");
               $length = strlen($titlefirstline);
               while ($length < $length_chars_title) {
                  $titlefirstline .= " ";
                  $titlefirstline .= strtok(" ");
                  $length = strlen($titlefirstline);
               }
               $lengthfull = strlen($title);
               $titlesecondline = substr($title, $length, $lengthfull);
               $title = $titlefirstline."<br />".$titlesecondline;
            }
         }
         //  HTML for outputing of Title
         $title_out .= "<span ".modDisplayNewsHelper::dn_style("dn-head").">";
         if ($link_titles ) {
            $title_out .= "<a href='".JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid ))."' ".modDisplayNewsHelper::dn_style("dn-title")." ";
            if ( $show_tooltips ) {
	            $title_out .= "title='".$text_hover_title.$row->title."'";
	        }
            $title_out .= ">".$title."</a>";
         } else {
            $title_out .= "<span ".modDisplayNewsHelper::dn_style("dn-title").">".$title."</span>";
         }
         $title_out .= "</span>";
      }
      //---------------------------------------------------------------------

      if ($show_hits ) {

         $hits_out .= "<span ".modDisplayNewsHelper::dn_style("dn-hits").">";
         $hits_out .= $row->hits;
         $hits_out .= "</span>";
      }

            //---------------------------------------------------------------------

      if ($show_rating ) {
		// look for images in template if available
		$img = '';
		$starImageOn 	= JHTML::_('image.site',  'rating_star.png', '/images/M_images/' );
		$starImageOff 	= JHTML::_('image.site',  'rating_star_blank.png', '/images/M_images/' );

		for ($i=0; $i < $row->rating; $i++) {
			$img .= $starImageOn;
		}
		for ($i=$row->rating; $i < 5; $i++) {
			$img .= $starImageOff;
		}
		
		$rating_out .= '<span class="content_rating">';
		$rating_out .= JText::_( 'User Rating' ) .':'. $img .'&nbsp;/&nbsp;';
		$rating_out .= intval( $row->rating_count );
		$rating_out .= "</span>\n<br />\n";
      }


      
      // Code for displaying of individual items Intro Text
      if ($show_text ) {
      	
        $text = $row->text;
      	
		if (!$show_image) {
			$text = preg_replace( '/<img[^>]*>/', '', $text );
		} else if ($show_image > 1) {
			$text = preg_replace( '/(<img[^>]*)(\s+width\s*=\s*["]*\d+["]*)([^>]*>)/i', '$1$3', $text );
			$text = preg_replace( '/(<img[^>]*)(\s+height\s*=\s*["]*\d+["]*)([^>]*>)/i', '$1$3', $text );
			$text = preg_replace( '/(<img[^>]*\s+style\s*=\s*")(\s*width\s*:\s*\d+[px\s%]*;)([^>]*>)/i', '$1$3', $text );
			$text = preg_replace( '/(<img[^>]*\s+style\s*=\s*")(\s*height\s*:\s*\d+[px\s%]*;)([^>]*>)/i', '$1$3', $text );
			$text = preg_replace( '/(<img[^>]*)(>)/i', '$1 '.($show_image == 2 ? "width" : "height").'='.$image_size.' $2', $text );
		};
		if ($show_image && $image_align) {
			$text = preg_replace( '/(<img[^>]*)(\s+align\s*=\s*["]?\s+["]?)([^>]*>)/', '$1$3', $text );
			$text = preg_replace( '/(<img[^>]*\s+style\s*=\s*")(\s*align:\s*\d+[px\s%]*;)([^>]*>)/', '$1$3', $text );
			/*      <option value="0">As Is</option>
				<option value="1">No</option>
				<option value="2">Left</option>
				<option value="3">Right</option> 	*/
			if ( $image_align >= 2) {
				$text = preg_replace( '/(<img\s+[^>]*)(>)/', '$1 align='.($image_align == 2 ? "left" : "right").' $2', $text );
			}
		}
      	
        $text = JFilterOutput::ampReplace($text);
		$text_limited = 0;
        if ($filter_text ) {
            $text = modDisplayNewsHelper::dn_filter( $text );
         	$text_limited = modDisplayNewsHelper::dn_limit( $text, $filter_text, $length_limit_text,
            								"" /* $show_readmore ? JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid )) : ""  */);
         }

         //  HTML for outputing of Intro Text
         $text_out .= "<span ".modDisplayNewsHelper::dn_style("dn-introtext").">";
         if ($link_text ) {
            $text_out .= "<a href='".JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid ))."' ".modDisplayNewsHelper::dn_style("dn-introtext-link")." ";
            
            if ( $show_tooltips ) {
	            $text_out .= "title='".$text_hover_title.$row->title."'";
	        }
            $text_out .= ">";
         }
         $text_out .= $text;
         if ($link_text ) {
            $text_out .= "</a>";
         }
         $text_out .= "</span>";
      }
      //---------------------------------------------------------------------
      
      // Code for displaying of individual items Read More link
      // $show_text && $show_full_text
        if ( ($show_readmore == 1) || 
             (($show_readmore == 2) && !$show_text ) ||
             (($show_readmore == 2) && $text_limited ) ||
             (($show_readmore == 2) && ( $filter_text < 2 ) && strlen( $row->fulltext ) && !($show_text && $show_full_text) ) 
            ) {
         $readmore_out .= "<span ".modDisplayNewsHelper::dn_style("dn-read_more").">";
		 
         $readmore_out .= "<a href='".JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid ))."' ".modDisplayNewsHelper::dn_style("dn-read_more")." ";
         if ( $show_tooltips ) {
	         $readmore_out .= "title='".$text_hover_readmore.$row->title."'";
	     }
         $readmore_out .= ">". ( $text_readmore != "" ? $text_readmore : ( $aparams->get('readmore') ? $aparams->get('readmore') : JText::_('Read more...') ) )."</a>";
         $readmore_out .= "</span>";
      }
      //---------------------------------------------------------------------

		$read_more_shown = 0;
         $out = "";
         $pf =0;
         for ( $i=0; $i < strlen( $format); $i += 1 ) {
            if ( $format[$i] == "%" ) {
               $pf = 1;
            } else {
               if ( $pf==1 ) {
                  $pf = 0;
                  switch ( $format[$i] ) {
                  case "s":
                     echo $section_out;
                     break;
                  case "c":
                     echo $category_out;
                     break;
                  case "d":
                     echo $date_out;
                     break;
                  case "t":
                     echo $title_out;
                     break;
                  case "h":
                     echo $hits_out;
                     break;
                  case "a":
                     echo $author_out;
                     break;
                  case "i":
                     echo $text_out;
                     break;
                  case "r":
                     echo $rating_out;
                     break;
                  case "m":
                     echo $readmore_out;
					 $read_more_shown = 1;
                     break;
                  case "%":
                     echo "%";
                     break;
                  default:
                  } // switch
               } else {
                  echo $format[$i];
               } // if ( pf
            } // if ( $format[i] == "%" )
            echo $out;
         } // for
         echo $out;

		 if (!$read_more_shown) {
			echo $readmore_out; // read more is not element of format, but should be printed at the end of each content entry
		}

      if ($style == 'horiz' ) {
         echo '</td>';
      } elseif ($style == 'vert' ) {
         echo '</td></tr>';
      } elseif ($style == 'flatlist' || $style == 'flatarrow' ) {
       	 echo '</li>';
	  } else {
	  	 echo '</div>';
	  }

   }

   // End of Loop //
   

   if ($style == 'horiz' ) {
      echo '</tr></table>';
   } elseif ($style == 'vert') {
      echo '</table>';
   } elseif ($style == 'flatarrow' ) {
   	  echo "\n</ul>";
   } elseif ($style == 'flatlist' ) {
   	  echo "\n</ol>";
   } else {
   }

   
   if ($scroll_direction != "no" ) {
         echo "</marquee>";
   }
   //---------------------------------------------------------------------

   echo "\n";

   // Error check if more than one Sectionid entered (searches for , in parameter)
   if ( strchr($set_section_id_extra, ",") ) {
      $set_section_id_extra = "";
   }
   // Error check if more than one Categoryid entered (searches for , in parameter)
   if ( strchr($set_category_id_extra, ",") ) {
      $set_category_id_extra = "";
   }

   //---------------------------------------------------------------------

   
   if ($show_more_auto ) {
      if  (($set_section_id_extra) && (! $set_category_id_extra))  {
         $more_section = & JTable::getInstance( 'section' );
         $more_section->load( $set_section_id_extra );
         echo "<div ".modDisplayNewsHelper::dn_style("dn-more").">";
         echo "<a href='".JRoute::_(ContentHelperRoute::getSectionRoute($more_section->id))."' ".modDisplayNewsHelper::dn_style("dn-more")." ";
         if ( $show_tooltips ) {
	         echo "title='".$text_hover_more_section.$more_section->title."'";
	     }
         echo ">".$text_more."</a>";
         echo "</div>";
      } else if (($set_category_id_extra ) && (!$set_section_id_extra)) {
         $more_category = & JTable::getInstance( 'category' );
         $more_category->load( $set_category_id_extra );
		 $category->catslug = strlen( $more_category->alias ) ?  $more_category->id . ":" . $more_category->alias : $more_category->id;  
         echo "<div ".modDisplayNewsHelper::dn_style("dn-more").">";
         echo "<a href='".JRoute::_(ContentHelperRoute::getCategoryRoute($more_category->catslug, $more_category->section))."' ".modDisplayNewsHelper::dn_style("dn-more")." ";
         if ( $show_tooltips ) {
	         echo "title='".$text_hover_more_category.$more_category->title."'";
	     }
         echo ">".$text_more."</a>";
         echo "</div>";
      }
   }
   //---------------------------------------------------------------------

   echo "\n</div>"; // dn-each
   //---------------------------------------------------------------------

   
   //
   if ( ($bottom_link_text <> NULL) && ($bottom_link_url <> NULL) ){
      echo "\n";
      echo "<div ".modDisplayNewsHelper::dn_style("dn-module_link").">";
      echo "\n<a href='".$bottom_link_url."' ".modDisplayNewsHelper::dn_style("dn-module_link")." ";
      if ( $show_tooltips ) {
	      echo "title='".$bottom_link_text."'";
	  }
      echo ">";
      echo $bottom_link_text;
      echo "\n</a>";
      echo "\n";
      echo "</div>";
   }
   //---------------------------------------------------------------------

   //---------------------------------------------------------------------



} 

echo "\n<!-- END module '".$version."' -->\n";

} // dn_main

}

?>