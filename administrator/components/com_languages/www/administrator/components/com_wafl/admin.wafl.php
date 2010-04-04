<?php
/**
 * admin.wafl.php
 * Index page
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator
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
 
// No direct access
 
defined('_JEXEC') or die('Restricted access');

/**
 *  get controller
 */
if ($con = JRequest::getCmd('c', 'wafl')) {
    // determine path
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$con.'.php';
    jimport('joomla.filesystem.file');
    if (JFile::exists($path)) {
         // controller exists, get it!
         include_once $path;
    } else {
         // controller does not exist
         JError::raiseError('500', JText::_('Unknown controller'));
    }
}

// instantiate and execute the controller
$con = 'WaflController'. ucfirst($con);
$controller = new $con();
$controller->execute(JRequest::getCmd('task', 'display'));

// redirect
$controller->redirect();

?>
