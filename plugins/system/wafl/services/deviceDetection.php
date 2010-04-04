<?php
/**
 * deviceDetection.php
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
 * Including required files.
 */
require_once WAFL_DIR . DS . 'services' . DS . 'settingsManagerClient.php';
require_once WAFL_DIR . DS . 'util' . DS . 'fileNotFoundException.php';
require_once WAFL_DIR . DS . 'util' . DS . 'devicedetection' . DS . 'dataProviderFactory.php';

/**
 * deviceDetection.php
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
class DeviceDetection extends SettingsManagerClient
{
    var $dataprovider;

    /**
     * Constructor is private
     */
    private function DeviceDetection()
    {
        parent::SettingsManagerClient();

        if ($this->settingsManager->isDeviceDetectionEnabled()) {
            // TODO: get dataprovider type from settings
            $this->dataprovider = DataProviderFactory::getDataProvider('array');
        }
    }

    /**
     * Singleton-method: gives back the singleton.
     *
     * @return DeviceDetection The singleton object of this class.
     */
    public static function getInstance()
    {
        return new DeviceDetection();
    }

    /**
     * Check if device which browses tot the site is mobile
     *
     * @return bool True if the device is a mobile
     */
    public function isMobile()
    {
        if ($this->settingsManager->isDeviceDetectionEnabled()) {

            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            // Match major devices
            foreach ($this->dataprovider->getMajorDevices() as $majordevice) {
                if (preg_match('/(' . $majordevice . ')/i', $user_agent)) {
                    return true;
                }
            }
            
            // Match common terms
            foreach ($this->dataprovider->getCommonTerms() as $commonterm) {
                if (preg_match('/(' . $commonterm . ')/i', $user_agent)) {
                    return true;
                }
            }
            
            // Match mobile headers
            foreach ($this->dataprovider->getMobileHeaders() as $header) {
                if (isset($_SERVER['' . $header])) {
                    return true;
                }
            }
             
            // Match accept headers
            $accept = (string)$_SERVER['HTTP_ACCEPT'];
            foreach ($this->dataprovider->getAcceptHeaders() as $header) {
                if ((strpos($accept, $header) > 0)) {
                    return true;
                }
            }
             
            // Match specific devices
            if (in_array(strtolower(substr($user_agent, 0, 4)), $this->dataprovider->getSpecificDevices())) {
                return true;
            }
        }

        return false;
    }
}
?>