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

	if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
		die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
	}
		
	
	function com_install()
	{		
		
		$tmp_fol = JPATH_SITE.'/dwfuploads';
			
		
		if(file_exists($tmp_fol)==false)
		{
			$flg = mkdir($tmp_fol, 0777);   
		}
	
		if($flg==false)
		{	
			echo "<span class=\"message\">Failed to create a folder, first give permission to site directory or create folder named 'dwfuploads' in your site root directory\n</span>";
			return false;
		}else
		{
			return true;
		}
	}
	
?>