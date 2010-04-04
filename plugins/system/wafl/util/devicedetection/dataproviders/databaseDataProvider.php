<?php
/**
 * databaseDataProvider.php
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
 * Dataprovider for device detection using sql 
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
class DatabaseDataProvider
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
    function DatabaseDataProvider($options)
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
            _readMajorDevices();
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
            _readCommonTerms();
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
            _readAcceptHeaders();
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
            _readMobileHeaders();
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
            _readSpecificDevices();
        }
        return $this->specificdevices;
    }
    
    /**
     * Reads the major devices out of the database
     * 
     * @access private
     * 
     * @return void
     */
    private function _readMajorDevices()
    {
        //TODO: implementation
    }
    
    /**
     * Reads the major devices out of the database
     * 
     * @access private
     * 
     * @return void
     */
    private function _readCommonTerms()
    {
        //TODO: implementation        
    }
    
    /**
     * Reads the major devices out of the database
     * 
     * @access private
     * 
     * @return void
     */
    private function _readMobileHeaders()
    {
        //TODO: implementation        
    }
    
    /**
     * Reads the major devices out of the database
     * 
     * @access private
     * 
     * @return void
     */
    private function _readAcceptHeaders()
    {
        //TODO: implementation       
    }
    
    /**
     * Reads the major devices out of the database
     * 
     * @access private
     * 
     * @return void
     */
    private function _readSpecificDevices()
    {
        //TODO: implementation        
    }
}

?>