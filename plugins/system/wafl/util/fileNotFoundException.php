<?php
/**
 * FileNotFoundException.php
 *
 * PHP version 5
 * 
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Util
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
 * Class to throw an error when a file is not found
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Util
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
class FileNotFoundException extends Exception
{
    /**
     * Redefine the exception so message isn't optional
     * 
     * @param string $message The message to be sent
     * @param int    $code    The message code
     * 
     * @return void
     */
    public function __construct($message, $code = 0)
    {
        // some code
        // make sure everything is assigned properly
        parent::__construct($message, $code);
    }
    /**
     * Custom string representation of object
     * 
     * @return string The error message
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
?>