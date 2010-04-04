<?php
/**
 * Hello World table class
 * 
 * @package    Joomla
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableUnidad extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;	 
	
	/**
	 * @var string
	 */
	var $title  = null;

        /**
	 * @var string
	 */
	var $file  = null;
 

	/**
	 * @var string
	 */
	var $description  = null; 
	
	/**
	 * @var date
	 */
	var $createdate  = null;

	/**
	 * @var date
	 */
	var $publishdate   = null;

         /**
	 * @var int
	 */
	var $articleid  = null;

         /**
	 * @var string
	 */
	var $articleid_name = null;
 

	/**
	 * @var int
	 */
	var $ordering =  null;

	/**
	 * @var int
	 */
	var $published = 0;


	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableUnidad(& $db) {
		parent::__construct('#__perchadownloadsattach', 'id', $db);
	}

        
	 
}
