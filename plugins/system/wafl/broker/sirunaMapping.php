<?php
/**
 * sirunaMapping.php
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Broker
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
 * Including dependecies
 *
 */
require_once WAFL_DIR . DS . 'services' . DS . 'settingsManagerClient.php';

/**
 * sirunaMapping.php
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Broker
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
class SirunaMapping extends SettingsManagerClient
{
    private $_dom;
    private $_mappingEl;
    private $_rootEl;
    private $_path;
    private $_secretkey;

    /**
     * Constructor
     *
     */
    function SirunaMapping()
    {
        parent::SettingsManagerClient();
        
        $id   = $this->settingsManager->getSirunaID();
        $user = $this->settingsManager->getSirunaUser();
        $this->_path = $this->settingsManager->getSirunaPath();
        $this->_secretkey = $this->settingsManager->getSirunaKey();

        // set up DOM Document with root Siruna and child Mapping
        $this->_dom = new DOMDocument('1.0', 'utf-8');
        $this->_dom->preserveWhiteSpace = false;
        $this->_rootEl = $this->_dom->createElement('Siruna');
        $this->_dom->appendChild($this->_rootEl);
        $this->_mappingEl = $this->_addElement($this->_rootEl, 'Mapping');
        
        // add projectname, user and path
        $this->_addTextElement($this->_mappingEl, 'Project', $id);
        $this->_addTextElement($this->_mappingEl, 'User', $user);
        $pathsEl = $this->_addElement($this->_mappingEl, 'Paths');
        $this->_addTextElement($pathsEl, 'Path', $this->_path);
    }
    
    /**
     * Setter for xml.
     * 
     * @param String $xml The xml to set
     *
     * @return void
     */
    public function setXML($xml)
    {
        $this->_dom = new DOMDocument('1.0', 'utf-8');
        $this->_dom->preserveWhiteSpace = false;
        $this->_dom->loadXML($xml);
        
        //set mappingEl and rootEl
        $xpath = new DOMXPath($this->_dom);
        $nodes = $xpath->query("//Mapping");
        foreach ($nodes as $node) {
            $this->_mappingEl = $node;
        }
        
        $xpath = new DOMXPath($this->_dom);
        $nodes = $xpath->query("//Siruna");
        foreach ($nodes as $node) {
            $this->_rootEl = $node;
        }
    }    
    
    /**
     * Getter for xml.
     *
     * @return SimpleXMLElement XML reprenting the mapping
     */
    public function getXML()
    {
        $this->_calculateHash();
        return $this->_dom->saveXML();
    }
    
    /**
     * Adds a filter to the mapping.
     *
     * @param String $name        Filter name
     * @param String $defaulturl  Filter url
     * @param Array  $expressions Filter expressions
     *
     * @return void
     */
    public function addFilter($name, $defaulturl, $expressions)
    {
        $filterEl      = $this->_addElement($this->_mappingEl, 'Filter');
        $this->_addTextElement($filterEl, 'Name', $name);
        if (strlen($defaulturl)>0) {
            $this->_addTextElement($filterEl, 'DefaultURL', $defaulturl);
        } else {
            $this->_addTextElement($filterEl, 'DefaultURL', $this->_path);
        }
        $expressionsEl = $this->_addElement($filterEl, 'Expressions');
        
        foreach ($expressions as $expression) {
            $this->_addTextElement($expressionsEl, 'Expression', $expression);
        }
    }

    /**
     * Adds a list of SirunaFilter elements to the filter list.
     *
     * @param Array  $name Filter name
     * @param String $data Entry data
     *
     * @return void
     */
    public function addEntryToFilter($name, $data)
    {
        //get right filter and add the entry as CDATA
        $xpath = new DOMXPath($this->_dom);
        $filters = $xpath->query("//Filter[Name='".$name."']");
        foreach ($filters as $filter) {
            $entryEl = $this->_addElement($filter, 'Entry');
            $cdata = $this->_dom->createCDATASection('<FilterExpression xmlns:map="http://apache.org/cocoon/sitemap/1.0">'.$data.'</FilterExpression>');
            $entryEl->appendChild($cdata);
        }
        
    }
    
     /**
     * Writes the mapping file to a specified file
     * 
     * @param String $filename Filename and path to write to
     * 
     * @return void
     */
    public function writeToFile($filename)
    {
        $this->_dom->formatOutput = true;
        $output = $this->_dom->save($filename);
        $this->_dom->formatOutput = false;
    }
    
     /**
     * Send the mapping file to the siruna server
     * 
     * @return Strin Result of POST
     */
    public function postToServer()
    {
        $this->_calculateHash();
        $output = $this->_dom->saveXML();
        $output = utf8_encode($output);
        $output = ereg_replace("^[^<]*", "", $output); //remove nasty BOM characters
        
        $url = $this->settingsManager->getSirunaURL() . ':';
        $url .= $this->settingsManager->getSirunaPort() . "/CMSIntegrationWAR/WAFLProcessMapping";
        $url = "http://wafl.desruelle.be:8080/CMSIntegrationWAR/WAFLProcessMapping";

        $fields = array('mapping' => $output);
        return $this->_doPost($url, $fields);
    }

     /**
     * Calculates the required security hash
     *
     * @return void
     */
    private function _calculateHash()
    {
        $md5content = $this->_dom->saveXML($this->_mappingEl);
        $md5content .= $this->_secretkey;
        
        // hash the shit out of it baby!
        $hash = md5($md5content);
        
        $xpath = new DOMXPath($this->_dom);
        $hashEls = $xpath->query('//Hash');
        foreach ($hashEls as $hashEl) {
            $this->_rootEl->removeChild($hashEl);
        }
        
        $this->_addTextElement($this->_rootEl, 'Hash', $hash);
    }
    
    /**
     * CURL post helper
     * 
     * @param String $url    URL of server
     * @param Array  $fields Array with key/value pairs
     *
     * @return String Result of post
     */
    private function _doPost($url, $fields) 
    {
        $fieldsStr = '';
        foreach ($fields as $key=>$value) { 
            $fieldsStr .= $key.'='.$value.'&'; 
        }
        rtrim($fieldsStr, '&');
        
        $headers = array(
            'Content-type: application/x-www-form-urlencoded',
            'Content-length: '.strlen($fieldsStr)
        );
        
        //open connection and set some options
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, count($fields));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fieldsStr);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        //execute post
        $result = curl_exec($curl);
        
        if (strlen($result)<3) {
            return "Mapping sent succesfully";
        } else {
            return $result;
        }
        
        curl_close($curl);
    } 
    
    /**
     * DOMDocument helper to easily add a new text node
     * 
     * @param String $root Element to add new node
     * @param String $name Name of new node
     * @param String $text Text of new node
     *
     * @return void
     */
    private function _addTextElement($root, $name, $text)
    {
        $newElement = $this->_dom->createElement($name);
        $root->appendChild($newElement);
        
        $textNode = $this->_dom->createTextNode($text);
        $newElement->appendChild($textNode);
        
        return $newElement;
    }
    
    /**
     * DOMDocument helper to easily add an empty node
     * 
     * @param String $root Element to add new node
     * @param String $name Name of new node
     *
     * @return void
     */
    private function _addElement($root, $name)
    {
        $newElement = $this->_dom->createElement($name);
        $root->appendChild($newElement);
        return $newElement;
    }
    
}
?>