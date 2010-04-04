<?php
/**
 * settings.php
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Tables
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

defined('_JEXEC') or die();

/**
 * Settings table handler
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Tables
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
class TableSettings extends JTable
{
    /** @var int Primary key */
    var $id = null;

    /** @var string Siruna login */
    var $siruna_login = "not set";

    /** @var string Siruna pass */
    var $siruna_pass = "not set";  
    
    /** @var string URL to Siruna server */
    var $siruna_url = "not set";

    /** @var string Port to Siruna*/
    var $siruna_port = 8080;

    /** @var string Project base URL*/
    var $base_url = "not set";
    
    /** @var string Siruna Mobile URL*/
    var $siruna_mobile_url = "not set";
    
    /** @var string Redirect Mobile URL*/
    var $redirect_mobile_url = "not set";
    
    /** @var string Redirect Dropbox value*/
    var $redirect_to_template = "not set";
    
     /** @var string base template dropbox value*/
    var $base_template = "not set";
    
    /** @var string siruna user*/
    var $siruna_user = "not set"; 
    
    /** @var int option*/
    var $option = 1;
    
    /** @var int device detection enabled*/
    var $device_detection = 0;
    
    /**
      * Constructor
      *
      * @param database &$db Database object
      */
    function __construct( &$db )
    {
         parent::__construct('#__wafl_settings', 'id', $db);
    }

}
?>
