<?php
/**
 * template_config.php
 *
 * PHP version 5
 *
 * @category   Templates
 * @package    Wafl
 * @subpackage Template
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

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Is required to use the table of the com_wafl module: here we fetch which
 * modules should be shown on the mobile page.
 */
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_wafl'.DS.'tables');
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_wafl'.DS.'models'.DS.'wafl.php';

/**
 * Gives back the mobile enabled modules
 * to show on the mobile version of the template.
 *
 * @return An array of module-objects.
 */
function getMobileModules()
{
    $waflModelSettings =& JModel::getInstance('Wafl', 'WaflModel');
    return $waflModelSettings->getMobileModulesByOrder();
}

/**
 * Get content of module on the page.
 *
 * @param $module module that should be printed.
 *
 * @return string rendered content
 */
function renderModule($module)
{
    $document = &JFactory::getDocument();
    $renderer = $document->loadRenderer('module');
    return $renderer->render($module);
}

?>

