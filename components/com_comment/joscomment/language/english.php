<?php
/*
 * Instruction to create a new translation (thank you in advance !):
 *      1. check in components/com_comment/joscomment/language directory
 *         that there is not already a translation file for your country
 *      2. if not, copy the english.php file in a new <yourlanguage>.php
 *      3. before translate, save <yourlanguage>.php in utf-8 mode ! 
 * 		   (for example for windows users, you can use notepad and save as...)
 *      4. do the translation
 * 		5. check the result !
 *      6. then propose your translation in the joomlacomment support forum !
 */
JOSC_define('_JOOMLACOMMENT_WRITECOMMENT', 'Write comment');
JOSC_define('_JOOMLACOMMENT_EDITCOMMENT', 'Edit comment');
JOSC_define('_JOOMLACOMMENT_UBBCODE', 'UBBCode:');
JOSC_define('_JOOMLACOMMENT_ENTERNAME', 'Name:');
JOSC_define('_JOOMLACOMMENT_ENTERNOTIFY0', 'do not notify');
JOSC_define('_JOOMLACOMMENT_ENTERNOTIFY1', 'notify');
JOSC_define('_JOOMLACOMMENT_NOTIFYTXT1', 'notify if new post');
JOSC_define('_JOOMLACOMMENT_NOTIFYTXT0', 'do not notify if new post');
JOSC_define('_JOOMLACOMMENT_ENTEREMAIL', 'Email:');
JOSC_define('_JOOMLACOMMENT_AUTOMATICEMAIL', 'automatic');
JOSC_define('_JOOMLACOMMENT_ENTERWEBSITE', 'Website:');
JOSC_define('_JOOMLACOMMENT_ENTERTITLE', 'Title:');
JOSC_define('_JOOMLACOMMENT_SENDFORM', 'Send');
JOSC_define('_JOOMLACOMMENT_BY', 'by');

JOSC_define('_JOOMLACOMMENT_COLOR', 'color');
JOSC_define('_JOOMLACOMMENT_AQUA', 'aqua');
JOSC_define('_JOOMLACOMMENT_BLACK', 'black');
JOSC_define('_JOOMLACOMMENT_BLUE', 'blue');
JOSC_define('_JOOMLACOMMENT_FUCHSIA', 'fuchsia');
JOSC_define('_JOOMLACOMMENT_GRAY', 'gray');
JOSC_define('_JOOMLACOMMENT_GREEN', 'green');
JOSC_define('_JOOMLACOMMENT_LIME', 'lime');
JOSC_define('_JOOMLACOMMENT_MAROON', 'maroon');
JOSC_define('_JOOMLACOMMENT_NAVY', 'navy');
JOSC_define('_JOOMLACOMMENT_OLIVE', 'olive');
JOSC_define('_JOOMLACOMMENT_PURPLE', 'purple');
JOSC_define('_JOOMLACOMMENT_RED', 'red');
JOSC_define('_JOOMLACOMMENT_SILVER', 'silver');
JOSC_define('_JOOMLACOMMENT_TEAL', 'teal');
JOSC_define('_JOOMLACOMMENT_WHITE', 'white');
JOSC_define('_JOOMLACOMMENT_YELLOW', 'yellow');

JOSC_define('_JOOMLACOMMENT_SIZE', 'size');
JOSC_define('_JOOMLACOMMENT_TINY', 'tiny');
JOSC_define('_JOOMLACOMMENT_SMALL', 'small');
JOSC_define('_JOOMLACOMMENT_MEDIUM', 'medium');
JOSC_define('_JOOMLACOMMENT_LARGE', 'large');
JOSC_define('_JOOMLACOMMENT_HUGE', 'huge');

JOSC_define('_JOOMLACOMMENT_QUOTE', 'Quote');
JOSC_define('_JOOMLACOMMENT_REPLY', 'Reply');
JOSC_define('_JOOMLACOMMENT_EDIT', 'Edit');
JOSC_define('_JOOMLACOMMENT_DELETE', 'Delete');

JOSC_define('_JOOMLACOMMENT_UBB_WROTE', 'wrote:');
JOSC_define('_JOOMLACOMMENT_UBB_QUOTE', 'Quote:');
JOSC_define('_JOOMLACOMMENT_UBB_CODE', 'Code:');

JOSC_define('_JOOMLACOMMENT_FORMVALIDATE', 'Please insert at least a comment.');
JOSC_define('_JOOMLACOMMENT_FORMVALIDATE_EMAIL', 'To be notified, please enter your email');
JOSC_define('_JOOMLACOMMENT_FORMVALIDATE_CAPTCHA', 'Please input the anti-spam code that you can read in the image.');
JOSC_define('_JOOMLACOMMENT_FORMVALIDATE_CAPTCHATXT', 'Please input the anti-spam code that you can read in the image.');
JOSC_define('_JOOMLACOMMENT_FORMVALIDATE_CAPTCHA_FAILED', 'Anti-spam code is not correct. Please input the code that you can read in the image.');
JOSC_define('_JOOMLACOMMENT_MSG_DELETE', 'Are you sure you want to delete this comment?');
JOSC_define('_JOOMLACOMMENT_SAVINGFAILED', 'Failed sending comment!');
JOSC_define('_JOOMLACOMMENT_EDITINGFAILED', 'Failed editing comment!');
JOSC_define('_JOOMLACOMMENT_DELETINGFAILED', 'Failed deleting comment!');
JOSC_define('_JOOMLACOMMENT_REQUEST_ERROR','Request failed');

JOSC_define('_JOOMLACOMMENT_ONLYREGISTERED', 'Only registered users can write comments!');
JOSC_define('_JOOMLACOMMENT_ANONYMOUS', 'Anonymous');

JOSC_define('_JOOMLACOMMENT_ADDNEW', 'Add New');
JOSC_define('_JOOMLACOMMENT_DELETEALL', 'Delete All');
JOSC_define('_JOOMLACOMMENT_MSG_DELETEALL', 'Are you sure you want to delete all comments?');
JOSC_define('_JOOMLACOMMENT_RSS', 'RSS');

JOSC_define('_JOOMLACOMMENT_SEARCH', 'Search');
JOSC_define('_JOOMLACOMMENT_PROMPT_KEYWORD', 'Search Keyword');
JOSC_define('_JOOMLACOMMENT_SEARCH_ANYWORDS', 'Any words');
JOSC_define('_JOOMLACOMMENT_SEARCH_ALLWORDS', 'All words');
JOSC_define('_JOOMLACOMMENT_SEARCH_PHRASE', 'Exact phrase');
JOSC_define('_JOOMLACOMMENT_NOSEARCHMATCH', 'No topics or posts met your search criteria.');
JOSC_define('_JOOMLACOMMENT_SEARCHMATCH', 'Search found %d match.');
JOSC_define('_JOOMLACOMMENT_SEARCHMATCHES', 'Search found %d matches.');

JOSC_define('_JOOMLACOMMENT_BEFORE_APPROVAL', 'Your comment has been queued for moderation by site administrators and will be published after approval.');

/* -----------------------------------------------------------------------------
 * NEW OR MODIFIED IN THE 3.20
 * 
 */
JOSC_define('_JOOMLACOMMENT_COMMENTS_TITLE', 'Comments'); 	/* Will replace {_COMMENTS_2_4} in template */ 
JOSC_define('_JOOMLACOMMENT_COMMENTS_0', 'Comments'); 		/* for Read ON: 0 */ 
JOSC_define('_JOOMLACOMMENT_COMMENTS_1', 'Comment'); 	/* for Read ON: when only one comment */
JOSC_define('_JOOMLACOMMENT_COMMENTS_2_4', 'Comments'); 	/* for Read ON: 2 to 4 comments */
JOSC_define('_JOOMLACOMMENT_COMMENTS_MORE', 'Comments'); /* for Read ON: more than 4 */ 

JOSC_define('_JOOMLACOMMENT_EMPTYCOMMENT', 'Character problem. empty comment');

JOSC_define('_JOOMLACOMMENT_NOTIFY_NEW_SUBJECT', 'NewComment:{title} [from:{name}][notify:{notify}]');
JOSC_define('_JOOMLACOMMENT_NOTIFY_NEW_MESSAGE',		'<p>A user has posted a new comment to a content item you have subscribed <br />in <a href="{livesite}">{livesite}</a>:</p>'
											. 	'<p><b>Name: </b>{name}<br />'
											. 	'<b>Title: </b>{title}<br />'
											. 	'<b>Text: </b>{comment}<br />'
											. 	'<b>Content item: </b><a href="{linkURL}">{linkURL}</a></p>'
											. 	'<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'
											. 	'<p>To unsubscribe to this content item :<br />'
											.	'- if you are a registered user : logon, go to the post and edit this post to modify the notify value<br />'
											.	'- if you are an unregistered user : add a new post setting again your email but with \'do not notify\' value</p>'
											);
JOSC_define('_JOOMLACOMMENT_NOTIFY_TOBEAPPROVED_SUBJECT', 'ToBeApproved:{title} [from:{name}][notify:{notify}]');
JOSC_define('_JOOMLACOMMENT_NOTIFY_TOBEAPPROVED_MESSAGE', '<p>A user has posted a new comment and must be approved <br />in <a href="{livesite}">{livesite}</a>:</p>'
											. 	'<p><b>Name: </b>{name}<br />'
											. 	'<b>Title: </b>{title}<br />'
											. 	'<b>Text: </b>{comment}<br />'
											. 	'<b>Content item: </b><a href="{linkURL}">{linkURL}</a></p>'
											. 	'<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'
											);
JOSC_define('_JOOMLACOMMENT_NOTIFY_EDIT_SUBJECT', 'EditedComment:{title} [from:{name}][notify:{notify}]');
JOSC_define('_JOOMLACOMMENT_NOTIFY_EDIT_MESSAGE',	'<p>A user has edited a comment of the content item you have subscribed <br />in {livesite}:</p>'
											. 	'<p><b>Name: </b>{name}<br />'
											. 	'<b>Title: </b>{title}<br />'
											. 	'<b>Text: </b>{comment}<br />'
											. 	'<b>Content item: </b><a href="{linkURL}">{linkURL}</a></p>'
											. 	'<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'
											. 	'<p>To unsubscribe to this content item :<br />'
											.	'- if you are a registered user : logon, go to the post and edit this post to modify the notify value<br />'
											.	'- if you are an unregistered user : add a new post setting again your email but with \'do not notify\' value</p>'
											);
JOSC_define('_JOOMLACOMMENT_NOTIFY_PUBLISH_SUBJECT', 'PublishedComment:{title} has been published');
JOSC_define('_JOOMLACOMMENT_NOTIFY_PUBLISH_MESSAGE','<p>The following comment has been published <br />from <a href="{livesite}">{livesite}</a>:</p>'
											. 	'<p><b>Name: </b>{name}<br />'
											. 	'<b>Title: </b>{title}<br />'
											. 	'<b>Text: </b>{comment}<br />'
											. 	'<b>Content item: </b><a href="{linkURL}">{linkURL}</a></p>'
											. 	'<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'
											);        
JOSC_define('_JOOMLACOMMENT_NOTIFY_UNPUBLISH_SUBJECT', 'UnpublishedComment:{title} has been UNpublished');
JOSC_define('_JOOMLACOMMENT_NOTIFY_UNPUBLISH_MESSAGE','<p>The following comment has been UNpublished <br />from <a href="{livesite}">{livesite}</a>:</p>'
											. 	'<p><b>Name: </b>{name}<br />'
											. 	'<b>Title: </b>{title}<br />'
											. 	'<b>Text: </b>{comment}<br />'
											. 	'<b>Content item: </b><a href="{linkURL}">{linkURL}</a></p>'
											. 	'<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'
											);        
JOSC_define('_JOOMLACOMMENT_NOTIFY_DELETE_SUBJECT', 'DeletedComment:{title} has been deleted');
JOSC_define('_JOOMLACOMMENT_NOTIFY_DELETE_MESSAGE','<p>The following comment has been deleted <br />from <a href="{livesite}">{livesite}</a>:</p>'
											. 	'<p><b>Name: </b>{name}<br />'
											. 	'<b>Title: </b>{title}<br />'
											. 	'<b>Text: </b>{comment}<br />'
											. 	'<b>Content item: </b><a href="{linkURL}">{linkURL}</a></p>'
											. 	'<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'
											);    
JOSC_define('_JOOMLACOMMENT_MSG_NEEDREFRESH', '' );
JOSC_define('_JOOMLACOMMENT_RELOAD_CAPTCHA', 'clic to reload a new image');
											    
?>
