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

	defined( '_JEXEC' ) or die( 'Restricted access' );	
	
	$files = $this->filelist;	
	
	$tmp_fol = JPATH_SITE.'/dwfuploads';
	if(file_exists($tmp_fol))
	{
		$flg = 1; 
	}else
	{	
		$flg = 2; 
	}	
?>
<?php if($flg == 1): ?>
<script language="javascript" type="text/javascript">
	function submitbutton( task )
	{			
		if(task=='save')
		{
			var fname =  document.adminForm.fpdf;
			//var valid_extensions = /(.pdf)$/i;
			var valid_extensions = /(\.doc|\.docx|\.pdf|.\xls|.\xlsx)$/i;
			//alert(fname.value);
			if(fname.value == '')
			{
				alert('Please choose a file');				
				return false;
			}else{
				if (valid_extensions.test(fname.value)==false)
				{ 
					alert('This file is not allowed.');
					fname.value='';
					fname.focus();
					return false;
				}
			}
		}				
			submitform(task);
	}	
</script>

		<form name="adminForm" method="POST" action="index.php" enctype="multipart/form-data">		
		 
		<table width="100%" align="center" class="adminlist">		
		<tr><td><b>Upload File&nbsp;<font color="red">(Files with extensions doc,docx,pdf,xls,xlsx are allowed)</font></b></td></tr>
		<tr><td><input type="file" name="fpdf" id="fpdf"></td></tr>		
		</table>
		
		<br/>	
		<table width="100%" align="center" class="adminlist">
		<?php if(count($files)>0): ?>		 
		<tr>
		<th width="6%"><input type="checkbox" name="toggle" onclick="checkAll(<?php echo count($files); ?>);" /></th>
		<th>File Name</th><th>Date</th>
		</tr>
		<?php			
			for( $i = 0; $i < count($files); $i++ ):
			$row = &$files[$i];			
			$checked = JHTML::_('grid.id', $i, $row->id );
			$base =  JURI::root();							
			$path = $base.'dwfuploads/'. $row->fname;	
			
			$file =  JPATH_SITE.'/dwfuploads/'.$row->fname;   				
   			if(file_exists($file))
			{
				$full = "<a href='$path' title='$path' target='_blank'>$row->fname</a>";
			}else
			{
				$full = 'No file found';				
			}	
	 	?>			
    	<tr height='30px'>
      <td><?php echo $checked; ?></td>    
      <td><?php echo $full; ?></td>
      <td><?php echo $row->udate ?> </td>
    	</tr>
    <?php
			endfor;
			else:
			echo '<tr><td colspan="3">No File Exists</td></tr>';
	?>	
 	
	<?php endif; ?>
	</table>
	<input type="hidden" name="boxchecked" value="" />  
	<input type="hidden" name="controller" value="filemgmt" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="<?php echo $_REQUEST['option']; ?>" />
	</form>
<?php else:
echo " 'dwfuploads' Folder was not found.Please create folder named 'dwfuploads' [with full permissions] in your site root directory and try again";
endif;
?>