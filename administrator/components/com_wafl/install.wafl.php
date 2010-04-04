<?php
/**
 * install.wafl.php
 *
 * PHP version 5 - This file takes care of the installing
 * of the admin component, the plugin, the modules and the 
 * wafl template in 1 zip
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
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Defining the WAFL directory (plugins/system/wafl) 
 * in terms of a Joomla! constant.
 */
if (!defined('WAFL_DIR')) {
    define('WAFL_DIR', JPATH_PLUGINS . DS . 'system' . DS . 'wafl');
}

/**
 * PCLZip lib
 *
 */
require_once JPATH_ADMINISTRATOR.DS."includes".DS."pcl".DS."pclzip.lib.php";

/**
 * Function which sets the message array for i18n
 *
 * @return array The message array
 */
function _setMessages(){
    return array (
        'en_US'=> array(
           'This component is released under the GNU/GPL version 2 License.' =>
               'This component is released under the GNU/GPL version 2 License.',
           'All copyright statements must be kept.' => 'All copyright statements must be kept.',
           'Visit us:' => 'Visit us:',
           'Creating WAFL Database Tables...' => 'Creating WAFL Database Tables...',
           'Inserting values into WAFL settings...' => 'Inserting values into WAFL settings...',
           'Deleting existing WAFL menu items...' => 'Deleting existing WAFL menu items...',
           'Cleaning conflicting WAFL directories...' => 'Cleaning conflicting WAFL directories...',
           'Install WAFL System Plugin...' => 'Install WAFL System Plugin...',
    	   'Install WAFL Editor Plugin...' => 'Install WAFL Editor Plugin...',
   		   'Install WAFL Content Plugin...' => 'Install WAFL Content Plugin...',
           'Install WAFL Cache Plugin...' => 'Install WAFL Cache Plugin...',
    	   'Install WAFL Template...' => 'Install WAFL Template...',
    	   'Install WAFL Admin Component...' => 'Install WAFL Admin Component...',
           'Creating WAFL Component entry in database...' => 'Creating WAFL Component entry in database...',
		   'Install WAFL Module...' => 'Install WAFL Module...',
           'Clean Cache...' => 'Clean Cache...',
           'WAFL Extension successfully installed' => 'WAFL Extension successfully installed',
           'FAILED' => 'FAILED'    
           ),
    
        'en_GB'=> array(
           'This component is released under the GNU/GPL version 2 License.' =>
               'This component is released under the GNU/GPL version 2 License.',
           'All copyright statements must be kept.' => 'All copyright statements must be kept.',
           'Visit us:' => 'Visit us:',
           'Creating WAFL Database Tables...' => 'Creating WAFL Database Tables...',
           'Inserting values into WAFL settings...' => 'Inserting values into WAFL settings...',
           'Deleting existing WAFL menu items...' => 'Deleting existing WAFL menu items...',
           'Cleaning conflicting WAFL directories...' => 'Cleaning conflicting WAFL directories...',
           'Install WAFL System Plugin...' => 'Install WAFL System Plugin...',
    	   'Install WAFL Editor Plugin...' => 'Install WAFL Editor Plugin...',
   		   'Install WAFL Content Plugin...' => 'Install WAFL Content Plugin...',
           'Install WAFL Cache Plugin...' => 'Install WAFL Cache Plugin...',
    	   'Install WAFL Template...' => 'Install WAFL Template...',
    	   'Install WAFL Admin Component...' => 'Install WAFL Admin Component...',
           'Creating WAFL Component entry in database...' => 'Creating WAFL Component entry in database...',
		   'Install WAFL Module...' => 'Install WAFL Module...',
           'Clean Cache...' => 'Clean Cache...',
           'WAFL Extension successfully installed' => 'WAFL Extension successfully installed',
           'FAILED' => 'FAILED'
           ),
    
        'nl_BE'=> array(
           'This component is released under the GNU/GPL version 2 License.' =>
               'Deze component is vrijgegeven onder de GNU/GPL versie 2 licentie.',
           'All copyright statements must be kept.' => 'Alle auteursrechten voorbehouden aan WAFL.',
           'Visit us:' => 'Bezoek ons:',
           'Creating WAFL Database Tables...' => 'Aanmaken van WAFL database tabellen...',
           'Inserting values into WAFL settings...' => 'Waarden in WAFL Settings steken...',
           'Deleting existing WAFL menu items...' => 'Verwijderen van bestaande WAFL menu items...',
           'Cleaning conflicting WAFL directories...' => 'Opkuisen van conflicterende WAFL mappen...',
           'Install WAFL System Plugin...' => 'Installatie WAFL System Plugin...',
    	   'Install WAFL Editor Plugin...' => 'Installatie WAFL Editor Plugin...',
   		   'Install WAFL Content Plugin...' => 'Installatie WAFL Content Plugin...',
           'Install WAFL Cache Plugin...' => 'Installatie WAFL Cache Plugin...',
    	   'Install WAFL Template...' => 'Installatie WAFL Template...',
    	   'Install WAFL Admin Component...' => 'Installatie WAFL Admin Component...',
           'Creating WAFL Component entry in database...' => 'Aanmaken WAFL Component in databank...',
		   'Install WAFL Module...' => 'Installatie WAFL Module...',
           'Clean Cache...' => 'Cachegeheugen verwijderen...',
           'WAFL Extension successfully installed' => 'Installatie WAFL Extensie successvol',
           'FAILED' => 'GEFAALD'
           ),
    
        'nl_NL'=> array(
           'This component is released under the GNU/GPL version 2 License.' =>
               'Deze component is vrijgegeven onder de GNU/GPL versie 2 licentie.',
           'All copyright statements must be kept.' => 'Alle auteursrechten voorbehouden aan WAFL.',
           'Visit us:' => 'Bezoek ons:',
           'Creating WAFL Database Tables...' => 'Aanmaken van WAFL database tabellen...',
           'Inserting values into WAFL settings...' => 'Waarden in WAFL Settings steken...',
           'Deleting existing WAFL menu items...' => 'Verwijderen van bestaande WAFL menu items...',
           'Cleaning conflicting WAFL directories...' => 'Opkuisen van conflicterende WAFL mappen...',
           'Install WAFL System Plugin...' => 'Installatie WAFL System Plugin...',
    	   'Install WAFL Editor Plugin...' => 'Installatie WAFL Editor Plugin...',
   		   'Install WAFL Content Plugin...' => 'Installatie WAFL Content Plugin...',
           'Install WAFL Cache Plugin...' => 'Installatie WAFL Cache Plugin...',
    	   'Install WAFL Template...' => 'Installatie WAFL Template...',
    	   'Install WAFL Admin Component...' => 'Installatie WAFL Admin Component...',
           'Creating WAFL Component entry in database...' => 'Aanmaken WAFL Component in databank...',
		   'Install WAFL Module...' => 'Installatie WAFL Module...',
           'Clean Cache...' => 'Cachegeheugen verwijderen...',
           'WAFL Extension successfully installed' => 'Installatie WAFL Extensie successvol',
           'FAILED' => 'GEFAALD'
           ),
    
        'fr_FR'=> array(
           'This component is released under the GNU/GPL version 2 License.' =>
               'Cette composante est publ&eacute; sous GNU/GPL version 2 licence.',
           'All copyright statements must be kept.' => 'Tous les d&eacute;clarations des droits d\'auteur doivent &ecirc;tre conserv&eacute;s.',
           'Visit us:' => 'Visitez-nous:',
           'Creating WAFL Database Tables...' => 'Cr&eacute;er tables de WAFL...',
           'Inserting values into WAFL settings...' => 'Installation des valeurs dans table des param&ecirc;tres...',
           'Deleting existing WAFL menu items...' => 'Suppression des &eacute;l&eacute;ments de menu existants...',
           'Cleaning conflicting WAFL directories...' => 'Nettoyage des &eacute;lements de WAFL contradictoires...',
           'Install WAFL System Plugin...' => 'Installation du WAFL System Plugin...',
           'Install WAFL Editor Plugin...' => 'Installation du WAFL Editor Plugin...',
           'Install WAFL Content Plugin...' => 'Installation du WAFL Content Plugin...',
           'Install WAFL Cache Plugin...' => 'Installation du WAFL Cache Plugin...',
    	   'Install WAFL Template...' => 'Installation du WAFL Template...',
    	   'Install WAFL Admin Component...' => 'Installatiion de la WAFL Admin Composante...',
           'Creating WAFL Component entry in database...' => 'Cr&eacute;er WAFL Composante dans database...',
		   'Install WAFL Module...' => 'Cr&eacute;er WAFL Module',
           'Clean Cache...' => 'Nettoyage de la m&eacute;moire cache...',
           'WAFL Extension successfully installed' => 'Installation de la extension de WAFL fini',
           'FAILED' => 'D&Eacute;FAILLI'
           ),
       );
}

/**
 * This function returns the installation locale to be used
 * 
 * @param string $locale The full locale string
 * 
 * @return string The locale used for installation
 */
function _getInstallLocale($locale)
{
	$langarray = array('en_GB', 'en_US', 'nl_BE', 'nl_NL', 'fr_FR');
	$ret = substr($locale,0,5);
	if (in_array($ret, $langarray)) {
		return $ret;
	} else {
		return 'en_GB';
	}
}

/**
 * This function echos the install table header in Joomla
 * 
 * @param array  $messages The i18n array
 * @param string $locale   The locale used
 * 
 * @return void
 */
function _printTableHeader($messages, $locale)
{
    $image = 'logowafl';

    // First a simple check which OS the user is working on
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (strstr($useragent, 'Mac') || strstr($useragent, 'Linux') || strstr($useragent, 'Unix')) {
        $image.='.png';
    } else {
        // Set the image for Win users or other OS'es to jpg
        $image.='.jpg';
    }
    
   	echo '
	<table class="adminlist" style="width:50%" border="0" align="center">
		<tr>
			<td style="width:100px" align="center">
				<img src="components/com_wafl/images/'.$image.'" alt="WAFL logo"/>
			</td>
			<td>
				<h2>Be hungry, Think WAFL</h2>
				<p>
				2009 &copy; - Wafl.<br />'
				.$messages[$locale]['This component is released under the GNU/GPL version 2 License.'].'<br />'
				.$messages[$locale]['All copyright statements must be kept.'].'
				</p>
				<p>'.$messages[$locale]['Visit us:'].' <a href="http://www.wafl.ugent.be">www.wafl.ugent.be</a></p>
			</td>
		</tr>
		<tr>
			<td style="padding:1em" colspan="2">
				<code>';
}


/**
 * This function echos the install table footer in Joomla
 * 
 * @param array  $messages The i18n array
 * @param string $locale   The locale used
 * 
 * @return void
 */
function _printTableFooter($messages, $locale) 
{
	echo '</code>
			</td>
		</tr>
		<tr>
			<td style="padding:1em" colspan="2" align="center">
				<strong>'.$messages[$locale]['WAFL Extension successfully installed'].'</strong><br/>
			</td>
		</tr>
	</table>';
}

/**
 * This function creates the jos_modules_wafl database table
 * 
 * @return boolean False if database operation was successful
 */
function _createModuleTable()
{
    $database =& JFactory::getDBO();
    $modTableFail = false;   
	// Create tables
	$query = 'CREATE TABLE `#__modules_wafl` (
    `id` int NOT NULL AUTO_INCREMENT, 
    `module_id` int NOT NULL,
    `published` int NOT NULL DEFAULT 1,
    `ordering` int NOT NULL DEFAULT 0,
     PRIMARY KEY(id),
     Foreign Key (module_id) references `#__modules(id)`
	 ) CHARSET=UTF8';
	$database->setQuery($query);
	if (!$database->query()) {
	    $modTableFail=true;
	}
	
	// Fill in jos_modules_wafl
    $insertFail = _fillInModulesTable();

    if($modTableFail === true || $insertFail === true) {
        return true;
    } else {
        return false;
    }
}

/**
 * This function fills in the jos_modules_wafl table
 * 
 * @return boolean $modTableFail False if database operation was successful
 */
function _fillInModulesTable()
{
    $database =& JFactory::getDBO();
    $modTableFail = false;
    
    $query = 'SELECT '
        . $database->nameQuote('id')
        . ' FROM '.$database->nameQuote('#__modules');
    $database->setQuery($query);
    if (!$database->query()) {
        $modTableFail = true;
    }
	$array_modules = $database->loadResultArray();
	foreach ($array_modules as $mod) {
	    $query = 'INSERT INTO '
	                .$database->nameQuote('#__modules_wafl').
	                ' (`module_id`,`published`, `ordering`) 
	                 VALUES ('.$mod.', 1, '.$mod.')';
	    $database->setQuery($query);
        if(!$database->query()){
            $modTableFail = true;
        }
	}
	return $modTableFail;
}

/**
 * This function creates the jos_wafl_settings database table
 * 
 * @return boolean $settingsTableFail False if database operation was successful
 */
function _createSettingsTable()
{
    $database =& JFactory::getDBO();
    $settingsTableFail = false;
	
    $query = 'CREATE TABLE `#__wafl_settings` (
    `id` int NOT NULL AUTO_INCREMENT, 
    `siruna_login` varchar(250),
    `siruna_pass` varchar(250),
    `siruna_url` varchar(250),
    `siruna_mobile_url` VARCHAR(250)NOT NULL,
    `device_detection` int NOT NULL DEFAULT 0,
    `redirect_mobile_url` VARCHAR(250)  NOT NULL,
    `redirect_to_template` VARCHAR(250)  NOT NULL,
    `base_template` VARCHAR(250)  NOT NULL,
    `siruna_port` int DEFAULT 80,
	`base_url` varchar(250),
	`siruna_user` varchar(250) NOT NULL,
	`option` int NOT NULL DEFAULT 1, 
     PRIMARY KEY(id)
	) CHARSET=UTF8';
    
	$database->setQuery($query);
	if (!$database->query()) {
	    $settingsTableFail=true;
	}
    
	return $settingsTableFail;
}

/**
 * This function inserts standard values in the jos_wafl_settings database table
 * 
 * @return boolean $insertSettingsFail False if database operation was successful
 */
function _insertSettingsValues()
{
    $database =& JFactory::getDBO();
    $insertSettingsFail = false;
    $query='INSERT INTO `#__wafl_settings` (`siruna_login`, `siruna_pass`, `siruna_url`, `siruna_mobile_url`, `device_detection`,
												`redirect_mobile_url`, `redirect_to_template`, `base_template`,
												`siruna_port`, `base_url`, `siruna_user`, `option`)
			VALUES ("not set", "not set", "not set", "not set", 0,
					"not set", "not set", "not set",
					8080, "not set", "not set", 1)';
	$database->setQuery($query);
	if (!$database->query()) {
	    $insertSettingsFail=true;
	}
	
	return $insertSettingsFail;
}

/**
 * This function returns the com_wafl component id out of the 
 * jos_components database table
 * 
 * @return Integer $id The id of com_wafl
 */
function _selectWaflComponentID()
{
    $database =& JFactory::getDBO();
    $database->setQuery("SELECT `id` FROM `#__components` WHERE `option` = 'com_wafl' AND `parent`='0'");
    $oldid = $database->loadResult();
    return $oldid;
}

/**
 * This function deletes an already existing com_wafl entry out of the database
 * 
 * @return boolean $deleteFail False if database operation was successful
 */
function _deleteComponentEntry()
{
    $database =& JFactory::getDBO();
    $deleteFail = false;
    $database->setQuery("DELETE FROM `#__components` WHERE `option`= 'com_wafl'");
    if (!$database->query()) {
        $deleteFail = true;
    }
    return $deleteFail;
}

/**
 * This function cleans out any directory conflicts in the administrator section
 * 
 * @return void
 */
function _cleanAdminConflicts()
{
    if (file_exists(JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS."controllers")) {
        JFolder::delete(JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS."controllers");
    }
    if (file_exists(JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS."models")) {
        JFolder::delete(JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS."models");
    }
    if (file_exists(JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS."tables")) {
        JFolder::delete(JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS."tables");
    }
	if (file_exists(JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS."views")) {
        JFolder::delete(JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS."views");
    }
}

/**
 * This function cleans out any directory conflicts in the plugin section
 * 
 * @see _cleanMainPlugin()
 * @see _cleanContentPlugin()
 * @see _cleanEditorPlugin()
 * 
 * @return void
 */
function _cleanPluginConflicts()
{
    _cleanMainPlugin();
    _cleanContentPlugin();
    _cleanEditorPlugin();
}

/**
 * This function cleans out any directory conflicts in the Main plugin section
 * 
 * @return void
 */
function _cleanMainPlugin()
{
    if (file_exists(WAFL_DIR.DS."broker")) {
        JFolder::delete(WAFL_DIR.DS."broker");
    }
    if (file_exists(WAFL_DIR.DS."lib")) {
        JFolder::delete(WAFL_DIR.DS."lib");
    }
    if (file_exists(WAFL_DIR.DS."services")) {
        JFolder::delete(WAFL_DIR.DS."services");
    }
    if (file_exists(WAFL_DIR.DS."util")) {
        JFolder::delete(WAFL_DIR.DS."util");
    }
	if (file_exists(WAFL_DIR)) {
        JFolder::delete(WAFL_DIR.DS);
    }
    
}

/**
 * This function cleans out any directory conflicts in the content plugin section
 * 
 * @return void
 */
function _cleanContentPlugin()
{
    if (file_exists(JPATH_PLUGINS.DS."content".DS."mobilecontent.php")) {
        JFile::delete(JPATH_PLUGINS.DS."content".DS."mobilecontent.php");
    }
	if (file_exists(JPATH_PLUGINS.DS."content".DS."mobilecontent.xml")) {
        JFile::delete(JPATH_PLUGINS.DS."content".DS."mobilecontent.xml");
    }
}

/**
 * This function cleans out any directory conflicts in the editor plugin section
 * 
 * @return void
 */
function _cleanEditorPlugin()
{
    if (file_exists(JPATH_PLUGINS.DS."content".DS."readmoremobile.php")) {
        JFile::delete(JPATH_PLUGINS.DS."content".DS."readmoremobile.php");
    }
	if (file_exists(JPATH_PLUGINS.DS."content".DS."readmoremobile.xml")) {
        JFile::delete(JPATH_PLUGINS.DS."content".DS."readmoremobile.xml");
    }
}

/**
 * This function cleans out any directory conflicts in the editor plugin section
 * 
 * @return void
 */
function _cleanModuleConflicts()
{
	if (file_exists(JPATH_ROOT.DS."modules".DS."mod_wafl")) {
        JFolder::delete(JPATH_ROOT.DS."modules".DS."mod_wafl");
    }
}

/**
 * This function takes care of the valid installation of the main 
 * Wafl plugin
 * 
 * @see _findExistingPlugin()
 * @see _insertPluginDB()
 * 
 * @return boolean $plugfail False if plugin installation was successful
 */
function _installMainPlugin()
{
    $plugfail = false;
    
    $archive = new PclZip(JPATH_ROOT.DS."components".DS."com_wafl".DS."pluginwafl.zip");
    $list = $archive->extract(PCLZIP_OPT_PATH, JPATH_PLUGINS.DS."system".DS);
    
    $id = _findWaflPlugin();

    // Register if needed & publish
    if (empty($id)) {
        $plugfail = _insertPluginDB();
    }
    unset($archive);
    
    return $plugFail;
}

/**
 * This function checks whether an entry for our wafl plugin 
 * in the jos_plugins database table already exists or not.
 * It returns the id, otherwise an empty id
 *  
 * @return Integer $id The id of the found plugin, otherwise empty
 */
function _findWaflPlugin()
{
    $database =& JFactory::getDBO();
    $database->setQuery("SELECT `id` FROM `#__plugins` WHERE `element`='wafl' AND `folder`='system'");
    $id = $database->loadResult();
    return $id;
}

/**
 * This function is solely called when there isn't an existing entry
 * in the jos_plugins database table for our wafl plugin
 * 
 * @return boolean $insertPlugFail False if plugin insertion was successful
 */
function _insertPluginDB()
{
    $insertPlugFail = false;
    $database =& JFactory::getDBO();
    $database->setQuery("REPLACE INTO `#__plugins` SET `name`='System - Wafl', 
        						`element`='wafl', `folder`='system', `access`=0, `ordering`='1', `published`='1'");
	if (!$database->query()) {
		$insertPlugFail = true;
	} 
	return $insertPlugFail;
}

/**
 * This function takes care of the valid installation of the  
 * editor plugin
 * 
 * @see _findEditorPlugin()
 * @see _insertEditorPluginDB()
 * 
 * @return boolean $plugfail False if plugin installation was successful
 */
function _installEditorPlugin()
{
    $plugfail = false;
    
    $archive = new PclZip(JPATH_ROOT.DS."components".DS."com_wafl".DS."editorwafl.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH,JPATH_PLUGINS.DS."editors-xtd".DS);

    $id = _findEditorPlugin();
    
    // Register if needed & publish
    if (empty($id)) {
        $plugfail = _insertEditorPluginDB();
    }
    unset($archive);   
    
    return $plugfail;
}

/**
 * This function checks whether an entry for the editor plugin 
 * in the jos_plugins database table already exists or not.
 * It returns the id, otherwise an empty id
 *  
 * @return Integer $id The id of the found plugin, otherwise empty
 */
function _findEditorPlugin()
{
    $database =& JFactory::getDBO();
    $database->setQuery("SELECT `id` FROM `#__plugins` WHERE `element`='readmoremobile' AND `folder`='editors-xtd'");
    $id = $database->loadResult();
    return $id;
}

/**
 * This function is solely called when there isn't an existing entry
 * in the jos_plugins database table for our editor plugin
 * 
 * @return boolean $insertEditorFail False if plugin insertion was successful
 */
function _insertEditorPluginDB()
{
    $insertEditorFail = false;
    $database =& JFactory::getDBO();
    $database->setQuery("REPLACE INTO `#__plugins` SET `name`='Button - Mobile Readmore', 
        						`element`='readmoremobile', `folder`='editors-xtd', `access`=0,`published`='1'");
	if(!$database->query()) {
		$insertEditorFail = true;
	}
	return $insertEditorFail;
}

/**
 * This function takes care of the valid installation of the  
 * editor plugin
 * 
 * @see _findContentPlugin()
 * @see _insertContentPluginDB()
 * 
 * @return boolean $plugfail False if plugin installation was successful
 */
function _installContentPlugin()
{
    $plugfail = false;
    
    $archive = new PclZip(JPATH_ROOT.DS."components".DS."com_wafl".DS."contentwafl.zip");
	$list = $archive->extract(PCLZIP_OPT_PATH,JPATH_PLUGINS.DS."content".DS);

    $id = _findContentPlugin();
    
    // Register if needed & publish
    if (empty($id)) {
        $plugfail = _insertContentPluginDB();
    }
    unset($archive);   
    
    return $plugfail;
}

/**
 * This function checks whether an entry for the content plugin 
 * in the jos_plugins database table already exists or not.
 * It returns the id, otherwise an empty id
 *  
 * @return Integer $id The id of the found plugin, otherwise empty
 */
function _findContentPlugin()
{
    $database =& JFactory::getDBO();
    $database->setQuery("SELECT `id` FROM `#__plugins` WHERE `element`='mobilecontent' AND `folder`='content'");
    $id = $database->loadResult();
    return $id;
}

/**
 * This function is solely called when there isn't an existing entry
 * in the jos_plugins database table for our content plugin
 * 
 * @return boolean $insertContentFail False if plugin insertion was successful
 */
function _insertContentPluginDB()
{
    $insertContentFail = false;
    $database =& JFactory::getDBO();
    $database->setQuery("REPLACE INTO `#__plugins` SET `name`='Content - Mobile Content', 
        						`element`='mobilecontent', `folder`='content', `access`=0, `published`='1', 
        						params='showtitle=1\nshowauthor=1\nshowcreatedate=1\nshowmodifydate=1\nshowpdficon=1\nshowmailicon=1\nshowprinticon=1\nreadmore=0\nautoreadmore=0\nbase=words\nnumber=50\n\n'");
	if(!$database->query()) {
		$insertContentFail = true;
	}
	return $insertContentFail;
}

/**
 * This function will install our home-made template correctly
 * 
 * @return void
 */
function _installWaflTemplate()
{
    $archive = new PclZip(JPATH_ROOT.DS."components".DS."com_wafl".DS."templatewafl.zip");
    $list = $archive->extract(PCLZIP_OPT_PATH,JPATH_ROOT.DS."templates".DS."wafl".DS);
    unset($archive);
}

/**
 * This function will install the admin part of our Joomla extension
 * 
 * @see _insertMainComponent()
 * @see _insertSubmenuComponents()
 * 
 * @return boolean $adminFail False if installation was succesful
 */
function _installWaflAdmin()
{
	$adminFail = false;
    
	$archive = new PclZip(JPATH_ROOT.DS."components".DS."com_wafl".DS."adminwafl.zip");
    $list = $archive->extract(PCLZIP_OPT_PATH, JPATH_ADMINISTRATOR.DS."components".DS."com_wafl".DS);
	unset($archive);
	
	$mainFail = _insertMainComponent();
	$subFail = _insertSubmenuComponents();
	
	if($mainFail === true || $subFail === true) {
	    $adminFail = true;
	}
	
	return $adminFail;
}

/**
 * This function inserts the main admin component correctly in the database
 * 
 * @return boolean $mainFail False if installation was successful
 */
function _insertMainComponent()
{
    $mainFail = false;
    $database =& JFactory::getDBO();
	$query='INSERT INTO `#__components` (`name`, `link`, `menuid`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, 
							`admin_menu_img`, `iscore`, `enabled`)
			VALUES ("Wafl", "option=com_wafl", 0, "option=com_wafl", "Wafl", "com_wafl", 0, 
							"js/ThemeOffice/component.png", 0, 1)';
    $database->setQuery($query);
    if (!$database->query()) {
        $mainFail = true;
    }   
    return $mainFail;
}

/**
 * This function inserts the submenu admin components correctly in the database
 * 
 * @return boolean $subFail False if installation was successful
 */
function _insertSubmenuComponents()
{
    $subFail = false;
    $database =& JFactory::getDBO();

    // First select the main component id out ouf the database
	$database->setQuery("SELECT `id` FROM `#__components` WHERE `name`='wafl'");
    $id = $database->loadResult();
    
    // Then insert values if id is not empty
    if (!empty($id)) {
		$query='INSERT INTO `#__components` (`name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, 
								`option`, `ordering`, `admin_menu_img`, `iscore`, `enabled`)
				VALUES ("Modules", "option=com_wafl&view=wafl&c=wafl", 0,'.$id.', "option=com_wafl&view=wafl&c=wafl", "Wafl Modules", 
								"com_wafl&view=wafl&c=wafl", 1, " ", 0, 1)';
		$database->setQuery($query);
        if (!$database->query()) {
            $subFail = true;
        }
		
		$query='INSERT INTO `#__components` (`name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, 
								`option`, `ordering`, `admin_menu_img`, `iscore`, `enabled`)
				VALUES ("Settings", "option=com_wafl&view=settings&c=settings", 0,'.$id.', "option=com_wafl&view=settings&c=settings", "Wafl Settings", 
								"com_wafl&view=settings&c=settings", 2, " ", 0, 1)';
		$database->setQuery($query);
        if (!$database->query()) {
            $subFail = true;
        }
        
    	$query='INSERT INTO `#__components` (`name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, 
								`option`, `ordering`, `admin_menu_img`, `iscore`, `enabled`)
				VALUES ("SirunaAdvanced", "option=com_wafl&view=advanced&c=advanced", 0,'.$id.', "option=com_wafl&view=advanced&c=advanced", "Wafl Siruna Advanced", 
								"com_wafl&view=advanced&c=advanced", 3, " ", 0, 1)';
		$database->setQuery($query);
        if (!$database->query()) {
            $subFail = true;
        }
	}
	
	return $subFail;
}

/**
 * This function takes care of the Wafl module installation
 * 
 * @return boolean $modFail False if database operation was successful
 */
function _installWaflModule()
{
    $database =& JFactory::getDBO();
    $modFail = false;
    
    //Unzip
    $archive = new PclZip(JPATH_ROOT.DS."components".DS."com_wafl".DS."modulewafl.zip");
    $list = $archive->extract(PCLZIP_OPT_PATH,JPATH_ROOT.DS."modules".DS."mod_wafl".DS);
    unset($archive);
	
    //Install
	$query='INSERT INTO `#__modules` (`title`, `ordering`, `position`, `published`, `module`,`access`, `showtitle`)
				VALUES ("WAFL Component Wrapper Module", 2, "left", 1, "mod_wafl", 0, 1)';
	$database->setQuery($query);
	if (!$database->query()) {
	    $modFail = true;
	}
	
	// jos_modules_menu update
	$database->setQuery("SELECT `id` FROM `#__modules` WHERE `module`='mod_wafl'");
    $id = $database->loadResult();
    if (!empty($id)) {
    	$query='INSERT INTO `#__modules_menu` (`moduleid`, `menuid`)
    				VALUES ('.$id.', "0")';
    	$database->setQuery($query);
    	if (!$database->query()) {
    	    $modFail === true;
    	}
    }
    
    // jos_modules_wafl update
    $database->setQuery('INSERT INTO '.$database->nameQuote('#__modules_wafl').
	                ' (`module_id`,`published`, `ordering`) 
	                 VALUES ('.$id.', 1, '.$id.')');
    if (!$database->query()) {
        $modFail === true;
    }
    
    return $modFail;  
}

/**
 * This function clears out the cache memory
 * 
 * @return void
 */
function _cleanCache()
{
   	$cache = JFactory::getCache();
	$cache->clean(); 
}


/**
 * This function clears out the cache memory in-depth
 * 
 * @return void
 */
function _cleanCacheInDepth()
{
    _cleanCache();
	$file_list = JFolder::files(JPATH_CACHE, ".xml");
	foreach ($file_list as $val) {
		if (strstr($val, "cache_")) {
			@unlink($cachepath . DS . $val);
		}
	}    
}


/**
 * This function does the actual installing
 *
 */
function com_install()
{
	// Get locale for i18n
	$language =& JFactory::getLanguage();
	$localearray = $language->getLocale();
	$locale = _getInstallLocale($localearray[0]);
    $messages = _setMessages();	

    // Prints the table header
    _printTableHeader($messages, $locale);
    
    /**
     * ===================================
     * = BEGIN CREATING DATABASE TABLES  =
     * = Creates the jos_modules_wafl    =
     * = and jos_wafl_settings table and =
     * = output success 				 =
     * ===================================
     */
    // 
    $modTableFail = _createModuleTable();
    $settingsTableFail = _createSettingsTable();
    if ($modTableFail === true || $settingsTableFail === true) {
	    echo '<img src="images/cancel_f2.png"> '.$messages[$locale]['Creating WAFL Database Tables...'].' '.$messages[$locale]['FAILED'];
	} else {
	   echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Creating WAFL Database Tables...'];
	}
	echo '<br />';
	
    // Insert standard values into jos_wafl_settings and output success
    $insertSettingsFail = _insertSettingsValues();
    
    if ($insertSettingsFail === true) {
	    echo '<img src="images/cancel_f2.png"> '.$messages[$locale]['Inserting values into WAFL settings...'].' '.$messages[$locale]['FAILED'];
	} else {
	    echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Inserting values into WAFL settings...'];
	}
	echo '<br />';

    // Delete existing menu if found and output success
    $oldid = _selectWaflComponentID();
    if (!empty($oldid)) {
        $deleteFail = _deleteComponentEntry();
        if ($deleteFail === true) {
	       echo '<img src="images/cancel_f2.png"> '.$messages[$locale]['Deleting existing WAFL menu items...'].' '.$messages[$locale]['FAILED'];
	    } else {
	        echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Deleting existing WAFL menu items...'];
	    }
	    echo '<br />'; 
    }  
    /**
     * ================================
     * = END CREATING DATABASE TABLES =
     * ================================
     */

    /**
     * ========================================
     * = BEGIN CLEANING CONFLICTING DIRS      = 
     * = Before copying, clean every possible =
     * = conflicting directory 			      =
     * ========================================
     */
    // 
    // Admin
    _cleanAdminConflicts();
    // Plugin
    _cleanPluginConflicts();
	// Module
    _cleanModuleConflicts();
	echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Cleaning conflicting WAFL directories...'].'<br />';
    /**
     * =================================
     * = END CLEANING CONFLICTING DIRS =
     * =================================
     */

	/**
     * =================================
     * = BEGIN PLUGIN INSTALLATION     =
     * = Install and unzip our plugins =
     * =================================
     */
	// Main plugin
	$mainPluginFail = _installMainPlugin();
	
	if ($mainPluginFail === true) {
	    echo '<img src="images/cancel_f2.png"> '.$messages[$locale]['Install WAFL System Plugin...'].' '.$messages[$locale]['FAILED'];
	} else {
	    echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Install WAFL System Plugin...'];
	}
	echo '<br/>';
	
	// Editor plugin
	$editorPluginFail = _installEditorPlugin();
	if ($editorPluginFail === true) {
	    echo '<img src="images/cancel_f2.png"> '.$messages[$locale]['Install WAFL Editor Plugin...'].' '.$messages[$locale]['FAILED'];
	} else {
	    echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Install WAFL Editor Plugin...'];
	}
	echo '<br/>';
	
	// Content plugin
    $contentPluginFail = _installContentPlugin();
    if ($editorPluginFail === true) {
	    echo '<img src="images/cancel_f2.png"> '.$messages[$locale]['Install WAFL Content Plugin...'].' '.$messages[$locale]['FAILED'];
	} else {
	    echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Install WAFL Content Plugin...'];
	}
	echo '<br/>';
	
	/**
     * ===========================
     * = END PLUGIN INSTALLATION =
     * ===========================
     */
	
	/**
     * ===================================
     * = BEGIN TEMPLATE INSTALLATION     =
     * = Install and unzip WAFL template =
     * ===================================
     */
	// 
	_installWaflTemplate();
	echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Install WAFL Template...'].'<br />';

	// Cache cleaning
	_cleanCache();
	/**
     * =============================
     * = END TEMPLATE INSTALLATION =
     * =============================
     */
	
	/**
     * ======================================
     * = BEGIN ADMIN INSTALLATION           =
     * = Unzip and install full admin files =
     * ======================================
     */
    $adminFail = _installWaflAdmin();
	echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Install WAFL Admin Component...'].'<br />';
    if ($adminFail === true) {
	    echo '<img src="images/cancel_f2.png"> '.$messages[$locale]['Creating WAFL Component entry in database...'].' '.$messages[$locale]['FAILED'];
	} else {
	    echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Creating WAFL Component entry in database...'];
	}
	echo '<br />';
	/**
     * ==========================
     * = END ADMIN INSTALLATION =
     * ==========================
     */
	
	/**
     * =============================
     * = BEGIN MODULE INSTALLATION =
     * =============================
     */
	$modFail = _installWaflModule();
	if ($modFail === true) {
        echo '<img src="images/cancel_f2.png"> '.$messages[$locale]['Install WAFL Module...'].' '.$messages[$locale]['FAILED'].'<br />';
    } else {
        echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Install WAFL Module...'].'<br />';
    }
	/**
     * ===========================
     * = END MODULE INSTALLATION =
     * ===========================
     */
	
	// Clean cache in-depth
	_cleanCacheInDepth();
	echo '<img src="images/apply_f2.png"> '.$messages[$locale]['Clean Cache...'].'<br />';
	
	// End of install
	_printTableFooter($messages, $locale);
}
?>