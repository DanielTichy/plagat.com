<?php defined('_JEXEC')  or die('Direct Access to this location is not allowed.');

/**
 * Copyright Copyright (C) 2008 Compojoom.com . All rights reserved.
 * Copyright Copyright (C) 2007 Alain Georgette. All rights reserved.
 * Copyright Copyright (C) 2006 Frantisek Hliva. All rights reserved.
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * !JoomlaComment is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * !JoomlaComment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA  02110-1301, USA.
 */


class JOSC_library {

    function getComponentList($set_id=0)
    {
	$list = array();
	if ($set_id==1) {
	    $list[] = JHTML::_('select.option', '', 'com_content');

	} else {
	    
	    $folderlist = JOSC_library::folderList(JPATH_COMPONENT_ADMINISTRATOR.DS.'plugin'.DS,false,true);
	    foreach($folderlist as $com) {
		if ($com!='com_content' && $com!='com_REPLACEnewplugin')
		$list[] = JHTML::_('select.option', $com, $com, 'value', 'text' );
		
	    }

	    array_unshift( $list, JHTML::_('select.option',  '', 'com_content', 'value', 'text' ) );

	}
	return $list;
    }
	
    function viewAbout() {

		/* when change of RELEASE number: use find and replace - should be also in xml and comment.class */
	?>
<table class="adminheading" cellpadding="4" cellspacing="0" border="0">
    <tr>
	<td>
	    <p><b>If your joomla installation is not in UTF-8, check and set if necessary the charset parameter in the joomlacomment setting !!</b></p>
	    <p><br /><b>!JoComment 4.0 alpha2 by  Compojoom.com - <a target="_blank" href="http://compojoom.com">http://compojoom.com</a></b>
		<br />
		<br />Copyright (C) 2008 Compojoom.com . All rights reserved.
		<br />Copyright (C) 2007 Alain Georgette. All rights reserved.
		<br />Copyright (C) 2006 Frantisek Hliva. All rights reserved.
		<br /><br />License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
		<br />!JoComment is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.
		<br />!JoComment is distributed in the hope that it will be useful,
		but <b>WITHOUT ANY WARRANTY</b>; without even the implied warranty of
		<b>MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE</b>.  See the
		GNU General Public License for more details.
		<br />You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
		MA  02110-1301, USA.
	    </p>
	</td>
    </tr>
    <tr>
	<td>
	    <a alt="DONATE" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=danielsd_bg@yahoo.fr&item_name=Daniel%20Dimitrov&amount=10&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8"><img align="center" border="0" src='http://www.paypal.com/en_US/i/btn/x-click-but04.gif' /></a>
	    &nbsp;<b>donation is appreciated.</b>
	</td>
    </tr>
    <tr>
	<td>
	    <img src="./../components/com_comment/joscomment/images/logo.jpeg">
	</td>
    </tr>
</table>
<?php
}

	function readOnly($readonly)
	{
    	return ($readonly) ? " readonly='readonly' " : '';
	}

	function input($tag_name, $tag_attribs, $value, $readonly = false)
	{
    	$readonly = JOSC_library::readOnly($readonly);
    	return "<input name='$tag_name' type='text' $tag_attribs value='$value' $readonly/>";
	}

	function customRadioList( $tag_name, $tag_attribs, $selected, $yes=_CMN_YES, $no=_CMN_NO ) 
	{
		
		$arr = array(
			JHTML::_('select.option',  '0', $no ),
			JHTML::_('select.option',  '1', $yes )
		);

		return JHTML::_('select.radiolist',  $arr, $tag_name, $tag_attribs, 'value', 'text', (int) $selected );
	}
	
	function textarea($tag_name, $tag_attribs, $value, $readonly = false)
	{
	    $readonly = JOSC_library::readOnly($readonly);
	    return "<textarea name='$tag_name' $tag_attribs $readonly>$value</textarea>";
	}

	function hidden($tag_name, $value = '')
	{
    	return "<input type='hidden' name='$tag_name' value='$value' />";
	}

//function button($tag_name, $value, $onClick)
//{
//    return "<input type='button' name='$tag_name' value='$value' onclick='$onClick' />";
//}

	function initVisibleJScript()
	{ 
?>
    	<script type='text/javascript'>
     		function JOSC_adminVisible(emptyvalue, showId, hideId) {
     		    
       		    if (showId && showId!=emptyvalue) {
     		    	document.getElementById(showId).style.visibility='visible';
					document.getElementById(showId).style.display = '';
     		    }
     		    if (hideId && hideId!=emptyvalue) {    
     				document.getElementById(hideId).style.visibility = 'hidden';
     				document.getElementById(hideId).style.display = 'none';
     		    }
     		    return(showId);    
     		}
        </script>
<?php
	}

//	function sections($tag_name, $values)
//	{
//    	$listBox = new JOSC_dbListBox($tag_name);
//    	$listBox->multiple();
//    	$listBox->selected($values);
//    	$listBox->add(-1, 'Static Content', in_array(-1, $listBox->selected));
//    	$listBox->loadFromDb('SELECT id,title FROM #__sections ORDER BY title ASC', 'title');
//    	return $listBox->listBox_htmlCode();
//	}
//
//	function categories($tag_name, $values)
//	{
//	    $listBox = new JOSC_dbListBox($tag_name);
//    	$listBox->multiple();
//    	$listBox->selected($values);
//    	$listBox->loadFromDb('SELECT id,title FROM #__categories WHERE section
//	REGEXP \'[1-9][0-9]*\' ORDER BY section, title ASC', 'title');
//    	return $listBox->listBox_htmlCode();
//	}

//	function usertypes($tag_name, $values = array(), $unregistered = true)
//	{
//    	$listBox = new JOSC_dbListBox($tag_name);
//    	$listBox->multiple();
//    	$listBox->selected($values);
//    	if ($unregistered)
//    	    $listBox->add(-1, 'unregistered', in_array(-1, $listBox->selected));
//	    	$listBox->loadFromDb('SELECT id,name FROM #__core_acl_aro_groups ORDER BY name ASC', 'name');
//    	$listBox->rename('Superadministrator', 'SAdministrator');
//    	return $listBox->listBox_htmlCode();*/
//	}

//function onClick($id, $onClick = '')
//{
//    echo "\n<script type='text/javascript'>";
//    echo "document.getElementById('$id').onclick = function(event)\{$onClick};";
//    echo "</script>";
//}

	function isPHP($fileName)
	{
    	if (strlen($fileName) >= 4) {
    	    if (strtolower(substr($fileName, -4, 4)) == '.php')
    	        return true;
    	}
    	return false;
	}

	function isCSS($fileName)
	{
    	if (strlen($fileName) >= 4) {
    	    if (strtolower(substr($fileName, -4, 4)) == '.css')
    	        return true;
    	}
    	return false;
	}

	function languageList($path)
	{
    	$folder = @dir($path);
    	$darray = array();
    	$darray[] = JHTML::_('select.option', 'auto', 'autodetect');
    	if ($folder) {
    	    while ($file = $folder->read()) {
    	        if (JOSC_library::isPHP($file))
    	            $darray[] = JHTML::_('select.option', $file, substr($file, 0, strlen($file)-4));
    	    }
    	    $folder->close();
    	}
    	sort($darray);
    	return $darray;
	}

	function cssList($path, $makeoption=true)
	{
    	$folder = @dir($path);
    	$darray = array();
    	if ($folder) {
    	    while ($file = $folder->read()) {
    	        if (JOSC_library::isCSS($file))
    	            $darray[] = $makeoption ? JHTML::_('select.option',  $file, substr($file, 0, strlen($file)-4)) : $file;
    	    }
    	    $folder->close();
    	}
    	sort($darray);
    	return $darray;
	}

	function TemplatesCSSList($path)
	{
	    $folderlist = JOSC_library::folderList($path, false);
    	$foldercsslist = array(); 
    	if ($folderlist) 
   		 	foreach($folderlist as $folder) {
    			$foldercsslist[$folder]['template'] = $folder;
    			$foldercsslist[$folder]['css'] 	= JOSC_library::cssList("$path/$folder/css");
    		}
    	return $foldercsslist;
	}

	/*
	 * return array of folder list option
	 */
	function folderList($path, $makeoption=true, $sort=true)
	{
    	$folder = dir($path);
    	$darray = array();
    	if ($folder) {
    	    while ($file = $folder->read()) {
    	        if ($file != "." && $file != ".." && is_dir("$path/$file"))
    	            $darray[] = $makeoption ? JHTML::_('select.option', $file, $file) : $file;
    	    }
    	    $folder->close();
    	}
    	if ($sort) sort($darray);
	
    	return $darray;
	}

	/*
	 * Function to handle an array of integers
 	 * Added 1.0.11
	 * JOSC for BACKWARD COMPATIBILITY
	 */
	function JOSCGetArrayInts( $name, $type=NULL ) 
	{
    
    	if (function_exists('josGetArrayInts')) {
    	  return call_user_func( 'josGetArrayInts', $name, $type ); /* call_user to avoid notice */
    	} else {
			if ( $type == NULL ) {
				$type = $_POST;
			}

			$array = JArrayHelper::getValue( $type, $name, array(0) );

			JArrayHelper::toInteger( $array );

			if (!is_array( $array )) {
			$array = array(0);
			}

			return $array;
    	}
	}

	/*
	 * transform an Array of integer in an Option object list (makeOption)
	 */
	function GetIntsMakeOption($intArray=array(), $OptionKey='id', $OptionValue='title') 
	{ 
		$result 	= array();
		if (count($intArray)>0)
			foreach ( $intArray as $int ) {
				$result[] = JHTML::_('select.option',   $int, "$int", $OptionKey, $OptionValue );
			}
		return $result;
	}

	function copyDir($source,$dest) 
	{		
	
		if(!@mkdir($dest,0755) || ($dirFile=@opendir($source))===false) 
			  return false;
		  
		$result = true;
		while(($file=readdir($dirFile))!==false) {
			if(($file==".." || $file==".")) continue;
		
			$new_source = $source	."/".$file;
			$new_dest 	= $dest		."/".$file;
			if(@is_dir($new_source)) {
			    /* recurse call... */
				$result=JOSC_library::copyDir($new_source,$new_dest);
			} else {
				$result=@copy($new_source,$new_dest);
			}
		}
		closedir($dirFile);
		return $result;
	}
}

class JOSC_element {
    function get($id)
    {
        return "var element = document.getElementById('$id');";
    }
    function visible($visible)
    {
        $result = '';
        if ($visible) {
            $result .= "element.style.visibility = 'visible';";
            $result .= "element.style.display = '';";
        } else {
            $result .= "element.style.visibility = 'hidden';";
            $result .= "element.style.display = 'none';";
        }
        return $result;
    }
}

class JOSC_tabRow {
    var $caption;
    var $component;
    var $help;
    var $id;
    function visible($visible = true)
    {
        if ($this->id) {
            echo "<script type='text/javascript'>";
            echo JOSC_element::get($this->id);
            echo JOSC_element::visible($visible);
            echo "</script>";
        }
    }

    function tabRow_htmlCode()
    {
        $cols = "\n<td align='left' valign='top'><b>$this->caption</b></td>\n";
        $colspan = ($this->help == false) ? " colspan='2'" : '';
        $cols .= "\n<td align='left' valign='top'$colspan>$this->component</td>\n";
        $cols .= ($this->help == false) ? '' : "\n<td align='left' valign='top' width='50%'>$this->help</td>\n";
        $id = $this->id ? " id='$this->id'" : "";
        return "\n<tr$id>$cols</tr>\n";
    }
}

class JOSC_tabRows {
    var $rows = '';
    function addRow(&$row)
    {
        $this->rows .= $row->tabRow_htmlCode();
    }

    function addTitle($title)
    {
        $this->rows .= "\n<tr><th colspan='3' class='title'>$title</th></tr>\n";
    }

    function addSeparator()
    {
        $this->rows .= "\n<tr><td colspan='3'><hr /></td></tr>\n";
    }
    
    /*
     * lines :
     * -	type		'title'	OR	'separator'	OR	'parameter'
     * -    param1  = 	 title							caption			
     * -    param2  =									html input
     * -    param3  =									help
     */
    function createRow( $type=null, $param1=null, $param2=null, $param3=null ) {
              	
            switch ($type) {
                case 'title' :
                	$this->addTitle($param1);
                	break;
                case 'separator':
                	$this->addSeparator();
                	break;
                case 'parameter':
	        		$row 			= new JOSC_tabRow();
       				$row->caption 	= $param1;
       				$row->component	= $param2;
       				$row->help 		= $param3;
       				$this->addRow($row);
                	break;
            }   
	}

    function tabRows_htmlCode()
    {
        return "\n<table class='adminlist' width='100%' cellpadding='4' cellspacing='2'>\n$this->rows\n</table>\n";
    }
    
}

class JOSC_listBox {
    var $_tagName;
    var $_size;
    var $_multiple;
    var $items = array();
    function JOSC_listBox($tagName, $size = 5)
    {
        $this->_tagName = $tagName . '[]';
        $this->_size = $size;
    }
    function multiple($value = true)
    {
        $this->_multiple = $value;
    }
    function add($value, $caption, $selected = false)
    {
        $item['caption'] = $caption;
        $item['selected'] = $selected;
        $this->items[$value] = $item;
    }
    function rename($oldCaption, $newCaption)
    {
        $this->items[array_search($oldCaption, $this->items)]['caption'] = $newCaption;
    }
    function listBox_htmlCode()
    {
        $multiple = $this->_multiple ? " multiple='multiple'" : '';
        $html = "<select size='$this->_size' name='$this->_tagName' class='inputbox'$multiple>";
        foreach ($this->items as $id => $option) {
            $html .= "<option value='$id' ";
            if (isset($option['selected']) && $option['selected']) $html .= "selected='selected'";
            $html .= ">" . ucfirst($option['caption']) . "</option>";
        }
        $html .= '</select>';
        return $html;
    }
}

class JOSC_dbListBox extends JOSC_listBox {
    var $selected;
    function JOSC_dbListBox($tagName, $size = 5)
    {
        $this->JOSC_listBox($tagName, $size);
        $this->selected = array();
    }
    function selected($values)
    {
        $this->selected = split(',', $values);
    }
    function loadFromDb($query, $optionDbColumn, $convertfunction)
    {
		$database =& JFactory::getDBO();
			

        $database->setQuery($query);
        $items = $database->loadAssocList();
        foreach($items as $item) {
            $selected = in_array($item['id'], $this->selected);
            $this->add($item['id'], $item[$optionDbColumn], $selected);
        }
    }
}

?>