<?php
/**
 * cssReader.php
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
 * Including dependencies
 */
require_once WAFL_DIR .DS. 'lib' .DS. 'csstidy' .DS. 'class.csstidy.php';
require_once WAFL_DIR .DS. 'util' .DS. 'fileNotFoundException.php';


/**
 * CLASS DESCRIPTION
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
class CssTidyReader
{
    // CssTidy instance
    var $cssTidy;
    // Full path to original CSS file
    var $cssFile;

    /**
     * Constructor
     *
     * @param string $cssFile File to be parsed
     *
     * @return void
     */
    function CssTidyReader($cssFile)
    {
        $this->cssTidy = new csstidy();
        $this->cssFile = $cssFile;
        if (file_exists($this->cssFile)) {
            $css = file_get_contents($this->cssFile);
            $this->cssTidy->parse($css);
            //echo "hellloo";
            //echo $this->cssTidy->print->formatted();
            //var_dump($this->cssTidy->css['small']['font-size']);
            //var_dump($this->cssTidy->log);
            //print_r($this->cssTidy->tokens);

        } else {
            throw new FileNotFoundException($this->cssFile);
        }
    }
    /**
     * Getter for cssFile
     *
     * @return string
     */
    public function getCssFile()
    {
        return $this->cssFile;
    }
    /**
     * Setter for cssFile
     *
     * @param string $file The css file
     *
     * @return void
     */
    public function setCssFile($file)
    {
        if (file_exists($file)) {
            $this->cssFile = $file;
            $css = file_get_contents($this->cssFile);
            $this->cssTidy->parse($css);
        } else {
            throw new FileNotFoundException($this->cssFile);
        }
    }
    /**
     * Returns the requested property of the requested argument,
     * returns 0 when not found.
     *
     * @param String $selector     the selector
     * @param String $propertyName the propertyname
     *
     * @return int The property value, else zero
     */
    public function getProperty($selector, $propertyName)
    {
        foreach ($this->cssTidy->css as $cssArray) {
            if (isset($cssArray[$selector][$propertyName])) {
                $propertyValue = $cssArray[$selector][$propertyName];
                if ($propertyValue != null && $propertyValue != '') {
                    return $propertyValue;
                }
            }
        }
        return 0;
    }
    /**
     * Returns an array containing all the properties of the requested argument,
     * returns 0 when not found.
     *
     * @param array $selector Desired selector
     *
     * @return string
     */
    public function getAllProperties($selector)
    {

        foreach ($this->cssTidy->css as $cssArray) {
            if (isset($cssArray[$selector])) {
                $properties = $cssArray[$selector];
                if ($properties != null && count($properties) > 0) {
                    return $properties;
                }
            }
        }
        return 0;
    }

    /**
     * Returns the entire css array
     *
     * @return array
     */
    public function getArray()
    {
        foreach ($this->cssTidy->css as $cssArray) {
            if ($cssArray != null) {
                return $cssArray;
            }
        }
        return 0;
    }
}
?>