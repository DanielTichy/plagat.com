<?php
/**
 * redirectHandler.php
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Broker
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
 * Including the settingsManager to get data from the database.
 */
require_once WAFL_DIR . DS . 'services' . DS . 'settingsManagerClient.php';
require_once WAFL_DIR . DS . 'services' . DS . 'adaptationEngineDetection.php';
require_once WAFL_DIR . DS . 'broker' . DS . 'sirunaChecker.php';

/**
 * redirectHandler.php
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Broker
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
class RedirectHandler extends SettingsManagerClient
{

    /**
     * Constructor
     *
     */
    function RedirectHandler()
    {
        parent::SettingsManagerClient();
    }

    /**
     * Determines if the incoming request is coming from a Siruna server.
     * If not, and a mobile device is detected, the request is redirected
     * to the Siruna server.
     *
     * @return boolean
     */
    function redirectIfNecessary()
    {
        if (!session_id()) {
            session_start();
        }
        
        if ($_SESSION['mobile']) {
            $adaptationEngineDetection = AdaptationEngineDetection::getInstance();
            $adaptationEngine = $adaptationEngineDetection->isAdaptationEngine();
            
            if (!$adaptationEngine) {
                if ($this->settingsManager->isSirunaEnabled()) {
                    $sirunaChecker = new SirunaChecker();
                    if ($sirunaChecker->isSirunaAlive()) {
                        // Redirect to Siruna
                        header("Location:" . $this->getRedirectURL(true));
                        
                        if (defined('WAFL_UNIT_TESTING')) {
                            return true;
                        }
                        exit;
                    }
                } else if ($this->settingsManager->isMobileRedirectEnabled()) {
                    header("Location:" . $this->getRedirectURL(false));
                    
                    if (defined('WAFL_UNIT_TESTING')) {
                        return true;
                    }
                    exit;
                }
            }
        }
        return false;
    }

    /**
     * Gives back the url to redirect to.
     *
     * @param bool $siruna_enabled specifies if siruna is enabled
     * 
     * @return String The url to redirect to.
     */
    function getRedirectURL($siruna_enabled)
    {
        if ($siruna_enabled) {
            return $this->settingsManager->getSirunaMobileURL();
        } else {
            return $this->settingsManager->getRedirectMobileURL();
        }
        
    }

    /**
     * Returns the current page URL, formatted for usage in an URL to
     * call the Siruna Transcoder.
     *
     * @return pageURL
     */
    function curPageURL()
    {
        $pageURL = '_http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "/";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]
                        .$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}
?>