<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/*
 * Copyright Copyright (C) 2007 Alain Georgette. All rights reserved.
 * Copyright Copyright (C) 2006 Frantisek Hliva. All rights reserved.
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * !JoomlaComment is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * !JoomlaComment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA  02110-1301, USA.
 */

/* 
 * set the release compatibility
 */
function JOSC_define($name, $value) {

   	if (!defined($name)) {
		$local_charset = defined('_JOOMLACOMMENT_LOCAL_CHARSET_VALUE') ? _JOOMLACOMMENT_LOCAL_CHARSET_VALUE : 'utf-8';	
   		DEFINE($name, JOSC_utils::myiconv_decode($value, $local_charset) );
   	}
}

/*
 * component class for plugin extends
 */
class JOSC_component  extends JObject {
	var $_component;
	var	$_sectionid;
	var	$_id; /* content_id */
	var $_official;
	
	function JOSC_component($component='',$sectionid=0,$id=0) 
	{
		$this->_component 	= $component;
		$this->_sectionid 	= $sectionid;
		$this->_id			= $id;
		/*
		 * set official property for backward compatibility in custom plugins
		 */
		switch ($this->_component) {
			case '':
			case 'com_docman':
			case 'com_eventlist':
			case 'com_joomlaflasgames':
			case 'com_puarcade':
			case 'com_seyret': 
				$this->_official = true;
				break;
			default:
				$this->_official = false;
				break;
		}
	}
}

/*
 * UTILS CLASS
 */
class JOSC_utils {

	function set_charsetConstant($charset)
	{
		if (!defined('_JOOMLACOMMENT_LOCAL_CHARSET_VALUE')) 
			define('_JOOMLACOMMENT_LOCAL_CHARSET_VALUE',$charset);		
	}
	
	function set_JoomlaRelease()
	{	
	   JOSC_define( '_JOSC_MOS_ALLOWHTML', JREQUEST_ALLOWHTML);
		
	}


	/*
	 * require once the component plugin file and create component class
	 * class component file name must be : josc_[component].class.php
	 * and it must be in the administrator/components/com_comment/plugin/[component]  directory
	 */
	function ComPluginObject($component, &$row, $set_id=0, $sectionid=0)
	{


	    JOSC_utils::set_JoomlaRelease();
	    $com = JOSC_utils::getComponentName($component, $set_id);
	    $file = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_comment'.DS.'plugin'.DS.$com.DS.'josc_'.$com.'.class.php';
	    if (!file_exists($file)) {
		var_dump("joomlacomment: unexpected error. No plugin found for component '$component' !");
		return null;
	    }
	    require_once($file);
	    $class = "JOSC_$com";
	    $list=array();
	    $list['sectionid'] = $sectionid;
	    $comObject = new $class($component, $row, $list);
	    return $comObject;
	}

	/*
	 * This is the exec function to call joomlacomment from any component
	 * 
	 * $exclude :	active section/category exclusion OR not
	 * 
	 * $row and $params will be passed to the plugin functions
	 * 
	 */ 
	function execJoomlaCommentPlugin(&$comObject, &$row, &$params, $exclude=true)
	{
	    global $option;
	
		if ($comObject==null) return;
				
		$board = JOSC_utils::boardInitialization($comObject, $exclude, $row, $params);
		
		/* exclude is set again in board  
		 * according to section/categories exclusion 
 	 	 */
	
		$html = "<!-- START of joscomment -->";
	    if (!$exclude) {
//	        $board->setContentId($comObject->_id);//$row->id);
	        $board->execute();
	        $html .= $board->visual_htmlCode();
	    } else {
	   		unset($board);
	    }
		$html .= "<!-- END of joscomment -->";
	
 	   return $html;
	}	

	/*
	 *      used in mod_comment module !
	 */
	function boardInitialization(&$comObject, &$exclude, &$row, &$params)
	{
	    global $option;
	
	    $GLOBALS['josComment_path'] = "/components/com_comment/joscomment";
	    $GLOBALS['josComment_absolute_path'] = JPATH_SITE . $GLOBALS['josComment_path'];
	    $GLOBALS['josComment_live_site'] = JURI::base(). $GLOBALS['josComment_path'];

		//    require_once($GLOBALS['josComment_absolute_path'] . '/utils.php');
	    require_once(JPATH_SITE."/administrator/components/com_comment/class.config.comment.php");
	    require_once($GLOBALS['josComment_absolute_path'] . '/comment.class.php');

	    $board = new JOSC_board($GLOBALS['josComment_absolute_path'], $GLOBALS['josComment_live_site'], $comObject, $exclude, $row, $params);		
	    
	    return $board;
	}
	
	function getComponentName($component, $set_id=0)
	{
		if ($set_id==1 || !$component)  return "com_content";
		return	$component;
	}

	function loadAdminLoadLanguage($_admin_language)
	{
	    $params   = JComponentHelper::getParams('com_languages');
	    $frontend_lang = $params->get('site', 'en-GB');
	    $lang = JLanguage::getInstance($frontend_lang);

/*        $path = JPATH_SITE.'/components/com_comment/joscomment';
		require_once($path.'/utils.php');*/

	    $path = JPATH_SITE.'/administrator/components/com_comment/admin_language/';
	    if ($_admin_language == 'auto')	{
		$language = $path . 'admin_' . $lang->getBackwardLang() . '.php';
	    } else {
		$language = $path . $_admin_language; /* admin_ already set in the value */
	    }
	    if (file_exists($language)){
		require_once($language);
	    }
	    require_once($path . 'admin_english.php');  // default is EN. non existant constants will be taken from default from this

	}
    
    function loadFrontendLoadLanguage($_language)
    {	
        $path = JPATH_SITE.'/components/com_comment/joscomment/language/';
		$language = $path . JOSC_utils::getAutoFrontendLanguage($_language);

        if (file_exists($language)) {
	    require_once($language);
	}
        	
        require_once($path . 'english.php');  // default is EN. non existant constants will be taken from default from this

    }

    function getAutoFrontendLanguage($_language)
    {
	if ( $_language!='' && $_language!='auto') {
	    return $_language;
	}


	$config = &JFactory::getConfig();
	$language    = $config->getValue('config.language');

	switch ($language)
	{
	    case 'bg-BG':
		$language = 'bulgarian.php';
		break;
	    case 'ca-ES':
		$language = 'spanish.php';
		break;
	    case 'cz-CZ':
		$language = 'czech.php';
		break;
	    case 'da-DK':
		$language = 'danish.php';
		break;
	    case 'de-DE':
		$language = 'germanf.php';
		break;
	    case 'el-GR':
		$language = 'greek.php';
		break;
	    case 'en-GB':
		$language = 'english.php';
		break;
	    case 'es-ES':
		$language = 'spanish.php';
		break;
	    case 'eu-ES':
		$language = 'spanish.php';
		break;
	    case 'fa-IR':
		$language = 'persian.php';
		break;
	    case 'fr-FR':
		$language = 'french.php';
		break;
	    case 'he-IL':
		$language = 'hebrew.php';
		break;
	    case 'hr-HR':
		$language = 'hrvatski.php';
		break;
	    case 'hu-HU':
		$language = 'hungarian.php';
		break;
	    case 'it-IT':
		$language = 'italian.php';
		break;
	    case 'ja-JP':
		$language = 'japanese.php';
		break;
	    case 'nl-NL':
		$language = 'dutch.php';
		break;
	    case 'pl-PL':
		$language = 'polish.php';
		break;
	    case 'pt-BR':
		$language = 'brazilian_portuguese.php';
		break;
	    case 'pt-PT':
		$language = 'brazilian_portuguese.php';
		break;
	    case 'ro-RO':
		$language = 'romanian.php';
		break;
	    case 'ru-RU':
		$language = 'russian.php';
		break;
	    case 'sr-RS':
		$language = 'serbian_lat.php';
		break;
	    case 'sk-SK':
		$language = 'slovak.php';
		break;
	    case 'zh-TW':
		$language = 'chinese_traditional.php';
		break;
	    default :
		     /*  try itself : if not english will be taken */
		break;
	}
	if (strpos($language,".php")===false) {
	    return $language.".php";
	} else {
	    return $language;
	}

    }
     
	function showMessage($msg)
	{
	    echo("<script type='text/javascript'>alert('$msg');</script>");
	}

	function insertToHead($html)
	{
	    global $mainframe;

    /*
     * header problems if cache -> example when voting
     * header is refreshed but not the bots ! so css, js...are lost.
     */
	    if ($mainframe->getCfg('caching')) {
		return $html;
	    } else {

		if (!strpos($mainframe->getHead(), $html))
		$mainframe->addCustomHeadTag($html);
		return "";
	    }
	}

	function getJOSCUserTypes($unregistered = true)
	{
		/* since joomla 1.5 table usertypes does not exist no more */
		
		$usertypes = array();

		if ($unregistered) {
		$usertypes[] = JHTML::_('select.option',  '-1', 'Unregistered', 'id', 'title' );
		}
		$usertypes[] = JHTML::_('select.option',  '3', '.Registered', 'id', 'title' );
		$usertypes[] = JHTML::_('select.option',  '4', '..Author', 'id', 'title' );
		$usertypes[] = JHTML::_('select.option',  '2', '...Editor', 'id', 'title' );
		$usertypes[] = JHTML::_('select.option',  '5', '....Publisher', 'id', 'title' );
		$usertypes[] = JHTML::_('select.option',  '6', '.Manager', 'id', 'title' );
		$usertypes[] = JHTML::_('select.option',  '1', '..Administrator', 'id', 'title' );
		$usertypes[] = JHTML::_('select.option',  '0', '....SAdministrator', 'id', 'title' );
			
        return $usertypes;
	}
	
	function getJOSCUserType($userType)
	{
    switch ($userType) {
        case 'Super Administrator':
        case 'SAdministrator':
            $result = 0;
            break;

        case 'Administrator':
            $result = 1;
            break;

        case 'Editor':
            $result = 2;
            break;

        case 'Registered':
            $result = 3;
            break;

        case 'Author':
            $result = 4;
            break;

        case 'Publisher':
            $result = 5;
            break;

        case 'Manager':
            $result = 6;
            break;
            
        default:
            $result = -1;
            break;
    }
    return $result;
	}

	/*
	 * convert joomlacomment usertype int to standard Joomla value 
	 */
	function getJoomlaUserType($JOSCUserType)
	{
    switch ($JOSCUserType) { 
        case 0:
			$result = 'Super Administrator';
            break;

        case 1:
            $result = 'Administrator';
            break;

        case 2:
            $result = 'Editor';
            break;

        case 3:
            $result = 'Registered';
            break;

        case 4:
            $result = 'Author';
            break;

        case 5:
            $result = 'Publisher';
            break;

        case 6:
            $result = 'Manager';
            break;
        default:
            $result = '';
            break;
    }
    return $result;
	}

/*
 * check if current ($my) user is moderator 
 * OR if the comment is one of its comment
 */
	function isCommentModerator($moderatorlist, $userid=0)
	{
    	$my = JFactory::getUser();

  		/* is $my moderator ? */
    	$ismoderator = JOSC_utils::isModerator($moderatorlist);
    	if ($ismoderator) return true;
    	
    	if (!$userid || !isset($my->id)) return false;
    	/* is comment userid = to $my userid ? */
    	return ($my->id==$userid);
	}

/*
 * check if current ($my) user is moderator 
 * OR if usertype param is moderator
 */
	function isModerator($moderator,$usertype='') 
	{
    	$my = JFactory::getUser();
    
    	if (!$usertype)
			return (in_array(JOSC_utils::getJOSCUserType($my->usertype), $moderator));
		else 
			return (in_array(JOSC_utils::getJOSCUserType($usertype), $moderator));
	}

	function partialIP($ip)
	{
    $quads = split('\.', $ip);
    $quads[3] = 'xxx';
    return join(".", $quads);
	}

//	function replaceNL($text)
//	{
//    return str_replace("\n", '\n', htmlspecialchars($text, ENT_QUOTES));
//	}

	function ignoreBlock($source, $name, $ignore, $newStr = '')
	{
    if ($ignore) {
        //if ($newStr == '') $after_replace = '';
        //else $after_replace = $newStr;
        $after_replace = $newStr;
    } else { 
    	$after_replace = '\\1';
    }
    return eregi_replace("\{".$name."\}([^\[]+)\{/".$name."\}", $after_replace, $source);
	}

	/*
	 * $display = true 	: get the block deleting tags
	 * $display = false : replace the block by $newStr
	 * 
	 */
	function checkBlock($name, $display, $source, $newStr = '')
	{
    	if ($display) {
    		$after_replace = '\\1';
    	} else { 
    	    $after_replace = $newStr;
    	}
    	//return eregi_replace("\{".$name."\}([^\[]+)\{/".$name."\}", $after_replace, $source);
    	//return preg_replace('/{'.$name.'}(.*?){\/'.$name.'}/si', $after_replace, $source);
	    $source = str_replace('$','&#36;',$source);
	    return preg_replace("/{".$name."}(.*?){\/".$name."}/si", $after_replace, $source);
	}

	function decodeData($varName)
	{
    	return JArrayHelper::getValue($_REQUEST, $varName, '', _JOSC_MOS_ALLOWHTML);
	}

	function myiconv_decode($var, $local_charset)
	{
	    if (strtoupper($local_charset)=="UTF-8") {
		return($var);
	    }
	    if (function_exists("iconv")){
		return iconv( "UTF-8", strtoupper($local_charset), $var );
	    } elseif (strtoupper($local_charset) == 'ISO-8859-1'){
		return utf8_decode($var);
	    } else {
		return($var);
	    }

	}

	function myiconv_encode($var, $local_charset) 
	{
	if (strtoupper($local_charset)== "UTF-8") return($var);
   	if (function_exists("iconv"))
   		return iconv( strtoupper($local_charset), "UTF-8",  $var );
   	elseif (strtoupper($local_charset) == 'ISO-8859-1')
   		return utf8_encode($var);
   	else
   		return($var);
	}

	function cdata($data)
	{
    if ($data == '') return '';
    else return "<![CDATA[$data]]>";
	}

	function block($source, $name)
	{
    $begin = '{' . $name . '}';
    $end = '{/' . $name . '}';
    $len = strlen($begin);
    $pos_begin = strpos($source, $begin);
    $pos_end = strpos($source, $end);
    if ($pos_begin===false || $pos_end==false )
    	return '';
    else
        return substr($source, $pos_begin + $len, $pos_end - ($pos_begin + $len));
	}

	function filter($html, $downward = false)
	{
    /*
     * remind :
     * 	ISO 	= &#code;
     *  HTML 	= &name;
     */
    if ($downward) {
        $html = str_replace('&#64;', '@', $html);
        $html = str_replace('&#92;', '\\', $html);
        $html = str_replace('&#34;', '"', $html);
    } else {
        $html = str_replace('@', '&#64;', stripslashes($html));
        $html = str_replace('\\', '&#92;', $html);
        $html = str_replace('"', '&#34;', $html);
    }
    return $html;
	}

	function buildTree($data)
	{
    	$tree = new JOSC_tree();
    	return $tree->build($data);
	}

    function setMaxLength($text, $_maxlength_text)
    {
        if (($_maxlength_text != -1) && (mb_strlen($text) > $_maxlength_text))
            $text = mb_substr($text, 0, $_maxlength_text-3) . '...';
        return $text;
    }
    
    function wrapText($text, $_maxlength_line, $char="<br />")
	{
    	if ($_maxlength_line > -1) 
    		return wordwrap($text, $_maxlength_line, $char, true);
    	else
    		return $text; 
	}

    function text_cut($str, $no_words_ret)
    {

	// $str est la cha�ne � couper
	// $no_words_ret est le nombre de mots qu'on souhaite en retour

	static $tags = array ('div', 'span', 'b', 'u', 'i', 'a', 'ul', 'li');

	$word_count = 0;
	$pos = 0;
	$str_len = mb_strlen($str);
	$str .= ' <';
	$open_tags = array ();

	while ($word_count < $no_words_ret && $pos < $str_len) {
	    $pos = min(strpos($str, ' ', $pos), strpos($str, '<', $pos));

	    if ($str[$pos] == '<') {
		if ($str[$pos + 1] == '/') {
		    array_pop($open_tags);
		    $word_count++;
		} else {
		    $sub = mb_substr($str, $pos + 1, min(strpos($str, ' ', $pos), strpos($str, '>', $pos)) - $pos - 1);
		    if (in_array($sub, $tags)) {
			array_push($open_tags, $sub);
		    }
		}
		$pos = strpos($str, '>', $pos) + 1;
	    } else {
		$pos++;
		$word_count++;
	    }

	}

	$str = mb_substr($str, 0, $pos);

	if (count($open_tags) > 0) {
	    foreach($open_tags as $value) {
		$str .= '</' . array_pop($open_tags) . '>';
	    }
	}

	return($str);
    }

    function censorText($text,$_censorship_enable,$_censorship_words,$_censorship_case_sensitive)
    { 
        if ($_censorship_enable && is_array($_censorship_words)) {
            if ($_censorship_case_sensitive) $replace = 'str_replace';
            else $replace = 'str_ireplace';
            foreach($_censorship_words as $from => $to) {
                $text = call_user_func( $replace, $from, $to, $text);
            }
        }
        return $text;
    }
    
	function inputHidden($tag_name, $value = '')
	{
	    return "<input type='hidden' name='$tag_name' value='$value' />";
	}

	function debug_array($array=array()) 
	{
		if (!is_array($array))
			return "$array is not an array";
		elseif (count($array)<=0)
			return "$array is empty";
			
		$index = 0;
		$html = "";
		foreach($array as $line) {
			$html .= "<b>array[".$index."]</b> ".print_r($line,true)."\n<br />";
			$index++;
		}
	}
	
	function getLocalDate($strdate,$format='Y-m-d H:i:s')
	{
		global $mainframe;
		return(date($format, strtotime($strdate)+($mainframe->getCfg('offset')*60*60)));
	}

	function mysql_escape_string($value)
	{
	    $database =& JFactory::getDBO();

//		$database->getEscaped($value);	
		/* getEscaped does not work always for some configurations... strange -> Character problem. empty comment */               
		$result = mysql_real_escape_string( $value, $database->_resource );
		return $result;			
	}

//	function myspecial_chars($s) {
////  	$s = htmlspecialchars($s, ENT_COMPAT,'UTF-8');
//  		$s = htmlentities($s, ENT_QUOTES,'UTF-8');
//  		return ($s);
//	}

}

/*
 * 
 */
class JOSC_TableUtils {

	function getTableList() 
	{
		$database =& JFactory::getDBO();			

		$database->setQuery( 'SHOW TABLES' );
		return $database->loadResultArray();
	}
	    
	function existsTable($name)
	{
		$database =& JFactory::getDBO();			

	    $name = $database->replacePrefix($name);
    	$database->setQuery("SHOW TABLES LIKE '$name';");
    	return ($database->loadResult()) ? true : false;
	}

	function TableColumnsGet( $tablename, $key='' ) {
		$database =& JFactory::getDBO();		

		$database->setQuery("SHOW COLUMNS FROM $tablename");
   		return ( $database->loadObjectList($key) );
	}

	function TableFieldCheck( $fieldname, &$tablecols ) {
	
		if (!$tablecols) return false;
    		$found = false;

    	foreach( $tablecols as $col ) {
    		if ($col->Field == $fieldname) {
        		$found = true;
         		break;
        	}
    	}

    	return( $found );
	}
}

class JOSC_install {
    
    function checkCompatibility( &$install_log )
    {
        $database =& JFactory::getDBO();

        /* tables captcha and voting installed in the xml */
        $query = array();

        /*
         * #__comment
         */
        $columns = JOSC_TableUtils::TableColumnsGet( '#__comment' );
        $install_log .= "#__comment update :<br />";
    /*
     *  voting_yes,  voting_no
     */
        $fieldname = 'voting_yes';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `voting_yes` INT(10) NOT NULL default '0' "
            . "\n AFTER `published`;"
            ;
            $install_log .= "- update of $fieldname.<br />";
        } else {
            $install_log .= "- $fieldname exist.<br />";
        }
        $fieldname = 'voting_no';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `voting_no` INT(10) NOT NULL default '0' "
            . "\n AFTER `voting_yes`;"
            ;
            $install_log .= "- update of $fieldname.<br />";
        } else {
            $install_log .= "- $fieldname exist.<br />";
        }

    /*
     *  parentid
     */
        $fieldname = 'parentid';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `parentid` INT(10) NOT NULL default '-1' "
            . "\n AFTER `voting_no`;"
            ;
            $install_log .= "- update of $fieldname.<br />";
        } else {
            $install_log .= "- $fieldname exist.<br />";
        }

    /*
     *  email
     */
        $fieldname = 'email';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `email` VARCHAR(50) "
            . "\n AFTER `name`;"
            ;
            $install_log .= "- update of $fieldname.<br />";
        } else {
            $install_log .= "- $fieldname exist.<br />";
        }

    /*
     *  website
     */
        $fieldname = 'website';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `website` VARCHAR(100) "
            . "\n AFTER `email`;"
            ;
            $install_log .= "- update of $fieldname.<br />";
        } else {
            $install_log .= "- $fieldname exist.<br />";
        }

    /*
     *  notify
     */
        $fieldname = 'notify';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `notify` TINYINT(1) NOT NULL default '0' "
            . "\n AFTER `website`;"
            ;
            $install_log .= "- update of $fieldname.<br />";

            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD INDEX `contentid` ( `contentid` );"
            ; //optimisation: many search by contentid
            $install_log .= "- create index contentid.<br />";
        } else {
            $install_log .= "- $fieldname exist.<br />";
        }

    /*
     *  userid
     */
        $fieldname = 'userid';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `userid` INT(11)"
            . "\n AFTER `ip`;"
            ;
            $install_log .= "- update of $fieldname.<br />";
        } else {
            $install_log .= "- $fieldname exist.<br />";
        }


    /*
     *  component
     */
        $fieldname = 'component';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `component` VARCHAR(50)  NOT NULL default '' "
            . "\n AFTER `contentid`;"
            ;
            $install_log .= "- update of $fieldname.<br />";

            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD INDEX `com_contentid` ( `component`, `contentid` );"
            ; //optimisation: many search by component/contentid
            $install_log .= "- create index com_contentid.<br />";

        } else {
            $install_log .= "- $fieldname exist.<br />";
        }

    /*
     *  importtable
     *  importid
     *  importparentid
     */
        $fieldname = 'importtable';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `importtable` VARCHAR(30) NOT NULL default '' "
            . "\n AFTER `parentid`;"
            ;
            $install_log .= "- update of $fieldname.<br />";

        } else {
            $install_log .= "- $fieldname exist.<br />";
        }
        $fieldname = 'importid';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `importid` INT(10) NOT NULL default '0' "
            . "\n AFTER `importtable`;"
            ;
            $install_log .= "- update of $fieldname.<br />";

        } else {
            $install_log .= "- $fieldname exist.<br />";
        }
        $fieldname = 'importparentid';
        if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n ADD `importparentid` INT(10) NOT NULL default '-1' "
            . "\n AFTER `importid`;"
            ;
            $install_log .= "- update of $fieldname.<br />";

        } else {
            $install_log .= "- $fieldname exist.<br />";
        }

    /*
     *  title 30 to 50
     */
        $fieldname = 'title';
        $row = JOSC_TableUtils::TableColumnsGet( '#__comment', 'Field' );
        if ($row && (strtolower($row[$fieldname]->Type)!="varchar(50)")) {
            $query[] = "ALTER TABLE `#__comment` "
            . "\n CHANGE `title` `title` VARCHAR(50) NOT NULL default '' "
            ;
            $install_log .= "- update of $fieldname.<br />";
        }

        /*
         * #__comment_setting
         */
        $columns = JOSC_TableUtils::TableColumnsGet( '#__comment_setting' );
        if (!$columns) {
                /* CREATE TABLE */
            $install_log .= "Create #__comment_setting table.<br />";
            $query[] = JOSC_install::getQuery_Create__comment_setting();

        } else {
                /* UPDATE TABLE */
            $columns = JOSC_TableUtils::TableColumnsGet( '#__comment_setting' );
            $install_log .= "#__comment_setting update :<br />";
            /*
             *  name
             */
            $fieldname = 'set_name';
            if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
                $query[] = "ALTER TABLE `#__comment_setting` "
                . "\n ADD `set_name` VARCHAR(50)  NOT NULL default '' "
                . "\n AFTER `id`;"
                ;
                $install_log .= "- update of $fieldname.<br />";
            } else {
                $install_log .= "- $fieldname exist.<br />";
            }
            /*
             *  component
             */
            $fieldname = 'set_component';
            if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
                $query[] = "ALTER TABLE `#__comment_setting` "
                . "\n ADD `set_component` VARCHAR(50)  NOT NULL default '' "
                . "\n AFTER `set_name`;"
                ;
                $install_log .= "- update of $fieldname.<br />";
            } else {
                $install_log .= "- $fieldname exist.<br />";
            }
            /*
             *  sectionid
             */
            $fieldname = 'set_sectionid';
            if (!JOSC_TableUtils::TableFieldCheck( $fieldname, $columns )) {
                $query[] = "ALTER TABLE `#__comment_setting` "
                . "\n ADD `set_sectionid` INT(11) NOT NULL default '0' "
                . "\n AFTER `set_component`;"
                ;
                $install_log .= "- update of $fieldname.<br />";
            } else {
                $install_log .= "- $fieldname exist.<br />";
            }
        }

        /*
         * Execute queries and set resulting log
         */
        $install_log2 = "";
        if (count($query)>0) {
            foreach ($query as $sql) {
                $database->SetQuery($sql);
                if(!$result = $database->query()) {
                    $install_log2 .= "Install error: " . $database->stderr() . "<br />" . $sql ."<br /><br />";
                }
            }
        }
        $install_log .= $install_log2;
        return (!$install_log2);  // true if no error / false if error
    }

    function checkDatabase( &$install_log )
    {
        $database =& JFactory::getDBO();

        if (JOSC_TableUtils::existsTable('#__comment')) {

            return( JOSC_install::checkCompatibility( $install_log ) );

        } else {

                /*
                 * #__comment
                 */
            $install_log .= "Create #__comment table.<br />";
                /* in case of change, don't forget to update the JOSC_josComment class */
            $query = JOSC_install::getQuery_Create__comment();

            $database->SetQuery($query);
            $result = $database->query();
            /*
             * component/contentid index
             */
            if ($result) {
                $install_log .= "Create com_contentid index<br />";
                $query = "ALTER TABLE `#__comment` ADD INDEX `com_contentid` ( `component`, `contentid` )";
                $database->SetQuery($query);
                $result = $database->query();
            }
                        /*
                         * check result
                         */
            if(!$result) {
                $install_log .= "Install error: " . $database->stderr() . "<br /><br />";
                return false; // or die(_JOOMLACOMMENT_SAVINGFAILED);
            }

                /*
                 * #__comment_setting
                 */
            $install_log .= "Create #__comment_setting table.<br />";
            $query = JOSC_install::getQuery_Create__comment_setting();
            $database->SetQuery($query);
            $result = $database->query();
                        /*
                         * check result
                         */
            if(!$result) {
                $install_log .= "Install error: " . $database->stderr() . "<br /><br />";
                return false; // or die(_JOOMLACOMMENT_SAVINGFAILED);
            }

                /*
                 * #__comment_captcha
                 */
            $install_log .= "Create #__comment_captcha table.<br />";
            $query = JOSC_install::getQuery_Create__comment_captcha();
            $database->SetQuery($query);
            $result = $database->query();
                        /*
                         * check result
                         */
            if(!$result) {
                $install_log .= "Install error: " . $database->stderr() . "<br /><br />";
                return false; // or die(_JOOMLACOMMENT_SAVINGFAILED);
            }

                /*
                 * #__comment_voting
                 */
            $install_log .= "Create #__comment_voting table.<br />";
            $query = JOSC_install::getQuery_Create__comment_voting();
            $database->SetQuery($query);
            $result = $database->query();
                        /*
                         * check result
                         */
            if(!$result) {
                $install_log .= "Install error: " . $database->stderr() . "<br /><br />";
                return false; // or die(_JOOMLACOMMENT_SAVINGFAILED);
            }



            return true;
        }
    }
	
    function getQuery_Create__comment()
    {
        $query = "CREATE TABLE `#__comment` (
                `id` INT(10) NOT NULL auto_increment,
                `contentid` INT(10) NOT NULL default '0',
                `component` VARCHAR(50) NOT NULL default '',
                `ip` VARCHAR(15) NOT NULL default '',
                `userid` int(11),
                `usertype` VARCHAR(25) NOT NULL default 'Unregistered',
                `date` DATETIME NOT NULL default '0000-00-00 00:00:00',
                `name` VARCHAR(30) NOT NULL default '',
                `email` VARCHAR(50) NOT NULL default '',
                `website` VARCHAR(100) NOT NULL default '',
                `notify` TINYINT(1) NOT NULL default '0',
                `title` VARCHAR(50) NOT NULL default '',
                `comment` TEXT NOT NULL,
                `published` TINYINT(1) NOT NULL default '0',
                `voting_yes` INT(10) NOT NULL default '0',
                `voting_no` INT(10) NOT NULL default '0',
                `parentid` INT(10) NOT NULL default '-1',
                `importedtable` VARCHAR(30) NOT NULL default '',
                `importedid` INT(10) NOT NULL default '0',
                `importedparentid` INT(10) NOT NULL default '-1',
                PRIMARY KEY  (`id`)) type=MyISAM;";
        return $query;
    }
	
        function getQuery_Create__comment_setting()
        {
            /* in case of change, don't forget to update the JOSC_josComment class */
	    $query = "CREATE TABLE `#__comment_setting` (
               `id` INT(11) NOT NULL auto_increment,
               `set_name` VARCHAR(50) NOT NULL default '',
               `set_component` VARCHAR(50) NOT NULL default '',
               `set_sectionid` INT(11) NOT NULL default '0',
               `params` text NOT NULL,
               PRIMARY KEY  (`id`))  type=MyISAM";
            return $query;
        }

        function getQuery_Create__comment_captcha()
        {
            $query = "CREATE TABLE IF NOT EXISTS `#__comment_captcha` (
        `ID` int(11) NOT NULL auto_increment,
        `insertdate` datetime NOT NULL default '0000-00-00 00:00:00',
        `referenceid` varchar(100) NOT NULL default '',
        `hiddentext` varchar(100) NOT NULL default '',
        PRIMARY KEY (`ID`)) type=MyISAM";
            return $query;
        }

        function getQuery_Create__comment_voting()
        {
            $query = "CREATE TABLE IF NOT EXISTS `#__comment_voting` (
        `id` INT(10) NOT NULL default '0',
        `ip` VARCHAR(15) NOT NULL default '',
        `time` INTEGER NOT NULL default '0') type=MyISAM";
            return $query;
        }

        function createImportSetting($execute=true)
        {
            $database =& JFactory::getDBO();

            $result = true;
                /* in case of change, don't forget to update the josImportSetting class */
            $query = "CREATE TABLE `#__comment_importsetting` (
                        `id` INT(10) NOT NULL auto_increment,
                        `tablename` VARCHAR(100) ";

            $columns = JOSC_TableUtils::TableColumnsGet( '#__comment' );
            if ($columns) {
                foreach($columns as $col) {
                    if ($col->Field != 'id')
                    $query .= ",`$col->Field` VARCHAR(100)";
                }
                $query .= ", PRIMARY KEY  (`id`)) type=MyISAM;";
                if ($execute) {
                    $database->SetQuery($query);
                    $result = $database->query();
                } else {
                    $result = $query;
                }
            } else {
                $query .= ") type=MyISAM;";
            }
            return $result;
        }
}

class JOSC_notification {
    var $_notify_admin;
    var $_notify_email;
    var $_notify_moderator;
    var $_moderator;
    var $_notify_users;
    var $_component;
    var $_comObject;
    
    var $_comment_id;
    var	$_content_id;
    var $lists = array();

    function JOSC_notification(&$object, $_comment_id=-1, $_content_id=-1)
    {

        $this->_comObject 			= $object->_comObject;
        $this->_component 			= $object->_comObject->_component;
        $this->_notify_admin     	= $object->_notify_admin;
        $this->_notify_email     	= $object->_notify_email;
        $this->_notify_moderator	= $object->_notify_moderator;
        $this->_moderator			= is_array($object->_moderator) ? $object->_moderator : explode(',', $object->_moderator);;
        $this->_notify_users		= $object->_notify_users;
        $this->setIDs($_comment_id, $_content_id);
    }

    function setIDs($_comment_id, $_content_id)
    {
        $this->_comment_id = $_comment_id;
        $this->_content_id = $_content_id;
    }
	
    function resetLists()
    {
        $this->lists = array();
    }
	
	/*
	 * mail to notify :
	 *   the writer (AT LEAST)
	 *   the users of those contentid (to inform of a new comment)
	 * 	 moderators
	 * 	TYPE = 'publish' or 'delete' or ?
	 */
    function notifyComments($cids, $type)
    {
        $my = JFactory::getUser();

        $database =& JFactory::getDBO();


        if (is_array($cids)) {
            $cids = implode(',',$cids);
        }

        $sentemail = "";
        $database->setQuery("SELECT * FROM #__comment WHERE id IN ($cids)");
        $rows = $database->loadObjectList();
        if ($rows) {
            $query = "SELECT email FROM #__users WHERE id='".$my->id."' LIMIT 1";
            $database->SetQuery($query);
            $myemail = $database->loadResult();
            $_notify_users =  $this->_notify_users;

            foreach($rows as $row) {
                $this->_notify_users = $_notify_users;
                $this->setIDs($row->id, $row->contentid);
                $this->resetLists();
                $this->lists['name'] 	= $row->name;
                $this->lists['title'] 	= $row->title;
                $this->lists['notify'] 	= $row->notify;
                $this->lists['comment']	= $row->comment;

                $email_writer = $row->email;
                                /*
                                 * notify writer of approval
                                 */
                if ($row->userid > 0) {
                    $query = "SELECT email FROM #__users WHERE id='".$row->userid."' LIMIT 1";
                    $database->SetQuery($query);
                    $result = $database->loadAssocList();
                    if ($result) {
                        $user = $result[0];
                        $email_writer    = $user['email'];
                    }
                }
			        
                if ($email_writer && $email_writer != $myemail) {
                    switch ($type) {
       			case 'publish':
                            $this->lists['subject'] = _JOOMLACOMMENT_NOTIFY_PUBLISH_SUBJECT;
                            $this->lists['message'] = _JOOMLACOMMENT_NOTIFY_PUBLISH_MESSAGE;
                            break;
                        case 'unpublish':
                            $this->lists['subject'] = _JOOMLACOMMENT_NOTIFY_UNPUBLISH_SUBJECT;
                            $this->lists['message'] = _JOOMLACOMMENT_NOTIFY_UNPUBLISH_MESSAGE;
                            break;
                        case 'delete' :
                            $this->lists['subject'] = _JOOMLACOMMENT_NOTIFY_DELETE_SUBJECT;
                            $this->lists['message'] = _JOOMLACOMMENT_NOTIFY_DELETE_MESSAGE;
                            break;
                    }
	    	    		
                    $sentemail .=  ($sentemail ? ';' : '').$this->notifyMailList($temp=array($email_writer));
                    $exclude = $myemail ? ($email_writer.','.$myemail): $email_writer;
                } else {
                    $exclude = $myemail ? $myemail:"";
                }
			        /*
			         * notify users, moderators, admin
			         */
                switch ($type) {
                    case 'publish':
                        $this->lists['subject']	= _JOOMLACOMMENT_NOTIFY_PUBLISH_SUBJECT;
                        $this->lists['message']	= _JOOMLACOMMENT_NOTIFY_PUBLISH_MESSAGE;
                        break;
                    case 'unpublish':
                        $this->_notify_users = false;
                        $this->lists['subject']	= _JOOMLACOMMENT_NOTIFY_UNPUBLISH_SUBJECT;
                        $this->lists['message']	= _JOOMLACOMMENT_NOTIFY_UNPUBLISH_MESSAGE;
                        break;
                    case 'delete' :
                        $this->_notify_users = false;
                        $this->lists['subject']	= _JOOMLACOMMENT_NOTIFY_DELETE_SUBJECT;
                        $this->lists['message']	= _JOOMLACOMMENT_NOTIFY_DELETE_MESSAGE;
                        break;
                 }
//	    	    	echo implode(',', $notification->getMailList($row->contentid));
                $templist = $this->getMailList($row->contentid, $exclude);
                $sentemail .=  ($sentemail ? ';' : '').$this->notifyMailList($templist);
            }
        }
        return $sentemail;
    }
	
    /*
     * get all users (unregistered and registered) 
     * notified for the given content item
     * AND all moderators if notify_moderator active
     * AND admin if notify admin active (for backward compatibility)
     */
	function getMailList($contentid='', $exclude='')
	{   /* exclude must be an array of values 
		 * OR not quoted list separated by , 
		 * 
		 * contentid should be an array of values
		 * OR not quoted list separated by ,
		 */
	    
		$database =& JFactory::getDBO();			
	    
	    if (is_array($contentid)) {
	        $contentid = implode(',', $contentid);
	    }
	    if (is_array($exclude)) {
	        $exclude = implode(',', $exclude);
	    }
	    
	    if ($this->_notify_users && $contentid) { 
	        /* Unregistered users  */
			$query 	= "SELECT DISTINCT email "
					. "\n FROM `#__comment` "
					. "\n   WHERE contentid IN ($contentid) AND component='$this->_component'"
					. "\n     AND ( userid = NULL OR userid = 0 )"
					. "\n     AND email  <> ''"
					. "\n     AND notify = '1'"
					;
			if ($exclude) {
				$quoted = str_replace( ',', "','", $exclude); /* add quotes */
				$query .= "\n     AND email NOT IN ('$quoted')";
			}
			$database->setQuery( $query );
			$unregistered_maillist = $database->loadResultArray();  //tableau
		
			if ($unregistered_maillist) {
			    $exclude = ($exclude ? $exclude.',' : '') . implode(',', $unregistered_maillist);
			}
		
  	      	/* Registered users*/
  	      	$registered_maillist = array();
			$query 	= "SELECT DISTINCT u.email "
					. "\n FROM `#__comment` AS c "
					. "\n INNER JOIN `#__users` AS u ON u.id = c.userid "
					. "\n   WHERE c.contentid IN ($contentid) AND component='$this->_component'"
					. "\n     AND u.email  <> ''"
					. "\n     AND c.notify = '1'"
					;
			if ($exclude) {
				$quoted = str_replace( ',', "','", $exclude); /* add quotes */
				$query .= "\n     AND u.email NOT IN ('$quoted')";
			}
			$database->setQuery( $query );
			$registered_maillist = $database->loadResultArray();  //tableau
//			$debugemail  = implode(';' , $maillist); // liste s�par� par des ;

			if ($registered_maillist) {
		    	$exclude = ($exclude ? $exclude.',' : '') . implode(',', $registered_maillist);
			}
	    }
	    
		$moderator_maillist = $this->getMailList_moderator($exclude);
		
		$maillist = array();
		if (isset($unregistered_maillist) && is_array($unregistered_maillist))
			$maillist = array_merge( $maillist, $unregistered_maillist);
		if (isset($registered_maillist) && is_array($registered_maillist))
			$maillist = array_merge( $maillist, $registered_maillist);
		if (isset($moderator_maillist) && is_array($moderator_maillist))
			$maillist = array_merge( $maillist, $moderator_maillist);
			
		return ($maillist);
	}
    
    /*
     * get moderators maillist
     */
	function getMailList_moderator($exclude='') 
	{
		/* exclude must be an array of values 
		 * OR not quoted list separated by , 
		 */
	    
		$database =& JFactory::getDBO();		
	    
	    if (is_array($exclude)) {
	        $exclude = implode(',', $exclude);
	    }
        
        /* Moderators(if requested) */
        
        $moderator_maillist = array();

        if ($this->_notify_moderator && $this->_moderator) {
            $usertype = '';
            foreach($this->_moderator as $moderator) {
                $usertype .= ($usertype ? ',':'') . "'" . JOSC_utils::getJoomlaUserType($moderator) . "'";
            }
			$query 	= "SELECT DISTINCT email "
					. "\n FROM `#__users` " 
					. "\n   WHERE email <> '' "
					. "\n     AND usertype IN ($usertype)"	
					;
			if ($exclude) {
				$quoted = str_replace( ',', "','", $exclude); /* add quotes */
				$query .= "\n     AND email NOT IN ('$quoted')";
			}
			$database->setQuery( $query );
			$moderator_maillist = $database->loadResultArray();  //tableau
			//echo  implode(';' , $moderator_maillist); // liste s�par� par des ;
        } elseif ($this->_notify_admin && $this->_notify_email <> '') {
            $moderator_maillist[] = $this->_notify_email;
        }
        return $moderator_maillist;
	}
	
	/*
	 * mail to the given maillist
	 * 	object->lists must be set (at least commentid and contentid)
	 */
    function notifyMailList( &$maillist )
    {
        $mailer =& JFactory::getMailer();

	$sentmail = '';

        if (!is_array($maillist) || count($maillist)<=0) {
	    return $sentmail;
	}
        
        $comment_id     = $this->_comment_id;	/* obligatory */
        $contentid      = $this->_content_id;	/* obligatory */
        $component		= $this->_component;	/* obligatory */
        $comObject		= $this->_comObject;	/* obligatory */
        $name           = $this->lists['name'];
        $title          = $this->lists['title'];
        $notify         = $this->lists['notify'];
        $comment  		= $this->lists['comment'];
        
        $subject		= $this->lists['subject'];
        $message		= $this->lists['message'];

        $articlelink = $comObject->linkToContent($contentid, $comment_id, true);
                										
		$subject = str_replace('{title}'	, $title,$subject);
		$subject = str_replace('{name}'		, $name,$subject);
		$subject = str_replace('{notify}'	, ($notify ? "yes" : "no"),$subject);

		$message = str_replace('{livesite}'	, JURI::base(),$message);
		$message = str_replace('{title}'	, $title,$message);
		$message = str_replace('{name}'		, $name,$message);
		$message = str_replace('{notify}'	, ($notify ? "yes" : "no"),$message);
		$message = str_replace('{comment}'	, $comment,$message);
		$message = str_replace('{linkURL}'	, $articlelink,$message);
/*
        $subject = 'NewComment :'.$title."[from:".$name."][notify:".($notify ? "yes" : "no")."]";

        $message = '<p>A user has posted a new comment to a content item you have subscribed <br />in '.JURI::base().':</p>';
        $message .= '<p><b>Name: </b>'.$name.'<br />';
        $message .= '<b>Title: </b>'.$title.'<br />';
        $message .= '<b>Text: </b>'.$comment.'<br />';
        $message .= '<b>Content item: </b><a href="'.$articlelink.'">'.$articlelink.'</a></p>';

        $message .= "<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>";
*/
        foreach($maillist as $mail) {

            if (JUTility::sendMail($mailer->From, $mailer->FromName, $mail, $subject, $message, true, $mailer->cc, $mailer->bcc, $mailer->attachment, $mailer->ReplyTo, $mailer->FromName )) {
				$sentmail .= ($sentmail ? ';' : '').$mail;
            }
        }
		return $sentmail;
    }
    
    function setNotifyAllPostOfUser($userid, $email, $notify) 
    {	
		$database =& JFactory::getDBO();		
    	
    	if ((!$userid && !$email) || !$this->_content_id) return false;

    	$where  = $userid ? " userid=$userid " : ( $email ? " email='$email' " : "" );
    	
    	$query 	= "UPDATE #__comment SET notify='$notify' "
    			. "\n  WHERE contentid=$this->_content_id "
    			. "\n    AND $where "
    			;
        $database->SetQuery($query);
        return ($database->Query());
            	
    }
}

/*
 * Page navigation class
 * adapted from joomla mosPageNav class
 */
class JOSC_PageNav {
	var $_ajax		= false;
	var $limitstart = null;
	var $limit 		= null;
	var $total 		= null;

	function JOSC_PageNav( $ajax, $total, $limitstart, $limit ) 
	{
		$this->_ajax		= $ajax;
		$this->total 		= (int) $total;
		$this->limitstart 	= (int) max( $limitstart, 0 );
		$this->limit 		= (int) max( $limit, 1 );
		if ($this->limit > $this->total) {
			/*  0....[total]...[limit] */
			$this->limitstart = 0;
		}
//		if (($this->limit-1)*$this->limitstart > $this->total) {
		/* rounded limitstart to multiple value of limit */
		$this->limitstart -= $this->limitstart % $this->limit; /* % = modulo */
//		}
	}

	function writePagesLinks( $link, $endlink='' )
	{
		$txt = '';
		$js = $this->_ajax;

		$displayed_pages = 10;
		$total_pages = $this->limit ? ceil( $this->total / $this->limit ) : 0;
		$this_page = $this->limit ? ceil( ($this->limitstart+1) / $this->limit ) : 1;
		$start_loop = (floor(($this_page-1)/$displayed_pages))*$displayed_pages+1;
		if ($start_loop + $displayed_pages - 1 < $total_pages) {
			$stop_loop = $start_loop + $displayed_pages - 1;
		} else {
			$stop_loop = $total_pages;
		}

		if (!$js && $link) $link .= "&amp;josclimit=". $this->limit;

		
		$_PN_LT 		=  JText::_('&lt');
		$_PN_RT 		= JText::_('&gt;');
		$_PN_START 		= JText::_( 'Begin' );
		$_PN_PREVIOUS 	= JText::_( 'Prev' );
		$_PN_NEXT		= JText::_( 'Next' );
		$_PN_END		= JText::_( 'End' );

		$pnSpace = "";
		if ($_PN_LT || $_PN_RT) $pnSpace = " ";

		if ($this_page > 1 && $link) {
			$page = ($this_page - 2) * $this->limit;
			if ($js)
				$href = "javascript:JOSC_getComments(-1, 0)"; 
			else
				$href = JRoute::_( "$link&amp;josclimitstart=0$endlink" );
			$txt .= "<a href='$href' class='pagenav' title='". $_PN_START ."'>". $_PN_LT . $_PN_LT . $pnSpace . $_PN_START ."</a> ";
			if ($js)
				$href = "javascript:JOSC_getComments(-1, $page)"; 
			else
				$href = JRoute::_( "$link&amp;josclimitstart=$page$endlink" );
			$txt .= "<a href='$href' class='pagenav' title='". $_PN_PREVIOUS ."'>". $_PN_LT . $pnSpace . $_PN_PREVIOUS ."</a> ";
		} else {
			$txt .= "<span class='pagenav'>". $_PN_LT . $_PN_LT . $pnSpace . $_PN_START ."</span> ";
			$txt .= "<span class='pagenav'>". $_PN_LT . $pnSpace . $_PN_PREVIOUS ."</span> ";
		}

		for ($i=$start_loop; $i <= $stop_loop; $i++) {
			$page = ($i - 1) * $this->limit;
			if ($i == $this_page || !$link) {
				$txt .= "<span class='pagenav'>". $i ."</span> ";
			} else {
				if ($js)
					$href = "javascript:JOSC_getComments(-1, $page)"; 
				else
					$href = JRoute::_( $link .'&amp;josclimitstart='. $page . $endlink );
				$txt .= "<a href='$href' class='pagenav'><strong>". $i ."</strong></a> ";
			}
		}

		if ($this_page < $total_pages && $link) {
			$page = $this_page * $this->limit;
			$end_page = ($total_pages-1) * $this->limit;
			if ($js)
				$href = "javascript:JOSC_getComments(-1, $page)"; 
			else
				$href = JRoute::_( $link ."&amp;josclimitstart=". $page . $endlink );
			$txt .= "<a href='". $href ." ' class='pagenav' title='". $_PN_NEXT ."'>". $_PN_NEXT . $pnSpace . $_PN_RT ."</a> ";
			if ($js)
				$href = "javascript:JOSC_getComments(-1, $end_page)"; 
			else
				$href = JRoute::_( $link ."&amp;josclimitstart=". $end_page . $endlink );
			$txt .= "<a href='". $href ." ' class='pagenav' title='". $_PN_END ."'>". $_PN_END . $pnSpace . $_PN_RT . $_PN_RT ."</a> ";
		} else {
			$txt .= "<span class='pagenav'>". $_PN_NEXT . $pnSpace . $_PN_RT ."</span> ";
			$txt .= "<span class='pagenav'>". $_PN_END . $pnSpace . $_PN_RT . $_PN_RT ."</span>";
		}
		return $txt;
	}
		
} 

?>
