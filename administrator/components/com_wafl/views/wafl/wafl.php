<?php
/**
 * wafl.php
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Views
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

defined('_JEXEC') or die('Restricted access');

/**
 * Toolbar for WaflViewWafl
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Views
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
class WaflToolbarWafl
{
    /**
     * Sets the toolbar for WaflViewWafl view.
     * 
     * @access public
     * @return void
     */
    function setWaflToolbar()
    {
        JHTML::_(
            'stylesheet', 'toolbar.css', 
            'administrator/components/com_wafl/views/wafl/'
        ); 
        JToolBarHelper::customX(
            'clear', 'clear.png', 'clear.png', 'disableAll', false
        );
        JToolBarHelper::customX(
            'changeSelected', 'changeselected.png', 'changeselected.png', 
            'changeSelected', false
        );
        JToolBarHelper::customX(
            'enableAll', 'enableall.png', 'enableall.png', 'enableAll', false
        );
    }
}
?>
