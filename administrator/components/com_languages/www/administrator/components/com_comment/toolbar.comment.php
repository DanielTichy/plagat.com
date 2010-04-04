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

require_once($mainframe->getPath('toolbar_html'));
require_once($mainframe->getPath('toolbar_default'));

//$cid 	= JOSC_library::JOSCGetArrayInts( 'cid' ); /* id will be used if direct link  */
//$set_id	= intval(mosGetParam( $_REQUEST, 'id', '0' ));  /* need the same in toolbar ...*/
//$set_id	= ( $set_id ? $set_id : intval( $cid[0] ) );

$component = JArrayHelper::getValue( $_REQUEST, 'component', '' );

switch ($task) {
    case "new":
        menucomment::FILE_MENU();
        break;

    case "editC":
        menucomment::FILE_MENU();
        break;

    case "edit":
        menucomment::FILE_MENU();
        break;

    case "language":
        menucomment::LANG_MENU();
        break;

    case "about":
        menucomment::ABOUT_MENU();
        break;

    case "settings":
        menucomment::SETTINGS_MENU();
        break;

    case "settingsexpert":
        menucomment::SETTINGSEXPERT_MENU();
        break;

    case "settingssimple":
    case "settingseditsimple":
        menucomment::CONFIGSIMPLE_MENU();
        break;

    case "settingsnew":
    case "settingsedit":
        menucomment::CONFIG_MENU();
        break;

    case "settingsnewexpert":
    case "settingseditexpert":
        menucomment::CONFIGEXPERT_MENU();
        break;

    case "importcomment":
        menucomment::IMPORT_MENU(array( "apply"=>$task, "import"=>"importexecuteSel", "importall"=>"importexecuteAll"));
        break;

    case "importexecuteAll":
    	break;

    case "convertlcharset":
        menucomment::JUST_BACK();
        break;

    default:
        menucomment::MENU_DEFAULT($component);
        break;
}

?>