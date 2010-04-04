<?php defined('_JEXEC')  or die('Direct Access to this location is not allowed.');

/**
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
require_once(JPATH_SITE."/administrator/components/com_comment/class.config.comment.php");

function existsAkoTable()
{
    JFactory::getDBO()->setQuery('');
}

class JOSCmosMenubar extends JToolbarHelper {
    
}

class menucomment {
    
    function MENU_DEFAULT($component='')
    {
    	
        

	    $config = new JOSC_defaultconfig($component);
    	$config->load();
        if (strpos(strtoupper($config->_local_charset), "UTF-8")===false) {
          		JOSCmosMenuBar::custom('convertlcharset', 'extensions_f2.png', 'extensions_f2.png', "Convert to $config->_local_charset", true);       	
        }
        unset($config);

        JOSCmosMenuBar::publishList();
        JOSCmosMenuBar::unpublishList();
        JOSCmosMenuBar::addNew();
        JOSCmosMenuBar::editList();
        JOSCmosMenuBar::deleteList(" Users will not be notified. To notify users use line delete button !");
        JOSCmosMenuBar::spacer();
        
    }
    
    function SETTINGS_MENU()
    {
//        JOSCmosMenuBar::publishList();
//        JOSCmosMenuBar::unpublishList();

		/* expert mode is not still available - only used for joomlacomment DEMO */		
   		if (!(strpos(JURI::base(), 'acgeorgette.net')===false)) JOSCmosMenuBar::custom('settingsexpert', 'extensions_f2.png', 'extensions_f2.png', "Expert Mode", false);       	
        JOSCmosMenuBar::addNew('settingsnew');
        JOSCmosMenuBar::editList('settingsedit'); /* $task='edit', $alt='Edit' */
        JOSCmosMenuBar::deleteList('', 'settingsremove'); /* $msg='', $task='remove', $alt='Delete' */
        JOSCmosMenuBar::spacer();
        
    }

    function SETTINGSEXPERT_MENU()
    {
    	
        

//        JOSCmosMenuBar::publishList();
//        JOSCmosMenuBar::unpublishList();
        JOSCmosMenuBar::addNew('settingsnewexpert');
        JOSCmosMenuBar::editList('settingseditexpert'); /* $task='edit', $alt='Edit' */
        JOSCmosMenuBar::deleteList('', 'settingsremove'); /* $msg='', $task='remove', $alt='Delete' */
        JOSCmosMenuBar::spacer();
        
    }

    function CONFIG_MENU()
    {
        
        JOSCmosMenuBar::save('savesettings');
        JOSCmosMenuBar::apply('applysettings');
        JOSCmosMenuBar::cancel('settings');
        JOSCmosMenuBar::spacer();
        
    }

    function CONFIGEXPERT_MENU()
    {
        
        JOSCmosMenuBar::save('savesettingsexpert');
        JOSCmosMenuBar::apply('applysettingsexpert');
        JOSCmosMenuBar::cancel('settingsexpert');
        JOSCmosMenuBar::spacer();
        
    }

    function CONFIGSIMPLE_MENU()
    {
        
        JOSCmosMenuBar::save('savesettingssimple');
        JOSCmosMenuBar::apply('applysettingssimple');
//        JOSCmosMenuBar::cancel('');  /* -> view comments */
        JOSCmosMenuBar::spacer();
        
    }

    function IMPORT_MENU($josctask)
    {
        
        JOSCmosMenuBar::apply($josctask['apply'], 'Preview');
		//mosMenuBar::custom($josctask['import'], 'move.png', 'move_f2.png', 'Import selected' );
      	JOSCmosMenuBar::custom($josctask['importall'], 'move.png', 'move_f2.png', 'Import ALL', false);
      	JOSCmosMenuBar::spacer();
        
    }

    function FILE_MENU()
    {
        
        JOSCmosMenuBar::save();
        JOSCmosMenuBar::cancel();
        JOSCmosMenuBar::spacer();
        
    }
    function ABOUT_MENU()
    {
        
        JOSCmosMenuBar::back();
        JOSCmosMenuBar::spacer();
        
    }

    function JUST_BACK()
    {
        
        JOSCmosMenuBar::back();
        JOSCmosMenuBar::spacer();
        
    }

}

?>