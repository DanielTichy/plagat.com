<?php
/**
 * @version $Id: toolbar.waticketsystem.php 66 2009-03-31 14:18:46Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL
 * @package wats
 */

// Don't allow direct linking
defined('_JEXEC') or die('Restricted Access');

require_once( $mainframe->getPath( 'toolbar_html' ) );



if ($act)
{
	switch ( $act )
	{
		case 'configure':
			menuWATS::WATS_EDIT();
			break;
		case 'ticket':
		case 'database':
		case 'about':
			// no menus
			break;
		case 'css':
			menuWATS::WATS_EDIT_BACKUP();
			break;
		default:
			switch ( $task )
			{
				case 'edit';
				case 'view';
					menuWATS::WATS_EDIT();
					break;
				case 'add';
					menuWATS::WATS_NEW();
					break;
				default:
					menuWATS::WATS_LIST();
					break;
			}
			break;
	}
}
?>