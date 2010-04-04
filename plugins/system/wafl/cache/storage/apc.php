<?php
/**
 * apc.php
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
 * APC cache storage handler
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
class JCacheStorageApc extends JCacheStorage
{
    /**
     * Constructor
     *
     * @param array $options optional parameters
     * 
     * @access protected
     * 
     * @return void
     */
    function __construct( $options = array() )
    {
        parent::__construct($options);

        $config =& JFactory::getConfig();
        $this->_hash = $config->getValue('config.secret');
    }

    /**
     * Get cached data from APC by id and group
     *
     * @param string  $id        The cache data id
     * @param string  $group     The cache data group
     * @param boolean $checkTime True to verify cache time expiration threshold
     *
     * @access	public
     * 
     * @return mixed Boolean False on failure or a cached data string
     */
    function get($id, $group, $checkTime)
    {
        $cache_id = $this->_getCacheId($id, $group);
        $this->_setExpire($cache_id);
        return apc_fetch($cache_id);
    }

    /**
     * Store the data to APC by id and group
     *
     * @param string $id    The cache data id
     * @param string $group The cache data group
     * @param string $data  The data to store in cache
     * 
     * @access public
     *
     * @return boolean True on success, false otherwise
     */
    function store($id, $group, $data)
    {
        $cache_id = $this->_getCacheId($id, $group);
        apc_store($cache_id.'_expire', time());
        return apc_store($cache_id, $data, $this->_lifetime);
    }

    /**
     * Remove a cached data entry by id and group
     *
     * @param string $id    The cache data id
     * @param string $group The cache data group
     * 
     * @access public
     *
     * @return boolean True on success, false otherwise
     */
    function remove($id, $group)
    {
        $cache_id = $this->_getCacheId($id, $group);
        apc_delete($cache_id.'_expire');
        return apc_delete($cache_id);
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
     * @access public
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
        return extension_loaded('apc');
    }

    /**
     * Set expire time on each call since memcache sets it on cache creation.
     *
     * @param string $key Cache key to expire.
     * 
     * @access private
     * 
     * @return void
     */
    function _setExpire($key)
    {
        $lifetime	= $this->_lifetime;
        $expire		= apc_fetch($key.'_expire');

        // set prune period
        if ($expire + $lifetime < time()) {
            apc_delete($key);
            apc_delete($key.'_expire');
        } else {
            apc_store($key.'_expire', time());
        }
    }

    /**
     * Get a cache_id string from an id/group pair
     *
     * @param string $id    The cache data id
     * @param string $group The cache data group
     * 
     * @access private
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
