<?php

/**
* @package 		FileManagement
* @copyright 	Copyright (C) 2009 DecryptWeb. All rights reserved.
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class FilemgmtModelFilemgmt extends JModel
{
	
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;
	}
	
	function getData()
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT * FROM #__file_upload ORDER BY udate DESC";
		$db->setQuery( $qry );		
		$result=$db->loadObjectList();		
		return $result;
	}
	
	function submitfile()
	{
		global $mainframe;
   		$db = & JFactory::getDBO();   		
   		$url = "index.php?option=".$_REQUEST['option']."";   		
		//below code for file upload		
   		//start
   		if(isset($_FILES))
   		{   						
   			$tmp_name = $_FILES['fpdf']['tmp_name'];
   			$fname = $_FILES['fpdf']['name'];
   			$filesize = intval($_FILES['fpdf']['size']);
   			$size_in_mb = ceil($filesize/(1024*1024)); 
   			if($size_in_mb<=5)
   			{  							   							
   				$destination =  JPATH_SITE.'/dwfuploads';   				
   				if(is_dir($destination))
   				{   
   					$fcopy = move_uploaded_file($tmp_name, "$destination/$fname");
   				} 	
   				$new_file = "$destination/$fname";
   					 
   				if(file_exists($new_file))
   				{   		
   					$udate = gmdate("Y-m-d h:i:s");
   					$new_name = addslashes($fname);
   					$sql= "INSERT INTO `#__file_upload`(`fname`,`udate`) VALUES('$new_name','$udate')";			 		
			 		$db->setQuery($sql);			 		
					if(!$db->query())
					{
						$msg = 'Wrong query fired';   						
					}else
					{
						$msg = 'File Uploaded Successfully.';  				
					}					
   				}else
   				{
   					$msg = 'Unable to upload file,please give permission and try again';   					
   				} 
   				
   			}else
   			{
   				$msg = 'File size must be less than 5MB, ';   				
   			}  					
   		}else 
   		{
   			$msg = 'File Not found.';   			  			
   		}    		
   		$mainframe->redirect($url,$msg);						
   		
	}

	function removefile()
	{
		global $mainframe;
		$cid = JRequest::getVar( 'cid', array(), '', 'array');
		$cids = implode( ',', $cid );
		$db = JFactory::getDBO();
	
		$url = "index.php?option=".$_REQUEST['option']."";   		
		if(count($cid)>0)
		{		
			
			while(current($cid))
			{
				$cat_id = current($cid);			
				
				$db->setQuery("SELECT `fname` FROM `#__file_upload` WHERE `id`=$cat_id ");		
				$row = $db->loadAssoc();
				$fname = $row['fname'];
						
				$destination =  JPATH_SITE.'/dwfuploads/'.$fname;   								
   				
   				if(file_exists($destination))
   				{   	
					unlink($destination);
				}else
				{
					$msg = "File not found.";
					$mainframe->redirect($url,$msg);	
				}							
				
				next($cid);
			}					
			
			$del="DELETE FROM `#__file_upload` WHERE `id` IN ($cids)";
			$db->setQuery($del);
			
			if($db->query())
			{
				$msg = "Data deleted successfully.";
			}else
			{
				$msg = "Please try again.";
			}		
		}else
		{
			$msg = "No selection.";
		}
		
		$mainframe->redirect($url,$msg);	
		
	}
	
}