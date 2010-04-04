<?php
/**
 * sirunaParser.php
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
require_once WAFL_DIR . DS . 'util' . DS . 'fileNotFoundException.php';
require_once WAFL_DIR . DS . 'broker' . DS . 'sirunaMapping.php';

/**
 * sirunaParser.php
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
class SirunaParser
{
    private $_mapping;
    private $_sitemap;
    private $_sirunaMapping;

    /**
     * Constructor 
     * 
     * @param XML     $mapping The mapper-object
     * @param Sitemap $sitemap The sitemap
     * 
     * @return void
     */
    function SirunaParser($mapping, $sitemap)
    {
        $this->_mapping       = $mapping;
        $this->_sitemap       = $sitemap;
        $this->_sirunaMapping = new SirunaMapping();
                
        $this->_parseMapping();
        $this->_parseSitemap();
    }
    
    /**
     * Returns a SirunaMapping object based on parsed data.
     *
     * @return SirunaMapping mapping file
     */
    public function getSirunaMapping()
    {
        return $this->_sirunaMapping;
    }
    
    /**
     * Parses the mapping file
     *
     * @return void
     */
    private function _parseMapping()
    {
        if (file_exists($this->_mapping)) {
            $xml = simplexml_load_file($this->_mapping);
        } else {
            throw new FileNotFoundException($this->_mapping);
        }
        
        $filtersNode = $xml->filters;
        foreach ($filtersNode->children() as $filterNode) {
            $name = (string)$filterNode->name;
            $defaulturl = (string)$filterNode->defaulturl;
            $expressions = array();
            foreach ($filterNode->expressions->children() as $expressionNode) {
                array_push($expressions, (string)$expressionNode);                     
            }
            
            $this->_sirunaMapping->addFilter($name, $defaulturl, $expressions);
        }
    }
    
    /**
     * Parses the sitemap
     *
     * @return void
     */
    private function _parseSitemap()
    {
        if (file_exists($this->_sitemap)) {
            $xml = simplexml_load_file($this->_sitemap);
        } else {
            throw new FileNotFoundException($this->_mapping);
        }
        $xml->registerXPathNamespace('map', 'http://apache.org/cocoon/sitemap/1.0');
        $matchNodes = $xml->xpath("//map:match");
        
        foreach ($matchNodes as $matchNode) {
            $pattern = (string)$matchNode['pattern'];

            // Hack om bug in SimpleXML te omzeilen
            $str = $matchNode->asXML();
            $start = '<map:match pattern="'.$pattern.'">';
            $stop = '</map:match>';
            $str = substr($str, stripos($str, $start)+strlen($start));
            $str = substr($str, 0, stripos($str, $stop));
            trim($str);
            
            $this->_sirunaMapping->addEntryToFilter($pattern, $str);
                
        }
    }
}
?>