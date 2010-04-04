<?php
/**
 * settingsManagerClient.php
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
 * Including the settingsManager to get data from the database.
 */
require_once WAFL_DIR . DS . 'services' . DS . 'settingsManager.php';

/**
 * Class that will connect to the siruna-core.
 * Implements the interface.core library.
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
class SettingsManagerClient
{
    protected $settingsManager;

    /**
     * Constructs a SettingsManagerClient class.
     *
     * @return void
     */
    function SettingsManagerClient()
    {
        $this->setSettingsManager(SettingsManager::getInstance());
    }

    /**
     * Gives back the settingsmanager that's being used.
     *
     * @return SettingsManager The settingsManager.
     */
    function getSettingsManager()
    {
        return $this->settingsManager;
    }

    /**
     * Sets the settingsManager that will be used. This can be used
     * to set an other class of settingsManager (e.g. testing stub).
     *
     * @param SettingsManager $settingsManager The settingsManager that will be used.
     *
     * @return void.
     */
    function setSettingsManager($settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

}
?>