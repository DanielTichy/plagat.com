<?php
/**
 * advanced.php
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Models
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
defined('_JEXEC') or die();
 
jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
require_once WAFL_DIR . DS . 'broker' . DS . 'sirunaMapping.php';

/**
 * Advanced Siruna Model
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Models
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
class WaflModelAdvanced extends JModel
{
    private static $_instance;
    
    /**
     * modules_wafl data array
     *
     * @var array
     */
    var $_data;

    /**
     * Get instance of the model
     * 
     * @access public
     * @return Model The instance of the model
     */   
    public static function getWAFLAdvancedInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new WaflModelAdvanced();
        }

        return self::$_instance;
    } 
    
    /**
     * Retrieves the admin data
     *
     * @access public
     * @return String The text from thesiruna mapping file.
     */
    function getData()
    {
        $path = JPATH_SITE.DS.'plugins'.DS.'system'.DS.'wafl'
        .DS.'lib'.DS.'SirunaMappingAdvanced.xml';
        if (JFile::exists($path)) {
            return JFile::read($path);
        }
        
        $path = JPATH_SITE.DS.'plugins'.DS.'system'.DS.'wafl'
        .DS.'lib'.DS.'SirunaMapping.xml';
        if (JFile::exists($path)) {
            return JFile::read($path);
        }
        return null;
    }
    
    /**
     * Saves the changes to the file
     *
     * @access public
     * @return Boolean|Array True if successful.
     * 						 array[0]=>error message, array[1]=>error type
     */
    function save() 
    {
        global $mainframe;
        $path = JPATH_SITE.DS.'plugins'.DS.'system'.DS.'wafl'
        .DS.'lib'.DS.'SirunaMappingAdvanced.xml';
        
        $buffer = JRequest::getVar(
            'edit', '', 'POST', 'string', JREQUEST_ALLOWRAW
        );
        $toReplace     = array("&lt;", "&gt;");
        $replaceWith   = array("<", ">");
        $buffer2 = str_replace($toReplace, $replaceWith, $buffer);
        $toParse = $buffer2;
        
        if ($buffer2 != '') {
            $smap = new SirunaMapping();
            $smap->setXML($buffer2);
            $result = $smap->postToServer();
            
            if ($this->_parseXML($toParse)) {
                if (!JFile::write($path, $buffer2)) {
                    $array = array('Unable to write', 'error');
                    return $array;
                }
            } else {
                  $array = array('Invalid XML', 'error');
                  return $array;               
            }
            if(stristr($result,'error'))
                $array = array($result, 'error');
            else
                $array = array($result, '');
            return $array;
        } else {
            $array = array('Text was empty', 'notice'); 
            return $array;
        }   
    }
    
    /**
     * Parses the xml in the texteditor 
     * and stores the changes in the database
     *
     * @param String $toParse XML String to parse
     *
     * @access private
     * @return boolean True if successful
     */
    private function _parseXML($toParse)
    {
        $parser =& JFactory::getXMLParser('Simple');

        //raises error but returns true?
        //$bool = $parser->loadString($toParse);

        if (!xml_parse($parser->_parser, $toParse)) {
            /*$parser->_handleError(
                xml_get_error_code($parser->_parser),
                xml_get_current_line_number($parser->_parser),
                xml_get_current_column_number($parser->_parser)
            );*/
            xml_parser_free($parser->_parser);
            return false;
        }
        //Free the parser
        xml_parser_free($parser->_parser);

        $document =& $parser->document;
        $mappingNode = $document->Mapping;
        $children = $document->children();
        
        //the project node
        $project = $children[0]->getElementByPath('project')->_data;

        //the path node
        $xmlpaths = $children[0]->getElementByPath('paths')->children();
        $xmlpath = $xmlpaths[0]->_data;
        
        //the user node
        $user = $children[0]->getElementByPath('user')->_data;
        
        //persist in database
        include_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_wafl'.DS.
             'models'.DS.'settings.php';
        $model =& WaflModelSettings::getWAFLSettingsInstance();
        $model->saveFromAdvanced($project, $user, $xmlpath);
        return true;
    }

}