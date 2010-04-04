<?php
/**
 * sirunaCore.php
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
require_once WAFL_DIR . DS . 'services' . DS . 'settingsManagerClient.php';

/**
 * sirunaCore.php
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
class PageGenerator extends SettingsManagerClient
{
    
    var $log;
    var $log_instance;
    /**
     * Constructor
     *
     * @return void
     */
    function PageGenerator()
    {
        parent::SettingsManagerClient();
    }
    /**
     * Activate pagegenerator
     *
     * @return bool
     */
    public function activate()
    {
        global $mainframe;
        if ($mainframe->isAdmin()) {
            return false;
        } else {
            if (!session_id()) {
                session_start();
            }
                       
            $this->checkToSwitchTemplate();
            
            return true;
        }
    }
    
    /**
     * Changes the template to the corect one, if necessary.
     * 
     * @return void
     */
    function checkToSwitchTemplate()
    {
        
        if ($this->settingsManager->isTemplateSwitchingEnabled()) {
            $this->switchTemplate($this->settingsManager->getMobileTemplateName());
             
        } else if ($this->settingsManager->isSirunaEnabled()) {
            $this->switchTemplate('wafl');
        }
    }

    /**
     * Switches the template the the one with the name that is provided.
     *
     * @param String $template_name to change to
     * 
     * @return void
     */
    function switchTemplate($template_name)
    {
        global $mainframe;

        $mainframe->setUserState('setTemplate', $template_name);
        $mainframe->setTemplate($mainframe->getUserState('setTemplate'));
    }
}
?>