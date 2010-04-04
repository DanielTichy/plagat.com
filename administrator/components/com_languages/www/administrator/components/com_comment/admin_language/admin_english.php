<?php

/* ***************************************************
 * *********** M A N A G E   C O M M E N T S *********
 * ***************************************************
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_CONFIRM_NOTIFY', 'Would you also send notifications ?\n[CANCEL=no notifications]');
JOSC_define('_JOOMLACOMMENT_ADMIN_NOTIFY_SENT_TO', 'Notifications sent to : ');
JOSC_define('_JOOMLACOMMENT_ADMIN_NOTIFY_NOT_SENT', 'Notifications not sent');

JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_id', 'Id');
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_writer', 'Writer'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_userid', 'Userid'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_notify', 'Notify'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_url', 'Url'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_date', 'Date'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_comment', 'Comment <br /><i>(links and pictures are deactivated)</i>'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_contentitem', 'ContentItem'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_published', 'Published (notify-writer)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_delete', 'Delete (notify-writer)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_ip', 'IP'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_votingyes', 'Voting Yes'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_votingno', 'Voting No'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_parentid', 'Parent Id'); 



/* ***************************************************
 * *************** S E T T I N G *********************
 * ***************************************************
 */
/*
 * common
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_SETTING_LINE_NAME', 'Name : ');
JOSC_define('_JOOMLACOMMENT_ADMIN_SETTING_LINE_COMPONENT', 'Component : ');
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_BASIC_SETTINGS', 'Basic Settings'); 
/*
 * generalPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_GENERAL_PAGE', 'General'); 
/* BASIC_SETTINGS */
JOSC_define('_JOOMLACOMMENT_ADMIN_complete_uninstall_CAPTION', 'Uninstall complete mode:'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_complete_uninstall_HELP', 'Delete also tables when uninstall ! Do not active except if you would not use !joomlacomment anymore.'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_mambot_func_CAPTION', 'Mambot content function:');
JOSC_define('_JOOMLACOMMENT_ADMIN_mambot_func_HELP', '<u>For expert only !</u> You can change here the joscomment mambot function, if you have <b>hacked</b> the content html (for example: to display the read only function first).');
JOSC_define('_JOOMLACOMMENT_ADMIN_language_CAPTION', 'Frontend language:');
JOSC_define('_JOOMLACOMMENT_ADMIN_language_HELP', 'if auto : will use the mosConfigLanguage parameters');
JOSC_define('_JOOMLACOMMENT_ADMIN_admin_language_CAPTION', 'Backend language:');
JOSC_define('_JOOMLACOMMENT_ADMIN_admin_language_HELP', 'if auto : will use the mosConfigLanguage parameters');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_CAPTION', 'Local charset :<br />if you are upgrading older release thant 3.0.0, read carefully description on the right !');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_HELPnoiconv', 'Will not be used !! php <a href="http://www.php.net/manual/fr/ref.iconv.php" target="_blank">iconv library</a><u/> is not available.  <b>If you are using a NOT utf-8 and a NOT iso-8859-1 joomla installation, please contact your administrator OR deactivate the ajax support parameter.</b>');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_HELPiconv', 'Php <a href="http://www.php.net/manual/fr/ref.iconv.php" target="_blank">iconv library</a> is available on your server.'
	                    .  '<br /><b>Input the charset of your joomla installation if it is different from utf-8.<br />Clic <a href="http://www.gnu.org/software/libiconv/" target="_blank">HERE</a> to check if it is supported by the inconv library ! else, contact the joomlacomment support.</b> '
						.  '<br /><br /><b>If this is an upgrade from a release older than 3.0.0</b>, once you have saved this parameter, <u>go to Manage Comments and use the Convert To LCharset</u> functionnality to convert related comments.'
        				.  '<br />If you have always used the ajax mode, convert every comments.'
        				.  '<br />If you had changed, some comments (created with ajax) have to be converted, some others (created without ajax) have not to be converted !'
        				.  ' In this case, select concerned comments only (those with strange characters !).'
        				);
/* SECTIONS_CATEGORIES */
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_sections_CAPTION', 'Exclude/Include sections:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_sections_HELP', 'Clic to Exclude/Include sections.<br />Use CTRL or SHIFT key to select unselect lines.');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_categories_CAPTION', 'Exclude/Include categories:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_categories_HELP', 'Clic to Exclude/Include categories.<br />Use CTRL or SHIFT key to select unselect lines.');
/* TECHNICAL */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_TECHNICAL', 'Technical parameters (for joomlacomment support only)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_debug_username_CAPTION', 'Username concerned by debug:');
JOSC_define('_JOOMLACOMMENT_ADMIN_debug_username_HELP', 'The debug alerts, will be dislplaid only for this username account.');
JOSC_define('_JOOMLACOMMENT_ADMIN_xmlerroralert_CAPTION', 'xmlErrorAlert:');
JOSC_define('_JOOMLACOMMENT_ADMIN_xmlerroralert_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajaxdebug_CAPTION', 'ajaxdebug:');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajaxdebug_HELP', '');

/*
 * layoutPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_LAYOUT', 'Layout');
/* FRONTPAGE */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_FRONTPAGE', '"Read on" link when intro text'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_show_readon_CAPTION', 'Show "Read On":');
JOSC_define('_JOOMLACOMMENT_ADMIN_show_readon_HELP', 'In case of intro display mode of content items (frontpage, blogs...), will display a write comment link with the count of existing comments for this content item');
JOSC_define('_JOOMLACOMMENT_ADMIN_menu_readon_CAPTION', 'Only if "Read On" set in Menu:');
JOSC_define('_JOOMLACOMMENT_ADMIN_menu_readon_HELP', 'Show only if "Read On" parameter of the calling menu link (in joomla admin Menu->...) is set. YES is recommended.');
JOSC_define('_JOOMLACOMMENT_ADMIN_intro_only_CAPTION', 'Do not show if detail link exists:');
JOSC_define('_JOOMLACOMMENT_ADMIN_intro_only_HELP', 'Do not show when content item has link to detail (Readon or Title) and the page is "intro only"');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_visible_CAPTION', 'Preview visible:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_visible_HELP', 'Display preview of last comments (if "Show Read On" is enable)');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_length_CAPTION', 'Preview Length:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_length_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_lines_CAPTION', 'Preview Number of lines:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_lines_HELP', ''); 
/* TEMPLATES */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_TEMPLATES', 'Templates'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_template_CAPTION', 'Standard template:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_HELP', 'Templates are the different looks of your comments.'
				   		. '<br />If you have enabled emoticons, adapt the numbers of emoticons by line (see below) according to your template choice.'
				   		);
JOSC_define('_JOOMLACOMMENT_ADMIN_copy_template_CAPTION', 'Copy current standard template to customized template directory:');
JOSC_define('_JOOMLACOMMENT_ADMIN_copy_template_HELP', 'If set, when saving setting, the selected standard template will be copied in custom directory as a new "my[standard template]" which you could then modify (see parameters below). It will be copied only if it does not already exist.');
JOSC_define('_JOOMLACOMMENT_ADMIN_TEMPLATE_CUSTOM_LOCATION', 'Location:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_custom_CAPTION', 'Your customized template:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_custom_HELP', 'Use the copy parameter to copy standard template. Then you will be allowed to modify HTML or CSS (it will not be overwritten during next upgrades). If none is selected, standard template will be used.');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_modify_CAPTION', 'Modify current customized template:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_modify_HELP', 'Set YES if you would like to modify HTML and CSS style of your current customized template. 2 new tabs will appear after saving the selection.'
                   		. '<br />Set to NO will simplify the save of the setting (faster).</b>'
                   		);                   		
JOSC_define('_JOOMLACOMMENT_ADMIN_template_library_CAPTION', 'Include javascript library:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_library_HELP', 'Include javascript library when using templates with effects (JQuery, Mootools...)'
       					. '<br />Set to NO if library is already included. Else, you will have javascript errors and problems.'
                   		);
JOSC_define('_JOOMLACOMMENT_ADMIN_form_area_cols_CAPTION', 'Columns number of the input area:');
JOSC_define('_JOOMLACOMMENT_ADMIN_form_area_cols_HELP', 'You can use this parameter to increase or decrease width of input text area according to your website feets.');
                   		
/* EMOTICONS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_EMOTICONS', 'Emoticons'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_support_emoticons_CAPTION', 'Emoticon (smilies) support:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_emoticons_HELP', 'Allow the use of Emoticons in comments ?');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_pack_CAPTION', 'Emoticon pack:');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_pack_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_wcount_CAPTION', 'Number of emoticon by line:');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_wcount_HELP', 'Number of emoticon to display on each line. Value 0 means no limit.'
        				. '<br />Proposition: use 12 in case of <i>emotop</i> templates (emoticon are on top) and 2 or 3 if possible for others (left emoticon templates). Try and see what is the best for your website !'
        				);
/*
 * postingPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_POSTING', 'Posting');
/* BASIC_SETTINGS */
JOSC_define('_JOOMLACOMMENT_ADMIN_ajax_CAPTION', 'Ajax support (recommended):');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajax_HELP', 'Asynchronous JavaScript + XML');
/* STRUCTURE */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_STRUCTURE', 'Structure'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_CAPTION', 'Allow nested comments:');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_HELP', 'This will allow users to insert post in response to any post of the content item (not only the last), with an indent display.');
JOSC_define('_JOOMLACOMMENT_ADMIN_mlink_post_CAPTION', 'Only moderators');
JOSC_define('_JOOMLACOMMENT_ADMIN_mlink_post_HELP', 'Only moderators will be allowed');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_indent_CAPTION', 'Indent (pixels):');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_indent_HELP', 'This is used to indent messages in threaded view.');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_CAPTION', 'Comments sorting (if NOT nested comments):');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_VALUE_FIRST', 'New entries first');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_VALUE_LAST', 'New entries last');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_HELP', 'Ordering of comments : used only if nested is NOT active.<br /> If New entries first, the form will be on the top, else it will be on the bottom');
JOSC_define('_JOOMLACOMMENT_ADMIN_display_num_CAPTION', 'Number of comments by page:');
JOSC_define('_JOOMLACOMMENT_ADMIN_display_num_HELP', 'Number of comments to be display by page');
/* POSTING */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_POSTING', 'Posting'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_enter_website_CAPTION', 'Input website field:');
JOSC_define('_JOOMLACOMMENT_ADMIN_enter_website_HELP', 'Allow the users to input their website link.');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_UBBcode_CAPTION', 'UBB code support:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_UBBcode_HELP', 'Allow the use of UBB Codes ?');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_pictures_CAPTION', 'Picture support:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_pictures_HELP', 'Allow the use of pictures in comments ?');
JOSC_define('_JOOMLACOMMENT_ADMIN_pictures_maxwidth_CAPTION', 'Picture maximum width:');
JOSC_define('_JOOMLACOMMENT_ADMIN_pictures_maxwidth_HELP', 'Maximum width in pixel');
JOSC_define('_JOOMLACOMMENT_ADMIN_voting_visible_CAPTION', 'Enable voting:');
JOSC_define('_JOOMLACOMMENT_ADMIN_voting_visible_HELP', 'If set AND ajax mode is set : will display reactive image to allow to vote + or - for any comments.');
JOSC_define('_JOOMLACOMMENT_ADMIN_use_name_CAPTION', 'Use names :');
JOSC_define('_JOOMLACOMMENT_ADMIN_use_name_HELP', 'Use names rather than usernames (it concerns only registered users)');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_profiles_CAPTION', 'Enable profiles:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_profiles_HELP', "Allow the use of <a href='http://www.joomlapolis.com/' target='_blank'>Community Builder</a> - Profiles in comments ?");
JOSC_define('_JOOMLACOMMENT_ADMIN_support_avatars_CAPTION', 'Enable avatars:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_avatars_HELP', "Allow the use of <a href='http://www.joomlapolis.com/' target='_blank'>Community Builder</a> - Avatars in comments ? (only if profiles is enabled)");
JOSC_define('_JOOMLACOMMENT_ADMIN_date_format_CAPTION', 'Date format:');
JOSC_define('_JOOMLACOMMENT_ADMIN_date_format_HELP', 'The syntax used is identical to the PHP date() function.');
JOSC_define('_JOOMLACOMMENT_ADMIN_no_search_CAPTION', 'Deactivate search button:');
JOSC_define('_JOOMLACOMMENT_ADMIN_no_search_HELP', 'Deactivate the search button.');
/* IP ADDRESS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_IP_ADDRESS', 'IP Address'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_visible_CAPTION', 'Visible:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_visible_HELP', 'If set, will display the IP address of the unregistered writers OR the "usertype" of the registered users.');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_usertypes_CAPTION', 'Usertypes:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_usertypes_HELP', 'At least one usertype must be selected.'
							. '<br />"IP visible" is set to Yes : will show only for comments of selected usertypes (select all is recommended).'
							. '<br />"IP visible" is set to No : will show only for selected connected usertypes'
							);
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_partial_CAPTION', 'Partial:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_partial_HELP', 'If set, will not display the last digit of the unregistered users IP address');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_caption_CAPTION', 'Caption:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_caption_HELP', 'Description before display the IP value');
/*
 * securityPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_SECURITY', 'Security'); 
/* BASICS SETTINGS */  
JOSC_define('_JOOMLACOMMENT_ADMIN_only_registered_CAPTION', 'Only registered:');
JOSC_define('_JOOMLACOMMENT_ADMIN_only_registered_HELP', 'Only registered users can write comments.');
JOSC_define('_JOOMLACOMMENT_ADMIN_autopublish_CAPTION', 'Autopublish comments:');
JOSC_define('_JOOMLACOMMENT_ADMIN_autopublish_HELP', 'If you set this to "no" then comments will be added to the database and will wait for you to review and publish them before showing.');
JOSC_define('_JOOMLACOMMENT_ADMIN_ban_CAPTION', 'Banlist:');
JOSC_define('_JOOMLACOMMENT_ADMIN_ban_HELP', 'To specify several different IP addresses separate them with commas.');
/* NOTIFICATIONS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_NOTIFICATIONS', 'Notification'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_admin_CAPTION', 'Notify admin (do not use anymore):');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_admin_HELP', 'do not use anymore - please use the Notify moderator parameter');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_email_CAPTION', 'Admin\'s email:');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_email_HELP', 'Mail notificationto which email address?');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_moderator_CAPTION', 'Notify moderators:');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_moderator_HELP', 'Notify moderators when new comment ?');
JOSC_define('_JOOMLACOMMENT_ADMIN_moderator_CAPTION', 'Moderator groups:');
JOSC_define('_JOOMLACOMMENT_ADMIN_moderator_HELP', 'Moderators will be able to modify or delete on-line any comments. A special menu will appear on each comment for those users.');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_users_CAPTION', 'Enable users notification :');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_users_HELP', 'Email and notification field will be available and if notified is set, users will receive information of new post.');
JOSC_define('_JOOMLACOMMENT_ADMIN_rss_CAPTION', 'Enable comment feed (RSS):');
JOSC_define('_JOOMLACOMMENT_ADMIN_rss_HELP', '');
/* OVERFLOW */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_OVERFLOW', 'Overflow'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_text_CAPTION', 'Post max length:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_text_HELP', 'Maximum allowed characters in posts (-1 for no max.)');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_line_CAPTION', 'Line max length:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_line_HELP', 'Maximum allowed characters in one line (-1 for no max.)');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_word_CAPTION', 'Word max length:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_word_HELP', 'Maximum allowed characters in words (-1 for no max.)');
/* ANTI-SPAM */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CAPTCHA', 'Anti-spam)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_CAPTION', 'Captcha Enabled (recommended):');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_HELP', 'Will force the user to input a random string displayed in a specially crafted image. This will disallow automated submits to your site.');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_usertypes_CAPTION', 'Captcha Usertypes:');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_usertypes_HELP', 'Only the selected usertypes will have to.');
JOSC_define('_JOOMLACOMMENT_ADMIN_website_registered_CAPTION', 'Website URL only for registered:');
JOSC_define('_JOOMLACOMMENT_ADMIN_website_registered_HELP', 'For posted comments: will display the writers website URL, only when user is registered');
/* CENSORSHIP */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CENSORSHIP', 'Censorship'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_enable_CAPTION', 'Enable:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_enable_HELP', 'Active or not censor filter. The censor will use the rules written in the censored words list to hide or change those words');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_case_sensitive_CAPTION', 'Case sensitive:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_case_sensitive_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_words_CAPTION', 'Censored words:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_words_HELP', false); /* colspan */
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_usertypes_CAPTION', 'Usertypes:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_usertypes_HELP', 'Will be applied only to the selected usertypes.');




/* MODIFIED IN 3.24 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_SECTIONS_CATEGORIES', 'Ids, Sections and Categories criteria'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_include_sc_CAPTION', 'Exclude/Include :');
JOSC_define('_JOOMLACOMMENT_ADMIN_include_sc_HELP', 'If INCLUDE, this will allow comments for items which are: <u>IN selected Ids <b>OR</b> IN selected sections <b>OR</b> IN selected categories.</u>'
													.'<br />If EXCLUDE, this will not allow comments for items which are: <u>IN selected Ids <b>OR</b> IN selected sections <b>OR</b> IN selected categories.</u>');

JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CONTENT_ITEM', 'Content Items by id (DO NOT USE ANYMORE)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_contentids_CAPTION', 'Excluded content item list(DO NOT USE ANYMORE):');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_contentids_HELP', 'DO NOT USE ANYMORE - use the parameter Ids in the previous part');

/* ADDED SINCE 3.24 */
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_contentitems_CAPTION', 'Exclude/Include items Ids:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_contentitems_HELP', 'You can use this field to exclude/include items Ids. <u>Format</u>: list of Ids separated by , with no space.');
JOSC_define('_JOOMLACOMMENT_ADMIN_INCLUDE', 'Include');
JOSC_define('_JOOMLACOMMENT_ADMIN_EXCLUDE', 'Exclude');

/* ADDED SINCE 3.25 */
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_importtable', 'Imported from'); 

?>
