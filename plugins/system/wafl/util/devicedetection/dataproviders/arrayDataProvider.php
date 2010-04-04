<?php
/**
 * arrayDataProvider.php
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Util.Devicedetection
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
 * Dataprovider for device detection using array repository
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Util.Devicedetection
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
class ArrayDataProvider
{
    var $majordevices;
    var $commonterms;
    var $acceptheaders;
    var $mobileheaders;
    var $specificdevices;

    /**
     * Constructor 
     * 
     * @param String $options The options
     * 
     * @return void
     */
    function ArrayDataProvider($options)
    {

    }

    /**
     * Gets the major device array
     * 
     * @return array The array
     */
    function getMajorDevices()
    {
        if (!isset($this->majordevices)) {
            $this->_readDevices();
        }
         
        return $this->majordevices;
    }

    /**
     * Gets the common terms array
     * 
     * @return array The array
     */
    function getCommonTerms()
    {
        if (!isset($this->commonterms)) {
            $this->_readDevices();
        }
         
        return $this->commonterms;
    }

    /**
     * Gets the acceptheaders array
     * 
     * @return array The array
     */
    function getAcceptHeaders()
    {
        if (!isset($this->acceptheaders)) {
            $this->_readDevices();
        }
         
        return $this->acceptheaders;
    }

    /**
     * Gets the mobileheaders array
     * 
     * @return array The array
     */
    function getMobileHeaders()
    {
        if (!isset($this->mobileheaders)) {
            $this->_readDevices();
        }
         
        return $this->mobileheaders;
    }

    /**
     * Gets the specific device array
     * 
     * @return array The array
     */
    function getSpecificDevices()
    {
        if (!isset($this->specificdevices)) {
            $this->_readDevices();
        }
         
        return $this->specificdevices;
    }

    /**
     * Reads in the arrays out of the .inc file
     * 
     * @access private
     * 
     * @return void
     */
    private function _readDevices()
    {
        include WAFL_DIR . DS . 'lib' . DS . 'devicedetection.inc';
         
        $this->majordevices = $majordevices;
        $this->commonterms = $commonterms;
        $this->acceptheaders = $acceptheaders;
        $this->mobileheaders = $mobileheaders;
        $this->specificdevices = $specificdevices;
    }
}

?>