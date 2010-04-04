<?php
/**
 * sirunaChecker.php
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

/**
 * Class that will connect to the siruna-core.
 * Implements the interface.core library.
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
class SirunaChecker extends SettingsManagerClient
{

    /**
     * Constructs a SirunaCore class.
     *
     * @return void
     */
    function SirunaChecker()
    {
        parent::SettingsManagerClient();
    }

    /**
     * Function that checks if we can connect to Siruna. This is some kind
     * of a 'ping' implementation.
     *
     * @return Boolean if siruna can be reached.
     */
    function isSirunaAlive()
    {
        $fixed_uri = $this->settingsManager->getSirunaURLNoProtocol();
        $port = $this->settingsManager->getSirunaPort();

        try {
            $file = @fsockopen($fixed_uri, (int)$port, $errno, $errstr, 10);

            if (!$file) {
                return false;
            }

            fclose($file);

        } catch(Exception $e) {
            return false;
        }

        return true;
    }

}
