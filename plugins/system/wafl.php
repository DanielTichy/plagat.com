<?php
/**
 * wafl.php
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugins
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
jimport('joomla.plugin.plugin');

/**
 * Including CMS Depending Header.
 */
require JPATH_PLUGINS .DS. 'system' .DS. 'wafl' .DS. 'util' .DS. 'CMSDependingHeader.php';

/**
 * Including dependencies
 */
require_once WAFL_DIR . DS . 'services' . DS . 'pageGenerator.php';
require_once WAFL_DIR . DS . 'services' . DS . 'deviceDetection.php';
require_once WAFL_DIR . DS . 'services' . DS . 'adaptationEngineDetection.php';
require_once WAFL_DIR . DS . 'broker' . DS . 'redirectHandler.php';

/**
 * Joomla! 1.5 WAFL plugin
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugins
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @copyright  2009 WAFL
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    Release: @package_version@
 * @link       http://www.wafl.ugent.be
 *
 */
class PlgSystemWafl extends JPlugin
{
    /**
     * Constructor
     *
     * @param object &$subject The object to observe
     * @param array  $config   An array that holds the plugin configuration
     *
     * @return void
     */
    function plgSystemWafl(&$subject, $config)
    {
        parent::__construct($subject, $config);
        
        //Use mobile aware caching
        $conf =& JFactory::getConfig();
        $handler =  $conf->getValue('config.cache_handler', 'file');
        $class = 'JCacheStorage'.ucfirst($handler);
        $path = WAFL_DIR . DS . 'cache' . DS . 'storage' . DS . $handler . '.php';
        JLoader::register($class, $path);
    }
    
    /**
     * Do something onAfterInitialise
     *
     * @return boolean
     */
    function onAfterInitialise()
    {     
        global $mainframe;
        if (!$mainframe->isAdmin()) {
            $session = & JFactory::getSession();
            if (!session_id()) {
                session_start();
            }
            //if (isset($_SESSION['mobile']) != true) {
            $adaptationEngineDetection = AdaptationEngineDetection::getInstance();
            $_SESSION['mobile'] = $adaptationEngineDetection->isAdaptationEngine();
            
            if ($_SESSION['mobile'] != true) {
                $deviceDetection = DeviceDetection::getInstance();
                $_SESSION['mobile'] = $deviceDetection->isMobile();
            }
            //}

            $redirectHandler = new RedirectHandler();
            $redirect = $redirectHandler->redirectIfNecessary();
            
            if ($_SESSION['mobile']) {
                $pageGenerator = new PageGenerator();
                $pageGenerator->activate();
            }

            return $redirect;
        }
        return false;
    }
}
?>
