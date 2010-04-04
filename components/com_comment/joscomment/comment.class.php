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


require_once(JPATH_SITE.'/components/com_comment/joscomment/utils.php');
require_once(JPATH_SITE.'/components/com_comment/joscomment/strutils.php');
require_once(JPATH_SITE.'/components/com_comment/joscomment/jscript.php');
require_once(JPATH_SITE.'/components/com_comment/joscomment/security.php');


class JOSC_template {
    var $_live_site;
    var $_absolute_path;
    var $_template_path = '';
    var $_template_absolute_path = '';
    var $_name = '';
    var $_css  = '';
    var $_title = '';
    /*
     * parsed blocks
     */
    var $_body = '';
    var $_library = '';
	var $_readon = '';
	var $_previewline = '';
    var $_menu = ''; /* ? */
    var $_post = '';
    var $_search = '';
    var $_searchResults = '';
    var $_form = '';
    var $_poweredby = '';

    function JOSC_template($name,$css='template_css.css')
    {
        $this->_name = $name;
        $this->_css  = $css;
    }

    function loadFromFile()
    {
        $fileName = $this->_template_absolute_path .'/'. $this->_name . '/index.html';
        if (file_exists($fileName)) {
            $file = fopen ($fileName, 'r');
            $template = fread ($file, filesize($fileName));
            fclose($file);
            return $template;
        } else die ('!JoomlaComment template not found: ' . $this->_name);
    }

    function CSS()
    {
	global $mainframe;
	$cache = $mainframe->getCfg('caching');

		/* 
		 * cache does not calculate again head for bots... :(
     	 * and link rel outside head is not good accepted by IE7
    	 * for example after a embed object...
   	  	 * not a complete solution but less blocking
   	  	 */ 
   		if ($cache) {
			$html	= "\n<script type = \"text/javascript\">"
      				. "<!--"
      				. "\n if (!JOSC_cssStyleSheet)"   /* TODO: search in StyleSheets elements if exist */
      				. "\n { var JOSC_csslink = document.createElement('link');"
      				. "\n var JOSC_cssStyleSheet = '". $this->_template_path . '/' . $this->_name . '/css/'.$this->_css ."';"
					. "\n JOSC_csslink.setAttribute('href', JOSC_cssStyleSheet );"
      				. "\n JOSC_csslink.setAttribute('rel', 'stylesheet');"
      				. "\n JOSC_csslink.setAttribute('type', 'text/css');"
      				. "\n var JOSC_csshead = document.getElementsByTagName('head').item(0);"
      				. "\n JOSC_csshead.appendChild(JOSC_csslink);"
      				. "\n }//-->"
	  				. "\n</script>";
	  		return $html;
   		} else {
   			return JOSC_utils::insertToHead('<link rel="stylesheet" href="' . $this->_template_path . '/' . $this->_name . '/css/'.$this->_css.'" type="text/css" />');
   		}
    }

    function parse($readon=false)
    {
        $template = $this->loadFromFile();
        $this->_body 	= JOSC_utils::block($template, 'body');
        $this->_library = JOSC_utils::block($template, 'library');
        if ($readon) {
        	$this->_readon = JOSC_utils::block($template, 'readon');
        	$this->_previewline = JOSC_utils::block($template, 'previewline');
        } else {
        	$this->_menu = JOSC_utils::block($template, 'menu');
        	$this->_search = JOSC_utils::block($template, 'search');
        	$this->_searchResults = JOSC_utils::block($template, 'searchresults');
        	$this->_post = JOSC_utils::block($template, 'post');
        	$this->_form = JOSC_utils::block($template, 'form');
        	$this->_poweredby = JOSC_utils::block($template, 'poweredby');
        }
    }
}

class JOSC_properties extends JOSC_template {
	/* special parameters */
    var $_contentrow;
    var $_params;
    var $_component;
    var $_sectionid;
    var $_comObject;
    var $_limitstart;
    var $_total;
    var $_request_uri;
    /* config params */
    var $_ajax;
	var $_local_charset;	
    var $_only_registered;
    var $_language;
    var $_moderator = array();
    var $_include_sc;
    var $_exclude_sections = array();
    var $_exclude_categories = array();
    var $_exclude_contentitems = array();
    var $_exclude_contentids = array();
    var $_template;
    var $_template_css;
    var $_form_area_cols;
    var $_emoticon_pack;
    var $_emoticon_wcount;
    var $_tree;
    var $_mlink_post;
    var $_tree_indent;
    var $_sort_downward;
    var $_display_num;
    var $_support_emoticons;
    var $_support_UBBcode;
    var $_support_pictures;
    var $_censorship_enable;
    var $_censorship_case_sensitive;
    var $_censorship_words;
    var $_censorship_usertypes;
    var $_IP_visible;
    var $_IP_partial;
    var $_IP_caption;
    var $_IP_usertypes;
    var $_preview_visible;
    var $_preview_length;
    var $_preview_lines;
    var $_voting_visible;
    var $_use_name;
    var $_notify_admin;
    var $_notify_email;
    var $_notify_moderator;
    var $_notify_users;
    var $_rss;
    var $_date_format;
    var $_no_search;
    var $_captcha;
    var $_autopublish;
    var $_ban;
    var $_avatar;
    var $_profile;
    var $_profiles;
    var $_maxlength_text;
    var $_maxlength_word;
    var $_maxlength_line;
    var $_show_readon;
    var $_debug_username;
    var $_xmlerroralert;
    var $_ajaxdebug;

    function JOSC_properties($absolutePath, $liveSite, &$comObject, &$exclude, &$row, &$params)
    {
//        $user = JFactory::getUser();
		$user =& JFactory::getUser();
//		var_dump($user);

		//require("$absolutePath/../config.comment.php"); 
		/* abolutepath = .../administrator/components/com_comment/joscomment absolute path */
		require_once(JPATH_SITE."/administrator/components/com_comment/class.config.comment.php");
		$config = new JOSC_config(0, $comObject);	
		if (!$config->load()) {
			$exclude = true;
			return;	
		}
		/* 
		 * setting
		 */
		$this->_comObject		= $config->_comObject;
		$this->_component 		= $this->_comObject->_component;
		$this->_sectionid 		= $this->_comObject->_sectionid;

        $this->_content_id 		= $this->_comObject->_id;

				      
        $this->_ajax 			= $config->_ajax;
        $this->_local_charset 	= $config->_local_charset;
        $this->_only_registered = $config->_only_registered;
        $this->_language 		= $config->_language;
        $this->_moderator 		= explode(',', $config->_moderator);

		/*
		 * content item
		 */                
        
        if ($exclude && isset($row)) {	      
			$this->_show_readon			= $this->_comObject->setShowReadon( $row, $params, $config );
			 
    	    $this->_exclude_contentids	= $config->_exclude_contentids ? explode(',', $config->_exclude_contentids) : array();
    	    $this->_exclude_contentitems = $config->_exclude_contentitems ? explode(',', $config->_exclude_contentitems) : array();
	        $this->_exclude_sections	= $config->_exclude_sections ? explode(',', $config->_exclude_sections) : array();
    	    $this->_exclude_categories	= $config->_exclude_categories ? explode(',', $config->_exclude_categories) : array();
    	    $this->_include_sc			= $config->_include_sc;
    	    if ($this->_comObject->_official) {
    	    	$obj = $this;
				if (!$this->_comObject->checkSectionCategory($row, $obj ))
					return false;
        	} else {
				if (!$this->_comObject->checkSectionCategory($row, $this->_include_sc, $this->_exclude_sections, $this->_exclude_categories, $this->_exclude_contentids ))
					return false;
			}
        }
        
        /*
         * others
         */
        $this->_tree 			= $config->_tree;
        $this->_mlink_post 		= $config->_mlink_post;
        $this->_tree_indent 	= $config->_tree_indent;
        $this->_sort_downward 	= $config->_sort_downward; //($this->_tree ? 0 : $config->_sort_downward);
        $this->_display_num 	= $config->_display_num;
        $this->_support_emoticons = $config->_support_emoticons;
        $this->_enter_website	= $config->_enter_website;
        $this->_support_UBBcode = $config->_support_UBBcode;
		$this->_support_pictures = $config->_support_pictures;
		$this->_pictures_maxwidth = $config->_pictures_maxwidth;
        $this->_censorship_enable = $config->_censorship_enable && in_array(JOSC_utils::getJOSCUserType($user->usertype), explode(',', $config->_censorship_usertypes));
        $this->_censorship_case_sensitive = $config->_censorship_case_sensitive;
//        $this->_censorship_words = explode(',', $config->_censorship_words);
        $this->Set_censorship_words($config->_censorship_words);
        $this->_IP_usertypes 	= explode(',', $config->_IP_usertypes);
        $this->_IP_visible 		= $config->_IP_visible;
        $this->_IP_partial 		= $config->_IP_partial;
        $this->_IP_caption 		= $config->_IP_caption;
        $this->_preview_visible = $config->_preview_visible;
        $this->_preview_length 	= $config->_preview_length;
        $this->_preview_lines 	= $config->_preview_lines;
        $this->_voting_visible 	= $config->_voting_visible;
        $this->_use_name 		= $config->_use_name;
        $this->_notify_admin 	= $config->_notify_admin;
        $this->_notify_email 	= $config->_notify_email;
        $this->_notify_moderator = $config->_notify_moderator;
        $this->_autopublish 	= $config->_autopublish;
        $this->_notify_users 	= $config->_notify_users;
        $this->_rss 			= $config->_rss;
        $this->_date_format 	= $config->_date_format;
        $this->_no_search		= $config->_no_search;
        $this->_captcha 		= $config->_captcha && in_array(JOSC_utils::getJOSCUserType($user->usertype), explode(',', $config->_captcha_usertypes));
        $this->_website_registered = $config->_website_registered;
        $this->_ban 			= $config->_ban;
        $cb = JOSC_TableUtils::existsTable('#__comprofiler');
        $this->_profile 		= $config->_support_profiles && $cb;
        $this->_avatar 			= $config->_support_avatars && $cb;
		$this->_maxlength_text 	= $config->_maxlength_text;
        $this->_maxlength_word 	= $config->_maxlength_word;
        $this->_maxlength_line 	= $config->_maxlength_line;

        $this->_absolute_path 	= $absolutePath;
        $this->_live_site 		= $liveSite;
        
        $this->_template 				= $config->_template_custom ? $config->_template_custom : $config->_template;
        $this->_template_path 			= $config->_template_custom ? $config->_template_custom_livepath : "$liveSite/templates";
        $this->_template_absolute_path 	= $config->_template_custom ? $config->_template_custom_path : "$absolutePath/templates";
        $this->_template_css			= $config->_template_custom ? $config->_template_custom_css : $config->_template_css;
        $this->JOSC_template($this->_template, $this->_template_css);
        $this->_template_library		= $config->_template_library;
		$this->_form_area_cols	= $config->_form_area_cols;
		
        $this->_emoticon_pack 	= $config->_emoticon_pack;
        $this->_emoticon_wcount = $config->_emoticon_wcount;
        $this->_emoticons_path = $liveSite . "/emoticons/$this->_emoticon_pack/images";

        JOSC_utils::set_charsetConstant($this->_local_charset);
        $this->loadLanguage($GLOBALS['josComment_absolute_path'], $this->_language);

        $this->loadEmoticons("$absolutePath/emoticons/$this->_emoticon_pack/index.php");
               
        $this->_debug_username = $config->_debug_username;
        $this->_xmlerroralert = $config->_xmlerroralert ? '1' : '0';
        $this->_ajaxdebug = $config->_ajaxdebug ? '1' : '0';
        if ($this->_profile)        
       		$this->loadProfiles();
        $exclude = false;
    }
    
    function Set_censorship_words($censorship_words)
    {    
    	$this->_censorship_words = array();
    	
        if ($this->_censorship_enable && $censorship_words) {
            
            $censorship_words = explode(',', $censorship_words);
            
            if (is_array($censorship_words)) {
                      
            	foreach($censorship_words as $word) {
            	    
               		$word = trim($word);
               		
                	if (strpos($word, '=')) {
                    	$word = explode('=', $word);
                   		$from = trim($word[0]);
                   		$to   = trim($word[1]);  
                	} else {
                	    $from = $word;
                	    $to   = JOSC_strutils::str_fill(strlen($word), '*');
                	}
                	
                	$this->_censorship_words[$from] = $to;
            	}
            }
        }
        return;
    }
    
    function jscriptInit()
    {
//        $user = JFactory::getUser();
		$user =& JFactory::getUser();
        
        $html = "\n<script type='text/javascript'>\n";
        $html .= "var JOSC_ajaxEnabled=$this->_ajax;";
        $html .= "if (!JOSC_http) JOSC_ajaxEnabled=false;";
        $html .= "var JOSC_sortDownward='$this->_sort_downward';";
        $captchaEnabled = $this->_captcha ? 'true' : 'false';
        $html .= "var JOSC_captchaEnabled=$captchaEnabled;";
        $html .= "var JOSC_template='$this->_template_path/$this->_name';";
        $html .= "var JOSC_liveSite='$this->_live_site';"; /* joscomment */
        $html .= "var JOSC_ConfigLiveSite='".JURI::base()."';"; 
        $html .= "var JOSC_linkToContent='".$this->_comObject->linkToContent( $this->_content_id )."';";
        $html .= "var JOSC_autopublish='$this->_autopublish';"; /* not used ?*/
        if ($this->_debug_username && ($user->username==$this->_debug_username || $this->_debug_username=="JOSCdebugactive")) {
        	$html .= "var JOSC_XmlErrorAlert=$this->_xmlerroralert;";
        	$html .= "var JOSC_AjaxDebug=$this->_ajaxdebug;";
        }     
        
        $html .= "\n</script>\n";
        return $html;
    }
    function loadLanguage($path, $language)
    {
	JOSC_utils::loadFrontendLoadLanguage($language);
    }
    
    function loadEmoticons($fileName)
    {
        require_once($fileName);
        $this->_emoticons = $GLOBALS["JOSC_emoticon"];
    }
    function loadProfiles()
    {
        $database =& JFactory::getDBO();
			
        $database->setQuery('SELECT u.username, c.user_id, c.avatar 
		FROM #__users AS u, #__comprofiler AS c
		   WHERE u.id = c.user_id');
        $userList = $database->loadAssocList();
        $this->_profiles = array();
        foreach ($userList as $item) {
            /*
             * set  _profiles[userid][avatar] 
             */
            if ($this->_avatar) 
                $this->_profiles[$item['user_id']]['avatar'] = $item['avatar'];
            else  
                $this->_profiles[$item['user_id']]['avatar'] = false;

            /*
             * set  _profiles[userid][id of cb] 
             */
            if ($this->_profile) 
                $this->_profiles[$item['user_id']]['id'] = $item['user_id'];
            else 
                $this->_profiles[$item['user_id']]['id'] = false;

        }
        unset($userList);
    }
    
}

class JOSC_visual extends JOSC_properties {
    var $_parent_id = -1;

    function insertMenu()
    {
        $menu = new JOSC_menu($this->_menu);
        $menu->setContentId($this->_content_id);
        $menu->setTemplate_path($this->_template_path);
        $menu->setTemplate_name($this->_name);
        $menu->setRSS($this->_rss);
        $menu->setModerator($this->_moderator);
        $menu->setOnly_registered($this->_only_registered);        
        $menu->setNoSearch($this->_no_search);
        return $menu->menu_htmlCode();
    }

	function insertPoweredby()
	{
	    return '<div id="poweredby" align="center" class="small">Powered by <a target="_blank" href="http://compojoom.com">!JoomlaComment '."4.0alpha".'</a></div>';
	}

	function insertHiddenCopyright()
	{
        return '<h4 style="display:none;">3.25 Copyright (C) 2007 Alain Georgette / Copyright (C) 2006 Frantisek Hliva. All rights reserved."</h4>';
	}
		
    function insertSearch()
    {
        $html = $this->_search;
        $hidden = JOSC_support::formHiddenValues($this->_content_id, $this->_component, $this->_sectionid);
        $html = str_replace('{_HIDDEN_VALUES}', $hidden, $html);
        $html = str_replace('{_JOOMLACOMMENT_SEARCH}', JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_SEARCH)), $html);
        $html = str_replace('{_JOOMLACOMMENT_PROMPT_KEYWORD}', JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_PROMPT_KEYWORD)), $html);
        $html = str_replace('{_JOOMLACOMMENT_SEARCH_ANYWORDS}', JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_SEARCH_ANYWORDS)), $html);
        $html = str_replace('{_JOOMLACOMMENT_SEARCH_ALLWORDS}', JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_SEARCH_ALLWORDS)), $html);
        $html = str_replace('{_JOOMLACOMMENT_SEARCH_PHRASE}', JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_SEARCH_PHRASE)), $html);
        return $html;
    }

	function initializePost($item, $postCSS) 
    {   /* post is used in module latest... ! */
        $post = new JOSC_post($this->_post); /* template block */
        $post->setUseName($this->_use_name); /* needed for setItem */
        $post->setItem($item);
        $post->setTemplate_path($this->_template_path);
        $post->setTemplate_name($this->_name);
        $post->setCSS($postCSS);
        $post->setAjax($this->_ajax);
        $post->setTree($this->_tree);
        $post->setMLinkPost($this->_mlink_post);
        $post->setTree_indent($this->_tree_indent);
        $post->setDate_format($this->_date_format);
        $post->setIP_visible($this->_IP_visible);
        $post->setIP_partial($this->_IP_partial);
        $post->setIP_caption($this->_IP_caption);
        $post->setIP_usertypes($this->_IP_usertypes);
        $post->setCensorShip(	$this->_censorship_enable, 
        						$this->_censorship_case_sensitive, 
        						$this->_censorship_words, 
        						$this->_censorship_usertypes 
        					);
        $post->setContentId($this->_content_id);
        $post->setComponent($this->_component);
        $post->setVoting_visible($this->_voting_visible);
        $post->setSupport_emoticons($this->_support_emoticons);
        $post->setSupport_UBBcode($this->_support_UBBcode);
        $post->setSupport_quotecode($this->_support_UBBcode); /* only module use */
        $post->setSupport_link($this->_support_UBBcode); /* only module use */
        $post->setSupport_pictures($this->_support_pictures, $this->_pictures_maxwidth);
        $post->setEmoticons($this->_emoticons);
        $post->setEmoticons_path($this->_emoticons_path);
        $post->setOnly_registered($this->_only_registered);
        $post->setWebsiteRegistered($this->_website_registered);
        $post->setModerator($this->_moderator);
        if ($post->_item['userid']) {
        	$post->setUser_id( ($this->_profile && isset($this->_profiles[$post->_item['userid']])) ? $this->_profiles[$post->_item['userid']]['id'] : false );
        	$post->setAvatar( ($this->_avatar  && isset($this->_profiles[$post->_item['userid']])) ? $this->_profiles[$post->_item['userid']]['avatar'] : false );
        }
        $post->setNotify_users($this->_notify_users, $this->_notify_moderator);
        $post->setMaxLength_text($this->_maxlength_text);
        $post->setMaxLength_word($this->_maxlength_word);
        $post->setMaxLength_line($this->_maxlength_line);

		return $post;
    }		
	
    function insertPost($item, $postCSS)
    {   /* post is used in module latest... ! */
        $post = $this->initializePost($item, $postCSS);
//	var_dump($post);

   	    return( $post->post_htmlCode() );
    }

	function getPageNav()
	{
		if ($this->_total <= $this->_display_num) return '';
		
		$pageNav = new JOSC_PageNav( $this->_ajax, $this->_total, $this->_limitstart, $this->_display_num );        
        
        $link = $this->_request_uri;
        /* delete limit and limitstart parameters before add new */
		$link = preg_replace("/(.*)(&josclimit=.*)(&.*|)/", '\\1\\3', $link);
		$link = preg_replace("/(.*)(&josclimitstart=.*)(&.*|)/", '\\1\\3', $link);

		$html = "<div id='joscPageNavLink'>".$pageNav->writePagesLinks( $link, "#joscpagenav" )."</div>";
		
		if ($this->_ajax) 
			$html.=  "<div id='joscPageNavNoLink' style='display:none;visibility:hidden;'>".$pageNav->writePagesLinks('')."</div>";

        $addjs =  "\n<script type='text/javascript'>";
        if ($this->_sort_downward) {
        	/* DESC  addeed to begin -> if not begin needs refresh*/
         	if ($this->_limitstart <= $this->_display_num)
         		 $addjs .= "var JOSC_postREFRESH=false;";
         	else
         		 $addjs .= "var JOSC_postREFRESH=true;";         		
        } else {
        	/* ASC addeed to end -> if not end needs refresh */
         	if (($this->_limitstart+$this->_display_num)>=$this->_total)
         		 $addjs .= "var JOSC_postREFRESH=false;";
         	else
         		 $addjs .= "var JOSC_postREFRESH=true;";
        }		
        $addjs .= "</script>\n";
                
		return "<a name='joscpagenav'>".$addjs.$html;
			
	}
	
	function insertPageNav()
	{	
		return "<div id='joscPageNav'>".$this->getPageNav()."</div>";
	}
	
    function getComments($onlydata=false)
    {
		$database =& JFactory::getDBO();			
		
        if ($this->_sort_downward) {
         	$sort = 'DESC'; /* new first */
        } else {
			$sort = 'ASC'; /* last first */
        }		
        $html = '';
		$com = $this->_component;
		/*
		 * ORDER must be done only on high level
		 * because children must be ordered ascending for tree construction
		 */        
        $queryselect 	= "SELECT * ";
        $querycount 	= "SELECT COUNT(*) ";
        $queryfrom 		= "\nFROM #__comment"
        				. "\n WHERE contentid='$this->_content_id' AND component='$com' "
        				. "\n   AND published='1' ";
		$queryparent	= $this->_tree ? "\n   AND parentid<=0 " : "";        				
		$querychildren	= $this->_tree ? "\n   AND parentid>0 "  : "";        				
        $queryorder		= "\n ORDER BY id $sort";    

        if ($this->_display_num>0) {

			/*
			 * pages -> use limitstart on root id (childs are not counted - always attached to root id)
			 */
			
	    	if ($this->_comment_id) {
				/* 
				 * - get the limitstart(page) of the comment_id 
				 * - comment id can be a root id but also a child ! 
				 * in this case, we must search for its root id.
				 */
				$parentid = $id = $this->_comment_id;
	    		for ($i=1; $i<=20 && $parentid>0; $i++)
	    		{   /* LEFT JOIN is for loop optimization : 1 loop = 2 levels */
    	   			/* 20 times is for infinity loop limit = maximum 40 levels.  it should be enough....? :) */
	    			$query 	= "SELECT c.id, c.parentid, p.id AS p_id, p.parentid AS p_parentid "
    	   					. "\n FROM #__comment AS c LEFT JOIN #__comment AS p ON c.parentid=p.id "
    	   					. "\n    WHERE c.id=$parentid LIMIT 1";
    	   			$database->SetQuery($query);
    	   			$row = $database->loadAssocList();
    	   			if ($row=$row[0]) { 
	    	   			$id = $row['id'];
    		   			$parentid = $row['parentid'];
    		   			if ($row['parentid']>0) {
    	   					$id = $row['p_id'];
    	   					$parentid=$row['p_parentid'];
    	   				}
    	   			} else {
    	   				$id = $parentid = -1;
    	   			}
	    		}
	    		if ($id) {
					/* get the limitstart from the root id */
        			$database->SetQuery("SELECT id ".$queryfrom.$queryparent.$queryorder);
    	    		$data = $database->loadResultArray();
    	    		$i = array_search($id, $data);
    	    		if ($i) $this->_limitstart = $i;
	    		}    		
	    	}

        	$database->SetQuery($querycount.$queryfrom.$queryparent.$queryorder);
        	$this->_total = $database->loadResult();

        	$checklimit = new JOSC_PageNav($this->_ajax, $this->_total, $this->_limitstart, $this->_display_num);
        	$this->_limitstart = $checklimit->limitstart;
        	
			$database->SetQuery($queryselect.$queryfrom.$queryparent.$queryorder, $this->_limitstart, $this->_display_num);
	        $dataparent = $database->loadAssocList();		
 			        	
        } else {
        	$database->SetQuery($queryselect.$queryfrom.$queryparent.$queryorder);
    	    $dataparent = $database->loadAssocList();
        }
        if ($this->_tree) {
			$database->SetQuery($queryselect.$queryfrom.$querychildren."\n ORDER BY id ASC");
			$datachildren = $database->loadAssocList();
 			        	
			$data = ($dataparent && count($datachildren)>0) ?  array_merge($dataparent,$datachildren) : $dataparent;
        } else {
        	$data = $dataparent;
        }
//return "displ=".$this->_display_num; 
//return JOSC_utils::debug_array($data);

		/* 
		 * $data is composed of ALL  or   ROOT array + CHILDREN array
		 * 	this means that position of a ROOT gives the page position.
		 */
        $postCSS = 1;

		if (!$data && $onlydata) return $data;

        if ($data != null) {
           	
            if ($this->_tree) $data = JOSC_utils::buildTree($data);

//return $data;
            if ($onlydata) return $data; /* after the foreach */
           
            if ($data != null) {
                foreach($data as $item) {
	            	$html .= $this->insertPost($item, $postCSS);
    	            $postCSS++;
        	        if ($postCSS == 3) $postCSS = 1;
                }
            }
        }
        
        $addjs =  "\n<script type='text/javascript'>"
        		. " var JOSC_postCSS=$postCSS;"
        		. "</script>";

		/* Daniel add-on for Allvideo Reloaded */
			if (JPluginHelper::importPlugin('content', 'avreloaded')) { 
    			$app = &JFactory::getApplication(); 
    			$res = $app->triggerEvent('onAvReloadedGetVideo', array($html)); 
    			if (is_array($res) && (count($res) == 1)) { 
    				$html = $res[0]; 
    			} 
			}
	
		/* *** */
        return $html.$addjs;
    }
    
    function insertComments()
    {
        return "<div id='Comments'>".$this->getComments()."</div>";
    }

    function insertForm()
    {
        $form = new JOSC_form($this->_form); /* template block */
        $form->setAbsolute_path($this->_absolute_path);
        $form->setLive_site($this->_live_site);
        $form->setOnly_registered($this->_only_registered);
        $form->setSupport_emoticons($this->_support_emoticons);
        $form->setSupport_UBBcode($this->_support_UBBcode);
        $form->setEmoticons($this->_emoticons);
        $form->setEmoticons_path($this->_emoticons_path);
        $form->setTemplate_path($this->_template_path);
		$form->setTemplateAbsolutePath($this->_template_absolute_path);
        $form->setTemplate_name($this->_name);
        $form->setContentId($this->_content_id);
        $form->setComponent($this->_component);
        $form->setSectionid($this->_sectionid);
        $form->setCaptcha($this->_captcha);
        $form->setNotifyUsers($this->_notify_users);
        $form->setEnterWebsite($this->_enter_website);
        $form->setEmoticonWCount($this->_emoticon_wcount);
        $form->setFormAreaCols($this->_form_area_cols);
        $form->set_tname($this->_tname);
        $form->set_temail($this->_temail);
        $form->set_twebsite($this->_twebsite);
        $form->set_tnotify($this->_tnotify);
        
        return $form->form_htmlCode();        
    }

    function comments($number)
    {
        if 		($number < 1) 	$comments = _JOOMLACOMMENT_COMMENTS_0;
        elseif 	($number == 1) 	$comments = _JOOMLACOMMENT_COMMENTS_1;
        elseif 	($number >= 2 && $number <= 4) $comments = _JOOMLACOMMENT_COMMENTS_2_4;
        else 	$comments = _JOOMLACOMMENT_COMMENTS_MORE;
        
        return $comments;
    }

    function insertCountButton()
    {
		$database =& JFactory::getDBO();
				
        $address = $this->_comObject->linkToContent( $this->_content_id );
		$com = $this->_component;

		/*
		 * READON BLOCK
		 */
		$query = "SELECT COUNT(*) FROM #__comment WHERE contentid='$this->_content_id' AND component='$com' AND published='1'";
        $database->SetQuery($query);
        $number = $database->loadResult();
        if (!$number) $number = 0;
        $html = $this->_readon;
        /*
         * no blocks
         */
        /* {READON_xxx} 	*/
        $html	= str_replace('{READON_LINK}', $address , $html);
		$html	= str_replace('{READON_WRITE_COMMENT}', _JOOMLACOMMENT_WRITECOMMENT, $html);
		$html	= str_replace('{READON_COUNT}', $number, $html);
		$html	= str_replace('{READON_COMMENTS}', $this->comments($number), $html);

		/*
		 * PREVIEW BLOCK
		 */

        /* {BLOCK-preview} */
        
		if ($this->_preview_visible) {
			$database->SetQuery("SELECT * FROM #__comment WHERE contentid='$this->_content_id' AND component='$com' AND published='1' ORDER BY date DESC");
        	$data = $database->loadAssocList();
		}
        $display	= $this->_preview_visible && ($data!=null);
        $html 		= JOSC_utils::checkBlock('BLOCK-preview', $display, $html);
        if ($display) {
        	$index = 0;
        	$previewlines = '';
			foreach($data as $item) {
            	if ($index >= $this->_preview_lines) 
                  	break;
                if ($item['title'] != '') {
                	$title = $item['title'];
                } else { 
                	$title = $item['comment'];    
                }
                if (strlen($title) > $this->_preview_length) 
                	$title = substr($title, 0, $this->_preview_length) . '...';

	            $previewline 	= $this->_previewline;
	            /* {PREVIEW_LINK} */
	            $previewline	= str_replace('{PREVIEW_LINK}', $address, $previewline);
	            /* {PREVIEW_DATE} */
	            $previewline	= str_replace('{PREVIEW_DATE}', JOSC_utils::getLocalDate($item['date'],$this->_date_format) , $previewline);//date($this->_date_format,strtotime($item['date'])) , $previewline);
	            /* {PREVIEW_TITLE} */
	            $previewline	= str_replace('{PREVIEW_TITLE}', $title, $previewline);
	            /* {PREVIEW_TITLE} */
	            $previewline	= str_replace('{id}', $item['id'], $previewline);

                $index++;
				$previewlines	.= $previewline;
			}
			/* {preview-lines} */
			$html = str_replace('{preview-lines}', $previewlines, $html);
			
        }
        return $html;
    }

    function visual_htmlCode()
    {
//        global $option, $task;

        $html = "";
        $css  = $this->CSS(); /* empty if no cache */
        
//        $contentId = intval($this->decodeData_Charset('id'));
        /*
         * if check htmlCode -> html code
         * else if check readon -> readon
         * else nothing
         * 		
         */
        $checkVisual = $this->_comObject->checkVisual( $this->_content_id );        
        if ($checkVisual) 
        {
            $html .= JOSC_jscript::insertJavaScript($this->_live_site);
        	/*
         	 * get template blocks
         	 * 		_body (container)
        	 * 		_menu
        	 * 		_search
        	 * 		_searchResults
        	 * 		_post
        	 * 		_form 
        	 * 		_poweredby 
        	 */
        	$this->parse(false);
        	
            /*
             * construct HTML (by replacement...)
             */
            $html .= "<div id='comment'>";
            if ($this->_body) {
            	$html .= $this->_body;
           		$html = JOSC_utils::checkBlock('library', $this->_template_library, $html); /* js scripts ... */
            	$html = JOSC_utils::checkBlock('menu', false, $html, $this->insertMenu());
            	$html = JOSC_utils::checkBlock('post', false, $html, $this->insertComments());
            	$html = JOSC_utils::checkBlock('form', false, $html, $this->insertForm());
            	$html = JOSC_utils::checkBlock('pagenav', false, $html, $this->insertPageNav());
	           	$html = JOSC_utils::checkBlock('poweredby', false, $html, $this->insertPoweredby());
            } else {
	            $html .= $this->insertMenu();
    	        if ($this->_sort_downward) {
        	        $html .= $this->insertForm();
            	    $html .= $this->insertComments();
	            } else {
    	            $html .= $this->insertComments();
        	        $html .= $this->insertForm();
            	}
            	$html .= $this->insertPoweredby();
            }
           	$html .= $this->insertHiddenCopyright();
            $html .= "</div>";
            $html .= $this->jscriptInit();
            $html .= $css;
                    
        } elseif ($this->_show_readon) {
	        /*
    	     * get template blocks
        	 * 		_readon
         	 * 		_previewlines
        	 */
        	$this->parse(true);
        	
            $html .= $this->insertCountButton();
            $html .= $css;
        } else
			return "";
			
        return $html;
    }
}

class JOSC_board extends JOSC_visual {
//    var $_contentId; /* row->id */
    var $_josctask;
    var $_userid;
    var $_usertype;
    var $_tname;
    var $_ttitle;
    var $_tcomment;
    var $_twebsite;
    var $_temail;
    var $_comment_id;
    var $_content_id = 0;	/* row-<id OR
    					 	 * decode content_id from url (comes from the add new comment form)
    					 	 * -> deleteall, editpost, getComments, gotoPost
    					 	 */
    var $_search_keyword;
    var $_search_phrase;
    var $_charset;

    function JOSC_board($absolutePath, $liveSite, &$comObject, &$exclude, &$row, &$params)
    {   /* be carefull, board is used in component but also in module !! */
        $this->JOSC_properties($absolutePath, $liveSite, $comObject, $exclude, $row, $params);
    }

    function setContentId($value)
    {
        $this->_content_id = $value;
    }

    function setUser() 
    {
        $database =& JFactory::getDBO();
        			
        /* also in post ! and notification */
        $query = "SELECT * FROM #__users WHERE id='".$this->_userid."' LIMIT 1";
        $database->SetQuery($query);
        $result = $database->loadAssocList();
        if ($result) {
            $user = $result[0];
            $this->_usertype  = $user['usertype'];
            $this->_tname     = $this->_use_name ? $user['name'] : $user['username'];
            $this->_temail    = $user['email'];
        }
       
    }  

    function voting($item, $mode)
    {
		$database =& JFactory::getDBO();
		        	
        $t = time()-3 * 86400;
        $database->SetQuery("DELETE FROM #__comment_voting WHERE time<'$t'");
        $database->Query();
        $database->SetQuery("SELECT COUNT(*) FROM #__comment_voting WHERE id='" . $item['id'] . "' AND ip='" . $_SERVER['REMOTE_ADDR'] . "'");
        $exists = $database->loadResult();
        if (!$exists) {
            $item["voting_$mode"]++;
            $database->SetQuery("
			UPDATE #__comment SET
        	voting_$mode='" . $item["voting_$mode"] . "'
        	WHERE id=$this->_comment_id");
            $database->Query() or die('Database error: voting(1)!');
            $database->SetQuery("INSERT INTO #__comment_voting(id,ip,time)
    		VALUES(
      		'" . $item['id'] . "',
      		'" . $_SERVER['REMOTE_ADDR'] . "',
      		'" . time() . "')");
            $database->Query() or die("Database error: voting(2)!");
        }
       	$header = 'Content-Type: text/xml; charset=utf-8'; //.$this->_local_charset;
		header($header);
        $xml = '<?xml version="1.0" standalone="yes"?><voting><id>' . $item['id'] . '</id><yes>' . $item["voting_yes"] . '</yes><no>' . $item["voting_no"] . '</no></voting>';
		$this->_comObject->cleanComponentCache();
        exit($xml);
    }

//    function getNewPost($sort, &$data)
//    {
//        $database->SetQuery("SELECT * FROM #__comment WHERE contentid='$this->_content_id' AND published='1' ORDER BY id $sort");
//        $data = $database->loadAssocList(); // or die('Database error: getNewPost!');
//    }
    	
    function isBlocked($ip)
    {
        if ($this->_ban != '') {
            $ipList = split(',', $this->_ban);
            foreach($ipList as $item) {
                if (trim($item) == $ip) return true;
            }
        }
        return false;
    }

//    function censorTextOLD($text)
//    {
//        if ($this->_censorship_enable && is_array($this->_censorship_words)) {
//            if ($this->_censorship_case_sensitive) $replace = str_replace;
//            else $replace = str_ireplace;
//            foreach($this->_censorship_words as $word) {
//                $word = trim($word);
//                if (strpos($word, '=')) {
//                    $word = explode('=', $word);
//                    $text = $replace(trim($word[0]), trim($word[1]), $text);
//                } else $text = $replace($word, JOSC_strutils::str_fill(strlen($word), '*'), $text);
//            }
//        }
//        return $text;
//    }

    function censorText($text)
    {
        return JOSC_utils::censorText($text,$this->_censorship_enable,$this->_censorship_words,$this->_censorship_case_sensitive);
    }
    
    function insertNewPost($ajax = false)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($this->isBlocked($ip)) return false;

        $user = JFactory::getUser();

		$database =& JFactory::getDBO();
		$debug = '';
		
		$com = $this->_component;
		$userid = $this->_userid;	
        $name = $this->censorText(JOSC_utils::mysql_escape_string(strip_tags($this->_tname)));
        $email = $this->censorText(JOSC_utils::mysql_escape_string(strip_tags($this->_temail)));
        $website = $this->censorText(JOSC_utils::mysql_escape_string(strip_tags($this->_twebsite)));
        if ( $website && strncmp( "http://", $website, 7 )!=0 )	$website = "http://" . $website;            
        $website = htmlentities($website);//ampReplace($website);
        $notify = JOSC_utils::mysql_escape_string(strip_tags($this->_tnotify)) ? true : false ;
        $title = $this->censorText(JOSC_utils::mysql_escape_string(strip_tags($this->_ttitle)));
        $comment = $this->censorText(JOSC_utils::mysql_escape_string(strip_tags($this->_tcomment)));
        if (!$comment) $comment = _JOOMLACOMMENT_EMPTYCOMMENT;
        $published = $this->_autopublish || JOSC_utils::isModerator($this->_moderator);
        $parent_id = $this->_parent_id;
        $database->SetQuery("
            INSERT INTO #__comment
            (contentid,component,ip,userid,usertype,date,name,email,website,notify,title,comment,published,voting_yes,voting_no,parentid)
            VALUES(
            '$this->_content_id',
            '$com', 
            '$ip',
            '$userid',
            '',
            now(),
            '$name',
            '$email',
            '$website',
            '$notify',
            '$title',
            '$comment',
            '$published',
            '0',
            '0',
            '$parent_id'
            )");
        $result=$database->Query() or die(_JOOMLACOMMENT_SAVINGFAILED); //.$database->getQuery());
        
        $this->_comment_id = $database->insertid();
		
        $notification = new JOSC_notification($this, $this->_comment_id, $this->_content_id);

        $notification->setNotifyAllPostOfUser($userid, $email, $notify);

        $notification->lists['name'] 	= $name;
        $notification->lists['title'] 	= $title;
        $notification->lists['notify'] 	= $notify;
        $notification->lists['comment']	= $comment;
                
		if ($published) {
	        $notification->lists['subject']	= _JOOMLACOMMENT_NOTIFY_NEW_SUBJECT;
    	    $notification->lists['message']	= _JOOMLACOMMENT_NOTIFY_NEW_MESSAGE;
    	    $templist	= $notification->getMailList($this->_content_id,$email);
        	$notification->notifyMailList( $templist );
		} else {
	        $notification->lists['subject']	= _JOOMLACOMMENT_NOTIFY_TOBEAPPROVED_SUBJECT;
    	    $notification->lists['message']	= _JOOMLACOMMENT_NOTIFY_TOBEAPPROVED_MESSAGE;
		    if (!JOSC_utils::isModerator($this->_moderator)) {
		    	$templist	= $notification->getMailList_moderator();
		    	$notification->notifyMailList($templist);
		    }
		}
        
        if ($ajax) {      
			$data = $this->getComments(true); 
			               
//            if ($this->_tree) {
//                $this->getNewPost('ASC', $data); /* get all post of the content_id */
//                if ($data) {
//                $id = $data[sizeOf($data)-1]['id'];
//                $data = JOSC_utils::buildTree($data);
//                $after = -1;
//                /* look for the right place */
//                foreach($data as $item) {
//                    if ($item['id'] == $id) {
//                        $item['after'] = $after;
//                        $item['view'] = $published;
//                        $item['debug'] = $debug;
//                        $item['noerror'] = 1;
//                        return $item;
//                    }
//                    $after = $item['id'];
//                }
//                }
//            } else {
//	           	$this->getNewPost('DESC LIMIT 1', $data);	
//            }
/*            $data[0]['view'] = $published;
            $data[0]['debug']	= $debug; 
            $data[0]['noerror'] = 1;
            return $data[0];
*/            
              $after = -1;
              /* look for the right place */             
              foreach($data as $item) {
                  if ($item['id'] == $this->_comment_id) {
                  	  $item['after'] = $after;
                      $item['view'] = $published;
                      $item['debug'] = $debug;
                      $item['noerror'] = 1;
                      return $item;
                  }
                  $after = $item['id'];
              } 
              $data[0]['view'] = $published;
              $data[0]['debug']	= $debug; 
              $data[0]['noerror'] = 1;
              return $data[0];
        } else return $published;
    }

    function editPost()
    {
	    $ip = $_SERVER['REMOTE_ADDR'];
        if ($this->isBlocked($ip)) return false;
                
		$database =& JFactory::getDBO();			

		$debug = '';
		
       	$database->SetQuery("SELECT * FROM #__comment WHERE id='$this->_comment_id'");
       	$item = $database->loadAssocList();
		    
		if ($this->checkEditPost($item[0])) {            
           	$title = $this->censorText(JOSC_utils::mysql_escape_string(strip_tags($this->_ttitle)));
           	$comment = $this->censorText(JOSC_utils::mysql_escape_string(strip_tags($this->_tcomment)));
	       	$website = $this->censorText(JOSC_utils::mysql_escape_string(strip_tags($this->_twebsite)));
     	  		if ( $website && strncmp( "http://", $website, 7 )!=0 )	$website = "http://" . $website;            
        	$website = htmlentities($website);//ampReplace($website);
        	$notify = JOSC_utils::mysql_escape_string(strip_tags($this->_tnotify)) ? '1' : '0' ;
        	$query = "
           	UPDATE #__comment SET
           		date=now()
           		,title='$title'
           		,comment='$comment'
          		 	,website='$website'
           		,notify='$notify'
           	WHERE id=$this->_comment_id";
           	$database->SetQuery($query);
           	$database->Query() or die(_JOOMLACOMMENT_EDITINGFAILED . "\n $query");
           	$database->SetQuery("SELECT * FROM #__comment WHERE id='$this->_comment_id' AND published='1' LIMIT 1");
           	$data = $database->loadAssocList() or die('Database error: editPost!');


	        $notification = new JOSC_notification($this, $this->_comment_id, $this->_content_id);
	        $notification->setNotifyAllPostOfUser($item[0]['userid'], $item[0]['email'], $notify);
	        
			/* send email to Moderator */               
	    	if (!JOSC_utils::isModerator($this->_moderator)) {
        			$notification->lists['name'] 	= $item[0]['name'];
        			$notification->lists['title'] 	= $title;
        			$notification->lists['notify'] 	= $item[0]['notify'];
        			$notification->lists['comment']	= $comment;
        			$notification->lists['subject']	= _JOOMLACOMMENT_NOTIFY_EDIT_SUBJECT;
        			$notification->lists['message']	= _JOOMLACOMMENT_NOTIFY_EDIT_MESSAGE;
	    	    	$templist = $notification->getMailList_moderator();
		    		$notification->notifyMailList( $templist );
	    	}
           	
           	$data[0]['view'] = 1;
           	$data[0]['debug'] = $debug;
           	$data[0]['noerror'] = 1;
           	return $data[0];
        }
    }

	/*
	 *  same as isCommentModerator
	 */
	function checkEditPost($item) 
	{
		//$user = JFactory::getUser();

		if (!$item) return false;
		/* edit if  registered or comment is own or is moderator */ 
        if ( JOSC_utils::isCommentModerator($this->_moderator, $item['userid'])) // $item['name'], $item['usertype']))
          return true;
        else
          return false;    
	}    	

	/* Function modidy($event = false)
	 * example of call : $this->modify(editPost)
 	 * 	  	event is a method which will be called below as  $this->$event(true)
 	 * 		where true means ajax call.
	*/
    function modify($event = false)
    {
 	global $mainframe;
        $user = JFactory::getUser();

        if (!$event) {
            if (!$user->username && $this->_only_registered) {
				/* only registered */            	
                JOSC_utils::showMessage(_JOOMLACOMMENT_ONLYREGISTERED);
            } else {
            	if (!($this->_captcha && !JOSC_security::captchaResult(true))) {
					/* captcha ok */            		
	                $published = $this->insertNewPost();
    	            unset($this->_tcomment);
					$this->_comObject->cleanComponentCache();
					if ($published) {
            	    	$mainframe->redirect($this->_comObject->linkToContent($this->_content_id, $this->_comment_id));
					} else {
         	    		$mainframe->redirect($this->_comObject->linkToContent($this->_content_id), _JOOMLACOMMENT_BEFORE_APPROVAL);
					}   
            	}
            }            
            $mainframe->redirect($this->_comObject->linkToContent($this->_content_id, $this->_comment_id));
        }
       	$header = 'Content-Type: text/xml; charset=utf-8'; //.$this->_local_charset;
       	header($header);
        if (!($this->_captcha && !JOSC_security::captchaResult(true))) {
            $item = $this->$event(true);
            if (!$item) exit();
            $this->parse();
            $xml = '<?xml version="1.0" standalone="yes"?>';
            $xml .= '<post>';
            $xml .= '<id>' . $item['id'] . '</id>';
            if ($this->_tree && isset($item['after'])) $xml .= '<after>' . $item['after'] . '</after>';
            $xml .= '<published>' 	. $item['view'] 	. '</published>';
            $xml .= '<noerror>' 	. $item['noerror'] 	. '</noerror>';
            $xml .= '<debug>'		. $item['debug'] 	. '</debug>';
            if ($item['view']) {
	            $html = JOSC_utils::cdata(JOSC_utils::filter($this->encodeData_Charset($this->insertPost($item, ''))));
    	        $xml .= "<body>$html</body>";
            }
            if ($this->_captcha) {
                $captcha = JOSC_utils::cdata(JOSC_security::insertCaptcha('security_refid'));
                $xml .= "<captcha>$captcha</captcha>";
            }
            $xml .= '</post>';
			$this->_comObject->cleanComponentCache();
            exit($xml);
        } else if ($this->_captcha) {
            $xml = $this->xml_refreshCaptcha(true);            
            exit($xml);
        } else exit;
    }

	function xml_refreshCaptcha($withalert=true) 
	{
            $captcha = JOSC_utils::cdata(JOSC_security::insertCaptcha('security_refid'));
            $xml = '<?xml version="1.0" standalone="yes"?>';
            $xml .= '<post>';
            $xml .= '<id>'. ($withalert ? 'captchaalert' : 'captcha' ).'</id>'; 
            $xml .= "<captcha>$captcha</captcha>";
            $xml .= '<noerror>1</noerror>';
            $xml .= '</post>';
            return $xml;
	}
	
    function deletePost($id = -1)
    {
		$database =& JFactory::getDBO();		

        $com = $this->_component;
        $contentid_where = "WHERE contentid='$this->_content_id' AND component='$com' "; 
        $where = ($id == -1) ?  $contentid_where : "WHERE id='$id'";
        $database->SetQuery("DELETE FROM #__comment $where");
        $database->Query() or die(_JOOMLACOMMENT_DELETINGFAILED);

		$this->_comObject->cleanComponentCache();
		
        /* send mail to the moderators and to the notified writers */
        if ($id == -1) {
        	$database->SetQuery("SELECT id FROM #__comment $where");
        	$cids = $database->loadResultArray();
        } else {
            $cids = $id;   /* TODO : this has no sens :D */
        }
        $notification = new JOSC_notification($this);
	    $notification->notifyComments($cids, 'delete');
        
    }

    function search()
    {
        $this->parse();
        $search = new JOSC_search($this->_searchResults, $this->_comObject);
        $search->setKeyword($this->_search_keyword);
        $search->setPhrase($this->_search_phrase);
        $search->setDate_format($this->_date_format);
        $search->setAjax($this->_ajax);
        $search->setLocalCharset($this->_local_charset);
        $search->setCensorShip(	$this->_censorship_enable, 
        						$this->_censorship_case_sensitive, 
        						$this->_censorship_words, 
        						$this->_censorship_usertypes 
        					);        
        $search->setMaxLength_text($this->_maxlength_text);
        $search->setMaxLength_word($this->_maxlength_word);
        $search->setMaxLength_line($this->_maxlength_line);
        $search->setContentId($this->_content_id);
        $search->setComponent($this->_component);
        $search->setSectionid($this->_sectionid);
        return $search->search_htmlCode();
    }

    function filterAll($item)
    {	/* used also by search class */
        $item['name'] 	= JOSC_utils::filter($this->encodeData_Charset($item['name']));
        $item['title'] 	= JOSC_utils::filter($this->encodeData_Charset($item['title']));
        $item['comment'] = JOSC_utils::filter($this->encodeData_Charset($item['comment']));
        return $item;
    }

    function decodeURI()
    {
	$user =& JFactory::getUser();

	$this->_request_uri = JArrayHelper::getValue( $_SERVER, 'REQUEST_URI', '' ); // _JOSC_MOS_ALLOWHTML
	$this->_josctask = $this->decodeData_Charset('josctask');

	if ($user->username) {
	    $this->_userid = $user->id;
	    $this->_usertype = $user->usertype;
	    $this->_tname = $user->username;
	    $this->setUser();
	} else {
	    $this->_userid = 0;
	    $this->_usertype = 'Unregistered';
	    $this->_tname = $this->decodeData_Charset('tname');
	    $this->_temail = $this->decodeData_Charset('temail');
	}

	$this->_tnotify = intval($this->decodeData_Charset('tnotify'));
	$this->_twebsite = $this->decodeData_Charset('twebsite');
	$this->_ttitle = $this->decodeData_Charset('ttitle');
	$this->_tcomment = $this->decodeData_Charset('tcomment');

	$this->_comment_id = intval($this->decodeData_Charset('comment_id'));
	//       	if (!$this->_comment_id) $this->_comment_id = intval(preg_replace("/(.*#josc)(.*)(&.*|)/", '\\2', $this->_request_uri));
	//				#xxx is not sent by navigators as FF...

	if (!$this->_content_id)	$this->_content_id = intval($this->decodeData_Charset('content_id'));
	if (!$this->_component)	$this->_component = strval($this->decodeData_Charset('component'));

	$this->_search_keyword = $this->decodeData_Charset('search_keyword');
	$this->_search_phrase = $this->decodeData_Charset('search_phrase');
	$this->_parent_id = $this->decodeData_Charset('parent_id');
	if ($this->_parent_id == '') $this->_parent_id = '-1';
	$this->_limitstart = intval($this->decodeData_Charset('josclimitstart'));
	//no necessary?        if ($this->_limitstart == '') $this->_limitstart = '0';
    }
    
    function decodeData_Charset($varName) {
	/* 
	 * javascript(ajax) encodeURI is only UTF-8. so we have to decode ajax send
	 * should be solved with joomla 1.5 ! (native utf-8)
	 */
	 if ($this->_ajax)
        return JOSC_utils::myiconv_decode( JOSC_utils::decodeData($varName), $this->_local_charset );
     else
     	return JOSC_utils::decodeData($varName);    	
    }
    
    function encodeData_Charset($var) {
	/* 
	 * javascript(ajax) encodeURI is only UTF-8. so we have to decode ajax send
	 * should be solved with joomla 1.5 ! (native utf-8)
	 */
	 if ($this->_ajax)
        return JOSC_utils::myiconv_encode( $var , $this->_local_charset );
     else
     	return $var;  	        
    }
    
    /*
     * decode URI 
     * and execute josctask if 'josctask'(ajax mode) 
     * 			OR insertnewPost if 'tcomment'(not ajax mode)
     */   
    function execute()
    {

		$database =& JFactory::getDBO();			

	    /* don't forget if modify josctask
	     * that it is first checked in comment.php ! 
	     */

        $this->decodeURI();
        if ($this->_josctask == 'noajax') {
        	if ($this->_tcomment) 
        		$this->modify(false); /* modify in not ajax mode */
        		        	
        } else {
            $query = "SELECT * FROM #__comment WHERE id='$this->_comment_id' LIMIT 1";
            $database->SetQuery($query);
            $item = $database->loadAssocList();
            $itemsave = $item ? $item[0] : "";
/*			if ($this->_josctask == 'unsubscribe') {
				$notification = new JOSC_notification($this, $this->_comment_id, $this->_content_id);
				$notification->setNotifyAllPostOfUser($userid, $email, $notify);
			}*/		
            if ($this->checkEditPost($itemsave)) {
                if ($this->_josctask == 'ajax_delete') {
                    $this->deletePost($this->_comment_id);
                    exit;
                }
                if ($this->_josctask == 'ajax_edit') $this->modify('editPost');
            }
            if (JOSC_utils::isModerator($this->_moderator)) {
                if ($this->_josctask == 'ajax_delete_all') {               
                    $this->deletePost();
                    exit;
                }
            }
            if ($this->_josctask == 'ajax_insert') {
               	/*  
               	 * if parent_id AND only moderator reply -> exit if not moderator
               	 * because javascript reply deactivated, should not be possible except volontary spam...or delay change of setting 
               	 * else ok.
               	 */
                if ($this->_parent_id<1 || !$this->_mlink_post || JOSC_utils::isModerator($this->_moderator)) //$this->checkEditPost($itemsave)) 
            		$this->modify('insertNewPost');
            	else exit(); 
            }
            if ($this->_josctask == 'ajax_modify' || $this->_josctask == 'ajax_quote') {
                /*
                 * return <post> content of current comment to the FORM
                 */
                $item = $this->filterAll($item[0]);
     	       	$header = 'Content-Type: text/xml; charset=utf-8'; //.$this->_local_charset; not ok for IE ! only utf-8 is possible
       			header($header);
                $item['email'] = ($item['userid'] ? JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_AUTOMATICEMAIL)) : $item['email']);
                $xml = '<?xml version="1.0" standalone="yes"?><post>'
                	 . '<name>'     . JOSC_utils::cdata($item['name'])    .'</name>'
                	 . '<title>'    . JOSC_utils::cdata($item['title'])   . '</title>'
					 . '<comment>'  . JOSC_utils::cdata($item['comment']) . '</comment>'
					 ;
					 if ($this->_josctask == 'ajax_modify' && $this->checkEditPost($itemsave)) {
					 $xml 	.= '<email>'    . JOSC_utils::cdata($item['email'])   . '</email>'
//					 		. '<userid>'   . JOSC_utils::cdata($item['userid'])  . '</userid>'
					 		. '<notify>'   . JOSC_utils::cdata($item['notify'])  . '</notify>'
					 		. '<website>'  . JOSC_utils::cdata($item['website']) . '</website>'
					 ;
					 }
					 
				$xml .= '</post>'
					 ;
                exit($xml);
            }
            if ($this->_josctask == 'ajax_reload_captcha') {
       			$header = 'Content-Type: text/xml; charset=utf-8'; //.$this->_local_charset;
       			header($header);
       			$xml = $this->xml_refreshCaptcha(false);            
            	exit($xml);            	
            }
            if ($this->_josctask == 'ajax_voting_yes') $this->voting($item[0], 'yes');
            if ($this->_josctask == 'ajax_voting_no') $this->voting($item[0], 'no');
            if ($this->_josctask == 'ajax_insert_search') {
                $this->parse();
     	       	$header = 'Content-Type: text/xml; charset=utf-8'; //.$this->_local_charset;
       			header($header);
                exit($this->insertSearch());
            }
            if ($this->_josctask == 'ajax_search') {
     	       	$header = 'Content-Type: text/xml; charset=utf-8'; //charset='.$this->_local_charset;
       			header($header);
                exit($this->search());
            }
            if ($this->_josctask == 'ajax_getcomments') {
     	       	$header = 'Content-Type: text/xml; charset=utf-8'; //charset='.$this->_local_charset;
       			header($header);
            	$this->parse();	            
            	$html 	 = $this->getComments();
	            if (!$html) exit();
				$pagenav = $this->getPageNav();
            	$xml = '<?xml version="1.0" standalone="yes"?>';
	            $xml .= '<getpost>';
    	        $xml .= '<limitstart>' . $this->_limitstart . '</limitstart>';
	            $xml .= '<body>'.JOSC_utils::cdata(JOSC_utils::filter($this->encodeData_Charset($html))).'</body>';
	            $xml .= '<pagenav>'.JOSC_utils::cdata(JOSC_utils::filter($this->encodeData_Charset($pagenav))).'</pagenav>';
//	            $xml .= '<debug>'.$this->_display_num.'</debug>';
                $xml .= '</getpost>';
//				$this->_comObject->cleanComponentCache();
        	    exit($xml);
            }
        }
    }
}

class JOSC_tree {
    var $_id;
    var $_counter;

    function getSeed($data, $seed)
    {
        $this->_counter++;
        if ($seed) {
            foreach($seed as $item) {
                $data[$item]['wrapnum'] = $this->_counter;
                $this->_new[] = $data[$item];
                if (isset($data[$item]['seed']) && $data[$item]['seed']) {
                    $this->getSeed($data, $data[$item]['seed']);
                    $data[$item] = null;
                }
            }
        }
        $this->_counter--;
    }

    function build($data)
    {
        $index = 0;
        $this->_new = null;
        $this->_counter = 0;
        /*
         * TREE :
         * 	parents can have several direct children
         * 	their children can have also their own children etc...
         * 
         * 	parent
         * 		|_	child1
         * 		|		|_	child1.1
         * 		|		|			|_ child1.1.1
         * 		|		|			|...
         * 		|		|_	child1.2
         * 		|		...
         * 		|_	child2
         * 		... 
         * 
         * SEED for one parent is the CHILDS ARRAY
         */
         
		/*
		 * FIRST LOOP : prepare datas
		 * 
		 * $index is $data key  (we call it: INDEX)
		 * 
		 * $old[] : key = comment_id / value = INDEX
		 * 
		 * - save INDEX in a new 'treeid' column
		 * 
		 * - for all children: replace parentid value by PARENT INDEX value
		 * -> sort must be with parents first !! (means already set in old)
		 * 
		 */
        foreach($data as $item) {
            $old[$item['id']] = $index;
            $data[$index]['treeid'] = $index;
            if ($data[$index]['parentid'] != -1) 
            	$data[$index]['parentid'] = isset($old[$item['parentid']]) ? $old[$item['parentid']] : -2;
            $index++;
        }
//$debug .= JOSC_utils::debug_array($data);
        
		/*
		 * 2ND LOOP : construct SEED
		 * 
		 * - for all childrens : construct 1st level 'seed'[] 
		 */
        foreach($data as $item) {
        	/*		IS CHILD			->			PARENT[SEED][] = CHILD INDEX				*/					
        	if ($item['parentid'] >= 0) {
        		 $data[$item['parentid']]['seed'][] = $item['treeid'];
//        		 $data[$item['treeid']]['treerootid'] = $this->getRootId($data, $item['parentid']);
        	}	
        }
//$debug .= JOSC_utils::debug_array($data);
        
        foreach($data as $item) {
        	/*		IS NOT A CHILD		->			DATA[]				*/					
            if ($item['parentid'] == -1) {
                $this->_new[] = $item;
                if (isset($item['seed'])) $this->getSeed($data, $item['seed']);
            }
        }

//$debug .=JOSC_utils::debug_array($this->_new);
// 	return $debug;
 	
        return $this->_new;
    }
    
    function getRootId( &$data, $index ) 
    {	
    	if ($data[$index]['parentid']!=-1) {
    		/* is a child */
    		if (!$data[$index]['treerootid']) {
    			/* for every nodes, treerootid = root */
    			$data[$index]['treerootid'] = $this->getRootId($data, $data[$index]['parentid']);
    		}
    		return $data[$index]['treerootid'];
    	} else {
    		return $data[$index]['treeid'];
    	}	
    }
}


class JOSC_support {
    var $_comObject;
    var $_ajax;
    var $_local_charset;
    var $_absolute_path;
    var $_live_site;
    var $_template_absolute_path;
    var $_template_path;
    var $_template_name;
    var $_only_registered;
    var $_website_registered;
    var $_support_emoticons;
    var $_support_UBBcode;
    var $_support_pictures;
    var $_pictures_maxwidth;
    var $_support_quotecode;
    var $_support_link;
    var $_hide;
    var $_emoticons;
    var $_emoticons_path;
    var $_censorship_enable;
    var $_censorship_case_sensitive;
    var $_censorship_words;
    var $_censorship_usertypes;        
    var $_content_id;
    var $_component;
    var $_sectionid;
    var $_moderator;
    var $_show_readon;
    var $_date_format;
    var $_maxlength_text;
    var $_maxlength_word;
    var $_maxlength_line;
    
	function JOSC_support(&$comObject)
	{
		$this->_comObject = $comObject;
	}
	
    function setAjax($value)
    {
        $this->_ajax = $value;
    }

    function setLocalCharset($value)
    {
        $this->_local_charset = $value;
    }

    function setAbsolute_path($value)
    {
        $this->_absolute_path = $value;
    }

    function setLive_site($value)
    {
        $this->_live_site = $value;
    }

    function setTemplate_path($value)
    {
        $this->_template_path = $value;
    }

    function setTemplateAbsolutePath($value)
    {
        $this->_template_absolute_path = $value;
    }

    function setTemplate_name($value)
    {
        $this->_template_name = $value;
    }

    function setOnly_registered($value)
    {
        $this->_only_registered = $value;
    }

    function setWebsiteRegistered($value)
    {
        $this->_website_registered = $value;
    }

    function setSupport_emoticons($value)
    {
        $this->_support_emoticons = $value;
    }

    function setSupport_UBBcode($value)
    {
        $this->_support_UBBcode = $value;
    }

    function setSupport_pictures($value,$maxwidth='')
    {
        $this->_support_pictures = $value;
        $this->_pictures_maxwidth = $maxwidth;
    }

    function getSupport_pictures()
    {	/* used in module */
        return $this->_support_pictures;
    }
	
    function setSupport_quotecode($value)
    {
        $this->_support_quotecode = $value;
    }

    function getSupport_quotecode()
    {	/* used in module */
        return $this->_support_quotecode;
    }

    function setSupport_link($value)
    {
        $this->_support_link = $value;
    }

    function getSupport_link()
    {	/* used in module */
        return $this->_support_link;
    }

    function setHide($value)
    {
        $this->_hide = $value;
    }

    function setEmoticons($value)
    {
        $this->_emoticons = $value;
    }

    function setEmoticons_path($value)
    {
        $this->_emoticons_path = $value;
    }

	function setContentId($value)
    {
        $this->_content_id = $value;
    }

	function setComponent($value)
    {
        $this->_component = $value;
    }

	function setSectionid($value)
    {
        $this->_sectionid = $value;
    }

	function setModerator($value)
    {
        $this->_moderator = $value;
    }
    
    function setReadon($value)
    {
        $this->_show_readon= $value;
    }
    
    function setDate_format($value)
    {
        $this->_date_format = $value;
    }
    
	function setCensorShip($enable, $case_sensitive, $words, $usertypes ) {
    	$this->_censorship_enable 			= $enable;
    	$this->_censorship_case_sensitive 	= $case_sensitive;
    	$this->_censorship_words 			= $words;
    	$this->_censorship_usertypes 		= $usertypes; 
	}

    function setMaxLength_text($value)
    {
        $this->_maxlength_text = $value;
    }

    function getMaxLength_text()
    {	/* used in module */
        return $this->_maxlength_text;
    }

    function setMaxLength_word($value)
    {
        $this->_maxlength_word = $value;
    }

    function getMaxLength_word()
    {	/* used in module */
        return $this->_maxlength_word;
    }

    function setMaxLength_line($value)
    {
        $this->_maxlength_line = $value;
    }

    function getMaxLength_line()
    {	/* used in module */
        return $this->_maxlength_line;
    }
	
    function decodeData_Charset($varName) {
	/* 
	 * javascript(ajax) encodeURI is only UTF-8. so we have to decode ajax send
	 * should be solved with joomla 1.5 ! (native utf-8)
	 */
	 if ($this->_ajax)
        return JOSC_utils::myiconv_decode( JOSC_utils::decodeData($varName), $this->_local_charset );
     else
     	return JOSC_utils::decodeData($varName);    	
    }
    
    function encodeData_Charset($var) {
	/* 
	 * javascript(ajax) encodeURI is only UTF-8. so we have to decode ajax send
	 * should be solved with joomla 1.5 ! (native utf-8)
	 */
	 if ($this->_ajax)
        return JOSC_utils::myiconv_encode( $var , $this->_local_charset );
     else
     	return $var;  	        
    }

    function censorText($text)
    {   
        return JOSC_utils::censorText($text,$this->_censorship_enable,$this->_censorship_words,$this->_censorship_case_sensitive);
    }

	function formHiddenValues($contentid, $component, $sectionid )
	{	/* used also in BOARD ! */
        $hidden  = JOSC_utils::inputHidden('content_id', $contentid);
        $hidden .= JOSC_utils::inputHidden('component', $component);
        $hidden .= JOSC_utils::inputHidden('joscsectionid', $sectionid);
		return $hidden;		
	}    
}


class JOSC_menu extends JOSC_support {
    var $_menu;
    var $_rss;
    var $_no_search;

    function JOSC_menu($value)
    {
        $this->_menu = $value;
    }

    function setRSS($value)
    {
        $this->_rss = $value;
    }

	function setNoSearch($value)
	{
		$this->_no_search = $value;	
	}
	
    function insertButton($text, $link, $icon = '')
    {
        if ($icon) $icon = "<img class='menuicon' src='$icon' alt='$icon' />";
        return "<td class='button'><a id='$text' href='$link'>$icon$text</a></td>";
    }

    function menu_htmlCode()
    {
		$user = JFactory::getUser();
		
		$html = $this->_menu;
		
		$only_registered = !$user->username && $this->_only_registered;
		
		/* {_JOOMLACOMMENT_COMMENTS_TITLE} */
        $html 		= str_replace('{_JOOMLACOMMENT_COMMENTS_TITLE}', _JOOMLACOMMENT_COMMENTS_TITLE, $this->_menu);
        /* {template_live_site} 	*/
        $html 		= str_replace('{template_live_site}', $this->_template_path.'/'.$this->_template_name, $html);

		/* {BLOCK-add_new}	_JOOMLACOMMENT_ADDNEW */
		$display	= !$only_registered;
        $html 		= JOSC_utils::checkBlock('BLOCK-add_new', $display, $html);
        if ($display) {
			$html = str_replace('{_JOOMLACOMMENT_ADDNEW}', _JOOMLACOMMENT_ADDNEW, $html);
			$html = str_replace('{BUTTON_ADDNEW_js}', 'JOSC_addNew()', $html);     
        }
        
		/* {BLOCK-delete_all}  _JOOMLACOMMENT_DELETEALL	 */        
		$display	= JOSC_utils::isModerator($this->_moderator);
        $html 		= JOSC_utils::checkBlock('BLOCK-delete_all', $display, $html);
        if ($display) {
            $html 	= str_replace('{_JOOMLACOMMENT_DELETEALL}', _JOOMLACOMMENT_DELETEALL, $html);
            $html 	= str_replace('{BUTTON_DELETEALL_js}', 'JOSC_deleteAll()', $html);
        }

		/* {BLOCK-search} _JOOMLACOMMENT_SEARCH */
		$display	= !$this->_no_search;
        $html 		= JOSC_utils::checkBlock('BLOCK-search', $display, $html);
        if ($display) {
			$html = str_replace('{_JOOMLACOMMENT_SEARCH}', _JOOMLACOMMENT_SEARCH, $html);
			$html = str_replace('{BUTTON_SEARCH_js}', 'JOSC_searchForm()', $html);
        }

		/* {BLOCK-rss} _JOOMLACOMMENT_RSS */        
		$display	= $this->_rss;
        $html 		= JOSC_utils::checkBlock('BLOCK-rss', $display, $html);
        if ($display) {
            $html 	= str_replace('{_JOOMLACOMMENT_RSS}', _JOOMLACOMMENT_RSS, $html);
            $html 	= str_replace('{BUTTON_RSS_URL}', "index2.php?option=com_comment&no_html=1&josctask=rss&contentid=$this->_content_id", $html);
        }
        return $html;
    }
}


class JOSC_post extends JOSC_support {
    var $_post;
    var $_item;
    var $_css;
    var $_tree;
    var $_mlink_post;
    var $_tree_indent;
    var $_IP_visible;
    var $_IP_partial;
    var $_IP_caption;
    var $_IP_usertypes;
    var $_voting_visible;
    var $_avatar;
    var $_user_id;
    var $_use_name;
    var $_notify_users;
    var $_notify_moderator;
    
//    var $_ubbcodeArray;

    function JOSC_post($value)
    {
        $this->_post = $value;
    }

	/*
	 * AGE comment: the use of the following methods to just set the value seems heavy 
	 * but this is a professional way to code from joomlacomment developpers
	 */
    function setItem($value)
    {
        $this->_item = $value;
        $this->setUser(); /* _use_name has to be set ! refresh comment user values according to */    
    }
    
    function setUser() 
    {
		$database =& JFactory::getDBO();			
        
        if (!$this->_item || !$this->_item['userid'])
        	return;
			     
        $query = "SELECT * FROM #__users WHERE id='".$this->_item['userid']."' LIMIT 1";
        $database->SetQuery($query);
        $result = $database->loadAssocList();
        if ($result) {
            $user = $result[0];
            $this->_item['name']     = $this->_use_name ? $user['name'] : $user['username'];
            $this->_item['usertype'] = $user['usertype'];
            $this->_item['email']    = $user['email'];
        }
       
    }  

    function setCSS($value)
    {
        $this->_css = $value;
    }

    function setTree($value)
    {
        $this->_tree = $value;
    }

    function setMLinkPost($value)
    {
        $this->_mlink_post = $value;
    }

    function setTree_indent($value)
    {
        $this->_tree_indent = $value;
    }

    function setIP_visible($value)
    {
        $this->_IP_visible = $value;
    }

    function setIP_partial($value)
    {
        $this->_IP_partial = $value;
    }

    function setIP_caption($value)
    {
        $this->_IP_caption = $value;
    }

    function setIP_usertypes($value)
    {
        $this->_IP_usertypes = $value;
    }

    function setVoting_visible($value)
    {
        $this->_voting_visible = $value;
    }

    function setAvatar($value)
    {
        $this->_avatar = $value;
    }

    function setUseName($value)
    {
        $this->_use_name = $value;
    }

    function setUser_id($value)
    {
        $this->_user_id = $value;
    }

    function setNotify_users($users, $moderators)
    {
        $this->_notify_users = $users;
        $this->_notify_moderator = $moderators;
    }

    function highlightAdmin($usertype)
    {
        if ($usertype=='Super Administrator') $usertype = 'SAdministrator';
        
        if (strpos($usertype, 'Administrator'))
            $usertype = "<span class='administrator'>$usertype</span>";
        return $usertype;
    }

    function anonymous($name)
    {
        if ($name == '') $name = _JOOMLACOMMENT_ANONYMOUS;
        return $name;
    }

    function space($title)
    {
        if ($title == '') return '';
        return ' - ';
    }

    function IP($ip, $usertype, $visible, $partial, $caption)
    {
    	$user = JFactory::getUser();

       	$int_usertype 	= JOSC_utils::getJOSCUserType($usertype); /* -1 for unresgistered */
       	$int_myusertype = JOSC_utils::getJOSCUserType($user->usertype); /* -1 for unregistered */
       	
       	$html = "";
       	
        if ($visible) {
			/* only if comment writer usertype is in _IP_usertypes */        	
        	$visible = in_array($int_usertype, $this->_IP_usertypes);
        } elseif ($int_myusertype>=0) {
        	/* not visible: only if my->usertype is in _IP_usertypes */
        	$visible = in_array($int_myusertype, $this->_IP_usertypes);
        }

        if ($visible) {
        	if ($int_usertype<0) {
        		/* IP address */
            	if ($partial) { 
            		$ip = JOSC_utils::partialIP($ip);
            	}
            	$html = $caption . $ip;
	        } else {
	        	/* usertype string */
        		$html = $this->highlightAdmin($usertype);
        	}
    	}
    	return $html;
    }

    function linkQuote($id)
    {
        return "<a href = 'javascript:JOSC_quote($id)'>" . _JOOMLACOMMENT_QUOTE . "</a>";
    }

    function linkPost($id)
    {
        return "<a href='javascript:JOSC_reply($id)'>" . _JOOMLACOMMENT_REPLY . '</a>';
    }

    function linkEdit($id)
    {
        return "<a href='javascript:JOSC_editComment($id)'>" . _JOOMLACOMMENT_EDIT . '</a>';
    }

    function linkDelete($id)
    {
        return "<a href='javascript:JOSC_deleteComment($id)'>" . _JOOMLACOMMENT_DELETE . '</a>';
    }

    function voting_cell($mode, $num, $id)
    {
        return "<td><a id='$mode$id' class='voting_$mode' href='javascript:JOSC_voting($id,\"$mode\")'>$num</a></td>";
    }

    function voting($voting_no, $voting_yes, $id, $contentId)
    {
        $html = '';
        if ($this->_voting_visible) {
            if ($voting_yes == '') {
                $voting_yes = 0;
                $voting_no = 0;
            }
            $html .= "<table cellspacing='0' cellpadding='0' border='0'>";
            $html .= "<tr>" . $this->voting_cell('yes', $voting_yes, $id);
            $html .= "<td>&nbsp;</td>";
            $html .= $this->voting_cell('no', $voting_no, $id) . "</tr>";
            $html .= "</table>";
        }
		/*
		 * If voting no are 2x greater than voting yes => mode hide
		 */
        $this->_hide = (($voting_no + 1) > (($voting_yes + 1) * 2)); 
        return $html;
    }

    function parseUBBCode($html)
    {
       	$ubbcode = new JOSC_ubbcode($html);
       	$ubbcode->setMaxlength($this->_maxlength_word, $this->_maxlength_text, $this->_maxlength_line);
       	$ubbcode->setSupport_emoticons($this->_support_emoticons);
       	$ubbcode->setSupport_UBBcode($this->_support_UBBcode);
       	$ubbcode->setSupport_quotecode($this->_support_quotecode);
       	$ubbcode->setSupport_link($this->_support_link);
       	$ubbcode->setSupport_pictures($this->_support_pictures,$this->_pictures_maxwidth);
       	$ubbcode->setHide($this->_hide);
       	$ubbcode->setEmoticons($this->_emoticons);
       	$ubbcode->setEmoticons_path($this->_emoticons_path);
       	$html = $ubbcode->ubbcode_parse();
       	return($html);
    }

    function envelope($html, $id, $wrapnum)
    {
        $wrapnum = ($this->_tree) ? $wrapnum : 0;
/*        $result = "<table class='postcontainer' id='post$id' width='100%' cellpadding='0' cellspacing='0' style='padding-left: $wrapnum;'>";
        $result .= "<tr><td><a name='josc$id'></a>$html</td></tr>";
        $result .= "</table>";*/
        $result = str_replace('{wrapnum}',$wrapnum, $html);
        return $result;
    }

    function setMaxLength($text)
    {
        return JOSC_utils::setMaxLength($text,$this->_maxlength_text);
    }

//    function wrapText($text)
//    {
//        return JOSC_utils::wrapText($text,$this->_maxlength_word);
//    }

    function profileLink($s, $id)
    {
        return $id ? "<a href=".JURI::base()."/index.php?option=com_comprofiler&task=userProfile&user=$id'>$s</a>" : $s;
    }

	function notifyLink($notify, $notify_users ) 
	{
        $notifyactive = ($notify_users && $notify) ? '1' : '0';
		return "<span class='postnotify$notifyactive' title='".( $notifyactive ? ( _JOOMLACOMMENT_NOTIFYTXT1 ) : _JOOMLACOMMENT_NOTIFYTXT0 )."'>&nbsp;</span>";	    
	}

    function post_htmlCode()
    {
//        global $user, $gid;
		$user = JFactory::getUser();
		$gid = $user->gid;

		/*
		 * THIS LOGIC IS USED ALSO FOR MODULE 
		 * so if changes are made, check also in module the result...
		 */
		/*
		 * use ' character instead of \" in html code
		 */        

		/*
		 * prepare datas
		 */
        $id 		= $this->_item['id'];
        $name	 	= $this->censorText(JOSC_utils::filter($this->anonymous($this->_item['name'])));
        $website 	= $this->censorText(JOSC_utils::filter($this->_item['website']));
        $title 		= $this->censorText(JOSC_utils::filter($this->_item['title']));
        $comment 	= $this->censorText(JOSC_utils::filter($this->_item['comment']));
        $usertype 	= $this->_item['usertype'];
        $ip 		= $this->_item['ip'];
        $date 		= JOSC_utils::getLocalDate($this->_item['date'],$this->_date_format);//date($this->_date_format, strtotime($this->_item['date']));
        
        /* */
        $isCommentModerator = JOSC_utils::isCommentModerator($this->_moderator, $this->_item['userid']); //, $name, $usertype);
		$isModerator		= JOSC_utils::isModerator($this->_moderator);
        $notify 	= ($this->_notify_moderator && $usertype && $isModerator) ? '1' : $this->_item['notify'];

        $edit 		= '';
        /* reply : if not only moderator OR user is moderator */
        if ($this->_tree) {
        	if ( !$this->_mlink_post || ($isModerator) ) 
        		$edit = $this->linkPost($id);
        }
        /* quote */
        if ($this->_support_UBBcode) {
            if ($edit) $edit .= ' | ';
            $edit .= $this->linkQuote($id);
        }
        /* edit and delete */
        if ($isCommentModerator) {
            if ($edit) $edit .= ' | ';
            $edit .= $this->linkEdit($id);
            $edit .= ' | ' . $this->linkDelete($id);
        }
        
        $voting 	= $this->voting($this->_item['voting_no'], $this->_item['voting_yes'], $id, $this->_content_id);
        $comment	= $this->parseUBBCode($comment);
//        $comment 	= $this->setMaxLength($comment); /* before long ubbcode tags ! */
//        $comment	= $this->parseUBBCode_2($comment);
        /*
         * parse template block
         */
        $html 		= $this->_post;

		$NLsearch  = array(); $NLsearch[]  = "\n"; 		$NLsearch[] = "\r";
		$BRreplace = array(); $BRreplace[] = "<br />"; 	$BRreplace[] = " ";

        /*
         * no blocks
         */
        /* {id} 	*/
        $html 		= str_replace('{id}', $id , $html);
        /* {template_live_site} 	*/
        $html 		= str_replace('{template_live_site}', $this->_template_path.'/'.$this->_template_name, $html);
        /* {postclass} 	*/
        $html 		= str_replace('{postclass}', 'sectiontableentry' . $this->_css, $html);
        /* {username} 	*/
        $html 		= str_replace('{username}', $this->profileLink($name, $this->_user_id), $html);
        /* {date} 		*/
        $html 		= str_replace('{date}', $date, $html);
        /* {content}	*/
        $html 		= str_replace('{content}', $comment, $html);
        /* {content_js}	*/
        $html 		= str_replace('{content_js}', addslashes(str_replace($NLsearch, $BRreplace,$comment)), $html);
        /* {notify} 	*/
        $html 		= str_replace('{notify}', $this->notifyLink($notify, $this->_notify_users) , $html);

        /*
         * with blocks
         */
        /* {avatar_picture} */
        $display	= $this->_avatar;
        $html 		= JOSC_utils::checkBlock('BLOCK-avatar_picture', $display, $html);
        if ($display) {
			if(strpos($this->_avatar,"gallery/")===false) 
	            $path = JURI::base()."/images/comprofiler/tn$this->_avatar";
			else
	            $path = JURI::base()."/images/comprofiler/$this->_avatar";		            
            $html 	= str_replace('{avatar_picture}', $this->profileLink("<img class='avatar' src='$path' alt='avatar' />", $this->_user_id), $html);
        }
        /* {website} 		*/
        $display	= ($website && (!$this->_website_registered || $user->gid > 0)) ? true : false;
        $html 		= JOSC_utils::checkBlock('BLOCK-website', $display, $html);
        if ($display) {
            $website = "<a class='postwebsite' rel='external nofollow' href='$website' title='$website' target='_blank'>&nbsp;</a>";
    	    $html 		= str_replace('{website}', $website, $html);
        }
        /* {title} 		*/
        $display	= $title ? true : false;
        $html 		= JOSC_utils::checkBlock('BLOCK-title', $display, $html);
        if ($display) {
        	$html	= str_replace('{title}', $title, $html);
        	$html	= str_replace('{title_js}', addslashes(str_replace($NLsearch, $BRreplace,$title)), $html);
        }
        
        /* {usertype} 		*/
        $display	= $this->IP($ip, $usertype, $this->_IP_visible, $this->_IP_partial, $this->_IP_caption);
        $html 		= JOSC_utils::checkBlock('BLOCK-usertype', $display, $html);
        if ($display) {
        	$html 	= str_replace('{usertype}', $display, $html);
        }

		$display 	= ((!$user->username && $this->_only_registered) || !$this->_ajax || ($edit == '')) ? false : true;
        $html 		= JOSC_utils::checkBlock('BLOCK-footer', $display, $html);
        if ($display) {
			/* {editbuttons} */
        	$html 		= str_replace('{editbuttons}', $edit, $html);
	        /* {voting} */
    	    $html 		= str_replace('{voting}', $voting, $html);
        }

        $wrapnum    = isset($this->_item['wrapnum']) ? $this->_item['wrapnum'] : 0;
        return $this->envelope($html, $id, ($wrapnum * $this->_tree_indent) . 'px');
        
    }

}


class JOSC_ubbcode extends JOSC_support {
    var $_comment;
//    var $_maxlength_word;
//    var $_maxlength_line;
 //   var $_maxlength_text;
    var $_ubbcodeCount=1;
    var $_ubbcodeArray=array();
    var $_splitTag;
    var $_limittextTag;
    var $_TO='<';  /* for debug change */
    var $_TC='>';  /* for debug change */

    function JOSC_ubbcode($value)
    {
        $this->_comment = $value;
    }
	
	function setMaxlength($word, $text, $line)
	{
		$this->_maxlength_word = $word;	
		$this->_maxlength_line = $line;	
		$this->_maxlength_text = $text;	
	}
	
    function parseEmoticons($html)
    {
        foreach ($this->_emoticons as $ubb => $icon) {
        	/* do not set ubb as alt text ! else will be replace twice or more...! */ 
            $html = str_replace($ubb, "<img src='" . $this->_emoticons_path . '/' . $icon . "' border='0' alt='' />", $html);
        }
        return $html;
    }

//    function parseImgElement($html)
//    {
//        //return preg_replace('/\[img\](.*?)\[\/img\]/i', '<img src=\'\\1\' alt=\'Posted image\' />', $html);
//        $regexp = '/\[img\](.*?)\[\/img\]/i';
//        return preg_replace_callback($regexp, array(&$this, 'callback_img'), $html);
//    }
//	function callback_img( &$matches )
//	{	
//	    $ubbcodeID = '['.$this->_ubbcodeCount++.']';
//	    
//	    $result 			= array();
//	    $result['type'] 	= 'img';
//	    $result['ID'] 		= $ubbcodeID;
//	    $result['value']	= $matches[1];
//	    
//		$this->_ubbcodeArray[]=$result;
//
//		return ($ubbcodeID); /* insert ID in string */	    
//	}
//	
//    function parseQuoteElement($html)
//    {
//        $q1 = substr_count($html, "[/quote]");
//        $q2 = substr_count($html, "[quote=");
//        if ($q1 > $q2) $quotes = $q1;
//        else $quotes = $q2;
//        $patterns = array("/\[quote\](.+?)\[\/quote\]/is",
//            "/\[quote=(.+?)\](.+?)\[\/quote\]/is");
//        $replacements = array("<div class='quote'><div class='genmed'><b>" . _JOOMLACOMMENT_UBB_QUOTE . "</b></div><div class='quotebody'>\\1</div></div>",
//            "<div class='quote'><div class='genmed'><b>\\1 " . _JOOMLACOMMENT_UBB_WROTE . "</b></div><div class='quotebody'>\\2</div></div>");
//        while ($quotes > 0) {
//            $html = preg_replace($patterns, $replacements, $html);
//            $quotes--;
//        }
//        return $html;
//    }

    function code_unprotect($val)
    {
        $val = str_replace("{ : }", ":", $val);
        $val = str_replace("{ ; }", ";", $val);
        $val = str_replace("{ [ }", "[", $val);
        $val = str_replace("{ ] }", "]", $val);
        $val = str_replace(array("\n\r", "\r\n"), "\r", $val);
        $val = str_replace("\r", '&#13;', $val);
		return JOSC_utils::filter($val, true);
    }

//    function parseCodeElement($html)
//    {
//		if (preg_match_all('/\[code\](.+?)\[\/code\]/is', $html, $replacementI)) {
//			foreach($replacementI[0] as $val) $html = str_replace($val, $this->code_unprotect($val), $html);
//        }
//        $pattern = array();
//        $replacement = array();
//        $pattern[] = "/\[code\](.+?)\[\/code\]/is";
//        $replacement[] = "<div class='code'><div class='genmed'><b>" . _JOOMLACOMMENT_UBB_CODE . '</b></div><pre>\\1</pre></div>';
//        return preg_replace($pattern, $replacement, $html);
//    }

	function parseUBB_withlimit($html)
	{
		/* this is COMPLEX !! but works for all.
		 * the reason is to a correct maximum length calculation 
		 * and in case of cut: close all opened tags 
		 *
		 *  !!  if someone can find one day a better way to do it working in all cases !! you're welcome man !
		 *
		 * logic :
		 * 		1. preg_replace all  with special addeed tags
		 * 		2. use special addeed tags to split in an array and process wrap and max... 
		 * 
		 */
		$this->_splitTag 		= $splitTag = 'joscplit';
		$this->_limittextTag	= $limittextTag = 'limittext';
		$TO = $this->_TO;
		$TC = $this->_TC;

		$debug = false;
//		$debug = true;
		if ($debug) {
			/* set true for debug */
			$TO = $this->_TO = "(";
			$TC = $this->_TC = ")";
		}
		
//		$maxlength_word 		= $this->_maxlength_word;
//		$maxlength_line			= $this->_maxlength_line;
//		$maxlength_text 		= $this->_maxlength_text;
		$maxlength_word 		= ($this->_maxlength_word!=-1) ? $this->_maxlength_word : 999999;
		$maxlength_line			= ($this->_maxlength_line!=-1) ? $this->_maxlength_line : 999999;
		$maxlength_text 		= ($this->_maxlength_text!=-1) ? $this->_maxlength_text : 999999;
		$parsedText = $this->parseUBB_recurse( $html );

		/* get an array of tag and texts separated */
        $TxtTags = explode($splitTag, $parsedText);
		if (($nblines=count($TxtTags))<=1) {
			$parsedText = JOSC_utils::wrapText($parsedText, $maxlength_word, ' ');
			$parsedText = JOSC_utils::wrapText($parsedText, $maxlength_line, '<br />');
			$parsedText = JOSC_utils::setMaxLength($parsedText, $maxlength_text);
			return $parsedText;
		}
		//var_dump($TxtTags);

		$text=""; $lentext=0; 
		$push=""; /* will contain end tags pushed and not poped [/x][/y] */ 
// 		if (substr($parsedText,0,strlen($splitTag))==$splitTag && $TxtTags[0]) $is_tag = true;
//		else  $is_tag = false;
		
		for($i=0;$i<$nblines ;$i++)
        {
			$line = $TxtTags[$i];
//			if ($is_tag && substr($line,0,1)!=$TO)
//				$is_tag = false;

			/* < is not accepted in comment ->  we are sure it is from the preg_replace */
			$is_tag = ($tagpos=strpos($line, $TO))===false ? false : true;
				
			if ($is_tag) {
				if ($debug)	$text .= "<br />DEBUG_istag".$line."<br />";
				/* push tags , pop end tags */
				$tagkey	= substr($line, 0, $tagpos);  			 /* xxx<... */
				if ($tagkey) {
					$line	= substr_replace($line, '', 0, $tagpos);  /* xxx<... */
					if (substr($line,1,1)=='/') {
						if (!(($pos=strpos($push, $tagkey))===false))
               	    		/* pop */
               	    		$push = substr_replace($push,'',$pos-2,strlen($tagkey)+3);
					} else {
						/* push */
						$push = "$TO/$tagkey$TC".$push;
					}
				}
				$text .= $line;
				$is_tag = false;
			} else {
				/* wrap and limit length */
				if ($debug)	$text .= "<br />DEBUG_isnot".$line."<br />";
				if (!(strpos($line, $limittextTag)===false)) {
					$line = str_replace($limittextTag, '', $line); /* could find several if users has included 2 links together... */
					$line = JOSC_utils::setMaxLength($line, $maxlength_word);
				}
				$line = JOSC_utils::wrapText($line, $maxlength_word, '&nbsp;');
				$line = JOSC_utils::wrapText($line, $maxlength_line, '<br />');
				$diff     = $maxlength_text - $lentext;	/* remainder before (positive) */
				$lentext += strlen($line);
//				$diff2    = $maxlength_text - $lentext;	/* remainder after (negative if over) */
				if ($lentext<$maxlength_text)
					$text.=$line;
				else {
					$text.= JOSC_utils::setMaxLength($line, $diff);
					break;
				}
				$is_tag = true;
          }
        }
        $text .= $push;
		
		return $text;	
	}
		
	function parseUBB_recurse( $matches )
	{	
		
		$split 	 	= $this->_splitTag;
		$limittext 	= $this->_limittextTag;
		$TO 		= $this->_TO;
		$TC 		= $this->_TC;

		/* other recurse : #\[img]((?:[^[]|\[(?!/?img])|(?R))+)\[/img]#i*/
		/* ?: => group but do not capture 
		 * change ...)*)\[/x...  in   )+)\[/x  if empty inside not authorized 
		 * 
		 * always :  $split+start+$split    text       $split.end.$split  
		 */
        $patterns = array(
			'#\[b]((?:[^[]|\[(?!/?b])|(?R))*)\[/b]#i',
            '#\[u]((?:[^[]|\[(?!/?u])|(?R))*)\[/u]#i',
            '#\[i]((?:[^[]|\[(?!/?i])|(?R))*)\[/i]#i',
            
            '#\[url=(.*?)]((?:[^[]|\[(?!/?url])|(?R))*)\[/url]#i',
            '#\[url]((?:[^[]|\[(?!/?url])|(?R))*)\[/url]#i',
            
            '#\[email]((?:[^[]|\[(?!/?email])|(?R))*)\[/email]#i',
            '#\[email=(.*?)]((?:[^[]|\[(?!/?email])|(?R))*)\[/email]#i',
            
            '#\[font=(.*?)]((?:[^[]|\[(?!/?font])|(?R))*)\[/font]#i',
            '#\[size=(.*?)]((?:[^[]|\[(?!/?size])|(?R))*)\[/size]#i',
            '#\[color=(.*?)]((?:[^[]|\[(?!/?color])|(?R))*)\[/color]#i',
            
            '#\[quote]((?:[^[]|\[(?!/?quote])|(?R))*)\[/quote]#i',
            '#\[quote=(.*?)]((?:[^[]|\[(?!/?quote])|(?R))*)\[/quote]#i',
            
            '#\[code]((?:[^[]|\[(?!/?code])|(?R))*)\[/code]#i',
            
			'#\[img]((?:[^[]|\[(?!/?img])|(?R))*)\[/img]#i',
    		'#\[img=(.*?)]((?:[^[]|\[(?!/?img])|(?R))*)\[/img]#i',
            );

		/*
		 * replacements
		 */
		$replacements = array();

		if ($this->_support_UBBcode) {		
        $replacements[] = 	$split.'b'		.$TO.'b'.$TC.$split.'\\1'.																	$split.'b'.$TO.'/b'.$TC.$split;
		$replacements[] = 	$split.'u'		.$TO.'u'.$TC.$split.'\\1'.																	$split.'u'.$TO.'/u'.$TC.$split;
		$replacements[] = 	$split.'i'		.$TO.'i'.$TC.$split.'\\1'.																	$split.'i'.$TO.'/i'.$TC.$split;

			if ($this->_support_link) {
		$replacements[] = 	$split.'a'		.$TO.'a target=\'_blank\' rel=\'external nofollow\' href=\'\\1\' title=\'Visit \\1\''.$TC.$split.$limittext.'\\2'.	$split.'a'.$TO.'/a'.$TC.$split;
		$replacements[] = 	$split.'a'		.$TO.'a target=\'_blank\' rel=\'external nofollow\' href=\'\\1\' title=\'Visit \\1\''.$TC.$split.$limittext.'\\1'.	$split.'a'.$TO.'/a'.$TC.$split;

		$replacements[] = 	$split.'a'		.$TO.'a href=\'mailto:\\1\''.$TC.$split.$limittext.'\\1'.									$split.'a'.$TO.'/a'.$TC.$split;
		$replacements[] = 	$split.'a'		.$TO.'a href=\'mailto:\\1\''.$TC.$split.$limittext.'\\2'.									$split.'a'.$TO.'/a'.$TC.$split;
			} else {
		$replacements[] = 	$split.' link:\\1';	
		$replacements[] = 	$split.' link:\\1';	
		$replacements[] = 	$split.' \\1';	
		$replacements[] = 	$split.' \\1';	
			}

		$replacements[] = 	$split.'span'	.$TO.'span style=\'font-family: \\1\''.$TC.$split.'\\2'.										$split.'span'.$TO.'/span'.$TC.$split;
		$replacements[] = 	$split.'span'	.$TO.'span style=\'font-size: \\1\''.$TC.$split.'\\2'.											$split.'span'.$TO.'/span'.$TC.$split;			
			if ($this->_hide) {
		$replacements[] = 	$split.'\\2';
			} else {
		$replacements[] = 	$split.'span'.$TO.'span style=\'color: \\1\''.$TC.$split.'\\2'.$split.$TO.'/span'.$TC.$split;			
			}
		} else {
		$replacements[] = $split.' \\1';	
		$replacements[] = $split.' \\1';	
		$replacements[] = $split.' \\1';	

		$replacements[] = $split.' link:\\1';	
		$replacements[] = $split.' link:\\1';	

		$replacements[] = $split.' \\1';	
		$replacements[] = $split.' \\1';	

		$replacements[] = $split.' \\2';	
		$replacements[] = $split.' \\2';	
		$replacements[] = $split.' \\2';	
		}

		/*
		 * quotes 
		 */
//        $patterns = array("/\[quote\](.+?)\[\/quote\]/is",
//            "/\[quote=(.+?)\](.+?)\[\/quote\]/is");
//        $replacements = array("<div class='quote'><div class='genmed'><b>" . _JOOMLACOMMENT_UBB_QUOTE . "</b></div><div class='quotebody'>\\1</div></div>",
//            "<div class='quote'><div class='genmed'><b>\\1 " . _JOOMLACOMMENT_UBB_WROTE . "</b></div><div class='quotebody'>\\2</div></div>");
		if ($this->_support_quotecode) {
		
		$replacements[]	= 	 $split.'div'	.$TO.'div class=\'quote\''.$TC.	$split
							.$split.'div'	.$TO.'div class=\'genmed\''.$TC.$split
							.$split.'b'		.$TO.'b'.$TC.$split. _JOOMLACOMMENT_UBB_QUOTE 
							.$split.'b'		.$TO.'/b'.$TC.$split
							.$split.'div'	.$TO.'/div'.$TC.$split
							.$split.'div'	.$TO.'div class=\'quotebody\''.$TC.$split.'\\1'
							.$split.'div'	.$TO.'/div'.$TC.$split
							.$split.'div'	.$TO.'/div'.$TC.$split;

		$replacements[]	= 	 $split.'div'	.$TO.'div class=\'quote\''.$TC.	$split
							.$split.'div'	.$TO.'div class=\'genmed\''.$TC.$split
							.$split.'b'		.$TO.'b'.$TC.$split.'\\1 '._JOOMLACOMMENT_UBB_WROTE 
							.$split.'b'		.$TO.'/b'.$TC.$split
							.$split.'div'	.$TO.'/div'.$TC.$split
							.$split.'div'	.$TO.'div class=\'quotebody\''.$TC.$split.'\\2'
							.$split.'div'	.$TO.'/div'.$TC.$split
							.$split.'div'	.$TO.'/div'.$TC.$split;
		} else {
		$replacements[]	=	$split.' \\1';						
		$replacements[]	=	$split.' \\2';						
		}

		/*
		 * code
		 */
		if ($this->_support_quotecode) {
		$replacements[]	= 	 $split.'div'	.$TO.'div class=\'code\''.$TC.	$split
							.$split.'div'	.$TO.'div class=\'genmed\''.$TC.$split
							.$split.'b'		.$TO.'b'.$TC.$split. _JOOMLACOMMENT_UBB_CODE 
							.$split.'b'		.$TO.'/b'.$TC.$split
							.$split.'div'	.$TO.'/div'.$TC.$split
							.$split.'div'	.$TO.'div class=\'quotebody\''.$TC.$split
							.$split.'pre'	.$TO.'pre'.$TC.$split.'\\1'
							.$split.'pre'	.$TO.'/pre'.$TC.$split							
							.$split.'div'	.$TO.'/div'.$TC.$split
							.$split.'div'	.$TO.'/div'.$TC.$split;
		} else {
		$replacements[]	=	$split.' \\1';						
		}
		/*
		 * images
		 */
		if ($this->_support_pictures) {
			$maxwidthpictures = (int) $this->_pictures_maxwidth;
			if ($maxwidthpictures>0) {
				$divO = $split.'div'	.$TO.'div style=\'width:'.$maxwidthpictures.'px;overflow:hidden;\''.$TC.	$split;
				$divC = $split.'div'	.$TO.'/div'.$TC.$split;
			} else {
				$divO = $divC = '';
			}			
		$replacements[]	=	$divO. $split  		.$TO.'img src=\'\\1\' alt=\'Posted image\' /'.$TC.$split.$divC;
		$replacements[]	=	$divO. $split  		.$TO.'img src=\'\\1\' alt=\'Posted image\' /'.$TC.$split.$divC;
		} else {
			/* no image = link */
			if ($this->_support_link) {
		$replacements[]	=	$split.'a'		.$TO.'a target=\'_blank\' rel=\'external nofollow\' href=\'\\1\' title=\'Visit \\1\''.$TC.$split.$limittext.'\\1'.	$split.'a'.$TO.'/a'.$TC.$split;
		$replacements[]	=	$split.'a'		.$TO.'a target=\'_blank\' rel=\'external nofollow\' href=\'\\1\' title=\'Visit \\1\''.$TC.$split.$limittext.'\\2'.	$split.'a'.$TO.'/a'.$TC.$split;
			} else {
		$replacements[] = 	$split.' image:\\1';	
		$replacements[] = 	$split.' image:\\1';					
			}
		}

		 
		if (is_array($matches)) {
	        $html = preg_replace($patterns, $replacements, $matches[0]);
       		$matches = $html;	
		}
		
		return preg_replace_callback($patterns, array(&$this, 'parseUBB_recurse'), $matches );
	}

    function ubbcode_parse()
    {
        

		$html = $this->_comment;
		$html = $this->parseUBB_withlimit($html); //$this->parseUBB($html, $this->_hide);

        if ($this->_support_emoticons) $html = $this->parseEmoticons($html);
        //if ($this->_support_pictures) $html = $this->parseImgElement($html);
//        if ($this->_support_UBBcode) {
            //$html = $this->parseUBB($html, $this->_hide);
            //$html = $this->parseCodeElement($html);
            //$html = $this->parseQuoteElement($html);
//        }
        if ($this->_hide) $html = "<span class='hide'>$html</span>";
		return str_replace('&#13;', "\r", nl2br($html));
    }
}


class JOSC_form extends JOSC_support {
    var $_form;
    var $_captcha;
    var $_notify_users;
    var $_enter_website;
    var $_emoticon_wcount;
    var $_tname;
    var $_temail;
    var $_twebsite;
    var $_tnotify;
    var $_form_area_cols;

    function JOSC_form($value)
    {
        $this->_form = $value;
    }

    function setCaptcha($value)
    {
        $this->_captcha = $value;
    }

    function setNotifyUsers($value)
    {
        $this->_notify_users = $value;
    }

	function setEnterWebsite($value)
	{
		$this->_enter_website = $value;	
	}
	
    function setEmoticonWCount($value)
    {
        $this->_emoticon_wcount = $value;
    }

    function set_tname($value)
    {
        $this->_tname = $value;
    }

    function set_temail($value)
    {
        $user = JFactory::getUser();
        
        $this->_temail = ($user->id ? _JOOMLACOMMENT_AUTOMATICEMAIL : $value); /* change also modify - ajax_quote ! */
    }

    function set_twebsite($value)
    {
        $this->_twebsite = $value;
    }

    function set_tnotify($value)
    {
        $this->_tnotify = $value;
    }

    function setFormAreaCols($value)
    {
        $this->_form_area_cols = $value;
    }

    function onlyRegistered()
    {
        return '<div class="onlyregistered">' . _JOOMLACOMMENT_ONLYREGISTERED . '</div>';
    }

    function readOnly($username)
    {
        if ($username) return 'DISABLED';  /* TODO: use readonly="readonly" for XHTML reason ? */
        else return '';
    }

    function displayStyle($display)
    {
        return ($display) ? "" : "display:none;";
    }

    function emoticons($link=true)
    {
        if (!$this->_support_emoticons) return '';
        $html = "<div class='emoticoncontainer'>";
        $html .= "<div class='emoticonseparator'></div>";
        $index = 0;
        $icon_used = array();
        foreach ($GLOBALS["JOSC_emoticon"] as $ubb => $icon) {

            if (in_array($icon, $icon_used))
                continue;  /* ignore: avoid same icons twice ! */

            $icon_used[] = $icon;

            $html .= "<span class='emoticonseparator'>";
            $html .= "<span class='emoticon'>";
            $html .= $link ? "<a href='javascript:JOSC_emoticon(\"$ubb\")'>" : "";
            $html .= "<img src='$this->_emoticons_path/$icon' border='0' alt='$ubb' />";
            $html .= $link ? "</a>":"";
            $html .= "</span></span>";
            $index++;
            if ($index == $this->_emoticon_wcount) {
                $index = 0;
                $html .= "<div class='emoticonseparator'></div>";
            }
        }
        $html .= '</div>';
        return "<div>$html</div>";
    }

    function loadUBBIcons(&$ubbIconList, $absolute_path, $live_site)
    {
        require_once("$absolute_path/ubb_icons.php");
        foreach($ubbIcons as $name => $icon) {
            $ubbIconList[$name] = "$live_site/$icon";
        }
    }

    function UBBCodeButtons()
    {
        $absolute_path = "$this->_template_absolute_path/$this->_template_name/images";
        $live_site = "$this->_template_path/$this->_template_name/images";
        $ubbIconList = array();
        $this->loadUBBIcons($ubbIconList, "$this->_absolute_path/images", "$this->_live_site/images");
        if (file_exists("$absolute_path/ubb_icons.php")) $this->loadUBBIcons($ubbIconList, $absolute_path, $live_site);
        $html = "<a href='javascript:JOSC_insertUBBTag(\"b\")'><img src='" . $ubbIconList['bold'] . "' class='buttonBB' name='bb' alt='[b]' /></a>&nbsp;";
        $html .= "<a href='javascript:JOSC_insertUBBTag(\"i\")'><img src='" . $ubbIconList['italicize'] . "' class='buttonBB' name='bi' alt='[i]' /></a>&nbsp;";
        $html .= "<a href='javascript:JOSC_insertUBBTag(\"u\")'><img src='" . $ubbIconList['underline'] . "' class='buttonBB' name='bu' alt='[u]' /></a>&nbsp;";
        $html .= "<a href='javascript:JOSC_insertUBBTag(\"url\")'><img src='" . $ubbIconList['url'] . "' class='buttonBB' name='burl' alt='[url]' /></a>&nbsp;";
        $html .= "<a href='javascript:JOSC_insertUBBTag(\"quote\")'><img src='" . $ubbIconList['quote'] . "' class='buttonBB' name='bquote' alt='[quote]' /></a>&nbsp;";
        $html .= "<a href='javascript:JOSC_insertUBBTag(\"code\")'><img src='" . $ubbIconList['code'] . "' class='buttonBB' name='bcode' alt='[code]' /></a>&nbsp;";
        $html .= "<a href='javascript:JOSC_insertUBBTag(\"img\")'><img src='" . $ubbIconList['image'] . "' class='buttonBB' name='bimg' alt='[img]' /></a>&nbsp;";
        return $html;
    }

    function UBBCodeSelect()
    {
        $html = '';
        $html .= "<select name='menuColor' class='select' onchange='JOSC_fontColor()'>";
        $html .= "<option>-" . _JOOMLACOMMENT_COLOR . "-</option>";
        $html .= "<option>" . _JOOMLACOMMENT_AQUA . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_BLACK . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_BLUE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_FUCHSIA . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_GRAY . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_GREEN . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_LIME . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_MAROON . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_NAVY . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_OLIVE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_PURPLE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_RED . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_SILVER . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_TEAL . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_WHITE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_YELLOW . "</option>";
        $html .= "</select>&nbsp;";
        $html .= "<select name='menuSize' class='select' onchange='JOSC_fontSize()'>";
        $html .= "<option>-" . _JOOMLACOMMENT_SIZE . "-</option>";
        $html .= "<option>" . _JOOMLACOMMENT_TINY . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_SMALL . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_MEDIUM . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_LARGE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_HUGE . "</option>";
        $html .= "</select>";
        return $html;
    }

	function notifySelect() {    	
	    $html = '';
        $html .= "<select name='tnotify' class='inputbox'>";
//		$html .= "<option value='0' ".($this->_tnotify ?  "" : "checked")	." >" 	. _JOOMLACOMMENT_ENTERNOTIFY0 	. "</option>";
//		$html .= "<option value='1' ".($this->_tnotify ? "checked" : "")	." >" 	. _JOOMLACOMMENT_ENTERNOTIFY1	. "</option>";
		$html .= "<option value='0' ".($this->_tnotify ?  "" : "selected")	." >" 	. _JOOMLACOMMENT_ENTERNOTIFY0 	. "</option>";
		$html .= "<option value='1' ".($this->_tnotify ? "selected" : "")	." >" 	. _JOOMLACOMMENT_ENTERNOTIFY1	. "</option>";
        $html .= "</select>";
		return $html;
	}
	
    function form_htmlCode()
    {

		$user =& JFactory::getUser();
		$gid = $user->gid;
		$hidden = $this->formHiddenValues($this->_content_id, $this->_component, $this->_sectionid);
        
        if (!$user->username && $this->_only_registered) {
        	$html = $this->onlyRegistered();
        	/* needed informations but hidden : */
			$html .= "<form name='joomlacommentform' method='post' action='PHP_SELF'>";
        	$html .= $hidden;
	  		$html .= "<table class='buttoncontainer' style='display:none;' cellpadding='0' cellspacing='0'>";
        	$html .= "<tr>";
          	$html .= "<td><input type='button' class='button' name='bsend' value='{_SENDFORM}' onclick='JOSC_editPost(-1,-1)' /></td>";
          	$html .= "<td id='JOSC_busy'></td>";
        	$html .= "</tr>";
      		$html .= "</table>";
      		$html .= "</form>";
			return $html;
        }

		/*
		 * parse template block _form
		 */
        $html = $this->_form;

        /*
         * No blocks
         */
        $html = str_replace('{_WRITECOMMENT}', _JOOMLACOMMENT_WRITECOMMENT, $html);
        $html = str_replace('{self}', 'index.php', $html);
        $html = str_replace('{id}', $this->_content_id, $html);
        
//        $hidden  = JOSC_utils::inputHidden('content_id',$this->_content_id);
//        $hidden .= JOSC_utils::inputHidden('component',$this->_component);
//        $hidden .= JOSC_utils::inputHidden('joscsectionid',$this->_sectionid);
//		$hidden = $this->formHiddenValues($this->_content_id, $this->_component, $this->_sectionid);
        $html = str_replace('{_HIDDEN_VALUES}', $hidden, $html);

        $html = str_replace('{template_live_site}', $this->_template_path.'/'.$this->_template_name, $html);
		$html = str_replace('{formareacols}', $this->_form_area_cols, $html);
	        
        $html = str_replace('{_ENTERNAME}', _JOOMLACOMMENT_ENTERNAME, $html);
        $html = str_replace('{username}', $this->_tname, $html);
        $html = str_replace('{registered_readonly}', $this->readOnly($this->_tname), $html);

        $html = str_replace('{_ENTERTITLE}', _JOOMLACOMMENT_ENTERTITLE, $html);

        $html = str_replace('{_SENDFORM}', _JOOMLACOMMENT_SENDFORM, $html);
        /*
         * With blocks
         */
        /* {_UBBCODE} {UBBCodeButtons} {UBBCodeSelect}	*/
        $display	= $this->_support_UBBcode;
        $html 		= JOSC_utils::checkBlock('BLOCK-_UBBCODE', $display, $html);
        if ($display) {
            $UBBCodeButtons = $this->UBBCodeButtons();
            $UBBCodeSelect = $this->UBBCodeSelect();
            $html = str_replace('{_UBBCODE}', _JOOMLACOMMENT_UBBCODE, $html);
            $html = str_replace('{UBBCodeButtons}', $UBBCodeButtons, $html);
            $html = str_replace('{UBBCodeSelect}', $UBBCodeSelect, $html);            
        }
        
        /* {_CAPTCHATXT} {security_image}				*/
        $display	= $this->_captcha;
        $html 		= JOSC_utils::checkBlock('BLOCK-_CAPTCHATXT', $display, $html);
        if ($display) {
       		$html = str_replace('{_CAPTCHATXT}', _JOOMLACOMMENT_FORMVALIDATE_CAPTCHATXT, $html);
        	$html = str_replace('{security_image}', "<div id='captcha'>" . JOSC_security::insertCaptcha('security_refid') . '</div>', $html);
        }
        
        /* {_ENTEREMAIL} {email} {notifyselect}			*/
        $display	= true;
        $html 		= JOSC_utils::checkBlock('BLOCK-_ENTEREMAIL', $display, $html);
        if ($display) {
        	$html = str_replace('{_ENTEREMAIL}', _JOOMLACOMMENT_ENTEREMAIL, $html);
        	$html = str_replace('{email}', $this->_temail, $html);
        	$html = str_replace('{notifyselect}', $this->_notify_users ? $this->notifySelect():"", $html);
        }

		/* {_ENTERWEBSITE} {website} 					*/        
        $display	= $this->_enter_website;
        $html 		= JOSC_utils::checkBlock('BLOCK-_ENTERWEBSITE', $display, $html);
        if ($display) {
        	$html = str_replace('{_ENTERWEBSITE}', _JOOMLACOMMENT_ENTERWEBSITE, $html);
        	$html = str_replace('{website}', $this->_twebsite, $html);
        }

		/* {emoticons} 									*/        
        $display	= $this->emoticons();
        $html 		= JOSC_utils::checkBlock('BLOCK-emoticons', $display, $html);
        if ($display) {
	        $html = str_replace('{emoticons}', $display, $html);
        }
                
        return $html;
    }

}

class JOSC_search extends JOSC_support {
    var $_search;
    var $_keyword;
    var $_phrase;
    var $_counter;
    var $_resultTemplate;

    function JOSC_search($value,&$comObject)
    {
        $this->_search 		= $value;
        $this->JOSC_support($comObject);  
    }

    function setKeyword($value)
    {
        $this->_keyword = addslashes(trim($value));
    }

    function setPhrase($value)
    {
        $this->_phrase = $value;
    }

    function anonymous($name)
    {
        if ($name == '') $name = JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_ANONYMOUS));
        return $name;
    }

//    function decodeData_Charset($varName) {
//	/* 
//	 * javascript(ajax) encodeURI is only UTF-8. so we have to decode ajax send
//	 * should be solved with joomla 1.5 ! (native utf-8)
//	 */
//	 if ($this->_ajax)
//        return JOSC_utils::myiconv_decode( JOSC_utils::decodeData($varName), $this->_local_charset );
//     else
//     	return JOSC_utils::decodeData($varName);    	
//    }

    function filterAll($item)
    {
	 	return JOSC_board::filterAll($item);
//        $item['name'] 	= JOSC_utils::filter($this->encodeData_Charset($item['name']));
//        $item['title'] 	= JOSC_utils::filter($this->encodeData_Charset($item['title']));
//        $item['comment'] = JOSC_utils::filter($this->encodeData_Charset($item['comment']));
//        return $item;
    }
    
    function searchMatch()
    {
        $result = ($this->_counter == 1) ? JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_SEARCHMATCH)) : JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_SEARCHMATCHES));
        return sprintf($result, $this->_counter);
    }

    function trimResult($html, $word, $size)
    {
        $html = str_replace("\n", '', $html);
        if ($word == '') return '';
        $p = strpos($html, $word);
        if ($p == 0) return substr($html, 0, $size);
        $len = strlen($html);
        $sublen = strlen($word);
        $size = ($size - $sublen) / 2;
        if ($size >= $len) $result = $html;
        else {
            if ($p < $size) $a = $p-1;
            else $a = $size;
            $c = $len - ($p + $sublen);
            if ($c < $size) $b = $c;
            else $b = $size;
            $b = $a + $b + $sublen;
            $result = substr($html, $p - $a, $b);
        }
        return $result;
    }

    function highlightWord($html, $maxSize = -1)
    {
        $html = stripslashes($html);
        if (($this->_phrase == 'any') Or ($this->_phrase == 'all')) {
            $words = split(' ', $this->_keyword);
            if ($maxSize != -1) $html = $this->trimResult($html, $words[0], $maxSize);
            foreach($words as $item) {
                if ($item != '')
                    $html = str_ireplace($item, "<span>$item</span>", $html);
            }
            return $html;
        } else {
            if ($maxSize != -1) $html = $this->trimResult($html, $this->_keyword, $maxSize);
            return str_ireplace($this->_keyword, "<span>$this->_keyword</span>", stripslashes($html));
        }
    }

    function addItem($item, $itemCSS)
    {
        $comment = $this->censorText($item['comment']);
        $title = $this->censorText($this->highlightWord($item['title']));
        $name = $this->censorText($this->highlightWord($this->anonymous($item['name'])));
//        $address = 'javascript:JOSC_goToPost(' . $item['contentid'] . ',' . $item['id'] . ')';
		$address = $this->_comObject->linkToContent($item['contentid'], $item['id']);

        $maxsize = min(200, $this->_maxlength_text);
		$comment = JOSC_utils::wrapText($comment, $this->_maxlength_word, '&nbsp;');
		$comment = JOSC_utils::wrapText($comment, $this->_maxlength_line, '<br />');
        if ($maxsize != 0 && strlen($comment) > $maxsize)
            $comment = '...' . $this->highlightWord($comment, $maxsize) . '...';
        else $comment = $this->highlightWord($comment);
        $html = $this->_resultTemplate;
        $html = str_replace('{postclass}', 'sectiontableentry' . $itemCSS, $html);
        $html = str_replace('{title}', "<b>$title</b>", $html);
        $html = str_replace('{_JOOMLACOMMENT_BY}', JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_BY)), $html);
        $html = str_replace('{name}', $name, $html);
        $html = str_replace('{address}', $address, $html);
        $html = str_replace('{preview}', $comment, $html);
        $html = str_replace('{date}', JOSC_utils::getLocalDate($item['date'],$this->_date_format), $html);//date($this->_date_format, strToTime($item['date'])), $html);
        return $html;
    }

    function find($terms)
    {
		$database =& JFactory::getDBO();		

        /* TODO : search for all only if .... */ 
        $database->setQuery("SELECT * FROM #__comment WHERE component='$this->_component' AND ( $terms ) ORDER BY date DESC");
        $data = $database->loadAssocList();
        $html = '';
        $itemCSS = 1;
        $this->_counter = 0;
        if ($data == null) return '';
        foreach($data as $item) {
            $item = $this->filterAll($item);
            $html .= $this->addItem($item, $itemCSS);
            $this->_counter++;
            $itemCSS++;
            if ($itemCSS == 3) $itemCSS = 1;
        }
        return $html;
    }

    function terms($list, $term)
    {
        $result = '';
        foreach($list as $item) {
            if ($result != '') $result .= ' OR ';
            $result .= $item . " $term ";
        }
        return $result;
    }

    function anyWords($list)
    {
        $result = '';
        if (!strpos($this->_keyword, ' ')) return $this->terms($list, "LIKE '%$this->_keyword%'");
        $words = split(' ', $this->_keyword);
        foreach($words as $item) {
            if ($item != '') {
                if ($result != '') $result .= ' OR ';
                $result .= $this->terms($list, "LIKE '%$item%'");
            }
        }
        return $result;
    }

    function allWords($list)
    {
        $result = '';
        if (!strpos($this->_keyword, ' ')) return $this->terms($list, "LIKE '%$this->_keyword%'");
        $words = split(' ', $this->_keyword);
        foreach($words as $item) {
            if ($item != '') {
                if ($result != '') $result .= ' AND ';
                $result .= '(' . $this->terms($list, "LIKE '%$item%'") . ')';
            }
        }
        return $result;
    }

    function exactPhrase($list)
    {
        return $this->terms($list, "LIKE '%$this->_keyword%'");
    }

    function search_htmlCode()
    {
        $html = $this->_search;        
        if ($this->_keyword) {
            $list[] = 'name';
            $list[] = 'title';
            $list[] = 'comment';
            if ($this->_phrase == 'any') $terms = $this->anyWords($list);
            if ($this->_phrase == 'all') $terms = $this->allWords($list);
            if ($this->_phrase == 'exact') $terms = $this->exactPhrase($list);
            $this->_resultTemplate = JOSC_utils::block($html, 'searchresult');
            $results = $this->find($terms);
        } else $results = '';
        $html = str_replace('{resulttitle}', ($results) ? $this->searchMatch() : JOSC_utils::filter($this->encodeData_Charset(_JOOMLACOMMENT_NOSEARCHMATCH)), $html);
        $html = JOSC_utils::ignoreBlock($html, 'searchresult', true, $results);


        return $html;
    }
}

?>
