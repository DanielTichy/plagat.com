<?php
/**
 * uninstall.wafl.php
 * This file uninstalls the admin component, 
 * the plugin and the template and thus leaving no WAFL-traces
 * in Joomla!
 *
 * PHP version 5
 * 
 * @category   Installer
 * @package    Wafl
 * @subpackage Installer
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://www.wafl.ugent.be
 */
 
 /**
 * Defining the WAFL directory (plugins/system/wafl) 
 * in terms of a Joomla! constant.
 */
if (!defined('WAFL_DIR')) {
    define('WAFL_DIR', JPATH_PLUGINS . DS . 'system' . DS . 'wafl');
}

/**
 * This function will drop all created WAFL tables
 * 
 * @return void
 */
function _dropWaflTables()
{
    $database =& JFactory::getDBO();
    
    $query = 'DROP TABLE IF EXISTS `#__wafl_settings`';
    $database->setQuery($query);
    $database->query();

    $query = 'DROP TABLE IF EXISTS `#__modules_wafl`';
    $database->setQuery($query);
    $database->query();  
}

/**
 * This function will delete all database entries of
 * the WAFL plugins installed
 * 
 * @return void
 */
function _deletePluginEntries()
{
    $database =& JFactory::getDBO();
    
    $query = "DELETE FROM `#__plugins` WHERE `element`='wafl' AND `folder`='system'";
    $database->setQuery($query);
    $database->query();
    
    $query = "DELETE FROM `#__plugins` WHERE `element`='mobilecontent' AND `folder`='content'";
    $database->setQuery($query);
    $database->query();
    
    $query = "DELETE FROM `#__plugins` WHERE `element`='readmoremobile' AND `folder`='editors-xtd'";
    $database->setQuery($query);
    $database->query();
    
    $query = "DELETE FROM `#__plugins` WHERE `element`='flexiblecache' AND `folder`='system'";
    $database->setQuery($query);
    $database->query();
}

/**
 * This function will delete the installed WAFL module 
 * out of the database
 * 
 * @return void
 */
function _deleteModule()
{
    $database =& JFactory::getDBO();
    
    $query = "SELECT `id` FROM `#__modules` WHERE `module`='mod_wafl'";
    $database->setQuery($query);
    $id = $database->loadResult();
    
    $query = "DELETE FROM `#__modules` WHERE `id`=".$id;
    $database->setQuery($query);
    $database->query();
    
    $query = "DELETE FROM `#__modules_menu` WHERE `moduleid`=".$id;
    $database->setQuery($query);
    $database->query();
}

/**
 * This function takes care of the removal of the created 
 * files in the joomla plugins folder on the harddrive
 * 
 * @return void
 */
function _uninstallPlugin()
{
    // Main plugin
    if (file_exists(JPATH_PLUGINS . DS . 'system' . DS . 'wafl.php')) {
        JFile::delete(JPATH_PLUGINS . DS . 'system' . DS . 'wafl.php');
    }
    if (file_exists(JPATH_PLUGINS . DS . 'system' . DS . 'wafl.xml')) {
        JFile::delete(JPATH_PLUGINS . DS . 'system' . DS . 'wafl.xml');
    }
    if (file_exists(WAFL_DIR)) {
        JFolder::delete(WAFL_DIR);
    }
    
    // Content plugin
    if (file_exists(JPATH_PLUGINS . DS . 'content' . DS . 'mobilecontent.xml')) {
        JFile::delete(JPATH_PLUGINS . DS . 'content' . DS . 'mobilecontent.xml');
    }
    if (file_exists(JPATH_PLUGINS . DS . 'content' . DS . 'mobilecontent.php')) {
        JFile::delete(JPATH_PLUGINS . DS . 'content' . DS . 'mobilecontent.php');
    }
    
    // Editor plugin
    if (file_exists(JPATH_PLUGINS . DS . 'editors-xtd' . DS . 'readmoremobile.xml')) {
        JFile::delete(JPATH_PLUGINS . DS . 'editors-xtd' . DS . 'readmoremobile.xml');
    }
    if (file_exists(JPATH_PLUGINS . DS . 'editors-xtd' . DS . 'readmoremobile.php')) {
        JFile::delete(JPATH_PLUGINS . DS . 'editors-xtd' . DS . 'readmoremobile.php');
    } 
    
    // Cache plugin
    if (file_exists(JPATH_PLUGINS . DS . 'system' . DS . 'flexiblecache.php')) {
        JFile::delete(JPATH_PLUGINS . DS . 'system' . DS . 'flexiblecache.php');
    }
    if (file_exists(JPATH_PLUGINS . DS . 'system' . DS . 'flexiblecache.xml')) {
        JFile::delete(JPATH_PLUGINS . DS . 'system' . DS . 'flexiblecache.xml');
    }
}

/**
 * This function takes care of the removal of the created 
 * template in the joomla template folder on the harddrive
 * 
 * @return void
 */
function _uninstallTemplate()
{
    if (file_exists(JPATH_ROOT .DS . 'templates' . DS . 'wafl')) {
        JFolder::delete(JPATH_ROOT .DS . 'templates' . DS . 'wafl');
    }
}

/**
 * This function takes care of the removal of the created 
 * module in the joomla modules folder on the harddrive
 * 
 * @return void
 */
function _uninstallModule()
{
    if (file_exists(JPATH_ROOT .DS . 'modules' . DS . 'mod_wafl' . DS . 'mod_wafl.php')) {
        JFile::delete(JPATH_ROOT .DS . 'modules' . DS . 'mod_wafl' . DS . 'mod_wafl.php');
    }
    if (file_exists(JPATH_ROOT .DS . 'modules' . DS . 'mod_wafl' . DS . 'mod_wafl.xml')) {
        JFile::delete(JPATH_ROOT .DS . 'modules' . DS . 'mod_wafl' . DS . 'mod_wafl.xml');
    }
    if (file_exists(JPATH_ROOT .DS . 'modules' . DS . 'mod_wafl' . DS . 'tmpl')) {
        JFolder::delete(JPATH_ROOT .DS . 'modules' . DS . 'mod_wafl' . DS . 'tmpl');
    }
    if (file_exists(JPATH_ROOT .DS . 'modules' . DS . 'mod_wafl')) {
        JFolder::delete(JPATH_ROOT .DS . 'modules' . DS . 'mod_wafl');
    }
}

/**
 * This function deletes the installfiles from the harddrive
 * 
 * @return void
 */
function _deleteInstallFiles()
{
    if (file_exists(JPATH_ROOT .DS . 'install.wafl.php')) {
        JFile::delete(JPATH_ROOT .DS . 'install.wafl.php');
    }
    if (file_exists(JPATH_ROOT .DS . 'install.wafl.xml')) {
        JFile::delete(JPATH_ROOT .DS . 'install.wafl.xml');
    }
}

/**
 * This function does the actual uninstalling
 * 
 * @return void
 */
function com_uninstall()
{	
    /**
     * ==========================
     * = BEGIN DATABASE CLEANUP =
     * ==========================
     */   
    // Drop tables
    _dropWaflTables();
    // Delete created plugins
    _deletePluginEntries();
    // Delete created module
    _deleteModule();
    /**
     * ========================
     * = END DATABASE CLEANUP =
     * ========================
     */ 
   
    /**
     * ====================
     * = BEGIN HD CLEANUP =
     * ====================
     */  
    // Uninstall plugin
    _uninstallPlugin();
    // Uninstall template
    _uninstallTemplate();
    // Uninstall module
    _uninstallModule();
    // Delete install files
    _deleteInstallFiles();
    /**
     * ==================
     * = END HD CLEANUP =
     * ==================
     */
}
?>