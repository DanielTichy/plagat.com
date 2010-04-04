<?php
/**
 * readmoremobile.php
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Editor-xtd
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

jimport('joomla.plugin.plugin');

/**
 * Button to set mobile-readmore location in content
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Editor-xtd
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    Release: @package_version@
 * @link       http://www.wafl.ugent.be
 */
class plgButtonReadmoreMobile extends JPlugin
{
    /**
     * Constructor
     *
     * @param object &$subject The object to observe
     * @param array  $config   An array that holds the plugin configuration
     * 
     * @return void
     */
    function plgButtonReadmoremobile(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /**
     * Create button and add script
     *
     * @param String $name The name of the content
     * 
     * @return button
     */
    function onDisplay($name)
    {
        global $mainframe;

        $doc =& JFactory::getDocument();
        $template = $mainframe->getTemplate();

        $getContent = $this->_subject->getContent($name);
        $present = JText::_('ALREADY EXISTS', true);

        $js = "
            function insertReadmoreMobile(editor) {
                var content = $getContent
                if (content.match(/<hr\s+id=(\"|')system-readmore-mobile(\"|')\s*\/*>/i)) {
                    alert('$present');
                    return false;
                } else {
                    jInsertEditorText('<hr id=\"system-readmore-mobile\" />', editor);
                }
            }
            ";

        $doc->addScriptDeclaration($js);

        $button = new JObject();
        $button->set('modal', false);
        $button->set('onclick', 'insertReadmoreMobile(\''.$name.'\');return false;');
        $button->set('text', JText::_('Readmore Mobile'));
        $button->set('name', 'readmore');
        $button->set('link', '#');

        return $button;
    }
}

?>