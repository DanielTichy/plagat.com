<?php
/**
 * settingsManager.php
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Services
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
 * Including CMS Depending Header.
 */
require WAFL_DIR . DS . 'util' . DS . 'CMSDependingHeader.php';

/**
 * Adding the settingsModel to get the url and port for the Siruna server.
 */
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_wafl'.DS.
             'models'.DS.'settings.php';

/**
 * settingsManager.php
 *
 * We will make this a singleton class.
 * You call this object as $settingsManager = SettingsManager::getInstance();
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Services
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
class SettingsManager
{

    /**
     * @var SettingsManager The singleton object of this class.
     */
    private static $_instance;

    /**
     * @var SettingsManager The singleton object of the WaflModelSettings class.
     */
    private static $_waflModelSettings;

    /**
     * Constructor is private.
     *
     */
    private function SettingsManager()
    {
        $this->_waflModelSettings =& WaflModelSettings::getWAFLSettingsInstance();
    }

    /**
     * Singleton-method: gives back the singleton.
     *
     * @access public
     * @return SettingsManager The singleton object of this class.
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new SettingsManager();
        }

        return self::$_instance;
    }
    
    /**
     * Returns value of device_detection
     * 
     * @access public
     * @return int device_detection value
     */
    function getDD()
    {
        return $this->_waflModelSettings->getIsDeviceDetectionEnabled();
    }

    /**
     * Is device detection enabled
     *
     * @access public
     * @return boolean Is device detection enabled
     */
    function isDeviceDetectionEnabled()
    {
        return $this->getDD() || $this->isMobileRedirectEnabled() || $this->isTemplateSwitchingEnabled();
    }
    
    /**
     * Returns the value of option
     * 
     * @access public
     * @return int Option value
     */
    function getOptionValue()
    {
        return $this->_waflModelSettings->getOptionValue();
    }

    /**
     * Gives back if we should use Siruna or not.
     *
     * @access public
     * @return boolean True if Siruna enabled.
     */
    function isSirunaEnabled()
    {
        return $this->_waflModelSettings->getIsSirunaEnabled();
    }
    
    /**
     * Gives back if we should redirect to a mobile URL.
     *
     * @access public
     * @return boolean True if redirection to a mobile URL is enabled.
     */
    function isMobileRedirectEnabled()
    {
        return $this->_waflModelSettings->getIsMobileRedirectEnabled();
    }
    
    /**
     * Gives back if template switching is enabled.
     *
     * @access public
     * @return boolean True if template switching is enabled.
     */
    function isTemplateSwitchingEnabled()
    {
        return $this->_waflModelSettings->getIsTemplateSwitchingEnabled();
    }
    
    /**
     * Gives back if the plugin is enabled.
     *
     * @access public
     * @return boolean True the plugin is enabled.
     */
    function isPluginEnabled()
    {
        return $this->isSirunaEnabled()
            || $this->isMobileRedirectEnabled()
            || $this->isTemplateSwitchingEnabled();
    }    

    /**
     * Gives the url to siruna fetchted from the database, using
     * the db_handler.
     *
     * @access public
     * @return String The url to siruna.
     */
    function getSirunaURL()
    {
        return $this->_waflModelSettings->getSirunaURL();
    }

    /**
     * Gives back the port of the siruna server, as saved in the dabase.
     *
     * @access public
     * @return Integer The port of the siruna server.
     */
    function getSirunaPort()
    {
        return $this->_waflModelSettings->getSirunaPort();
    }

    /**
     * Gives back the full url to siruna, with port number appended to it.
     *
     * @access public
     * @return String The url to siruna, including port.
     */
    function getSirunaURLWithPort()
    {
        return  $this->getSirunaURL() . ":" . $this->getSirunaPort();
    }

    /**
     * Gives back the url to siruna as storend in the database, but
     * with the protocol removed.
     * e.g. http://www.wafl.ugent.be becomes www.wafl.ugent.be
     *
     * @access public
     * @return String The URL without protocol.
     */
    function getSirunaURLNoProtocol()
    {
        return str_replace('http://', '', $this->getSirunaURL());
    }

    /**
     * Gives back the Siruna secret key , as saved in the dabase.
     *
     * @access public
     * @return String The secret key.
     */

    function getSirunaKey()
    {
        return $this->_waflModelSettings->getSirunaKey();
    }

    /**
     * Gives back Siruna project ID, as saved in the dabase.
     *
     * @access public
     * @return String The Siruna project id.
     */
    function getSirunaID()
    {
        return $this->_waflModelSettings->getSirunaID();
    }

    /**
     * Gives back Siruna project path, as saved in the dabase.
     *
     * @access public
     * @return String The Siruna project path.
     */
    function getSirunaPath()
    {
        return $this->_waflModelSettings->getBaseURL();
    }

    /**
     * Gives back Siruna mobile URL, as saved in the dabase.
     * 
     * @access public
     * @return String The Siruna mobile URL.
     */
    function getSirunaMobileURL()
    {
        return $this->_waflModelSettings->getSirunaMobileURL();
    }
    
    /**
     * Return the current default template name, 
     * since getTemplate doesn't return the name
     * 
     * @access public
     * @return String The current template name
     */
    function getDefaultTemplateName()
    {
        return $this->_waflModelSettings->getDefaultTemplateName();
    }
    
    /**
     * Gives back the URL to redirect to, if redirection is enabled
     * and Siruna support is disabled.
     * 
     * @access public
     * @return String The mobile URL to redirect to.
     */
    function getRedirectMobileURL()
    {
        return $this->_waflModelSettings->getRedirectMobileURL();
    }

    /**
     * Gives back the name of the mobile template name,
     * as saved in the dabase.
     * 
     * @access public
     * @return String The mobile template name.
     */
    function getMobileTemplateName()
    {
        return $this->_waflModelSettings->getMobileTemplateName();
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
        return $this->_waflModelSettings->getBaseTemplate();
    }
    
    /**
     * Returns all the front-end templates.
     * Parses all the xml files recursively in the templates folder 
     * and makes objects of them.   
     * 
     * @access public
     * @return Array Array of the objects. StdClass object([directory]=>, [name]=> ...)
     */
    function getTemplates() 
    {
        return $this->_waflModelSettings->getTemplates();
    }
    
    /**
     * Returns siruna user as saved in the databank
     * 
     * @access public
     * @return String Siruna user
     */
    function getSirunaUser()
    {
        return $this->_waflModelSettings->getSirunaUser();
    }
}
?>