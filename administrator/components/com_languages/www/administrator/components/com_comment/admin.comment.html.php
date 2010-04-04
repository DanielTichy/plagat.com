<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/*
 * Copyright Copyright (C) 2008 Compojoom.com. All rights reserved.
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
class HTML_comments extends JObject {
	
    function viewComments($option, &$rows, &$lists, &$search, &$pageNav, $task='')
    {
        global $mainframe;
        
        /*
         * USED FOR VIEWCOMMENTS BUT ALSO FOR IMPORT PREVIEW !!! 
         */
         
		$onlydisplay = (isset($lists['noedit']) && $lists['noedit']) ? true  : false;
		$adminform	 = (isset($lists['noform']) && $lists['noform']) ? false : true;
		$checkread	 = (isset($lists['checkread']) && $lists['checkread']) ? true : false;

		$null=null;
		$componentObj = JOSC_utils::ComPluginObject($lists['component'],$null);
    	$config 	= JOSC_utils::boardInitialization($componentObj, $null, $null, $exclude=false);		
		$max_length_word = "100"; /* max admin width in characters */

		$component_exist = ($lists['component']=="" || @is_dir(JPATH_SITE."/components/".$lists['component']));
?>
<?php if ($adminform) { ?><form action="index.php" method="post" name="adminForm"><?php } ?>

    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">

    	<tr>

      		<th width="100%" align="left"><?php echo $lists['title'] ?>  </th>
      		<td>
		    <?php echo $lists['componentlist']; ?>
		</td>
      		<td>Search:</td>
      		<td>
        		<input type="text" name="search" value="<?php echo $search; ?>" class="inputbox" onChange="document.adminForm.submit();" />
      		</td>
    	</tr>
    </table>

<?php
// echo 'test';

	if (!$component_exist) {

	
?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
     <tr><td align="center"><b>Component <?php echo $lists['component']; ?> is not installed !</b></td></tr>
    </table>
<?php
	} else {
?>  
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
        <?php $colspan = 16; ?>
        <th width="2%" class="title"><input type="checkbox" name="toggle" <?php echo $checkread ? "DISABLED" : ""; ?> value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_id; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_writer; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_userid; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_notify; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_url; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_date; ?></div></th>
		<th class="title" nowrap="nowrap"><div align="left"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_comment; ?></div></th>
		<th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_contentitem; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_published; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_delete; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_ip; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_votingyes; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_votingno; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_parentid; ?></div></th>
        <th class="title"><div align="center"><?php echo _JOOMLACOMMENT_ADMIN_viewcom_importtable; ?></div></th>
      </tr>
<?php
        $k = 0;
        for ($i = 0, $n = count($rows); $i < $n; $i++) {
            $row = &$rows[$i];

            $componentObj->setRowDatas($row); /* used for link... */

            $row->name 		= isset($row->name) ? $row->name : ''; /* to avoid notice in import preview */
            $row->comment 	= isset($row->comment) ? $row->comment : ''; /* to avoid notice in import preview */

			if (!$onlydisplay) {
				/* parseUBBcode... comment */
				$assoc = JArrayHelper::fromObject( $row, '', '' );
				$assoc['contentid'] = isset($assoc['contentid']) ? $assoc['contentid'] : 0; /* to avoid notice in import preview */
				$config->setContentId($assoc['contentid']);
				$post = $config->initializePost($assoc,''); /* to use post functions ! bbcode replace.... */
				$row->comment = $post->censorText(JOSC_utils::filter($post->_item['comment']));
				$post->setMaxLength_word(min($max_length_word,$post->getMaxLength_word()));
				$post->setSupport_link(false);
				$post->setSupport_pictures(false);
				$row->comment = $post->parseUBBCode($row->comment);
	   	   		/* end parse */
			} else {
	            $row->comment = stripslashes($row->comment);
    	        if (strlen($row->comment) > $max_length_word) {
        	        $row->comment = substr($row->comment, 0, $max_length_word);
            	    $row->comment .= "...";
            	}
			}

            if ($row->name == '') $row->name = 'Anonymous';

			$content = $componentObj->linkToContent($row->contentid, $row->id, true, true);
?>           
     <tr valign="top" class="row<?php echo $k; ?>">
     	<td width='2%'>
     		<input type='checkbox'  <?php echo $checkread ? "DISABLED" : ""; ?> id='cb<?php echo $i; ?>' name='cid[]' value='<?php echo $row->id; ?>' onclick='isChecked(this.checked);' />
     	</td>
        <td valign="top" align='center'>
        	<?php echo $row->id; ?>
        </td>
        <td align='center'>
<?php		if (!$onlydisplay) { 
?>
			<a href="index.php?option=<?php echo $option; ?>&task=edit&id=<?php echo $row->id ?>">
        		<?php echo $row->name; ?>
        	</a>
<?php
			} else 
				echo $row->name;
?>
        </td>
        <td align='center'>
        	<?php echo $row->userid; ?>
        </td>
        <td align='center'>
        	<a href='mailto:<?php echo $row->userid ? $row->usermail : $row->email; ?>'>
<?php
            if ($row->notify) {
                $notifyimg = "mailgreen.jpg";
                $notifytxt = "notify if new post";
                $notifyalt = "yes";
            } else {
                $notifyimg = "mailred.jpg";
                $notifytxt = "not notify if new post";
                $notifyalt = "no";
            } 
?>
				<img border=0 src="./../components/com_comment/joscomment/images/<?php echo $notifyimg; ?>" title="<?php echo $notifytxt; ?>" alt="<?php echo $notifyalt; ?>">
			</a>
		</td>                
        <td align='center' width="5%">
        	<?php $row->website = isset($row->website) ? $row->website : ''; ?>
        	<?php echo $row->website ? "<a href='$row->website'>url</a>" : ""; ?>
        </td>
        <td align='center'>
        	<?php $row->date = isset($row->date) ? JOSC_utils::getLocalDate($row->date) : ''; ?>
        	<?php echo $row->date; ?>
        </td>
        <td align='left' width="10%">
        	<?php $row->title = isset($row->title) ? $row->title : ''; ?>
        	<b><?php echo $row->title; ?></b>
        	<br /><?php echo $row->comment; ?>
        </td>
        <td width='15%' align='center'>
        	<?php $row->ctitle = isset($row->ctitle) ? $row->ctitle : ''; ?>
        	<a href="<?php echo $content; ?>" target="_blank"><?php echo $row->ctitle; ?></a>
        </td>
<?php
			$row->published = isset($row->published) ? $row->published : 0;
            $published = $row->published ? 'unpublish' : 'publish';
            $delete = 'remove';
            $img = $row->published ? 'publish_g.png' : 'publish_r.png';
            $confirm_notify = "document.adminForm.confirm_notify.value='1';";//confirm('"._JOOMLACOMMENT_ADMIN_CONFIRM_NOTIFY."')?'1':'0';";
?>
        <td width="10%" align="center">
<?php		if (!$onlydisplay) { 
?>               
        	<a href="javascript:return void(0);" onclick="<?php echo $confirm_notify; ?>return listItemTask('cb<?php echo $i; ?>','<?php echo $published; ?>')">
<?php
			}
?>        	
        		<img src="images/<?php echo $img; ?>" width="12" height="12" border="0" alt="" />
<?php		if (!$onlydisplay) {
?>               
        	</a>
<?php
			}
?>
        </td>
        <td width="10%" align="center">
<?php		if (!$onlydisplay) {
?>               
        	<a href="javascript:return void(0);" onclick="<?php echo $confirm_notify; ?>return listItemTask('cb<?php echo $i; ?>','<?php echo $delete; ?>')">
<?php
			}
?>        	
        		<img src="components/com_comment/admin_images/delete.png" width="12" height="12" border="0" alt="" />
<?php		if (!$onlydisplay) { 
?>               
        	</a>
<?php
			}
?>
        </td>
        <td align='center'>
        	<?php $row->ip = isset($row->ip) ? $row->ip : ''; ?>
        	<?php echo $row->ip; ?>
        </td>
        <td align='center'>
        	<?php $row->voting_yes = isset($row->voting_yes) ? $row->voting_yes : 0; ?>
        	<?php echo $row->voting_yes; ?>
        </td>
        <td align='center'>
        	<?php $row->voting_no = isset($row->voting_no) ? $row->voting_no : 0; ?>
        	<?php echo $row->voting_no; ?>
        </td>
        <td align='center'>
        	<?php $row->parentid = isset($row->parentid) ? $row->parentid : -1; ?>
        	<?php echo $row->parentid; ?>
        </td>
        <td align='left'>
        	<?php echo $row->importedtable; ?>
        </td>
     </tr>
<?php 
			$k = 1 - $k;
	}

?>

   <tr>
      <th align="center" colspan="<?php echo $colspan; ?>">
        <?php echo $pageNav->getListFooter(); ?>
      </th>
    </tr>
  </table>
<?php
	}
?>
  <input type="hidden" name="boxchecked" value="0" />
<?php 	if ($adminform) { ?>
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="<?php echo $task; ?>" />
  <input type="hidden" name="confirm_notify" value="" />
  </form>
<?php 	} ?>

<?php
    }

    function editComment($option, &$row, &$lists)
    {
//        mosMakeHtmlSafe($row, ENT_QUOTES, 'comment');
	JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'comment' );

?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
      var form = document.adminForm;
      if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
      }

      if (form.comment.value == ""){
        alert( "You must at least write the comment text." );
      } else if (form.contentid.value == "0"){
        alert( "You must select a corresponding content item." );
      } else {
        submitform( pressbutton );
      }
    }
    </script>
    <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%"><span class="sectionname"><?php echo $row->id ? 'Edit' : 'Add'; ?> Comment</span></td>
    </tr>
  </table>
  <form action="index.php" method="post" name="adminForm" id="adminForm">
    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
    
      <tr>
        <td width="20%" align="right">Component:</td>
        <td width="80%">
          <?php echo $lists['component']; ?>
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Content Item:</td>
        <td>
          <?php echo $lists['content']; ?>
        </td>
      </tr>
      <tr>
        <td width="20%" align="right">Name:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="name" size="50" maxlength="30" value="<?php echo $row->name; ?>" />
        </td>
      </tr>
      <tr>
        <td width="20%" align="right">Userid:</td>
        <td width="80%"><?php echo $lists['userid']; ?> </td>
      </tr>
      <tr>
        <td width="20%" align="right">Email:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="email" size="50" maxlength="50" value="<?php echo $row->email;

        ?>" />
        </td>
      </tr>

      <tr>
        <td width="20%" align="right">Website:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="website" size="50"  maxlength="100" value="<?php echo $row->website;

        ?>" />
        </td>
      </tr>

      <tr>
        <td width="20%" align="right">Notify:</td>
        <td width="80%">
          <?php echo JHTML::_('select.booleanlist', 'notify', 'class="inputbox"', $row->notify); ?>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Title:</td>
        <td>
          <input class="inputbox" type="text" name="title" value="<?php echo $row->title;

        ?>" size="50" maxlength="50" />
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Comment:</td>
        <td>
          <textarea class="inputbox" cols="50" rows="5" name="comment"><?php echo $row->comment;

        ?></textarea>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Published:</td>
        <td>
          <?php echo $lists['published'];

        ?>
        </td>
      </tr>

    </table>

    <input type="hidden" name="id" value="<?php echo $row->id;

        ?>" />
    <input type="hidden" name="option" value="<?php echo $option;

        ?>" />
    <input type="hidden" name="task" value="" />
    </form>
<?php
    }

    function viewSettings($option, &$rows, &$lists, &$search, &$pageNav, $task='')
    {
        global $mainframe;
		$onlydisplay = (isset($lists['noedit']) && $lists['noedit']) ? true  : false;
		$adminform	 = (isset($lists['noform']) && $lists['noform']) ? false : true;
		$checkread	 = (isset($lists['checkread']) && $lists['checkread']) ? true : false;
		$expertmode  = (isset($lists['expert']) && $lists['expert']) ? true : false ; //(strpos($task, 'expert')===false) ? false : true;
?>
<?php 	if ($adminform) { ?><form action="index.php" method="post" name="adminForm"><?php 	} ?>

    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
    	<tr>
      		<th width="100%" align="left"><?php echo $lists['title'] ?></th>
      		<td nowrap="nowrap">Display #</td>
      		<td><?php echo $pageNav->getLimitBox(); ?>
      		</td>
      		<td>Search:</td>
      		<td>
        		<input type="text" name="search" value="<?php echo $search; ?>" class="inputbox" onChange="document.adminForm.submit();" />
      		</td>
    	</tr>
    </table>
    
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
        <th width="2%" class="title"><input type="checkbox" name="toggle" <?php echo $checkread ? "DISABLED" : ""; ?> value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
        <?php $colspan = 5; ?>
        <th class="title" width="10%" >Component</th>
        <th class="title"><?php if ($expertmode) { ?>Section<?php } ?></th>
        <th class="title">Name</th>
        <th class="title"><div align="center">Id</div></th>
      </tr>
<?php
        $k = 0;
        for ($i = 0, $n = count($rows); $i < $n; $i++) {
            $row = &$rows[$i];
?>           
     <tr class="row<?php echo $k; ?>">
     	<td width='5%'>
<?php
		if ($row->id!=1)
		{
?>			    		
     		<input type='checkbox'  <?php echo $checkread ? "DISABLED" : ""; ?> id='cb<?php echo $i; ?>' name='cid[]' value='<?php echo $row->id; ?>' onclick='isChecked(this.checked);' />
<?php
        }

?>     		
     	</td>
        <td>
<?php		if (!$onlydisplay) { 
?>
			<a href="index.php?option=<?php echo $option; ?>&task=settingsedit<?php echo $expertmode ? "expert" : ""; ?>&id=<?php echo $row->id ?>">
	        	<b><?php echo JOSC_utils::getComponentName($row->set_component); ?></b>
        	</a>
<?php
			} else {
?>			
	        	<b><?php echo JOSC_utils::getComponentName($row->set_component); ?></b>
<?php
			}
?>	        	
        </td>
        <td>
<?php 
		if ($expertmode) {
			$null=null;
			$componentObj = JOSC_utils::ComPluginObject($row->set_component,$null);
//			if (function_exists($componentObj->getExpertSectionTitle))
				echo $componentObj->getExpertSectionTitle($row->set_sectionid);
			unset($componentObj);
		}
?>
        </td>
        <td>
<?php		if (!$onlydisplay) { 
?>
			<a href="index.php?option=<?php echo $option; ?>&task=settingsedit<?php echo $expertmode ? "expert" : ""; ?>&id=<?php echo $row->id ?>">
        		<?php echo $row->set_name; ?>
        	</a>
<?php
			} else
				echo $row->set_name;
?>
        </td>
        <td align='center'>
<?php		if (!$onlydisplay) { 
?>
			<a href="index.php?option=<?php echo $option; ?>&task=settingsedit<?php echo $expertmode ? "expert" : ""; ?>&id=<?php echo $row->id ?>">
        	<?php echo $row->id; ?>
        	</a>
<?php
			} else
				echo $row->id;
?>
        </td>
     </tr>
<?php 
			$k = 1 - $k;
        }
?>
    <tr>
      <th align="center" colspan="<?php echo $colspan; ?>">
        <?php echo $pageNav->writePagesLinks(); ?>
      </th>
    </tr>
    <tr>
      <td align="center" colspan="<?php echo $colspan; ?>">
        <?php echo $pageNav->writePagesCounter(); ?>
      </td>
    </tr>
  </table>
  <input type="hidden" name="boxchecked" value="0" />
<?php 	if ($adminform) { ?>
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="<?php echo $task; ?>" />
  </form>
<?php 	} ?>

<?php
    }

	function importPanel($option, $task, $fromtable='', $fromcomponent='', $component) 
	{
	        $database =& JFactory::getDBO();
	
	    
?>
    	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
    		<tr>
      			<th width="100%" align="left">Import Comments from one another Comment System to !JoomlaComment.</th>
      		</tr>
      	</table>        
<?php
	    echo "<form action='index.php' method='POST' name='adminForm'>";
	    jimport('joomla.html.pane');
	    $tabs = & JPane::getInstance();
        echo $tabs->startPane("jos_importpanel");
        
        echo $tabs->startPanel("Mapping", "mapping");
        importMapping($option, $task, $fromtable, $fromcomponent, $component);
        echo $tabs->endPanel();
	
        echo $tabs->startPanel("Preview", "preview");
        previewImportComments($option, $task, $fromtable, $component);
        echo $tabs->endPanel();
                
        echo $tabs->endPane();
        
        echo JOSC_library::hidden('task',$task);  /* set value for display and search in preview */
        echo JOSC_library::hidden('option', $option);
        
        echo "</form>";        
	}
	
	function importMapping( &$lists ) 
	{				           		
        $rows = new JOSC_tabRows();

        //echo JOSC_library::hidden('component', '1'); /* to initialise and TODO: allows to change */
		$rows->createRow('title'	, 'Import Comments Mapping');
		$rows->createRow('parameter', 
			'Import from standard comment system component', $lists['fromcomponent'], 
			'<b>If your component is available in the list, select its value will propose automatically below the table and columns according to this component</b>.'
			. ' There should be missing columns dependent of the release of the components, verify the mapping and if needed, you can change it manually !'
			. ' It is possible that some columns have no equivalent in the source system. In this case leave them empty. For example mXcomment is using rating, not voting yes, and voting no. We affect "voting_yes" joomlacomment to "rating" and we leave empty "voting_no".'
			. '<br /><b>If your component is not in the list, you have to do the mapping manually</b>, selecting first the table, then the columns (see below).'
			. '<br />If you need help, do not hesitate to contact the joomlacomment support !'
			);
		$rows->createRow('separator');
		$rows->createRow('parameter', 
		'Import from table <b style="color:red;">(obligatory)</b>', $lists['fromtablelist'], 'Select the <b>database table which contains the comments to import</b>' );
		$rows->createRow('parameter', 
		'Commented component value <b style="color:red;">(obligatory)</b>', $lists['componentlist'], 'Select the <b>Component name</b> from and to which you want to import comments.' );
		$rows->createRow('parameter', 
		'Commented component column', JHTML::_('select.genericlist', $lists['columns'], 'componentfield', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['componentfield'] ), 'Select the column which contains the <b>Component name selection</b>.' );
		$rows->createRow('parameter', 
		'--- Id from the column <b style="color:red;">(obligatory)</b>', JHTML::_('select.genericlist', $lists['columns'], 'id', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['id'] ), 'Select the column which contains the <b>Comment Id</b>.' );
		$rows->createRow('parameter', 
		'--- Content Id from the column <b style="color:red;">(obligatory)</b>', JHTML::_('select.genericlist', $lists['columns'], 'contentid', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['contentid'] ), 'Select the column which contains the <b>Content Item Id</b>' );
		$rows->createRow('parameter', 
		'--- Date from the column', JHTML::_('select.genericlist', $lists['columns'], 'date', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['date'] ), 'Select the column which contains the <b>Date of the comment</b>' );
		$rows->createRow('parameter', 
		'--- Name from the column', JHTML::_('select.genericlist', $lists['columns'], 'name', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['name'] ), 'Select the column which contains the <b>Name</b> of the comment writer' );
		$rows->createRow('parameter', 
		'--- Userid from the column', JHTML::_('select.genericlist', $lists['columns'], 'userid', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['userid'] ), 'Select the column which contains the <b>Userid</b> of the comment writer' );
		$rows->createRow('parameter', 
		'--- IP from the column', JHTML::_('select.genericlist', $lists['columns'], 'ip', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['ip'] ), 'Select the column which contains the <b>IP</b> of the comment writer' );
		$rows->createRow('parameter', 
		'--- Email from the column', JHTML::_('select.genericlist', $lists['columns'], 'email', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['email'] ), 'Select the column which contains the <b>Email</b> of the comment writer' );
		$rows->createRow('parameter', 
		'--- Notify parameter from the column', JHTML::_('select.genericlist', $lists['columns'], 'notify', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['notify'] ), 'Select the column which contains the <b>Notify parameter</b> of the comment writer (notify if new post paramter)' );
		$rows->createRow('parameter', 
		'--- Website from the column', JHTML::_('select.genericlist', $lists['columns'], 'website', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['website'] ), 'Select the column which contains the <b>Website</b> of the comment writer' );
		$rows->createRow('parameter', 
		'--- Title from the column', JHTML::_('select.genericlist', $lists['columns'], 'title', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['title'] ), 'Select the column which contains the <b>Title</b> of the comment' );
		$rows->createRow('parameter', 
		'--- Text from the column', JHTML::_('select.genericlist', $lists['columns'], 'comment', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['comment'] ), 'Select the column which contains the <b>Text</b> of the comment' );
		$rows->createRow('parameter', 
		'--- Voting Yes count from the column', JHTML::_('select.genericlist', $lists['columns'], 'voting_yes', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['voting_yes'] ), 'Select the column which contains the <b>Voting Yes count</b> of the comment' );
        $rows->createRow('parameter', 
		'--- Voting No count from the column', JHTML::_('select.genericlist', $lists['columns'], 'voting_no', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['voting_no'] ), 'Select the column which contains the <b>Voting No count</b> of the comment' );
        $rows->createRow('parameter', 
		'--- Published from the column', JHTML::_('select.genericlist', $lists['columns'], 'published', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['published'] ), 'Select the column which contains the <b>Published parameter</b> of the comment' );
        $rows->createRow('parameter', 
		'--- Parent id from the column', JHTML::_('select.genericlist', $lists['columns'], 'parentid', ' class="inputbox" ', 'Field', 'desc', $lists['sel_columns']['parentid'] ), 'Select the column which contains the <b>Parent Id</b> of the comment (when comment is linked as a child -- response -- of another comment)' );
		$rows->createRow('separator');
		$rows->createRow('parameter', 
		'Save sql queries after import execution ?', $lists['savequeries'], 'will save the sql queries in a file (directory: media).' );
    
        echo $rows->tabRows_htmlCode();
        
    }

    
}
?>