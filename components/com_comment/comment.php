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



require_once(JPATH_SITE."/components/com_comment/joscomment/utils.php");

JOSC_utils::set_JoomlaRelease();

/* global first to verify josctask values AND allow rss execution */
$josctask 	= JOSC_utils::decodeData('josctask');
$component  = JOSC_utils::decodeData('component');
$sectionid  = intval(JOSC_utils::decodeData('joscsectionid')); 
switch ($josctask) {
    case 'ajax_insert':
    case 'ajax_quote':
    case 'ajax_modify':
    case 'ajax_edit':
    case 'ajax_getcomments':
    case 'ajax_delete':
    case 'ajax_delete_all':
    case 'ajax_voting_yes':
    case 'ajax_voting_no':
    case 'ajax_reload_captcha':
    case 'ajax_search':
    case 'ajax_insert_search':
		execPlugin($component,$sectionid);
        break;

    case 'rss':
        createFeed();
        break;

    case 'noajax':
		execPlugin($component,$sectionid);
        break;

    default:
        break;
}

function execPlugin($component,$sectionid)
{
	$null=null;
	$comObject = JOSC_utils::ComPluginObject($component, $null, 0, $sectionid);
	JOSC_utils::execJoomlaCommentPlugin($comObject, $null, $null, false);
}

function createFeed()
{
    require_once(JPATH_SITE."/includes/feedcreator.class.php");
    $contentid = JOSC_utils::decodeData('contentid');
	$component = ''; /* com_content TODO: adapt to others...  */
    
	$database = JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__content WHERE id='$contentid'");
    $content = null;
    $database->loadObject($content);
    $rss = new UniversalFeedCreator();
    $rss->useCached();
    $rss->title = $content->title;
    $rss->description = $content->title_alias;
    $rss->link = JURI :: base();
    $database->setQuery("SELECT *,UNIX_TIMESTAMP( date ) AS rss_date FROM #__comment WHERE contentid='$contentid' AND component='$component' AND published='1' ORDER BY id ASC");
    $data = $database->loadAssocList();
    if ($data != null) {
        foreach($data as $item) {
            $rss_item = new FeedItem();
            $rss_item->author = $item['name'];
            $rss_item->title = $item['title'];
            $rss_item->link = JRoute::_("index.php?option=com_content&task=view&id=$contentid#josc" . $item['id']);/* TODO : adapt to others component */
            $rss_item->description = $item['comment'];
            $rss_item->date = date('r', $item['rss_date']);
            $rss->addItem($rss_item);
        }
//        $rss->saveFeed("RSS2.0", "feed.xml");
    }
	$rss->cssStyleSheet = "";//http://www.w3.org/2000/08/w3c-synd/style.css";
    $rss->saveFeed("RSS2.0", JPATH_SITE."/components/com_comment/joscfeed.xml");
}

?>
