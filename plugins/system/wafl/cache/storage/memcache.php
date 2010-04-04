<?php
/**
 * memcache.php
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Cache
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

defined('JPATH_BASE') or die();

/**
 * Memcache cache storage handler
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Cache
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
class JCacheStorageMemcache extends JCacheStorage
{
    /**
     * Resource for the current memcached connection.
     * @var resource
     */
    var $_db;

    /**
     * Use compression?
     * @var int
     */
    var $_compress = null;

    /**
     * Use persistent connections
     * @var boolean
     */
    var $_persistent = false;

    /**
     * Constructor
     *
     * @param array $options optional parameters
     */
    function __construct( $options = array() )
    {
        if (!$this->test()) {
            return JError::raiseError(404, "The memcache extension is not available");
        }
        parent::__construct($options);

        $params =& JCacheStorageMemcache::getConfig();
        $this->_compress = (isset($params['compression'])) ? $params['compression'] : 0;
        $this->_db =& JCacheStorageMemcache::getConnection();

        // Get the site hash
        $this->_hash = $params['hash'];
    }

    /**
     * return memcache connection object
     *
     * @static
     * @access private
     *
     * @return object memcache connection object
     */
    function &getConnection() 
    {
        static $db = null;
        if (is_null($db)) {
            $params =& JCacheStorageMemcache::getConfig();
            $persistent	= (isset($params['persistent'])) ? $params['persistent'] : false;
            // This will be an array of loveliness
            $servers	= (isset($params['servers'])) ? $params['servers'] : array();

            // Create the memcache connection
            $db = new Memcache;
            foreach ($servers AS $server) {
                $db->addServer($server['host'], $server['port'], $persistent);
            }
        }
        return $db;
    }

    /**
     * Return memcache related configuration
     *
     * @static
     * @access private
     *
     * @return array options
     */
    function &getConfig() 
    {
        static $params = null;
        if (is_null($params)) {
            $config =& JFactory::getConfig();
            $params = $config->getValue('config.memcache_settings');
            if (!is_array($params)) {
                $params = unserialize(stripslashes($params));
            }

            if (!$params) {
                $params = array();
            }
            $params['hash'] = $config->getValue('config.secret');
        }
        return $params;
    }

    /**
     * Get cached data from memcache by id and group
     *
     * @param string  $id        The cache data id
     * @param string  $group     The cache data group
     * @param boolean $checkTime True to verify cache time expiration threshold
     *
     * @return mixed Boolean false on failure or a cached data string
     */
    function get($id, $group, $checkTime)
    {
        $cache_id = $this->_getCacheId($id, $group);
        return $this->_db->get($cache_id);
    }

    /**
     * Store the data to memcache by id and group
     *
     * @param string $id    The cache data id
     * @param string $group The cache data group
     * @param string $data  The data to store in cache
     *
     * @return boolean True on success, false otherwise
     */
    function store($id, $group, $data)
    {
        $cache_id = $this->_getCacheId($id, $group);
        return $this->_db->set($cache_id, $data, $this->_compress, $this->_lifetime);
    }

    /**
     * Remove a cached data entry by id and group
     *
     * @param string $id    The cache data id
     * @param string $group The cache data group
     *
     * @return boolean True on success, false otherwise
     */
    function remove($id, $group)
    {
        $cache_id = $this->_getCacheId($id, $group);
        return $this->_db->delete($cache_id);
    }

    /**
     * Clean cache for a group given a mode.
     *
     * group mode		: cleans all cache in the group
     * notgroup mode	: cleans all cache not in the group
     *
     * @param string $group The cache data group
     * @param string $mode  The mode for cleaning cache [group|notgroup]
     *
     * @return boolean True on success, false otherwise
     */
    function clean($group, $mode)
    {
        return true;
    }

    /**
     * Garbage collect expired cache data
     *
     * @access public
     *
     * @return boolean True on success, false otherwise.
     */
    function gc()
    {
        return true;
    }

    /**
     * Test to see if the cache storage is available.
     *
     * @static
     * @access public
     *
     * @return boolean True on success, false otherwise.
     */
    function test()
    {
        return (extension_loaded('memcache') && class_exists('Memcache'));
    }

    /**
     * Get a cache_id string from an id/group pair
     *
     * @param string $id    The cache data id
     * @param string $group The cache data group
     *
     * @return string The cache_id string
     */
    function _getCacheId($id, $group)
    {
        if ($_SESSION['mobile'] == true) {
            $prefix = 'wafl';
        } else {
            $prefix = 'desktop';
        }

        $name	= md5($prefix.'-'.$this->_application.'-'.$id.'-'.$this->_hash.'-'.$this->_language);
        return 'cache_'.$group.'-'.$name;
    }
}