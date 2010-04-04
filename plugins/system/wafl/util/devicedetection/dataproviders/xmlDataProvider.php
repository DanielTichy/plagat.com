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
 * Dataprovider for device detection using xml repository
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
class XmlDataProvider
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
    function XmlDataProvider($options)
    { 
        $this->majordevices = array();
        $this->commonterms = array();
        $this->acceptheaders = array();
        $this->mobileheaders = array();
    }

    /**
     * Gets the major device array
     *
     * @return array The array
     */
    function getMajorDevices()
    {
        if (!isset($this->majordevices)) {
            _readDevices();
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
            _readDevices();
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
            _readDevices();
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
            _readDevices();
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
            _readDevices();
        }
        return $this->specificdevices;
    }
    
    /**
     * Reads in the devices out of the xml-file
     * 
     * @access private
     * 
     * @return void
     */
    private function _readDevices()
    {
        $xml_file = WAFL_DIR . DS . 'lib' . DS . 'devicedetection.xml';
        if (file_exists($xml_file)) {
            $xml = simplexml_load_file($xml_file);
        } else {
            throw new FileNotFoundException($xml_file);
        }

        // Major Devices
        $result = $xml->xpath('/devicedetection/majordevices');
        foreach ($result[0] as $child) {
            array_push($this->majordevices, $child);
        }
        
        // Common terms
        $result = $xml->xpath('/devicedetection/commonterms');
        foreach ($result[0] as $child) {
            array_push($this->commonterms, $child);
        }
        
        // Accept Headers
        $result = $xml->xpath('/devicedetection/acceptheaders');
        foreach ($result[0] as $child) {
            array_push($this->acceptheaders, $child);
        }
        
        // Mobile Headers
        $result = $xml->xpath('/devicedetection/mobileheaders');
        foreach ($result[0] as $child) {
            array_push($this->mobileheaders, $child);
        }
        
        // Specific Devices
        $result = $xml->xpath('/devicedetection/specificdevices');
        foreach ($result[0] as $child) {
            array_push($this->specificdevices, (string)$child);
        }
    }
}

?>