<?php 
/**
 * settings.php
 *
 * PHP version 5
 * 
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Controllers
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
defined('_JEXEC') or die('Restricted Access');

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_wafl'.DS.
             'models'.DS.'settings.php';
require_once WAFL_DIR .DS. 'broker' .DS. 'sirunaParser.php';
require_once WAFL_DIR .DS. 'services' .DS. 'cssComposer.php';

jimport('joomla.application.component.controller');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

/**
 * Settings Controller
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Controllers
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
class WaflControllerSettings extends JController
{

    /**
     * constructor (registers additional tasks to methods)
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }


    /**
     * Method to display the view
     *
     * @access public
     * @return void
     */
    function display()
    {
        parent::display();
    }

    /**
     * Logic that handles the save button of the form
     *
     * @access public
     * @return void
     */
    function save()
    {
        global $option;

        // Update model
        $model =& WaflModelSettings::getWAFLSettingsInstance();
        $model->save();

        // Clean cache
        $cache = JFactory::getCache();
        $cache->clean();  

        // Compose CSS
        $composer =& CssComposer::getCssComposerInstance();
        if ($model->getIsTemplateSwitchingEnabled()) {
            $name = $model->getMobileTemplateName();
            $defaultTemplateName = $model->getDefaultTemplateName();
            if ($name === "wafl") {
                $composer->compose($defaultTemplateName);
            }
        } else if ($model->getIsSirunaEnabled()) {     
            $composer->compose($model->getBaseTemplate());
        }
        
        // Siruna mapping
        $mapping = WAFL_DIR . DS . 'lib' . DS . 'mapping.xml';
        $sitemap = WAFL_DIR . DS . 'lib' . DS . 'sitemap.xmap';
        $outputFile = WAFL_DIR . DS . 'lib' . DS . 'SirunaMapping.xml';
        $parser = new SirunaParser($mapping, $sitemap);
        $parser->getSirunaMapping()->writeToFile($outputFile);
        $parser->getSirunaMapping()->postToServer();

        $this->setRedirect(
            'index.php?option=' .$option.'&view='
            . JRequest::getVar('view', JREQUEST_ALLOWRAW).'&c='
            .JRequest::getVar('c', JREQUEST_ALLOWRAW)
        );

        
    }
    
    /**
     * Logic that handles the cancel button of the form
     *
     * @access public
     * @return void
     */
    function cancel()
    {
        global $option;
        $this->setRedirect('index.php?option='.$option.'&view=wafl&c=wafl');
    }
    

}
?>
