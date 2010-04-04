<?php defined('_JEXEC')  or die('Direct Access to this location is not allowed.');

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

class JOSC_config {
    var $_configFile;
    var $_contentFile;
    var $_comObject=null;
    var $tabs;
    var $_set_id=-1; /* -1 = problem / 0 = new / >0 = normal */
    var $_set_name;
    var $_set_component=''; /* empty means also com_content */
    var $_set_sectionid=0;
/*  params */    
    var $_complete_uninstall;
    var $_mambot_func;
    var $_ajax;
	var $_local_charset;
    var $_only_registered;
    var $_language;
    var $_admin_language;
    var $_moderator;
    var $_inexclude_sc;
    var $_exclude_sections;
    var $_exclude_categories;
    var $_exclude_contentitems;
    var $_exclude_contentids;
    var $_template;
    var $_template_css;
    var $_template_custom;
    var $_template_custom_css;
    var $_template_custom_path;
    var $_template_custom_livepath;
    var $_template_modify;
    var $_template_library;
    var $_form_area_cols;
    var $_emoticon_pack;
    var $_emoticon_wcount;
    var $_tree;
	var $_mlink_post;    
    var $_tree_indent;
    var $_sort_downward;
    var $_display_num;
    var $_support_profiles;
    var $_support_avatars;
    var $_support_emoticons;
    var $_support_UBBcode;
    var $_enter_website;
    var $_support_pictures;
    var $_pictures_maxwidth;
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
    var $_notify_users;
    var $_rss;
    var $_date_format;
    var $_no_search;
    var $_captcha;
    var $_captcha_usertypes;
    var $_website_registered;
    var $_autopublish;
    var $_ban;
    var $_maxlength_text;
    var $_maxlength_word;
    var $_maxlength_line;
    var $_show_readon;
    var $_menu_readon;
    var $_debug_username;
    var $_xmlerroralert;
    var $_ajaxdebug;

	/*
	 * init  set_id, name, component, sectionid
	 */
    function JOSC_config( $set_id, &$comObject )
    {

	$database =& JFactory::getDBO();


        $path = JPATH_SITE.DS.'components'.DS.'com_comment'.DS.'joscomment';

        require_once($path.DS.'utils.php'); /* JOSC_get_com_name */

        JOSC_utils::set_JoomlaRelease();

        $this->_configFile = JPATH_SITE.DS.'administrator/components/com_comment/defaultconfig.comment.php';

        $component = $comObject != null ? $comObject->_component : '';
        $sectionid = $comObject != null ? $comObject->_sectionid : 0;
        $this->_comObject = $comObject;

                /*
                 * search on set_id   if set
                 * else
                 * 		search on component with sectionid DESC
                 * 				(to get 0 sectionid if no line exist for the specified)
                 */
        $whereid = $set_id ? " id=$set_id " : "";
        $query 	= "SELECT * FROM #__comment_setting "
        . "\n  WHERE ".($whereid ? $whereid : "set_component='$component' AND ( set_sectionid='$sectionid' OR set_sectionid = '0' ) ")
        . "\n  ORDER BY set_component ASC,set_sectionid DESC LIMIT 1"
        ;
        $database->setQuery( $query );

        $rows = $database->loadObjectList();
//	var_dump($rows);
        if (!$rows) {

                        /* not found is not accepted if other than com_content */
            if ($set_id > 1 || $component) {
                $this->_set_id = -1;
		
            } else {
		
                $this->_set_id = 1;  /* it is possible to have no line in case of new install or upgrade <3.1.0) */
                $this->_set_name = "Content Items";
            }
        } else {
	    
            $this->_set_id = $rows[0]->id;
            $this->_set_name = ($this->_set_id==1 && !$rows[0]->set_name) ? "Content Items" : $rows[0]->set_name;
            $this->_set_component = $rows[0]->set_component;
            $this->_set_sectionid = $rows[0]->set_sectionid;
        }

        if ($this->_set_id >= 0 && $this->_comObject == null) {
	    
            $row=null;
	    
            $this->_comObject = JOSC_utils::ComPluginObject($this->_set_component, $row, $this->_set_id);
        }
    }

    /*
     * set new Config (to add new)
     */
    function newConfig()
    {
        $this->_set_id = 0;
    }
    
    /*
     * load config line  and include adminlanguage
     *
     */
    function load()
    {
        global  $mosConfig_language, $iso_client_lang;

        $database =& JFactory::getDBO();

        if ($this->_set_id == -1) return false;
        //		echo $this->_configFile;
        require($this->_configFile);
                /*
                 * october 2007 : store in database and not in file.
                 * to avoid many modifications with regression risk, this time
                 * we keep the config logic of setting in variables
                 */

        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comment'.DS.'tables');
        $row =& JTable::getInstance('Setting', 'Table');

        $row->load($this->_set_id);
        $params = new JParameter($row->params );

        /*
         * 'def' is doing 'get' with default value if not found.
         */
        $this->_complete_uninstall 		= $params->def( '_complete_uninstall'	, $_complete_uninstall );
        $this->_mambot_func 			= $params->def( '_mambot_func'			, $_mambot_func );
        $this->_ajax 					= $params->def( '_ajax'					, $_ajax );
        $this->_local_charset 			= $params->def( '_local_charset'		, $_local_charset );
        $this->check_local_charset();
        $this->_only_registered 		= $params->def( '_only_registered'		, $_only_registered );
        $this->_language				= $params->def( '_language'				, $_language );
        $this->_admin_language 			= $params->def( '_admin_language'		, $_admin_language );
        $this->_moderator 				= $params->def( '_moderator'			, $_moderator);
        $this->_include_sc				= $params->def( '_include_sc'			, $_include_sc);
        $this->_exclude_sections 		= $params->def( '_exclude_sections'		, $_exclude_sections);
        $this->_exclude_categories 		= $params->def( '_exclude_categories'	, $_exclude_categories);
        $this->_exclude_contentitems	= $params->def( '_exclude_contentitems'	, $_exclude_contentitems);
        $this->_exclude_contentids 		= $params->def( '_exclude_contentids'	, $_exclude_contentids);
        $this->_template 				= $params->def( '_template'				, $_template);
        $this->_template_css 			= $params->def( '_template_css'			, $_template_css);
        $this->_copy_template			= $params->def( '_copy_template'		, $_copy_template);
        $this->_template_custom			= $params->def( '_template_custom'		, $_template_custom);
        $this->_template_custom_css		= $params->def( '_template_custom_css'	, $_template_custom_css);
        $this->setTemplateCustomPath();  /* custom_path , custom_livepath*/
        $this->_template_modify			= $params->def( '_template_modify'		, $_template_modify);
        $this->_template_library		= $params->def( '_template_library'		, $_template_library);
        $this->_form_area_cols			= $params->def( '_form_area_cols'		, $_form_area_cols);
        $this->_emoticon_pack 			= $params->def( '_emoticon_pack'		, $_emoticon_pack);
        $this->_emoticon_wcount 		= $params->def( '_emoticon_wcount'		, $_emoticon_wcount);
        $this->_tree 					= $params->def( '_tree'					, $_tree);
        $this->_mlink_post 				= $params->def( '_mlink_post'			, $_mlink_post);
        $this->_tree_indent 			= $params->def( '_tree_indent'			, $_tree_indent);
        $this->_sort_downward 			= $params->def( '_sort_downward'		, $_sort_downward);
        $this->_display_num 			= $params->def( '_display_num'			, $_display_num);
        $this->_support_profiles 		= $params->def( '_support_profiles'		, $_support_profiles);
        $this->_support_avatars 		= $params->def( '_support_avatars'		, $_support_avatars);
        $this->_support_emoticons 		= $params->def( '_support_emoticons'	, $_support_emoticons);
        $this->_enter_website 			= $params->def( '_enter_website'		, $_enter_website);
        $this->_support_UBBcode 		= $params->def( '_support_UBBcode'		, $_support_UBBcode);
        $this->_support_pictures 		= $params->def( '_support_pictures'		, $_support_pictures);
        $this->_pictures_maxwidth 		= $params->def( '_pictures_maxwidth'	, $_pictures_maxwidth);
        $this->_censorship_enable 		= $params->def( '_censorship_enable'	, $_censorship_enable);
        $this->_censorship_case_sensitive = $params->def( '_censorship_case_sensitive'	, $_censorship_case_sensitive);
        $this->_censorship_words 		= $params->def( '_censorship_words'		, $_censorship_words);
        $this->_censorship_usertypes 	= $params->def( '_censorship_usertypes'	, $_censorship_usertypes);
        $this->_IP_visible 				= $params->def( '_IP_visible'			, $_IP_visible);
        $this->_IP_partial 				= $params->def( '_IP_partial'			, $_IP_partial);
        $this->_IP_caption 				= $params->def( '_IP_caption'			, $_IP_caption);
        $this->_IP_usertypes 			= $params->def( '_IP_usertypes'			, $_IP_usertypes);
        $this->_preview_visible 		= $params->def( '_preview_visible'		, $_preview_visible);
        $this->_preview_length 			= $params->def( '_preview_length'		, $_preview_length);
        $this->_preview_lines 			= $params->def( '_preview_lines'		, $_preview_lines);
        $this->_voting_visible 			= $params->def( '_voting_visible'		, $_voting_visible);
        $this->_use_name 				= $params->def( '_use_name'				, $_use_name);
        $this->_notify_admin 			= $params->def( '_notify_admin'			, $_notify_admin);
        $this->_notify_email 			= $params->def( '_notify_email'			, $_notify_email);
        $this->_notify_moderator		= $params->def( '_notify_moderator'		, $_notify_moderator);
        $this->_notify_users 			= $params->def( '_notify_users'			, $_notify_users);
        $this->_rss 					= $params->def( '_rss'					, $_rss);
        $this->_date_format 			= $params->def( '_date_format'			, $_date_format);
        $this->_no_search	 			= $params->def( '_no_search'			, $_no_search);
        $this->_captcha 				= $params->def( '_captcha'				, $_captcha);
        $this->_captcha_usertypes 		= $params->def( '_captcha_usertypes'	, $_captcha_usertypes);
        $this->_website_registered 		= $params->def( '_website_registered'	, $_website_registered);
        $this->_autopublish 			= $params->def( '_autopublish'			, $_autopublish);
        $this->_ban 					= $params->def( '_ban'					, $_ban);
        $this->_maxlength_text 			= $params->def( '_maxlength_text'		, $_maxlength_text);
        $this->_maxlength_word 			= $params->def( '_maxlength_word'		, $_maxlength_word);
        $this->_maxlength_line 			= $params->def( '_maxlength_line'		, $_maxlength_line);
        $this->_show_readon 			= $params->def( '_show_readon'			, $_show_readon);
        $this->_intro_only 				= $params->def( '_intro_only'			, $_intro_only);
        $this->_menu_readon 			= $params->def( '_menu_readon'			, $_menu_readon);

                /* technical default parameters */
        $this->_debug_username				= $params->def( '_debug_username'	, $_debug_username);
        $this->_xmlerroralert			= $params->def( '_xmlerroralert'		, $_getxmlerroralert);
        $this->_ajaxdebug				= $params->def( '_ajaxdebug'			, $_ajaxdebug);

        JOSC_utils::set_charsetConstant($this->_local_charset);
        JOSC_utils::loadAdminLoadLanguage($this->_admin_language);
        JOSC_utils::loadFrontendLoadLanguage($this->_language); /* frontend for common parts */

        return true;
    }
       
    function check_local_charset() {
        if (!$this->_local_charset) {
		$document =& JFactory::getDocument();
		$charset =  $document->getCharset();
         	$iso = split( '=', $charset );
        	$this->_local_charset = $iso[0];
        }
    }

    /*
     * function to save the configuration
     */
    function save($option, $task, $apply=false)
    {
        global $mainframe;
        $database =& JFactory::getDBO();

        $expert  = (strpos($task, 'expert')===false) ? "" : "expert";
        $simple  = (strpos($task, 'simple')===false) ? "" : "simple";

                /* simple parameters */
        $params = JRequest::getVar('params', '', $_POST);

                /* arrays */
        $params['_moderator'] = implode( ',', JOSC_library::JOSCGetArrayInts('_moderator'));
        $params['_exclude_sections'] = implode( ',', JOSC_library::JOSCGetArrayInts('_exclude_sections') );
        $params['_exclude_categories'] = implode( ',', JOSC_library::JOSCGetArrayInts('_exclude_categories') );
        $params['_IP_usertypes'] = implode( ',', JOSC_library::JOSCGetArrayInts('_IP_usertypes') );
        $params['_captcha_usertypes'] = implode( ',', JOSC_library::JOSCGetArrayInts('_captcha_usertypes') );
        $params['_censorship_usertypes'] = implode( ',', JOSC_library::JOSCGetArrayInts('_censorship_usertypes') );
                /* dependant parameters */
        $params['_template_css'] = $params['_template_css'.$params['_template']];
        $params['_template_custom_css']	= $params['_template_custom_css'.$params['_template_custom']];

        $set_id = JRequest::getVar('set_id', '', $_POST);
        $component = JRequest::getVar('set_component','', $_POST);
        $sectionid = JRequest::getVar('set_sectionid', 0, $_POST );

        if ($set_id!=1 && !$component && $sectionid==0) {
            if ($expert)
            echo "<script> alert('Select at least a section or another component'); window.history.go(-1); </script>\n";
            else
            echo "<script> alert('Select another component'); window.history.go(-1); </script>\n";        	exit();
        }

        if (is_array( $params )) {
            $txt = array();
            foreach ($params as $k=>$v) {
                $txt[] = "$k=$v";
            }
            $_POST['params'] = $this->textareaHandling($txt);
        }
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comment'.DS.'tables');
        $row =& JTable::getInstance('Setting', 'Table');
        $row->load($set_id);
        if ($row->params == null) {
            $row->id = 0;
        }
        if (!$row->bind($_POST)) {
            echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
            exit();
        }

        if (!$row->store()) {
            echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
            exit();
        }

        //    	$row->updateOrder("contentid='$row->contentid'");

        /* Save CURRENT TEMPLATE_CUSTOM HTML AND CSS */
        if ($params['_template_modify']) {

            $this->setTemplateCustomPath(); /* set custom path and make copy if param set... */

            $templateHTML 		= strval( JRequest::getVar('joscTemplateHTML', '', $_POST ) );
            $templateHTMLcontent 	= JRequest::getVar( 'joscTemplateHTMLcontent', '', $_POST);
            $enable_writeHTML 	= JRequest::getVar('enable_writeHTML',0,$_POST);
            $disable_writeHTML 	= JRequest::getVar('disable_writeHTML',0, $_POST);

            if ($templateHTML == $params['_template_custom'] 	 && $templateHTMLcontent) {
                $msg = "";
                if (!$this->saveTemplateHTMLSource( $templateHTML, $templateHTMLcontent, $enable_writeHTML, $disable_writeHTML, $msg )) {
                    $msg = " Template not saved: " . $msg;
                }
            }


            $templateCSS		= strval( JRequest::getVar('joscTemplateCSS', '',$_POST ) );
            $templateCSSCSS		= strval( JRequest::getVar('joscTemplateCSSCSS', '',$_POST ) );
            $templateCSScontent 	= JRequest::getVar(  'joscTemplateCSScontent', '', $_POST );
            $enable_writeCSS 	= JRequest::getVar('enable_writeCSS',0, $_POST);
            $disable_writeCSS 	=JRequest::getVar('disable_writeCSS',0,$_POST);

            if ($templateCSS == $params['_template_custom'] && $templateCSSCSS == $params['_template_custom_css']	 && $templateCSScontent) {
                $msg = "";
                if (!$this->saveTemplateCSSSource( $templateCSS, $templateCSSCSS, $templateCSScontent, $enable_writeCSS, $disable_writeCSS, $msg )) {
                    $msg = " Template not saved: " . $msg;
                }
            }
        }

        if ($apply) {
            $link = JRoute::_('index.php?option=com_comment&task=settingsedit'.$expert.$simple.'&id='.$row->id, false);
        } else {
            $link = JRoute::_("index.php?option=com_comment&task=settings$expert", false);
        }

        if ($option)
        {
            $mainframe->redirect($link, 'Settings saved. '.$msg );
        } 
        else
        {
            return true;
        }
    }
/* transform the parameters to a format that we can save in the param field
* in the database
*/
    function textareaHandling($txt) {
        $total = count( $txt );
        for( $i=0; $i < $total; $i++ ) {
            if ( strstr( $txt[$i], "\n" ) ) {
                $txt[$i] = str_replace( "\n", '<br />', $txt[$i] );
            }
        }
        $txt = implode( "\n", $txt );
        return $txt;
    }

    function saveTemplateHTMLSource( $template, $filecontent, $enable_write=0, $disable_write=0, &$msg ) {
	if ( !$template ) {
		$msg = '<b>Operation failed: No template specified.</b>';
		return false;
	}
	if ( !$filecontent ) {
		$msg = '<b>Operation failed: Content empty.</b>';
                return false;
	}
	$file = $this->_template_custom_path. "/" . $template .'/index.html';

	$oldperms 	= fileperms($file);
	
	if ($enable_write) @chmod($file, $oldperms | 0222);

	clearstatcache(); /* ????????????????????? */
	if ( is_writable( $file ) == false ) {
		$msg = '<b>Operation failed: '. $file .' is not writable.</b>';
                return false;
	}

	if ( $fp = fopen ($file, 'w' ) ) {
		fputs( $fp, stripslashes( $filecontent ), strlen( $filecontent ) );
		fclose( $fp );

		if ($enable_write) {
			@chmod($file, $oldperms);
		} else {
			if ($disable_write)
				@chmod($file, $oldperms & 0777555);
		}
		return true;
	} else {
		if ($enable_write) @chmod($file, $oldperms);
		$msg = '<b>Operation failed: Failed to open file for writing.</b>';
		return false;
	}

    }
    
    function saveTemplateCSSSource( $template, $templateCSS, $filecontent, $enable_write=0, $disable_write=0, &$msg ) {
	
	if ( !$template || !$templateCSS ) {
		$msg = '<b>Operation failed: No CSS specified.</b>';
		return false;
	}
	if ( !$filecontent ) {
		$msg = '<b>Operation failed: Content empty.</b>';
                return false;
	}
//	$file = JPATH_SITE."/components/com_comment/joscomment/templates/". $template .'/css/template_css.css';
	$file = $this->_template_custom_path. "/" . $template .'/css/'. $templateCSS;

	$oldperms 	= fileperms($file);
	
	if ($enable_write) @chmod($file, $oldperms | 0222);

	clearstatcache(); /* clean PHP file cache */
	if ( is_writable( $file ) == false ) {
		$msg = '<b>Operation failed: '. $file .' is not writable.</b>';
                return false;
	}

	if ( $fp = fopen ($file, 'w' ) ) {
		fputs( $fp, stripslashes( $filecontent ), strlen( $filecontent ) );
		fclose( $fp );

		if ($enable_write) {
			@chmod($file, $oldperms);
		} else {
			if ($disable_write)
				@chmod($file, $oldperms & 0777555);
		}
		return true;
	} else {
		if ($enable_write) @chmod($file, $oldperms);
		$msg = '<b>Operation failed: Failed to open file for writing.</b>';
		return false;
	}

    }
	
    function generalPage()
    {
        echo $this->tabs->startPanel(_JOOMLACOMMENT_ADMIN_TAB_GENERAL_PAGE, "General-page");
        $rows = new JOSC_tabRows();

        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_BASIC_SETTINGS);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_complete_uninstall_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_complete_uninstall]', 'class="inputbox"', $this->_complete_uninstall);
        $row->help 		= _JOOMLACOMMENT_ADMIN_complete_uninstall_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_mambot_func_CAPTION;
        $row->component = JOSC_library::input('params[_mambot_func]', 'class="inputbox"', $this->_mambot_func);
        $row->help 		= _JOOMLACOMMENT_ADMIN_mambot_func_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_language_CAPTION;
        $languagelist	= JOSC_library::languageList(JPATH_SITE."/components/com_comment/joscomment/language/");
        $row->component = JHTML::_('select.genericlist',$languagelist, 'params[_language]', 'class="inputbox"', 'value', 'text', $this->_language);
        $row->help 		= _JOOMLACOMMENT_ADMIN_language_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_admin_language_CAPTION;
        $languagelist	= JOSC_library::languageList(JPATH_SITE."/administrator/components/com_comment/admin_language/");
        $row->component = JHTML::_('select.genericlist',$languagelist, 'params[_admin_language]', 'class="inputbox"', 'value', 'text', $this->_admin_language);
        $row->help 		= _JOOMLACOMMENT_ADMIN_admin_language_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_local_charset_CAPTION;
        $row->component = JOSC_library::input('params[_local_charset]', 'class="inputbox"', $this->_local_charset);
        if (!function_exists("iconv"))
        	$row->help 	.= _JOOMLACOMMENT_ADMIN_local_charset_HELPnoiconv;
		else        	
	 		$row->help 	.= _JOOMLACOMMENT_ADMIN_local_charset_HELPiconv;
        $rows->addRow($row);        

        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_SECTIONS_CATEGORIES);
		$row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_include_sc_CAPTION;        
        //$row->component = JHTML::_('select.booleanlist',  'params[_include_sc]', 'class="inputbox"', $this->_include_sc);
        $row->component = JOSC_library::customRadioList('params[_include_sc]', 'class="inputbox"', $this->_include_sc, _JOOMLACOMMENT_ADMIN_INCLUDE, _JOOMLACOMMENT_ADMIN_EXCLUDE);
        $row->help 		= _JOOMLACOMMENT_ADMIN_include_sc_HELP;
		$rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_exclude_contentitems_CAPTION;
        $row->component = JOSC_library::input('params[_exclude_contentitems]', 'class="inputbox"', $this->_exclude_contentitems);
        $row->help 		= _JOOMLACOMMENT_ADMIN_exclude_contentitems_HELP;
        $rows->addRow($row);
		$row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_exclude_sections_CAPTION;
        $sectionlist 	=  $this->_comObject->getSectionsIdOption();
        $selected 		= JOSC_library::GetIntsMakeOption(split(',', $this->_exclude_sections));
        $row->component = JHTML::_('select.genericlist',$sectionlist, '_exclude_sections[]', 'class="inputbox" multiple="multiple"', 'id', 'title', $selected); 
        $row->help 		= _JOOMLACOMMENT_ADMIN_exclude_sections_HELP;
		$rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_exclude_categories_CAPTION;
        $categorylist 	=  $this->_comObject->getCategoriesIdOption();
        $selected 		= JOSC_library::GetIntsMakeOption(split(',', $this->_exclude_categories));       
        $row->component = JHTML::_('select.genericlist',$categorylist, '_exclude_categories[]', 'class="inputbox"  multiple="multiple"', 'id', 'title', $selected); 
        $row->help 		= _JOOMLACOMMENT_ADMIN_exclude_categories_HELP;
        $rows->addRow($row);
                
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_CONTENT_ITEM);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_exclude_contentids_CAPTION;
        $row->component = JOSC_library::input('params[_exclude_contentids]', 'class="inputbox"', $this->_exclude_contentids);
        $row->help 		= _JOOMLACOMMENT_ADMIN_exclude_contentids_HELP;
        $rows->addRow($row);

        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_TECHNICAL);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_debug_username_CAPTION;
        $row->component = JOSC_library::input('params[_debug_username]', 'class="inputbox"', $this->_debug_username);
        $row->help 		= _JOOMLACOMMENT_ADMIN_debug_username_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_xmlerroralert_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_xmlerroralert]', 'class="inputbox"', $this->_xmlerroralert);
        $row->help 		= _JOOMLACOMMENT_ADMIN_xmlerroralert_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_ajaxdebug_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_ajaxdebug]', 'class="inputbox"', $this->_ajaxdebug);
        $row->help 		= _JOOMLACOMMENT_ADMIN_ajaxdebug_HELP;
        $rows->addRow($row);

        echo $rows->tabRows_htmlCode();
        echo $this->tabs->endPanel();
    }

    function postingPage()
    {
        echo $this->tabs->startPanel(_JOOMLACOMMENT_ADMIN_TAB_POSTING, "Posting-page");
        $rows = new JOSC_tabRows();

        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_BASIC_SETTINGS);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_ajax_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_ajax]', 'class="inputbox"', $this->_ajax);
        $row->help 		= _JOOMLACOMMENT_ADMIN_ajax_HELP;
        $rows->addRow($row);
        
		/*
		 * STRUCTURE
		 */
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_STRUCTURE);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_tree_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_tree]', 'class="inputbox"', $this->_tree);
        $row->help 		= _JOOMLACOMMENT_ADMIN_tree_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_mlink_post_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_mlink_post]', 'class="inputbox"', $this->_mlink_post);
        $row->help 		= _JOOMLACOMMENT_ADMIN_mlink_post_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_tree_indent_CAPTION;
        $row->component = JOSC_library::input('params[_tree_indent]', 'class="inputbox"', $this->_tree_indent);
        $row->help 		= _JOOMLACOMMENT_ADMIN_tree_indent_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_sort_downward_CAPTION;
        $sorting 		= array();
        $sorting[] 		= JHTML::_('select.option','1', _JOOMLACOMMENT_ADMIN_sort_downward_VALUE_FIRST);
        $sorting[] 		= JHTML::_('select.option','0', _JOOMLACOMMENT_ADMIN_sort_downward_VALUE_LAST);
        //$rowSorting->id = 'sorting';
        $row->component = JHTML::_('select.genericlist',$sorting, 'params[_sort_downward]', 'class="inputbox"', 'value', 'text', $this->_sort_downward);
        $row->help 		= _JOOMLACOMMENT_ADMIN_sort_downward_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_display_num_CAPTION;
        $row->component = JOSC_library::input('params[_display_num]', 'class="inputbox"', $this->_display_num);
        $row->help 		= _JOOMLACOMMENT_ADMIN_display_num_HELP;
        $rows->addRow($row);
        
		/*
		 * POSTING
		 */
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_POSTING);        
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_enter_website_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_enter_website]', 'class="inputbox"', $this->_enter_website);
        $row->help 		= _JOOMLACOMMENT_ADMIN_enter_website_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_support_UBBcode_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_support_UBBcode]', 'class="inputbox"', $this->_support_UBBcode);
        $row->help 		= _JOOMLACOMMENT_ADMIN_support_UBBcode_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_support_pictures_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_support_pictures]', 'class="inputbox"', $this->_support_pictures);
        $row->help 		= _JOOMLACOMMENT_ADMIN_support_pictures_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_pictures_maxwidth_CAPTION;
        $row->component = JOSC_library::input('params[_pictures_maxwidth]', 'class="inputbox"', $this->_pictures_maxwidth);
        $row->help 		= _JOOMLACOMMENT_ADMIN_pictures_maxwidth_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_voting_visible_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_voting_visible]', 'class="inputbox"', $this->_voting_visible);
        $row->help 		= _JOOMLACOMMENT_ADMIN_voting_visible_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_use_name_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_use_name]', 'class="inputbox"', $this->_use_name);
        $row->help 		= _JOOMLACOMMENT_ADMIN_use_name_HELP;
		$rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_support_profiles_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_support_profiles]', 'class="inputbox"', $this->_support_profiles);
        $row->help 		= _JOOMLACOMMENT_ADMIN_support_profiles_HELP;
		$rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_support_avatars_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_support_avatars]', 'class="inputbox"', $this->_support_avatars);
        $row->help 		= _JOOMLACOMMENT_ADMIN_support_avatars_HELP;
		$rows->addRow($row);
        $row = new JOSC_tabRow();
        $row->caption = _JOOMLACOMMENT_ADMIN_date_format_CAPTION;
        $row->component = JOSC_library::input('params[_date_format]', 'class="inputbox"', $this->_date_format);
        $row->help = _JOOMLACOMMENT_ADMIN_date_format_HELP;
        $rows->addRow($row);        
        $row = new JOSC_tabRow();
        $row->caption = _JOOMLACOMMENT_ADMIN_no_search_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_no_search]', 'class="inputbox"', $this->_no_search);
        $row->help = _JOOMLACOMMENT_ADMIN_no_search_HELP;
        $rows->addRow($row);

        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_IP_ADDRESS);
        $row = new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_IP_visible_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_IP_visible]', 'class="inputbox"', $this->_IP_visible);
        $row->help 		= _JOOMLACOMMENT_ADMIN_IP_visible_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_IP_usertypes_CAPTION;
//        $row->component = JOSC_library::usertypes('_IP_usertypes', $this->_IP_usertypes);
		$usertypeslist	= JOSC_utils::getJOSCUserTypes();
        $selected 		= JOSC_library::GetIntsMakeOption(split(',', $this->_IP_usertypes));
        $row->component = JHTML::_('select.genericlist',$usertypeslist, '_IP_usertypes[]', 'class="inputbox" multiple="multiple"', 'id', 'title', $selected);
        $row->help 		= _JOOMLACOMMENT_ADMIN_IP_usertypes_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_IP_partial_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_IP_partial]', 'class="inputbox"', $this->_IP_partial);
        $row->help 		= _JOOMLACOMMENT_ADMIN_IP_partial_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_IP_caption_CAPTION;
        $row->component = JOSC_library::input('params[_IP_caption]', 'class="inputbox"', $this->_IP_caption);
        $row->help 		= _JOOMLACOMMENT_ADMIN_IP_caption_HELP;
        $rows->addRow($row);

        echo $rows->tabRows_htmlCode();
        echo $this->tabs->endPanel();

/* 
        $rowSorting->visible(!$this->_tree);
		$element = JOSC_element::get('sorting');
		
        JOSC_library::onClick('_tree0', $element . JOSC_element::visible(true));
        JOSC_library::onClick('_tree1', $element . JOSC_element::visible(false));
*/
    }
    
    function layoutPage()
    {
        echo $this->tabs->startPanel(_JOOMLACOMMENT_ADMIN_TAB_LAYOUT, "Layout-page");
        $rows = new JOSC_tabRows();

        /*
         * FRONTPAGE
         */
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_FRONTPAGE);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_show_readon_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_show_readon]', 'class="inputbox"', $this->_show_readon);
        $row->help 		= _JOOMLACOMMENT_ADMIN_show_readon_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_menu_readon_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_menu_readon]', 'class="inputbox"', $this->_menu_readon);
        $row->help 		= _JOOMLACOMMENT_ADMIN_menu_readon_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_intro_only_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_intro_only]', 'class="inputbox"', $this->_intro_only);
        $row->help 		= _JOOMLACOMMENT_ADMIN_intro_only_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_preview_visible_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_preview_visible]', 'class="inputbox"', $this->_preview_visible);
        $row->help 		= _JOOMLACOMMENT_ADMIN_preview_visible_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_preview_length_CAPTION;
        $row->component = JOSC_library::input('params[_preview_length]', 'class="inputbox"', $this->_preview_length);
        $row->help 		= _JOOMLACOMMENT_ADMIN_preview_length_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_preview_lines_CAPTION;
        $row->component = JOSC_library::input('params[_preview_lines]', 'class="inputbox"', $this->_preview_lines);
        $row->help 		= _JOOMLACOMMENT_ADMIN_preview_lines_HELP;
        $rows->addRow($row);

        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_TEMPLATES);
        /*
         * TEMPLATES
         */
        /* standard template and CSS */
        $style = $this->_template_custom ? ' style="color:grey;" ' : ''; 
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_template_CAPTION;
		$foldercsslist	= JOSC_library::TemplatesCSSList(JPATH_SITE."/components/com_comment/joscomment/templates");
		$css = "";
        if ($foldercsslist) {
        	foreach($foldercsslist as $folder) {
        	    $param = '_template_css'.$folder['template'];
				$css 	.=	'<tr id="'.$param.'" style="display:none;" >'
					 	.	'<td><b>CSS </b></td><td>'
					 	.	JHTML::_('select.genericlist',$folder['css'], "params[$param]", ' class="inputbox" '.$style , 'value', 'text', $this->_template_css)
					 	.	'</td></tr>'
					 	;
        	}
        }
        $folderlist 	= JOSC_library::folderList(JPATH_SITE."/components/com_comment/joscomment/templates");
        $onchange 		= $css ? " onchange=\"JOSC_template_active=JOSC_adminVisible('_template_css', '_template_css'+document.getElementsByName('params[_template]')[0].value,JOSC_template_active);\" ": "";
        $row->component = 	'<table cellpadding=0 cellspacing=0><tr><td><b>HTML </b></td><td>'
        				.	JHTML::_('select.genericlist',$folderlist, 'params[_template]', 'class="inputbox" '.$style. $onchange, 'value', 'text', $this->_template)
        				.	'</td></tr>'
						. 	$css . "<script type='text/javascript'>var JOSC_template_active='_template_css'+'$this->_template';JOSC_adminVisible('_template_css', JOSC_template_active);</script>"
						.	'</table>'   
						;
        $row->help 		= _JOOMLACOMMENT_ADMIN_template_HELP;
        $rows->addRow($row);
        /* copy of template ? */
        $copytemplate	= $this->_copy_template ? $this->_template:''; 
        $check 			= $this->setTemplateCustomPath(true, $copytemplate); /* get path and copy if asked */
        $this->_copy_template = '0'; /* reset */
        
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_copy_template_CAPTION;
        $row->component = JHTML::_('select.booleanlist',   'params[_copy_template]', 'class="inputbox" ', $this->_copy_template);
        $row->help 		= _JOOMLACOMMENT_ADMIN_copy_template_HELP;
        $rows->addRow($row);
		/* customized template and CSS */
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_template_custom_CAPTION . ($check ? ("<br />"._JOOMLACOMMENT_ADMIN_TEMPLATE_CUSTOM_LOCATION.$check):"");
		$foldercsslist	= JOSC_library::TemplatesCSSList($this->_template_custom_path);
		$css = "";
        if ($foldercsslist) {
        	foreach($foldercsslist as $folder) {
        	    $param = '_template_custom_css'.$folder['template'];
				$css 	.=	'<tr id="'.$param.'" style="display:none;" >'
					 	.	'<td><b>CSS </b></td><td>'
						.	JHTML::_('select.genericlist',$folder['css'], "params[$param]", ' class="inputbox"', 'value', 'text', $this->_template_custom_css)
					 	.	'</td></tr>'
					 	;
        	}
        }
        $folderlist		= JOSC_library::folderList($this->_template_custom_path);
		// add empty value
		array_unshift( $folderlist, JHTML::_('select.option', '', '-- Use standard --', 'value', 'text' ) );
        $onchange 		= $css ? " onchange=\"JOSC_template_custom_active=JOSC_adminVisible('_template_custom_css', '_template_custom_css'+document.getElementsByName('params[_template_custom]')[0].value, JOSC_template_custom_active);\" ": "";		
        $row->component = 	'<table cellpadding=0 cellspacing=0><tr><td><b>HTML </b></td><td>'
        				.	JHTML::_('select.genericlist',$folderlist, 'params[_template_custom]', 'class="inputbox"'. $onchange, 'value', 'text', $this->_template_custom)
        				.	'</td></tr>'
        				. $css . "<script type='text/javascript'>var JOSC_template_custom_active='_template_custom_css'+'$this->_template_custom';JOSC_adminVisible('_template_custom_css', JOSC_template_custom_active);</script>"   
						.	'</table>'   
						;
        $row->help 		= _JOOMLACOMMENT_ADMIN_template_custom_HELP;
        $rows->addRow($row);
        
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_template_modify_CAPTION;
        $row->component = JHTML::_('select.booleanlist',   'params[_template_modify]', 'class="inputbox" ', $this->_template_modify);
        $row->help 		= _JOOMLACOMMENT_ADMIN_template_modify_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_template_library_CAPTION;
        $row->component = JHTML::_('select.booleanlist',   'params[_template_library]', 'class="inputbox" ', $this->_template_library);
        $row->help 		= _JOOMLACOMMENT_ADMIN_template_library_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_form_area_cols_CAPTION;
        $row->component = JOSC_library::input('params[_form_area_cols]', 'class="inputbox"', $this->_form_area_cols);
        $row->help 		= _JOOMLACOMMENT_ADMIN_form_area_cols_HELP;
        $rows->addRow($row);
        
        
        /*
         * EMOTICONS
         */
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_EMOTICONS);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_support_emoticons_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_support_emoticons]', 'class="inputbox"', $this->_support_emoticons);
        $row->help 		= _JOOMLACOMMENT_ADMIN_support_emoticons_HELP;
        $rows->addRow($row);                
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_emoticon_pack_CAPTION;
        $selectlist		= array();

        $folderlist		= JOSC_library::folderList(JPATH_SITE."/components/com_comment/joscomment/emoticons", false, false);
        $help_emoticons = "";

        if ($folderlist) {
        	foreach($folderlist as $pack) {
        	    $help_id = 'help_emoticons_'.$pack;
        	    $help_emoticons .= 	"<div id=\"$help_id\" style=\"display:none\">" 
        	    				. 	$this->emoticons_confightml($pack) 
        	    				. 	"</div>";
 				$selectlist[] = JHTML::_('select.option',$pack, $pack);
         	}
        }
        $onchange 		= $folderlist ? " onchange=\"JOSC_help_emoticons_active=JOSC_adminVisible('help_emoticons_', 'help_emoticons_'+document.getElementsByName('params[_emoticon_pack]')[0].value, JOSC_help_emoticons_active);\" ": "";		        
        $row->component = JHTML::_('select.genericlist',$selectlist, 'params[_emoticon_pack]', 'class="inputbox"'. $onchange, 'value', 'text', $this->_emoticon_pack);
        $row->help 		= $help_emoticons 
        				. "<script type='text/javascript'>var JOSC_help_emoticons_active='help_emoticons_'+'$this->_emoticon_pack';JOSC_adminVisible('help_emoticons_', JOSC_help_emoticons_active);</script>"
        				;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_emoticon_wcount_CAPTION;
        $row->component = JOSC_library::input('params[_emoticon_wcount]', 'class="inputbox"', $this->_emoticon_wcount);
        $row->help 		= _JOOMLACOMMENT_ADMIN_emoticon_wcount_HELP;
        $rows->addRow($row);

        echo $rows->tabRows_htmlCode();
        echo $this->tabs->endPanel();

/* 
        $rowSorting->visible(!$this->_tree);
		$element = JOSC_element::get('sorting');
		
        JOSC_library::onClick('_tree0', $element . JOSC_element::visible(true));
        JOSC_library::onClick('_tree1', $element . JOSC_element::visible(false));
*/
    }
    /*
     * showing the emoticons in backend.
     */
    function emoticons_confightml($pack) {
               
        $GLOBALS["JOSC_emoticon"] = array(); /* reset ! */
        $_emoticons_path = JURI::root()."/components/com_comment/joscomment/emoticons/$pack/images";
        
        $file = JPATH_SITE."/components/com_comment/joscomment/emoticons/$pack/index.php";
        if (file_exists($file)) {
        
        require_once($file);
		require_once(JPATH_SITE."/components/com_comment/joscomment/comment.class.php");
        $form = new JOSC_form(null);
        $form->setSupport_emoticons($this->_support_emoticons);
        //$form->setEmoticons($this->_emoticons); /* only to parse ... */
        $form->setEmoticons_path($_emoticons_path);
        $form->setEmoticonWCount($this->_emoticon_wcount);
        return $form->emoticons(false);
        }
        return "";
        
    }
    
    function securityPage()
    {
        echo $this->tabs->startPanel(_JOOMLACOMMENT_ADMIN_TAB_SECURITY, "Security-page");
        $rows = new JOSC_tabRows();
        
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_BASIC_SETTINGS);
        $row 			= new JOSC_tabRow();        
        $row->caption 	= _JOOMLACOMMENT_ADMIN_only_registered_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_only_registered]', 'class="inputbox"', $this->_only_registered);
        $row->help 		= _JOOMLACOMMENT_ADMIN_only_registered_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_autopublish_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_autopublish]', 'class="inputbox"', $this->_autopublish);
        $row->help 		= _JOOMLACOMMENT_ADMIN_autopublish_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_ban_CAPTION;
        $row->component = JOSC_library::textarea('params[_ban]', 'class="inputbox" rows="5"', $this->_ban);
        $row->help 		= _JOOMLACOMMENT_ADMIN_ban_HELP;
        $rows->addRow($row);

		/*
		 * NOTIFICATIONS
		 */
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_NOTIFICATIONS);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_notify_admin_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_notify_admin]', 'class="inputbox"', $this->_notify_admin);
        $row->help 		= _JOOMLACOMMENT_ADMIN_notify_admin_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_notify_email_CAPTION;
        $row->component = JOSC_library::input('params[_notify_email]', 'class="inputbox"', $this->_notify_email);
        $row->help 		= _JOOMLACOMMENT_ADMIN_notify_email_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_notify_moderator_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_notify_moderator]', 'class="inputbox"', $this->_notify_moderator);
        $row->help 		= _JOOMLACOMMENT_ADMIN_notify_moderator_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_moderator_CAPTION;
//        $row->component = JOSC_library::usertypes('_moderator', $this->_moderator, false);
		$usertypeslist	= JOSC_utils::getJOSCUserTypes(false);
        $selected 		= JOSC_library::GetIntsMakeOption(split(',', $this->_moderator));
        $row->component = JHTML::_('select.genericlist',$usertypeslist, '_moderator[]', 'class="inputbox" multiple="multiple"', 'id', 'title', $selected);
        $row->help 		= _JOOMLACOMMENT_ADMIN_moderator_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_notify_users_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_notify_users]', 'class="inputbox"', $this->_notify_users);
        $row->help 		= _JOOMLACOMMENT_ADMIN_notify_users_HELP;
        $rows->addRow($row);
        $row->caption 	= _JOOMLACOMMENT_ADMIN_rss_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_rss]', 'class="inputbox"', $this->_rss);
        $row->help 		= _JOOMLACOMMENT_ADMIN_rss_HELP;        
        $rows->addRow($row);
        
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_OVERFLOW);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_maxlength_text_CAPTION;
        $row->component = JOSC_library::input('params[_maxlength_text]', 'class="inputbox"', $this->_maxlength_text);
        $row->help 		= _JOOMLACOMMENT_ADMIN_maxlength_text_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_maxlength_line_CAPTION;
        $row->component = JOSC_library::input('params[_maxlength_line]', 'class="inputbox"', $this->_maxlength_line);
        $row->help 		= _JOOMLACOMMENT_ADMIN_maxlength_line_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_maxlength_word_CAPTION;
        $row->component = JOSC_library::input('params[_maxlength_word]', 'class="inputbox"', $this->_maxlength_word);
        $row->help 		= _JOOMLACOMMENT_ADMIN_maxlength_word_HELP;
        $rows->addRow($row);
        
        /*
         * ANTI-SPAM
         */
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_CAPTCHA);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_captcha_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_captcha]', 'class="inputbox"', $this->_captcha);
        $row->help 		= _JOOMLACOMMENT_ADMIN_captcha_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_captcha_usertypes_CAPTION;
//        $row->component = JOSC_library::usertypes('_captcha_usertypes', $this->_captcha_usertypes);
		$usertypeslist	= JOSC_utils::getJOSCUserTypes();
        $selected 		= JOSC_library::GetIntsMakeOption(split(',', $this->_captcha_usertypes));
        $row->component = JHTML::_('select.genericlist',$usertypeslist, '_captcha_usertypes[]', 'class="inputbox" multiple="multiple"', 'id', 'title', $selected);
        $row->help 		= _JOOMLACOMMENT_ADMIN_captcha_usertypes_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_website_registered_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_website_registered]', 'class="inputbox"', $this->_website_registered);
        $row->help 		= _JOOMLACOMMENT_ADMIN_website_registered_HELP;
        $rows->addRow($row);
        /*
         * CENSOR
         */
        $rows->addTitle(_JOOMLACOMMENT_ADMIN_TITLE_CENSORSHIP);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_censorship_enable_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_censorship_enable]', 'class="inputbox"', $this->_censorship_enable);
        $row->help		= _JOOMLACOMMENT_ADMIN_censorship_enable_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_censorship_case_sensitive_CAPTION;
        $row->component = JHTML::_('select.booleanlist',  'params[_censorship_case_sensitive]', 'class="inputbox"', $this->_censorship_case_sensitive);
        $row->help 		= _JOOMLACOMMENT_ADMIN_censorship_case_sensitive_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_censorship_words_CAPTION;
        $row->component = JOSC_library::textarea('params[_censorship_words]', 'class="inputbox" rows="5" cols="70"', $this->_censorship_words);
        $row->help 		= _JOOMLACOMMENT_ADMIN_censorship_words_HELP;
        $rows->addRow($row);
        $row 			= new JOSC_tabRow();
        $row->caption 	= _JOOMLACOMMENT_ADMIN_censorship_usertypes_CAPTION;
//        $row->component = JOSC_library::usertypes('_censorship_usertypes', $this->_censorship_usertypes);
		$usertypeslist	= JOSC_utils::getJOSCUserTypes();
        $selected 		= JOSC_library::GetIntsMakeOption(split(',', $this->_censorship_usertypes));
        $row->component = JHTML::_('select.genericlist',$usertypeslist, '_censorship_usertypes[]', 'class="inputbox" multiple="multiple"', 'id', 'title', $selected);
        $row->help 		= _JOOMLACOMMENT_ADMIN_censorship_usertypes_HELP;
        $rows->addRow($row);
        
        echo $rows->tabRows_htmlCode();
		echo $this->tabs->endPanel();
    }

    function editTemplateHTMLPage() {
        echo $this->tabs->startPanel("HTML", "TemplateHTML");
        $this->editTemplateHTMLSource();
		echo $this->tabs->endPanel();
    }

    function editTemplateCSSPage() {
        echo $this->tabs->startPanel("CSS", "TemplateCSS");
        $this->editTemplateCSSSource();
		echo $this->tabs->endPanel();
    }

    function execute($option, $task)
    {
		$expertmode  = (strpos($task, 'expert')===false) ? false : true;
?>
        <form action='index.php' method='POST' name='adminForm'>
			<input type="hidden" name="set_id" value="<?php echo $this->_set_id>0 ? $this->_set_id:''; ?>" />   
            <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
    			<tr>
      				<th  width="100%" align="left">
      					<?php echo $this->_set_name . " / " . JOSC_utils::getComponentName($this->_set_component, $this->_set_id); ?></th>
      				<td  nowrap="nowrap">
      					<?php echo _JOOMLACOMMENT_ADMIN_SETTING_LINE_NAME; ?>
      				</td>
      				<td>
		        		<input type="text" name="set_name" value="<?php echo $this->_set_name; ?>" class="inputbox" />
      				</td>
      				<td  nowrap="nowrap">
      					<?php echo _JOOMLACOMMENT_ADMIN_SETTING_LINE_COMPONENT; ?>
      				</td>
      				<td>
<?php
		$readonly = $this->_set_id==1 ? " readonly='readonly' " : "";		
		$component		= $this->getComponentList();
        echo JHTML::_('select.genericlist',$component, 'set_component', 'class="inputbox" '.$readonly, 'value', 'text', $this->_set_component);
?>        
      				</td>
      				<td> 
<?php
		if (!$expertmode) {
			echo JOSC_library::hidden('set_sectionid',$this->_set_sectionid);
		} else {
	        if ($this->_set_id==1 || !method_exists($this->_comObject, 'getExpertSectionIdOption'))
    	    	$sectionid[] 	= JHTML::_('select.option','0', '-- all --', 'id', 'title' );
        	else
            	$sectionid	= $this->_comObject->getExpertSectionIdOption($this->_exclude_sections, $this->_include_sc);
        	echo JHTML::_('select.genericlist',$sectionid, 'set_sectionid', 'class="inputbox" '.$readonly, 'id', 'title', $this->_set_sectionid);
		}
?>
      				</td>
    			</tr>
    		</table>
		You have questions, problems or features requests ? contact the <a href="http://joomlacode.org/gf/project/joomagecomment/" target="_blank">joomlacomment support</a>!
<?php

		JOSC_library::initVisibleJScript();

        jimport('joomla.html.pane');
        $this->tabs = & JPane::getInstance('tabs'); /* 1 to remember the selected tab */
		echo $this->tabs->startPane( 'pane' );
//        echo $this->tabs->startPanel("jos_comment", '1');
        $this->generalPage();
        $this->securityPage();
        $this->postingPage();
        $this->layoutPage();
        if ($this->_template_modify && $this->_template_custom) {
          $this->editTemplateHTMLPage();
          $this->editTemplateCSSPage();
        }
        echo $this->tabs->endPane();
?>     
		<input type="hidden" name="task" value="" />   
		<input type="hidden" name="option" value="<?php echo $option; ?>" />   
        </form>
<?php
    }

	function getComponentList() 
	{
		return (JOSC_library::getComponentList($this->_set_id));
	}

	function setTemplateCustomPath($check=false,$copytemplate='') 
	{
		if (!$check) {
		    $this->_template_custom_path = '';
		    $this->_template_custom_livepath = ''; 
		}		
		$mediapath 		= JPATH_SITE . "/media";
		$absolute_path	= $mediapath. "/myjosctemplates";
		$livepath		= JURI::base() . "/media/myjosctemplates";
		$standardpath 	= JPATH_SITE."/components/com_comment/joscomment/templates";
		 
    	if (!is_writable("$mediapath")) {
    	    return ($check ? "<SPAN style=\"color:red;\">$mediapath is not writable</SPAN>":"");
    	}
    	/*
    	 * check directory and create if not exist
    	 */
		if (!@is_dir($absolute_path)) {
			if (!@mkdir($absolute_path, 0755))
				return ($check ? "<SPAN style=\"color:red;\">Unable to create directory '$absolute_path'</SPAN>":"");
		}
		if ($copytemplate) {
			/*
		 	 * if copytemplate = '*' 
		 	 *      copy all standard templates (which are not already copied) in custom directory if not exist
		 	 * 	else copy only copytemplate to 'my'copytemplate
			 */
			$folderlist	= JOSC_library::folderList($standardpath, false, false);
			if ($folderlist) {
				foreach($folderlist as $template) {
				    if ($copytemplate!='*' && $copytemplate!=$template) 
				    	continue;
					if (!@is_dir($absolute_path."/my$template"))
	    				JOSC_library::copyDir($standardpath."/$template", $absolute_path."/my$template");
				}    
	    	}
		}

		if ($check) {
		    return "<SPAN style=\"color:green;\">$absolute_path is writable</SPAN>";
		} else {
		    $this->_template_custom_path = $absolute_path;
		    $this->_template_custom_livepath = $livepath; 
		}
		
	}

    function editTemplateHTMLSource() 
    {
	//$file = JPATH_SITE."/components/com_comment/joscomment/templates/". $this->_template ."/index.html";
	$file = $this->_template_custom_path . "/". $this->_template_custom ."/index.html";

	if ( $fp = fopen( $file, 'r' ) ) {
		$content = fread( $fp, filesize( $file ) );
		$content = htmlspecialchars( $content );

		$this->HTML_editTemplateHTMLSource( $content, $file );
	} else {
		echo "<b>Operation Failed: Could not open". $file . "</b>";
                return;
	}
    }

    function editTemplateCSSSource() {

//	$file = JPATH_SITE."/components/com_comment/joscomment/templates/". $this->_template ."/css/template_css.css";
	$file = $this->_template_custom_path . "/". $this->_template_custom ."/css/". $this->_template_custom_css;

	if ( $fp = fopen( $file, 'r' ) ) {
		$content = fread( $fp, filesize( $file ) );
		$content = htmlspecialchars( $content );

		$this->HTML_editTemplateCSSSource( $content, $file );
	} else {
		echo "<b>Operation Failed: Could not open". $file . "</b>";
                return;
	}
    }

	function HTML_editTemplateHTMLSource( &$content, $file ) {
		$template_path = $file;
                $template = $this->_template_custom;
		?>
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td><table class="adminheading"><tr>
			  <th class="templates">HTML template editor : <?php echo $template; ?></th>
			</tr></table></td>
			<td>
				<span class="componentheading">file is :
				<b><?php echo is_writable($template_path) ? '<font color="green"> Writable</font>' : '<font color="red"> Not Writable</font>'; ?></b>
				</span>
			</td>
<?php
			jimport('joomla.filesystem.path');
			if (JPath::canChmod($template_path)) {
				if (is_writable($template_path)) {
?>
		  <td>
				<input type="checkbox" id="disable_writeHTML" name="disable_writeHTML" value="1"/>
				<label for="disable_writeHTML"></label>
				Set Not Writable
				<label for="label">after saved</label></td>
<?php
				} else {
?>
		  <td>
				<input type="checkbox" id="enable_writeHTML" name="enable_writeHTML" value="1"/>
			  <label for="enable_writeHTML">Ignore the Writable / Not Writable status</label>			</td>
<?php
				} // if
			} // if
?>
		</tr>
		</table>
		<table class="adminform">
			<tr><th><?php echo $template_path; ?></th></tr>
			<tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="joscTemplateHTMLcontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="joscTemplateHTML" value="<?php echo $template; ?>" />
		<?php
	}

	function HTML_editTemplateCSSSource( &$content, $file ) {
		$template_path = $file;
               	$template = $this->_template_custom;
               	$templateCSS = $this->_template_custom_css;
		?>
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td><table class="adminheading"><tr>
			  <th class="templates">CSS template editor : <?php echo $template; ?></th>
			</tr></table></td>
			<td>
				<span class="componentheading">file is :
				<b><?php echo is_writable($template_path) ? '<font color="green"> Writable</font>' : '<font color="red"> Not Writable</font>'; ?></b>
				</span>
			</td>
<?php
			jimport('joomla.filesystem.path');
			if (JPath::canChmod($template_path)) {
				if (is_writable($template_path)) {
?>
		  <td>
				<input type="checkbox" id="disable_writeCSS" name="disable_writeCSS" value="1"/>
				<label for="disable_writeCSS"></label>
				Set Not Writable
				<label for="label">after saved</label></td>
<?php
				} else {
?>
		  <td>
				<input type="checkbox" id="enable_writeCSS" name="enable_writeCSS" value="1"/>
			  <label for="enable_writeCSS">Ignore the Writable / Not Writable status</label>			</td>
<?php
				} // if
			} // if
?>
		</tr>
		</table>
		<table class="adminform">
			<tr><th><?php echo $template_path; ?></th></tr>
			<tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="joscTemplateCSScontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="joscTemplateCSS" value="<?php echo $template; ?>" />
		<input type="hidden" name="joscTemplateCSSCSS" value="<?php echo $templateCSS; ?>" />
		<?php
	}

}

class JOSC_defaultconfig extends JOSC_config {

	function JOSC_defaultconfig( $component )
	{
		$null=null;
		$comObject = JOSC_utils::ComPluginObject($component,$null);
		$this->JOSC_config(0,$comObject);
		unset($comObject);
	}	
}

?>