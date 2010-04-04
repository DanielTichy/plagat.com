<?php
/**
 * xcache.php
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
 * XCache cache storage handler
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
class JCacheStorageXCache extends JCacheStorage
{
    /**
     * Constructor
     *
     * @param array $options optional parameters
     */
    function __construct($options = array())
    {
        parent::__construct($options);

        $config			=& JFactory::getConfig();
        $this->_hash	= $config->getValue('config.secret');
    }

    /**
     * Get cached data by id and group
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

        //check if id exists
        if (!xcache_isset($cache_id)) {
            return false;
        }

        return xcache_get($cache_id);
    }

    /**
     * Store the data by id and group
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
        return xcache_set($cache_id, $data, $this->_lifetime);
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

        if (!xcache_isset($cache_id)) {
            return true;
        }

        return xcache_unset($cache_id);
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
     * Test to see if the cache storage is available.
     *
     * @static
     * @access public
     *
     * @return boolean True on success, false otherwise.
     */
    function test()
    {
        return (extension_loaded('xcache'));
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