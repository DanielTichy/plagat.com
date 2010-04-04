<?php
/**
 * settings.php
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Models
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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
if (!defined('WAFL_DIR')) {
    define('WAFL_DIR', JPATH_PLUGINS . DS . 'system' . DS . 'wafl');
}

jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
require_once JPATH_ADMINISTRATOR . DS .'components'.DS.
    'com_templates'.DS. 'helpers'.DS.'template.php';

/**
 * Settings Model
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Models
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
class WaflModelSettings extends JModel
{
    private static $_instance;

    /**
     * modules_wafl data array
     *
     * @var array
     */
    var $_data;
    
    const NONE     = '1';
    const TEMPLATE = '2';
    const MOBILE   = '3';
    const SIRUNA   = '4';
    
    /**
     * Gets the instance of the settingsmodel
     * 
     * @access public
     * @return Model The instance of the settingsmodel
     */
    public static function getWAFLSettingsInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new WaflModelSettings();
        }

        return self::$_instance;
    } 

    /**
     * Returns the query
     *
     * @access public
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
        $query = 'SELECT * FROM #__wafl_settings';
        return $query;
    }

    /**
     * Retrieves the admin data
     *
     * @access public
     * @return array Array of objects containing the data from the database
     */
    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data )) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }

    /**
     * Fetches the settings record with all the settings.
     *
     * @access public
     * @return Object The record
     */
    private function _getSettingsRecord()
    {
        $array = $this->getData();
        return $array[0];
    }

    /**
     * Fetches the URL for siruna from the database.
     *
     * @access public
     * @return String The URL the user entered for Siruna.
     */
    function getSirunaURL()
    {
        $record = $this->_getSettingsRecord();
        return $record->siruna_url;
    }

    /**
     * Fetches the port for Siruna from the database.
     *
     * @access public
     * @return Integer The port number.
     */
    function getSirunaPort()
    {
        $record = $this->_getSettingsRecord();
        return (int)$record->siruna_port;
    }

    /**
     * Fetches the project id for siruna from the database.
     *
     * @access public
     * @return String The project id.
     */
    function getSirunaID()
    {
        $record = $this->_getSettingsRecord();
        return $record->siruna_login;
    }

    /**
     * Fetches the secret key for Siruna from the database.
     *
     * @access public
     * @return Integer The secret key.
     */
    function getSirunaKey()
    {
        $record = $this->_getSettingsRecord();
        return $record->siruna_pass;
    }
    
    /**
     * Returns if device detection is enabled
     *
     * @access public
     * @return Integer Is enabled
     */
    function getIsDeviceDetectionEnabled()
    {
        $record = $this->_getSettingsRecord();
        return (int)$record->device_detection;
    }
    
    /**
     * Returns the value of option
     * 
     * @access public
     * @return int Option value
     */
    function getOptionValue()
    {
        $record = $this->_getSettingsRecord();
        return (int)$record->option;
    }

    /**
     * Returns if Siruna is enabled
     *
     * @access public
     * @return Integer Is enabled.
     */
    function getIsSirunaEnabled()
    {
        $record = $this->_getSettingsRecord();
        $enabled = $record->option === WaflModelSettings::SIRUNA;
        return $enabled;
    }
    
    /**
     * Returns if mobile redirect is enabled
     *
     * @access public
     * @return Integer Is enabled.
     */
    function getIsMobileRedirectEnabled()
    {
        $record = $this->_getSettingsRecord();
        return $record->option === WaflModelSettings::MOBILE;
    }
    
    /**
     * Returns if template switching is enabled
     *
     * @access public
     * @return Integer Is enabled.
     */
    function getIsTemplateSwitchingEnabled()
    {
        $record = $this->_getSettingsRecord();
        return $record->option === WaflModelSettings::TEMPLATE;
    }

    /**
     * Returns the project base URL
     *
     * @access public
     * @return String base URL.
     */
    function getBaseUrl()
    {
        $record = $this->_getSettingsRecord();
        return $record->base_url;
    }

    /**
     * Returns the Mobile Redirect URL
     *
     * @access public
     * @return String Mobile URL.
     */
    function getRedirectMobileURL()
    {
        $record = $this->_getSettingsRecord();
        return $record->redirect_mobile_url;
    }


    /**
     * Returns the Siruna Mobile URL
     *
     * @access public
     * @return String Mobile URL.
     */
    function getSirunaMobileURL()
    {
        $record = $this->_getSettingsRecord();
        return $record->siruna_mobile_url;
    }

    /**
     * Gives back the name of the mobile template,
     * as saved in the dabase.
     * 
     * @access public
     * @return String The mobile template name.
     */
    function getMobileTemplateName()
    {
        $record = $this->_getSettingsRecord();
        return $record->redirect_to_template;
    }

    /**
     * Gives back the name of the base template,
     * as saved in the dabase.
     * 
     * @access public
     * @return String The base template name.
     */
    function getBaseTemplate()
    {
        $record = $this->_getSettingsRecord();
        return $record->base_template;
    }
    /**
     * Logic behind the save button.
     *
     * @access public
     * @return void
     */
    function save()
    {
        $row =& JTable::getInstance('settings', 'Table');
        $id = JRequest::getVar('id', JREQUEST_ALLOWRAW);
        $row->load($id);

        $row->siruna_login = $this->_getVar(
            'siruna_login', 'not set', 'string'
        );
        $row->siruna_pass = $this->_getVar(
            'siruna_pass', 'not set', 'string'
        );
        $row->siruna_url = $this->_getVar(
            'siruna_url', 'not set', 'string'
        );
        $row->base_url = $this->_getVar(
            'base_url', 'not set', 'string'
        );
        $row->siruna_port = $this->_getVar(
            'siruna_port', 8080, 'string'
        );
        $row->siruna_mobile_url = $this->_getVar(
            'siruna_mobile_url', 'not set', 'string'
        );
        $row->redirect_mobile_url = $this->_getVar(
            'redirect_mobile_url', 'not set', 'string'
        );
        $row->base_template = $this->_getVar(
            'base_template', 'not set', 'string'
        );
        $row->redirect_to_template = $this->_getVar(
            'redirect_template', 'not set', 'string'
        );
        $row->option = $this->_getVar(
            'radio', 1, 'int'
        );
        $row->siruna_user = $this->_getVar(
            'siruna_user', 'not set', 'string'
        );
        $row->device_detection = $this->_getVar(
            'device_detection', 0, 'int'
        );

        if (!$row->store()) {
            echo "<script> alert('".$row->getError()."'); window.history.
        go(-1); </script>\n";
            exit();
        }
        
        $query = $this->_buildQuery();
        $this->_data = $this->_getList($query);
    }

    /**
     * Changes in the Advanced Siruna tab need to be stored in the databank
     * 
     * @param String $project Project ID
     * @param String $user    Username
     * @param String $path    Base URL
     * 
     * @access public
     * @return void
     */
    function saveFromAdvanced($project, $user, $path)
    {
        $row =& JTable::getInstance('settings', 'Table');
        $id = $this->_getId();
        $row->load($id);
        
        $row->siruna_login = $project;
        $row->siruna_user  = $user;
        $row->base_url     = $path;
        
        if (!$row->store()) {
            echo "<script> alert('".$row->getError()."'); window.history.
        go(-1); </script>\n";
            exit();
        }
    }
    /**
     * Returns siruna user as saved in the databank
     * 
     * @access public
     * @return String Siruna user
     */
    function getSirunaUser()
    {
        $record = $this->_getSettingsRecord();
        return $record->siruna_user;
    }
    
    /**
     * Gets a variable from the POST object.
     * If empty, return a default value
     *
     * @param String $name    the name
     * @param String $default the default value
     * @param String $type    the type
     *
     * @access private
     * @return String|Integer The value
     */
    private function _getVar($name, $default, $type)
    {
        $tmp = JRequest::getVar(
            $name, $default, 'POST', $type, 0
        );
        if ($tmp === "") {
            $tmp = $default;
        }
        return $tmp;

    }

    /**
     * Gets the default template name
     * 
     * @access public
     * @return String The default template name
     */
    function getDefaultTemplateName() 
    {
        $db    =& $this->_db;
        $query = 'SELECT '. $db->nameQuote('template')
        .' FROM '.$db->nameQuote('#__templates_menu');
        $db->setQuery($query);
        if (!$db->query()) {
            echo $db->stderr();
            // Log a possible error
            $msg = 'my_application ' . $db->getErrorNum() . ' ' . $db->getErrorMsg();
            error_log($msg);
            return false;
        }
        $result = $db->loadResult();
        return $result;
    }

    /**
     * Get all templates
     * 
     * @access public
     * @return array Templates array
     */
    function getTemplates()
    {
        $templateBaseDir = JPATH_SITE . DS. 'templates';
        // Read the template folder to find templates
        $rows = TemplatesHelper::parseXMLTemplateFiles($templateBaseDir);  
        return $rows;
    }
    
    /**
     * Returns the id of the row
     * 
     * @access private
     * @return Integer The id.
     */
    private function _getId()
    {
        $db    =& $this->_db;
        $query = 'SELECT '. $db->nameQuote('id')
        .' FROM '.$db->nameQuote('#__wafl_settings');
        $db->setQuery($query);
        if (!$db->query()) {
            echo $db->stderr();
            // Log a possible error
            $msg = 'my_application ' . $db->getErrorNum() . ' ' . $db->getErrorMsg();
            error_log($msg);
            return false;
        }
        $result = $db->loadResult();
        return $result;
    }

}