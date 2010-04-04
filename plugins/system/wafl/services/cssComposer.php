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
//defined('_JEXEC') or die('Restricted access');

/**
 * Including dependencies
 */
require_once  JPATH_SITE . DS. 'administrator'.DS.'components'.DS.
    'com_templates'.DS. 'helpers'.DS.'template.php';
require_once  'settingsManager.php';
require_once  JPATH_SITE . DS. 'plugins' . DS . 'system' . DS 
. 'wafl' . DS . 'services'.DS.'cssTidyReader.php';
require_once  JPATH_SITE . DS. 'plugins' . DS . 'system' . DS 
. 'wafl' . DS . 'services' . DS . 'settingsManager.php';


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
class CssComposer
{
    private static $_instance;
    // reader instance
    var $reader;
    // entire css array from css file
    var $css;
    // folder to check
    var $folder;
    // current template
    var $template;
    // current file being written to
    var $file;

    /**
     * Constructor
     *
     * @return void
     */
    function CssComposer()
    {      
        //$this->compose("ja_purity");
        //$this->compose("beez");
    }
    
    /**
     * Gets de composer instance
     * 
     * @return Composer The composer instance
     */
    public static function getCssComposerInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new CssComposer();
        }
        
        return self::$_instance;
    } 
    
    /**
     * Composer main function.
     * 
     * Previous code, to use when the template NAME is given in stead
     * of the directory: 
     * 
     * $rows = TemplatesHelper::parseXMLTemplateFiles(JPATH_BASE.DS.'templates');
     *   
     *   foreach ($rows as $row) {
     *       if ($row->name === $newtemplate) {
     *           $this->template = $row->directory;
     *       }
     *   }       
     * 
     * @param String $newtemplate The template directory to read in
     * 
     * @return void
     */
    public function compose($newtemplate)
    {
        $this->template = $newtemplate;
         
        //$this->cssfile = JPATH_SITE . DS.'templates' .DS. $this->template .DS.
        //    'css' .DS. 'template.css';
        
        $this->folder = JPATH_SITE . DS.'templates' .DS. $this->template .DS.
            'css' .DS ;

        //$this->reader = new CssTidyReader($this->cssfile);

        //$this->css = $this->reader->getArray();
        $this->scanFolder();
    }

    /**
     * Returns the cssfile on which the waflcss file
     * is based, no longer needed when using the folder scan
     *
     * @return cssFile
     *
    public function getCSSfile()
    {
        return $this->cssfile;
    }*/

    /**
     * Scans the css folder of the current template
     * and when a .css, a .inc or a .php file is
     * found, the css file is build into the
     * wafl css directory
     *
     * @return boolean succes Is true when no errors occured
     * 
     */
    public function scanFolder()
    {
        $this->foldercontents = scandir($this->folder);
        foreach ($this->foldercontents as $filetocheck) {
            $iscss = strpos($filetocheck, '.css');
            $isphp = strpos($filetocheck, '.php');
            $isinc = strpos($filetocheck, '.inc');


            if ($iscss != false || $isphp != false  || $isinc != false ) {
                /**
                 * First clear the template.css file, so previous data is erased
                 */
                $this->clear($filetocheck);
                
                $this->addAllToCSS($filetocheck, 1);
            }
        } 	
        
        /**
         * Returns true when no errors occured 
         * (which would stop the method from getting here)
         */ 
        return true;
    }

    /**
     * Add argument to css file
     *
     * @param string $argument Arguement to add
     * 
     * @return void
     *
    public function addToCSS($argument)
    {

        $Handle = fopen($this->file, 'a');

        fwrite($Handle, "$argument { \n");

        foreach ($this->reader->getAllProperties($argument) as $key => $value) {
            $towrite = "    $key : $value; \n";
            fwrite($Handle, $towrite);
        }

        fwrite($Handle, "} \n \n");

        fclose($Handle);
        umask($this->file);
    }*/

    /**
     * Clear the CSS file
     * 
     * @param String $filetocheck The css file to clean
     *
     * @return void
     */
    public function clear($filetocheck)
    {
        $this->file = JPATH_SITE . DS.'templates'.DS.'wafl'.DS.'css' 
            .DS. $filetocheck;
        $clearHandle = fopen($this->file, 'w');
        fwrite($clearHandle, "");
        fclose($clearHandle);
        umask($this->file);

    }

    /**
     * Method to add the entire CSS file to the wafl css file
     * when autowidth set to 1 all width attributes are
     * set to auto
     *
     * @param String  $filetocheck The file to add to cssfile
     * @param boolean $autowidth   Using autowidth or not
     * 
     * @return unknown_type
     */
    public function addAllToCSS($filetocheck, $autowidth)
    {

        
        $this->file = JPATH_SITE . DS.'templates'.DS.'wafl'.DS.'css' 
            .DS. $filetocheck;

        $Handle = fopen($this->file, 'a');

        $fileurl = $this->folder .DS. $filetocheck;

        $this->reader = new CssTidyReader($fileurl);
        $this->css = $this->reader->getArray();
        

        foreach ($this->css as $key => $value) {

            fwrite($Handle, "$key { \n");

            foreach ($value as $attribute => $attributevalue) {

                $checkpx = strpos($attributevalue, 'px');

                if ($autowidth ==  1 && $attribute === 'width') {
                    $towrite = "    width : auto; \n";
                } else if ($attribute ==='font-size') {
                    $towrite = "    font-size : 11px; \n";
                } else if ($checkpx != false) {
                    $towrite ="";
                } else if ($attribute === 'background') {
                    
                    $double = strpos($attributevalue, '../../');
                    
                    $single = strpos($attributevalue, '../');
                     
                    if ($double  != false) {
                        $string = "../../../" . $this->template . DS 
                        .substr($attributevalue, $double + 6);
                        $towrite = "    $attribute : url(" . $string ." ; \n";
                        
                    } else if ($single  != false) {
                        $string = "../../" . $this->template . DS 
                        .substr($attributevalue, $single + 3);
                        $towrite = "    $attribute : url(" . $string ." ; \n";
                    } else {
                        $towrite = "    $attribute : $attributevalue; \n";
                    }
                    
                } else {
                    $towrite = "    $attribute : $attributevalue; \n";
                }
                
                fwrite($Handle, $towrite);
                 
            }

            if ($key === 'body') {
                $towrite = "    font-size : 11px; \n";
                fwrite($Handle, $towrite);
            }

            fwrite($Handle, "} \n \n");



        }
        fwrite(
            $Handle, "
        	#waflpage { \n
        		width: 240px; \n
            	min-width: 240px; \n
            	max-width: 240px; \n \n }"
        );

        fclose($Handle);
        umask($this->file);
    }


}

?>