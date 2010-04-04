<?php
/**
* @version		$Id: helper.php 6140 2007-03-01 08:00:18Z jj $
* @package		Joomla M17n
* @copyright	Copyright (C) 2005 - 2007 SDIC SA
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


jimport('joomla.base.tree');
jimport('joomla.utilities.simplexml');

/**
 * mod_mainmenu Helper class
 *
 * @static
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package		Joomla
 * @subpackage	Menus
 * @since		1.5
 */
class modMainMenuHelper
{
	function buildXML(&$params)
	{
		$menu = new JMenuTree($params);
		$items = &JSite::getMenu();

		// Get Menu Items
		$rows = $items->getItems('menutype', $params->get('menutype'));

		// Build Menu Tree root down (orphan proof - child might have lower id than parent)
		$user =& JFactory::getUser();
		$ids = array();
		$ids[0] = true;
		$orphans = array();

		// pop the first item until the array is empty
		if(is_array($rows)){
			$tripleCheck=array();
			while ( !is_null($row = array_shift($rows)))
			{
				if (array_key_exists($row->parent, $ids)) {
					$menu->addNode($row);
					// record loaded parents
					$ids[$row->id] = true;
				} else {
					// SDIC Hack : M17n endless loop prevention
					$wasPushed=0;
					if(isset($tripleCheck[$row->id]))$wasPushed=$tripleCheck[$row->id];
					// no parent yet so push item to back of list
					if($wasPushed < 3){
						// Pushed only three times
						$wasPushed++;
						$tripleCheck[$row->id]=$wasPushed;
						array_push($rows, $row);
					}else{
						// No parent found, incorrect language settings
						// Element will not be usedbut stored in $orphans
						$orphans[]=$row->id;
					}
				}
			}
		}
		return $menu->toXML();
	}

	function &getXML($type, &$params, $decorator)
	{
		static $xmls;

		if (!isset($xmls[$type])) {
			$cache =& JFactory::getCache('mod_mainmenu');
			$string = $cache->call(array('modMainMenuHelper', 'buildXML'), $params);
			$xmls[$type] = $string;
		}

		// Get document
		$xml = new menuXML();
		$xml->loadString($xmls[$type]);
		$doc = &$xml->document;

		$menu	= &JSite::getMenu();
		$active	= $menu->getActive();
		$start	= $params->get('startLevel');
		$end	= $params->get('endLevel');
		$sChild	= $params->get('showAllChildren');
		$path	= array();

		// Get subtree
		if ($start) 
		{
			$found = false;
			$root = true;
			$path = $active->tree;
			for ($i=0,$n=count($path);$i<$n;$i++)
			{
				foreach ($doc->children() as $child)
				{
					if ($child->attributes('id') == $path[$i]) {
						$doc = &$child->ul[0];
						$root = false;
						break;
					}
				}
				
				if ($i == $start-1) {
					$found = true;
					break;
				}
			}
			if ((!is_a($doc, 'JSimpleXMLElement')) || (!$found) || ($root)) {
				$doc = new menuXMLElement('ul');
			}
		}		

		if ($doc && is_callable($decorator)) {
			$doc->map($decorator, array('end'=>$end, 'children'=>$sChild));
		}
		return $doc;
	}

	function render(&$params, $callback)
	{
		switch ( $params->get( 'menu_style', 'list' ) )
		{
			case 'list_flat' :
				// Include the legacy library file
				require_once(dirname(__FILE__).DS.'legacy.php');
				mosShowHFMenu($params, 1);
				break;

			case 'horiz_flat' :
				// Include the legacy library file
				require_once(dirname(__FILE__).DS.'legacy.php');
				mosShowHFMenu($params, 0);
				break;

			case 'vert_indent' :
				// Include the legacy library file
				require_once(dirname(__FILE__).DS.'legacy.php');
				mosShowVIMenu($params);
				break;

			default :
				// Include the new menu class
				// require_once(dirname(__FILE__).DS.'menu.php');
				$xml = modMainMenuHelper::getXML($params->get('menutype'), $params, $callback);
				if ($xml) {
					$class = $params->get('class_sfx');
					$xml->addAttribute('class', 'menu'.$class);
					if ($tagId = $params->get('tag_id')) {
						$xml->addAttribute('id', $tagId);
					}
					
					echo JFilterOutput::ampReplace($xml->toString((bool)$params->get('show_whitespace')));
				}
				break;
		}
	}
}

/**
 * Main Menu Tree Class.
 *
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package		Joomla
 * @subpackage	Menus
 * @since		1.5
 */
class JMenuTree extends JTree
{
	/**
	 * Node/Id Hash for quickly handling node additions to the tree.
	 */
	var $_nodeHash = array();

	/**
	 * Menu parameters
	 */
	var $_params = null;

	/**
	 * Menu parameters
	 */
	var $_buffer = null;

	function __construct(&$params)
	{
		$this->_params =& $params;
		$this->_root =& new JMenuNode(0, 'ROOT');
		$this->_nodeHash[0] =& $this->_root;
		$this->_current = & $this->_root;
	}

	function addNode($item)
	{
		// Get menu item data
		$data = $this->_getItemData($item);

		$itemConf=new JParameter($item->params);
		$lang=$itemConf->get('lang',null);
		$kids=$itemConf->get('kids',null);
		$users=$itemConf->get('users',null);
		
		// Create the node and add it
		$node = new JMenuNode($item->id, $item->name, $item->access, $data, null, $lang, $kids, $users);
		
		if (isset($item->mid)) {
			$nid = $item->mid;
		} else {
			$nid = $item->id;
		}
		$this->_nodeHash[$nid] =& $node;
		$this->_current =& $this->_nodeHash[$item->parent];

		if ($this->_current) {
			$this->addChild($node, true);
		} else {
			// sanity check
			JError::raiseError( 500, 'Orphan Error. Could not find parent for Item '.$item->id );
		}
	}

	function toXML()
	{
		// Initialize variables
		$this->_current =& $this->_root;

		// Recurse through children if they exist
		while ($this->_current->hasChildren())
		{
			$this->_buffer .= '<ul>';
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->_getLevelXML(0);
			}
			$this->_buffer .= '</ul>';
		}
		if($this->_buffer == '') { $this->_buffer = '<ul />'; }
		return $this->_buffer;
	}

	function _getLevelXML($depth)
	{
		$depth++;

		// Start the item
		$this->_buffer .= '<li access="'.$this->_current->access.'" level="'.$depth.'" id="'.$this->_current->id.'" lang="'.$this->_current->lang.'" kids="'.$this->_current->kids.'" users="'.$this->_current->users.'">';

		// Append item data
		$this->_buffer .= $this->_current->link;

		// Recurse through item's children if they exist
		while ($this->_current->hasChildren())
		{
			$this->_buffer .= '<ul>';
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->_getLevelXML($depth);
			}
			$this->_buffer .= '</ul>';
		}

		// Finish the item
		$this->_buffer .= '</li>';
	}

	function _getItemData($item)
	{
		$data = null;

		// Menu Link is a special type that is a link to another item
		if ($item->type == 'menulink')
		{
			$menu = &JSite::getMenu();
			if ($tmp = clone($menu->getItem($item->query['Itemid']))) {
				$tmp->name	 = '<span><![CDATA['.$item->name.']]></span>';
				$tmp->mid	 = $item->id;
				$tmp->parent = $item->parent;
			} else {
				return false;
			}
		} else {
			$tmp = clone($item);
			$tmp->name = '<span><![CDATA['.$item->name.']]></span>';
		}

		$iParams = new JParameter($tmp->params);
		if ($iParams->get('menu_image') && $iParams->get('menu_image') != -1) {
			$image = '<img src="'.JURI::base(true).'/images/stories/'.$iParams->get('menu_image').'" alt="" />';
		} else {
			$image = null;
		}
		switch ($tmp->type)
		{
			case 'separator' :
				return '<span class="separator">'.$image.$tmp->name.'</span>';
				break;

			case 'url' :
				if ((strpos($tmp->link, 'index.php?') !== false) && (strpos($tmp->link, 'Itemid=') === false)) {
					$tmp->url = $tmp->link.'&amp;Itemid='.$tmp->id;
				} else {
					$tmp->url = $tmp->link;
				}
				break;

			default :
				$tmp->url = 'index.php?Itemid='.$tmp->id;
				break;
		}

		// Print a link if it exists
		if ($tmp->url != null)
		{
			// Handle SSL links
			$iSecure = $iParams->def('secure', 0);
			if ($tmp->home == 1) {
				$tmp->url = JURI::base();
			} elseif (strcasecmp(substr($tmp->url, 0, 4), 'http') && (strpos($tmp->link, 'index.php?') !== false)) {
				$tmp->url = JRoute::_($tmp->url, true, $iSecure);
			} else {
				$tmp->url = str_replace('&', '&amp;', $tmp->url);
			}

			switch ($tmp->browserNav)
			{
				default:
				case 0:
					// _top
					$data = '<a href="'.$tmp->url.'">'.$image.$tmp->name.'</a>';
					break;
				case 1:
					// _blank
					$data = '<a href="'.$tmp->url.'" target="_blank">'.$image.$tmp->name.'</a>';
					break;
				case 2:
					// window.open
					$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$this->_params->get('window_open');

					// hrm...this is a bit dickey
					$link = str_replace('index.php', 'index2.php', $tmp->url);
					$data = '<a href="'.$link.'" onclick="window.open(this.href,\'targetWindow\',\''.$attribs.'\');return false;">'.$image.$tmp->name.'</a>';
					break;
			}
		} else {
			$data = '<a>'.$image.$tmp->name.'</a>';
		}

		return $data;
	}
}

/**
 * Main Menu Tree Node Class.
 *
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package		Joomla
 * @subpackage	Menus
 * @since		1.5
 */
class JMenuNode extends JNode
{
	/**
	 * Node Title
	 */
	var $title = null;

	/**
	 * Node Link
	 */
	var $link = null;

	/**
	 * CSS Class for node
	 */
	var $class = null;

	/**
	 * Node Lang
	 */
	var $lang = null;

	/**
	 * Ids of node childrens
	 */
	var $kids = null;

	/**
	 * User Access List
	 */
	var $users = null;

	function __construct($id, $title, $access = null, $link = null, $class = null, $lang = null, $kids = null, $users = null)
	{
		$this->id		= $id;
		$this->title	= $title;
		$this->access	= $access;
		$this->link		= $link;
		$this->class	= $class;
		$this->lang		= $lang;
		$this->kids		= $kids;
		$this->users	= $users;
	}
}

function isMenuType($str){
	$db=JFactory::getDBO();
	$db->setQuery("SELECT id FROM `#__menu_types` WHERE menutype='$str'");
	$id=$db->loadResult();
	if($id==null || $id=="" || $id<1){
		return false;
	}else{
		return true;
	}
}

function getMenuByType($type){
	$db=JFactory::getDBO();
	$db->setQuery("SELECT id FROM `#__menu` WHERE menutype='$type'");
	$ids=$db->loadResultArray();
	if (is_array($ids) && count($ids)>0) {
		return $ids;
	}else{
		$arr=Array();
		return $arr;
	}
}

// XML classes Hacked by Extension
// Recursive Element Needed
class menuXML extends JSimpleXML
{
	function __construct($options = null){
		parent::__construct($options = null);
	}
	/**
	 * Handler function for the start of a tag
	 *
	 * @access protected
	 * @param resource $parser
	 * @param string $name
	 * @param array $attrs
	 */
	function _startElement($parser, $name, $attrs = array())
	{
		//Make the name of the tag lower case
		$name = strtolower($name);

		//Check to see if tag is root-level
		if (count($this->_stack) == 0)
		{
			//If so, set the document as the current tag
			$this->document = new menuXMLElement($name, $attrs);

			//And start out the stack with the document tag
			$this->_stack = array('document');
		}
		//If it isn't root level, use the stack to find the parent
		else
		{
			 //Get the name which points to the current direct parent, relative to $this
			$parent = $this->_getStackLocation();

			//Add the child
			eval('$this->'.$parent.'->addChild($name, $attrs, '.count($this->_stack).');');

			//Update the stack
			eval('$this->_stack[] = $name.\'[\'.(count($this->'.$parent.'->'.$name.') - 1).\']\';');
		}
	}
}

class menuXMLElement extends JSimpleXMLElement {
	
	function __construct($name, $attrs = array(), $level = 0){
		parent::__construct($name, $attrs, $level);
	}
	/**
	 * Return a well-formed XML string based on SimpleXML element
	 *
	 * @return string
	 */
	### Hack By SDIC : List menu overwrites parent when children found
	### Parameter added to prevent impact on the method, only called by mod_mainmenu
	function asXML($whitespace=true,$includeParent=false)
	{
		//Start a new line, indent by the number indicated in $this->level, add a <, and add the name of the tag
		if ($whitespace) {
			$out = "\n".str_repeat("\t", $this->_level).'<'.$this->_name;
		} else {
			$out = '<'.$this->_name;
		}

		//For each attribute, add attr="value"
		foreach($this->_attributes as $attr => $value)
			$out .= ' '.$attr.'="'.$value.'"';

		//If there are no children and it contains no data, end it off with a />
		if(empty($this->_children) && empty($this->_data))
			$out .= " />";

		//Otherwise...
		else
		{
			//If there are children
			if(!empty($this->_children))
			{
				//Close off the start tag
				$out .= '>';
				### Hack By SDIC : List menu overwrites parent when children found
				### When includeParent is true, parent's data is added back to output
				if($includeParent)$out.=$this->_data;

				//For each child, call the asXML function (this will ensure that all children are added recursively)
				foreach($this->_children as $child)
					$out .= $child->asXML($whitespace,$includeParent);

				//Add the newline and indentation to go along with the close tag
				if ($whitespace) {
					$out .= "\n".str_repeat("\t", $this->_level);
				}
			}

			//If there is data, close off the start tag and add the data
			elseif(!empty($this->_data))
				$out .= '>'.$this->_data;

			//Add the end tag
			$out .= '</'.$this->_name.'>';
		}

		//Return the final output
		return $out;
	}
	
	function trash(){
		foreach ($this->children() as $child)
		{
			$this->removeChild($child);
		}
		
		$this->removeAttribute('kids');
		$this->removeAttribute('users');
		$this->removeAttribute('lang');
		$this->removeAttribute('access');
		$this->removeAttribute('level');
		
		// Hack By SDIC
		$this->setData("");
		$this->_name='input';
		$this->addAttribute('type',"hidden");
		$this->addAttribute('name',"hiddenmenu");
	}
}

?>