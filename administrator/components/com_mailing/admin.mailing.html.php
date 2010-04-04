<?php
/*
* @name ULTIMATE MAILING LISTS
* Created By Chris Michaelides <info@axxis.gr>
* http://www.axxis.gr
* @copyright AXXIS Internet Solutions Copyright (C) 2009  www.Axxis.gr / All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die(dirname(__FILE__).DS.'Restricted access');



class HTML_mailing{



	function sendMessage($option,$switch,$cid,$subject,$message)

	{

	 global $defaultgroup;

    $grUser =& JFactory::getUser(); 		
		$db	=& JFactory::getDBO();
		$query = "SELECT * FROM #__mailing_conf WHERE id=1";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$server = $rows[0]->server;
		$smtp_user = $rows[0]->smtp_user;
		$password = $rows[0]->password;
		$limit = $rows[0]->limit;
		$query = "SELECT * FROM #__users WHERE id=".$grUser->id;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$name = $rows[0]->name;
		$email = $rows[0]->email;
		$address = "";
	  if ($switch=="email") {
       foreach ($cid as $id) {
         $query = "SELECT * FROM #__mailing_emails WHERE id=".$id." ORDER BY email";
  	     $db->setQuery($query);
  	     $rows = $db->loadObjectlist();
  	     foreach ($rows as $row) { $address.=$row->email."; "; }
  	   }
    }
	  if ($switch=="group") {
       foreach ($cid as $id) {
         $query = "SELECT * FROM #__mailing_groups WHERE id=".$id." ORDER BY name";
         $db->setQuery($query);
  	     $rows = $db->loadObjectlist();
  	     foreach ($rows as $row) { $address.=$row->name."; "; }
  	   }
    }
	  if ($switch=="") {
  		$query = "SELECT * FROM #__mailing_groups WHERE id=".$defaultgroup;
      $db->setQuery( $query );
  		$rows = $db->loadObjectList();
  		$groupname = $rows[0]->name;
      $query = "SELECT * FROM #__mailing_emails WHERE groupid=".$defaultgroup." ORDER BY email";
      $db->setQuery($query);
      $rows = $db->loadObjectlist();
      foreach ($rows as $row) { $address.=$row->email."; "; }
  	}
  	if ($switch=="")
		  HTML_mailing::setMessageToolbar($switch,$groupname);
		else
		  HTML_mailing::setMessageToolbar($switch,$address);
		?>
		<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm">
              <table align="center" cellpadding="2" cellspacing="0" width="100%">
                <tr>
                  <td width="104"><font class="mailformtext">Recipient(s):</font></td>
                  
                  <?php if ($switch=="new") { ?>
                  <td width="100%"><input class=mailformfield type="text" name="address" id="address" value="" size="60" /> OR select from the list...</td><td rowspan="5" valign="top">

  Contact Lists<br />
  <SELECT NAME="addressgroup[]" MULTIPLE SIZE=30><?php
  		$query = "SELECT * FROM #__mailing_groups ORDER BY name";
      $db->setQuery( $query );
  		$rows = $db->loadObjectList();
  		foreach ($rows as $row) { ?>
    <OPTGROUP LABEL="<?php echo $row->name; ?>" ><?php 
  		$query = "SELECT * FROM #__mailing_emails WHERE groupid=".$row->id." ORDER BY email";
      $db->setQuery( $query );
  		$emailrows = $db->loadObjectList();
  		foreach ($emailrows as $emailrow) { ?>
      <OPTION LABEL="<?php echo $emailrow->email; ?>" value="<?php echo $emailrow->id; ?>" ><?php echo $emailrow->email; ?></OPTION>
      <?php }
      } ?>
  </SELECT><br /><br />
                  </td>
                  <?php } else { ?>
                  <td colspan="2"><?php echo $address; ?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <td width="104"><font class="mailformtext">Name:</font></td>
                  <td width="280"><input name="name" type="text" class="mailformfield" id="name" value="<?php echo $name; ?>" size="60" /></td>
                </tr>
                <tr>
                  <td><font class="mailformtext">Email address:</font></td>
                  <td><input class=mailformfield type="text" name="email" id="email" value="<?php echo $email; ?>" size="60" /></td>
                </tr>
                <tr>
                  <td width="104"><font class="mailformtext">Subject:</font></td>
                  <td width="280"><input name="subject" type="text" class="mailformfield" id="subject" value="<?php echo $subject; ?>" size="80" /></td>
                </tr>
                <tr>
                  <td valign="top"><font class="mailformtext">Message:</font></td><td>
                  <textarea name="message" style="width:100%;height:400px;"><?=$message;?></textarea>
                  </td></tr><tr><td></td>
                </tr>
              </table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="sendit" />
    <input type="hidden" name="groupid" value="<?php echo $defaultgroup; ?>" />
    <input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="id" value="1" />
    <input type="hidden" name="emailorgroup" value="<?php echo $switch; ?>" />
    </form>
<?php
  }

	function details( $option,$subject,$message,$attachment,$recipient )
	{
		  HTML_mailing::setDetailsToolbar();
		?>
              <table align="center" cellpadding="2" cellspacing="0">
                <tr>
                  <td width="104"><font class="mailformtext">Recipient(s):</font></td>
                  <td><?php echo $recipient; ?></td>
                </tr>
                <tr>
                  <td width="104"><font class="mailformtext">Subject:</font></td>
                  <td width="280"><?php echo $subject; ?></td>
                </tr>
                <tr>
                  <td valign="top"><font class="mailformtext">Message:</font></td>
                  <td><textarea class=mailformfield name="message" cols="100" rows="30" id="message" READONLY><?php echo $message; ?></textarea></td>
                </tr>
                <tr>
                  <td><font class="mailformtext">Attachment:</font></td>
                  <td><?php echo $attachment; ?></td>
                </tr>
              </table>
    </form>
<?php	
	}
  
  function editgroup($option, &$row)
	{
		HTML_mailing::setEditGroupToolbar($row->id);
		
		?>
		<form action="index.php" method="post" name="adminForm">
		
		<div class="col100">
			<fieldset class="adminform">
			<table class="admintable">
				<tbody>
					<tr>
						<td width="20%" class="key">
							<label for="name">Contact List</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="name" id="name" size="40" value="<?php echo $row->name; ?>" />
						</td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="description">Description</label>
						</td>
						<td><?php
                $editor =& JFactory::getEditor();
                echo $editor->display('description', $row->description, '550', '400', '60', '20', false); ?>
						</td>
					</tr>
				</tbody>
			</table>
			</fieldset>
		</div>
		
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id ?>" />
		</form>
		<?php
	}

	function editemail($option, &$row)
	{
	 global $defaultgroup;
		HTML_mailing::setEditEmailToolbar($row->id);
		
		$db	=& JFactory::getDBO();
		$query = "SELECT * FROM #__mailing_groups WHERE id=".$defaultgroup;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$groupname = $rows[0]->name;
		
		?>
		<form action="index.php" method="post" name="adminForm">
		
		<div class="col100">
			<fieldset class="adminform">
			<table class="admintable">
				<tbody>
        <tr><td align="right">Contact List</td><td><strong><?php echo $groupname; ?></strong></td></tr>
					<tr>
						<td width="20%" class="key">
							<label for="email">Email Address</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="email" id="email" size="40" value="<?php echo $row->email; ?>" />
						</td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="name">Name</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="name" id="name" size="40" value="<?php echo $row->name; ?>" />
						</td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="email">Notes</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="notes" id="notes" size="80" value="<?php echo $row->notes; ?>" />
						</td>
					</tr>
				</tbody>
			</table>
			</fieldset>
		</div>
		
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id ?>" />
		<input type="hidden" name="groupid" value="<?php echo $defaultgroup;?>" />
		</form>
		<?php
	}

	function showbatch ($option)
	{
	 global $defaultgroup;
		HTML_mailing::setBatchToolbar();
		
		$db	=& JFactory::getDBO();
		$query = "SELECT * FROM #__mailing_groups WHERE id=".$defaultgroup;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$groupname = $rows[0]->name;
		
		?>
		<form action="index.php" method="post" name="adminForm">
		
		<div class="col100">
			<fieldset class="adminform">
			<table class="admintable">
				<tbody>
        <tr><td align="right">Contact List</td><td><strong><?php echo $groupname; ?></strong></td></tr>
					<tr>
						<td width="20%" class="key">
							<label for="filter">Filter (include)</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="filter" id="filter" size="40" value="" />
						</td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="excludefilter">Filter (exclude)</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="excludefilter" id="excludefilter" size="40" value="" />
						</td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="emails">Email Addresses<br/>( separate with ; )</label>
						</td>
            <td><textarea name="emails" cols="100" rows="25" id="emails"></textarea></td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="name">Name</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="name" id="name" size="40" value="" />
						</td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="email">Notes</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="notes" id="notes" size="80" value="" />
						</td>
					</tr>
				</tbody>
			</table>
			</fieldset>
		</div>
		
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="groupid" value="<?php echo $defaultgroup;?>" />
		</form>
		<?php
	}

  function listGroups( $option, &$rows ) {
    global $defaultgroup,$mainframe;$grsite  = $mainframe->getCfg('live_site');

		HTML_mailing::setGroupsToolbar();
  ?>
  <form action="index.php" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
  <tr>
  <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
  <th width="20">ID</th>
  <th width="20">-></th>
  <th width="270">Name</th>
  <th width="30">Contacts</th>
  <th>Description</th>
  <th width="30">Published</th>
  </tr>
  
  <?php
  	$k = 0;
  	for($i=0; $i < count( $rows ); $i++) {
  	$row = &$rows[$i];
		$published = JHTML::_('grid.published', $row, $i );
  	$db	=& JFactory::getDBO();
  	$query = "SELECT * FROM #__mailing_emails WHERE groupid=".$row->id;
    $db->setQuery( $query );
    $emails = $db->loadObjectlist();
     ?>
  	<tr class="<?php echo "row$k"; ?>">
  	<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
  	<td><?php echo $row->id; ?></td>
  	<td><?php if ($row->id==$defaultgroup) echo "<img src=\"".$grsite."/images/apply_f2.png\" height=15 >"; ?></td>
  	<td><a href="<?=JURI::base();?>index.php?option=com_mailing&task=assign&cid=<?=$row->id;?>"><?php echo $row->name; ?></a></td>
  	<td align="right"><?php echo count($emails); ?></td>
  	<td><?php echo $row->description; ?></td>
		<td align="center"><?php echo $published; ?></td>
  	<?php $k = 1 - $k; ?>
  	</tr>
  <?php } ?>
  <tfoot>
    <td colspan="8">Ultimate Mailing Lists</td>
  </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  </form> 
  <? } 
  
  function listEmails( $option, &$rows, &$pageNav ) {
  		HTML_mailing::setEmailsToolbar();
  ?>
  <form action="index.php" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
  <tr>
  <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
  <th width="200">Email Address</th>
  <th width="200">Name</th>
  <th>Notes</th>
  <th>Subscription</th>
  </tr>
  
  <?php
  	$k = 0;
  	for($i=0; $i < count( $rows ); $i++) {
  	$row = $rows[$i];
    switch ($row->subscription) {
      case -1:
        $subscribed = "<span style=\"color:red;\">unsubscribed</span>";
      break;
      case 0:
        $subscribed = "<span style=\"color:orange;\">not yet</span>";
      break;
      case 1:
        $subscribed = "<span style=\"color:green;\">subscribed</span>";
      break;
    }
   ?>
	<tr class="<?php echo "row$k"; ?>">
	<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
	<td><a href="<?=JURI::base();?>index.php?option=com_mailing&task=sendtocontact&cid=<?=$row->id;?>"><?php echo $row->email; ?></a></td>
	<td><?php echo $row->name; ?></td>
	<td><?php echo $row->notes; ?></td>
	<td><?php echo $subscribed; ?></td>
	<?php $k = 1 - $k; ?>
	</tr>
  <?php } ?>
  <tfoot>
    <td colspan="8"><?php echo $pageNav->getListFooter(); ?></td>
  </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  </form> 
  <? } 

  function listEmailLog( $option, &$rows, $act ) {
  		HTML_mailing::setEmailLogToolbar();
  ?>
  <form action="index.php" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
  <tr>
  <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
  <th width="100">Date</th>
  <th width="250">Recipient(s)</th>
  <th >Title</th>
  </tr>
  
  <?php
  	$k = 0;
  	for($i=0; $i < count( $rows ); $i++) {
  	$row = $rows[$i];
     ?>
  	<tr class="<?php echo "row$k"; ?>">
  	<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
  	<td><?php echo $row->date; ?></td><?php
    $recps = "";
		$db	=& JFactory::getDBO();
		if ($act=='success') $dbs = 1; else $dbs = 0;
		$query = "SELECT recipient FROM #__mailing_recipients WHERE email_id=".$row->id." AND success=$dbs";
    $db->setQuery( $query );
		$recipients = $db->loadObjectList();
		foreach ($recipients as $recipient) {
		  $recps .= $recipient->recipient."; ";
		}
		$recps = substr($recps,0,-2);
  	?>
  	<td><?php echo $recps; ?></td>
  	<td><a href="<?=JURI::base();?>index.php?option=com_mailing&task=details&cid=<?=$row->id;?>"><?php echo $row->title; ?></a></td>
  	<?php $k = 1 - $k; ?>
  	</tr>
  <?php } ?>
  <tfoot>
    <td colspan="4">Ultimate Mailing Lists</td>
  </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  </form> 
  <? } 
  
  function maintenance( $option ) {
  		HTML_mailing::setMaintenanceToolbar();
    	$db	=& JFactory::getDBO();
  	  $query = "SELECT sync FROM #__mailing_conf WHERE id=1";
      $db->setQuery( $query );
      $row = $db->loadResult();
  ?>
  <form action="index.php" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
  <tr><th>Synchronisation</th><th>Retrieval</th></tr>
  
  <tr>
    <td width="40%">Last synchronisation with registered users: <?php echo $row; ?><br /><br />
    <input type="button" name="syncbutton" value="Add new users since then" onclick="document.getElementById('div').innerHTML='OK! Please press the [Perform Maintenance] button now...';document.getElementById('synchronize').value='true';" />
    <br /><br /><div id="div"><br /></div></td>
  	<td width="60%">Retrieve from table: <input type="text" name="table" />  email field: <input type="text" name="field" />   name field: <input type="text" name="name" /> notes field: <input type="text" name="notes" />
    <br /><br />  field: <input type="text" name="distinct" /> as contact lists</td>
  </tr>
  <tfoot>
    <td colspan="2">Ultimate Mailing Lists</td>
  </tfoot>
  </table>
  <input type="hidden" name="synchronize" id="synchronize" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="lastdate" value="<?php echo $row; ?>" />
  <input type="hidden" name="task" value="do_maintenance" />
  <input type="hidden" name="boxchecked" value="1" />
  </form> 
  <? } 
  
	function setBatchToolbar()
	{
		JToolBarHelper::title('Add multiple email addresses', 'generic.png');
		JToolBarHelper::save('savebatch');
		JToolBarHelper::cancel();
	}
	
	function setEditGroupToolbar($id)
	{
		if ($id) {
			$newEdit = 'Edit';
		} else {
			$newEdit = 'New';
		}
		
		JToolBarHelper::title($newEdit . ' Contact List', 'generic.png');
		JToolBarHelper::save('savegroup');
		JToolBarHelper::back();
	}
	
	function setEditEmailToolbar($id)
	{
		if ($id) {
			$newEdit = 'Edit';
		} else {
			$newEdit = 'New';
		}
		
		JToolBarHelper::title($newEdit . ' Email Address', 'generic.png');
		JToolBarHelper::save('saveemail');
		JToolBarHelper::cancel();
	}
	
	function setMessageToolbar($switch,$address)
	{
    global $mainframe;$grsite  = $mainframe->getCfg('live_site');
    $document = & JFactory::getDocument();
    $document->addStyleSheet('components/com_mailing/css/mailing.css');
		if ($switch=="") JToolBarHelper::title('Send Message to '.$address, 'generic.png');
		else if ($switch=="email" || $switch=="new") {
            JToolBarHelper::title('Send Message to contact(s)', 'generic.png');
         }
		     else {
            JToolBarHelper::title('Send Message to contact list(s)', 'generic.png');
         }
		JToolBarHelper::custom('sendit', 'apply_f2.png', 'apply_f2.png', "Send It!");
		JToolBarHelper::back();
	}

	function setGroupsToolbar()
	{
	 global $defaultgroup;
	 	$db	=& JFactory::getDBO();
   
		$query = "SELECT * FROM #__mailing_groups WHERE id=".$defaultgroup;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$groupname = $rows[0]->name;
		
    $document = & JFactory::getDocument();
    $document->addStyleSheet('components/com_mailing/css/mailing.css');
		JToolBarHelper::custom('addusers', 'graddusers.png', 'graddusers.png', "Add Registered Users");
		JToolBarHelper::title('Contact List Manager | <small>Current Assigned Contact List: '.$groupname.'</small>', 'generic.png');
		JToolBarHelper::addNew('addgroup');
		JToolBarHelper::editList('editgroup');
		JToolBarHelper::assign();
		JToolBarHelper::custom('sendtogroup', 'write.png', 'write.png', "Send to selected");
		JToolBarHelper::custom('sendnew', 'grnew.png', 'grnew.png', "Compose new", false, false);
		JToolBarHelper::deleteList('Are you sure? All email addresses in this Contact List will be lost!', 'deletegroup');
	}

	function setEmailsToolbar()
	{
	 global $defaultgroup;
	 	$db	=& JFactory::getDBO();
   
		$query = "SELECT * FROM #__mailing_groups WHERE id=".$defaultgroup;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$groupname = $rows[0]->name;
		
    $document = & JFactory::getDocument();
    $document->addStyleSheet('components/com_mailing/css/mailing.css');
    if (count($rows)) JToolBarHelper::title($groupname, 'generic.png');
    else JToolBarHelper::title("Mailing List Component", 'generic.png');
		JToolBarHelper::addNew('addemail');
		JToolBarHelper::editList('editemail');
		JToolBarHelper::custom('batch', 'batch.png', 'batch.png', "Add multiple", false, false);
		JToolBarHelper::custom('sendtocontact', 'write.png', 'write.png', "Send to selected");
		JToolBarHelper::custom('sendnew', 'grnew.png', 'grnew.png', "Compose new", false, false);
		JToolBarHelper::deleteList('Are you sure?', 'deleteemail');
	}

	function setEmailLogToolbar()
	{
    $document = & JFactory::getDocument();
    $document->addStyleSheet('components/com_mailing/css/mailing.css');
    JToolBarHelper::title("Email Log", 'generic.png');
		JToolBarHelper::custom('details', 'grdetails.png', 'grdetails.png', "Details");
		JToolBarHelper::deleteList('Are you sure?', 'deletelog');
	}

	function setMaintenanceToolbar()
	{
    $document = & JFactory::getDocument();
    $document->addStyleSheet('components/com_mailing/css/mailing.css');
    JToolBarHelper::title("Maintenance", 'generic.png');
		JToolBarHelper::custom('do_maintenance', 'apply_f2.png', 'apply_f2.png', "Perform Maintenance!");
	}

	function setDetailsToolbar()
	{
		JToolBarHelper::back();
	}
}
?>