<?php
/**
 * adaptationEngineDetection.php
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
require_once WAFL_DIR . DS . 'util' . DS . 'fileNotFoundException.php';

/**
 * adaptationEngineDetection.php
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
class AdaptationEngineDetection
{
    
    /**
     * @var AdaptationEngineDetection The singleton object of this class.
     */
    private static $_instance;

    /**
     * Constructor is private
     */
    private function AdaptationEngineDetection()
    {

    }
    
    /**
     * Singleton-method: gives back the singleton.
     *
     * @return AdaptationEngineDetection The singleton object of this class.
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new AdaptationEngineDetection();
        }

        return self::$_instance;
    }
    
    /**
     * Check if request comes from adaptation engine
     *
     * @return bool True if the device is a mobile
     */
    public function isAdaptationEngine()
    {
        $key = 'HTTP_X_ADAPTATION_ENGINE';
        $fromMobixx = strpos(strtolower(@$_SERVER[$key]), "mobixx");
        $fromSiruna = strpos(strtolower(@$_SERVER[$key]), "siruna");        

        if (!($fromMobixx === false) || !($fromSiruna === false)) {
            
            return true;
        }
        
        return false;
    }
}
?>