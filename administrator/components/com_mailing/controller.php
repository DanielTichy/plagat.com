<?php
/*
* @name ULTIMATE MAILING LISTS
* Created By Chris Michaelides <info@axxis.gr>
* http://www.axxis.gr
* @copyright AXXIS Internet Solutions Copyright (C) 2008  www.Axxis.gr / All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class mailinglistController extends JController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask('addgroup', 'editgroup');
		$this->registerTask('addemail', 'editemail');
		$this->registerTask('unpublish', 'publish');
	}
	
    function check_email_address($email) {
      if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
             return "Invalid email address: ".$email;
      }
      $email_array = explode("@", $email);
      $local_array = explode(".", $email_array[0]);
      for ($i = 0; $i < sizeof($local_array); $i++) {
         if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
             return "Invalid email address: ".$email;
        }
      } 
      if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
             return "Invalid email address: ".$email;
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
          if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
             return "Invalid email address: ".$email;
          }
        }
      }
      return $email;
    }

	function sendit()
	{
	 global $defaultgroup,$mainframe;$grsite  = $mainframe->getCfg('live_site');
		$db	=& JFactory::getDBO();
		$option = JRequest::getCmd('option');
		$this->setRedirect('index.php?option=' . $option . '&act=success');
		
		$post = JRequest::get('post');

    if (empty($post[name])) {
      $this->setMessage('You did not enter your name. Message not sent!');
      return;
    }
    if (empty($post[email])) {
      $this->setMessage('You did not enter your email. Message not sent!');
      return;
    }
    if (empty($post[subject])) {
      $this->setMessage('You did not enter a subject. Message not sent!');
      return;
    }
    if (empty($post[message])) {
      $this->setMessage('You did not enter a message. Message not sent!');
      return;
    }
    
    $name = $post[name];
    $namelog = mysql_real_escape_string($name);
    $title = $post[subject];
    $titlelog = mysql_real_escape_string($title);
    $body = $post[message];
    $bodylog = mysql_real_escape_string($body);
    $email = $post[email]; 
    if (strstr($email, 'email address:')) {
 		  $this->setMessage('Your email address is not valid');
 		  return;
    }    
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/plain; charset=utf-8' . "\r\n";
    $headers .= 'From: '. $name . '<'.$email.'>' . "\r\n";
        
    $now = date("Y-m-d H:i:s");

    $query = "INSERT INTO `#__mailing_email_log` (
      `date`, `title`, `body`, `text`, `attachment`, `sendername`, `senderemail`, `file_path`, `file_name`, `file_type`) VALUES
      ('$now', '$titlelog', '$bodylog', '$bodylog', '', '$namelog', '$email', '', '', '')";
		$db->setQuery($query);
		$db->query();
    $db->setQuery( "SELECT * FROM #__mailing_email_log WHERE id = LAST_INSERT_ID()" );
		$emails = $db->loadObjectList();
    $email_id = $emails[0]->id;

    $switch = $post[emailorgroup];
    $overide = $post[overide];
    $address = $post[address];
  	$addressgroup = $post[addressgroup];

	  $setMessageSuccess = 'Email send to the following contacts: ';
	  //$setMessageSuccess.= '<form action="components/com_mailing/timer.php" target="gr_iframe" method="post" style="display:inline;">';
	  //$setMessageSuccess.= '<input type="submit" value="Send!" /></form>';
	  $setMessage = '';
	  $fail = "";
    if ($switch=="new") {
      if ($address!="") {
        if (strstr($address,";")) {
          $emails = explode(";", $address);
          foreach ($emails as $_email2) {
         	  $_email = ereg_replace(" ", "",$_email2);
            if (@mail($_email,$title,$body,$headers)) {   
              $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$_email',$email_id,1)";
              $db->setQuery( $query );$db->query();
        		  $setMessage .= $_email.'; ';
        		} else {
              $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$_email',$email_id,0)";
              $db->setQuery( $query );$db->query();
              $fail.= $_email."; ";
            }
          }
        } else {
            if (@mail($address,$title,$body,$headers)) {   
              $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$address',$email_id,1)";
              $db->setQuery( $query );$db->query();
        		  $setMessage .= $address.'; ';
        		} else {
              $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$address',$email_id,0)";
              $db->setQuery( $query );$db->query();
              $fail.= $address."; ";
            }
        }
      } else {
  	    foreach ($addressgroup as $address) {
  	      $proceed = TRUE;
          $db->setQuery("SELECT * FROM #__mailing_emails WHERE id=$address");
          $me = $db->loadObjectList();
          $eaddress = $me[0]->email;
          $subscription = $me[0]->subscription;
  	      if (!$overide && $subscription==-1) {
            $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$eaddress',$email_id,0)";
            $db->setQuery( $query );$db->query();
            $fail.= $eaddress."; ";
  	        $proceed = FALSE;
          }
          if ($proceed) { 
            if (@mail($eaddress,$title,$body,$headers)) {   
              $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$eaddress',$email_id,1)";
              $db->setQuery( $query );$db->query();
        		  $setMessage .= $eaddress.'; ';
        		} else {
              $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$eaddress',$email_id,0)";
              $db->setQuery( $query );$db->query();
              $fail.= $eaddress."; ";
            }
        	}
        }
      }
    } else {
  		if ($switch=="") {
        $query = "SELECT * FROM #__mailing_emails WHERE groupid=".$defaultgroup;
        $db->setQuery( $query );
    		$rows = $db->loadObjectList();
        $countrows = count($rows);
    		$i=0;
    		while ($i<$countrows) {
      		$row = $rows[$i];
          $subscription = $row->subscription;
      	  if (!$overide && $subscription==-1) {
            $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('".$row->email."',$email_id,0)";
            $db->setQuery( $query );$db->query();
            $fail.= $row->email."; ";
          } else {
            $_email = $this->check_email_address($row->email); 
            if (!strstr($_email, 'email address:')) {
              if (@mail($_email,$title,$body,$headers)) {   
                $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$_email',$email_id,1)";
                $db->setQuery( $query );$db->query();
          		  $setMessage .= $_email.'; ';
          		} else {
                $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$_email',$email_id,0)";
                $db->setQuery( $query );$db->query();
                $fail.= $_email."; ";
              }
            } else {
              $_email = substr($_email, 23);
              $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$_email',$email_id,0)";
              $db->setQuery( $query );$db->query();
              $fail.= $_email."; ";
            }
          }
          $i++;
        }
      } else {
        $db->setQuery("SELECT * FROM #__mailing_tmp");
      	$temprows = $db->loadObjectList();
      	foreach ($temprows as $temprow) {
          $query = "SELECT * FROM #__mailing_emails WHERE id=".$temprow->id;
          $db->setQuery( $query );
      		$rows = $db->loadObjectList();
          $countrows = count($rows);
      		$i=0;
      		while ($i<$countrows) {
        		$row = $rows[$i];
            $subscription = $row->subscription;
        	  if (!$overide && $subscription==-1) {
              $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('".$row->email."',$email_id,0)";
              $db->setQuery( $query );$db->query();
              $fail.= $row->email."; ";
            } else {
              $_email = $this->check_email_address($row->email); 
              if (!strstr($_email, 'email address:')) {
                if (@mail($_email,$title,$body,$headers)) {   
                  $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$_email',$email_id,1)";
                  $db->setQuery( $query );$db->query();
            		  $setMessage .= $_email.'; ';
            		} else {
                  $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$_email',$email_id,0)";
                  $db->setQuery( $query );$db->query();
                  $fail.= $_email."; ";
                }
              } else {
                $_email = substr($_email, 23);
                $query = "INSERT INTO `#__mailing_recipients` (`recipient`,`email_id`,`success`) VALUES ('$_email',$email_id,0)";
                $db->setQuery( $query );$db->query();
                $fail.= $_email."; ";
              }
            }
            $i++;
          }
        }
      }
    }
    
    $echo = "";

//    if (!strstr($setMessageSuccess,'@')) $setMessageSuccess = "";
    if ($fail) {
      $setMessage .= '<br/><br/>Wrong email addresses / Unsubscribed recipients: '.$fail;
    }
    $this->setMessage("<table style=\"text-align:center;padding-left:2cm\"><tr><td>".$setMessageSuccess.$setMessage.$method.$echo."</td></tr></table>");
	}
//	phew! big function! :D

	function forward()
	{
		$option = JRequest::getCmd('option');
		$switch = "new";
		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
    $id = $cid[0];
		$query = "SELECT * FROM #__mailing_email_log WHERE id=".$id;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
    $subject = $rows[0]->title;
    $message = $rows[0]->body;
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::sendMessage( $option,$switch,'',$subject,$message );
  }

	function details()
	{
		$option = JRequest::getCmd('option');
		$switch = "new";
		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
    $id = $cid[0];
		$query = "SELECT * FROM #__mailing_email_log WHERE id=".$id;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
    $subject = $rows[0]->title;
    $message = $rows[0]->body;
    $attachment = $rows[0]->attachment;
		$query = "SELECT * FROM #__mailing_recipients WHERE email_id=".$id." ORDER BY recipient";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		$recipient = "";
    foreach ($rows as $row) $recipient .= $row->recipient."; ";
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::details( $option,$subject,$message,$attachment,$recipient );
  }

	function sendnew()
	{
		$option = JRequest::getCmd('option');
		$switch = "new";
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::sendMessage( $option,$switch,'','','' );
  }

	function sendtogroup()
	{
		$option = JRequest::getCmd('option');
		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		$switch = "group";
		$db->setQuery("TRUNCATE TABLE #__mailing_tmp");
		if (!$db->query()) {
      echo "Error emptying temporary table. Please contact support.<br/>";
    }
    foreach ($cid as $id) {
      $query = "INSERT INTO `#__mailing_tmp` (`id`) VALUES ($id)";
  		$db->setQuery($query);
  		if (!$db->query()) {
        echo "Error populating temporary table. Please contact support.<br/>";
      }
    }
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::sendMessage( $option,$switch,$cid,'','' );
  }

	function sendtocontact()
	{
		$option = JRequest::getCmd('option');
		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		$switch = "email";
		$db->setQuery("TRUNCATE TABLE #__mailing_tmp");
		if (!$db->query()) {
      echo "Error emptying temporary table. Please contact support.<br/>";
    }
    foreach ($cid as $id) {
      $query = "INSERT INTO `#__mailing_tmp` (`id`) VALUES ($id)";
  		$db->setQuery($query);
  		if (!$db->query()) {
        echo "Error populating temporary table. Please contact support.<br/>";
      }
    }
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::sendMessage( $option,$switch,$cid,'','' );
  }

	function sendMessage()
	{
		$option = JRequest::getCmd('option');
		
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::sendMessage( $option,'','','','' );
	}

	function batch()
	{
		$option = JRequest::getCmd('option');
		
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::showbatch( $option );
	}

	function savebatch()
	{
		$option = JRequest::getCmd('option');
		$this->setRedirect('index.php?option=' . $option);
		$db	=& JFactory::getDBO();
		
		$post = JRequest::get('post');
		$filter = $post[filter];
		$excludefilter = $post[excludefilter];
		$emails = explode(";", $post[emails]);
    $name = ereg_replace("'", "",$post[name]);
    $notes = ereg_replace("'", "",$post[notes]);
    $groupid = $post[groupid];
	  $success = 0;
	  $failed = 0;
    $identical = 0;
    $excluded = 0;
	  $setMessage = '';
    $identicalMessage = "";

		$query = "SELECT * FROM #__mailing_groups WHERE id=".$groupid;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
    $groupname = $rows[0]->name;

		foreach ( $emails as $email) {
   	 $email = ereg_replace(" ", "",$email);
  	 $email = $this->check_email_address($email);
  	 if (strstr($email, $filter) || $filter=="") {
  	 if (!strstr($email, $excludefilter) || $excludefilter=="") {
      if (!strstr($email, 'email address:')) {
    		$query = "SELECT email FROM #__mailing_emails WHERE email='".$email."' AND groupid=".$groupid;
        $db->setQuery( $query );
    		$rows = $db->loadObjectList();
    		if (count($rows)) {$identical++;} else {
          $db->setQuery("INSERT INTO #__mailing_emails (
          `email`, `name`, `notes`, `groupid`) VALUES (
          '$email', '$name', '$notes', $groupid)");
      		if (!$db->query()) {
            $this->setMessage("Error updating contacts table. Please contact support.<br/>");
            return;
          }
          $success++;
        }
      } else {
        $email = substr($_email, 23);
        $fail[$i] = $_email;
        $failed++;
      }
        $i++;
     } else {$excluded++;}
     }
    }

    if ($failed) {
      $setMessage = " || $failed contacts NOT succesfully saved: ";
      foreach ($fail as $failure) {
        $setMessage.= "$failure; ";
      }
    }
    
    if ($identical) {
      $identicalMessage = " || Skipped $identical identical email addresses";
    }
 	  $setMessageSuccess = $success." contacts succesfully saved to $groupname";
 	  if ($filter!="") $filter = " || inclusion filter used: ".$filter;
 	  if ($excludefilter!="") $excludefilter = " || exclusion filter used: ".$excludefilter." || $excluded contacts excluded";
    $this->setMessage($setMessageSuccess.$setMessage.$identicalMessage.$filter.$excludefilter);
	}
	
	function savegroup()
	{
		$option = JRequest::getCmd('option');
		$this->setRedirect('index.php?option=' . $option . '&act=groups');
		
		$post = JRequest::get('post');
		
		$row =& JTable::getInstance('EditGroup', 'Table');
				
    if (!$row->bind($post)) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		if (!$row->store()) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		$html_message = stripslashes( $_POST['description'] ) ;
		$groupid = $post[id];
		if (!$groupid) $groupid="LAST_INSERT_ID()";
		$db	=& JFactory::getDBO();
		$query = "UPDATE #__mailing_groups SET description='".$html_message."' WHERE id=$groupid";
    $db->setQuery( $query );
    $db->Query( $query );
		$this->setMessage('Contact List Saved');
	}
	
	function saveemail()
	{
		$option = JRequest::getCmd('option');
		$this->setRedirect('index.php?option=' . $option);
		
		$post = JRequest::get('post');
		
		$row =& JTable::getInstance('EditEmail', 'Table');
				
    if (!$row->bind($post)) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		if (!$row->store()) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		$this->setMessage('Contact Saved');
	}
	
	function deletegroup()
	{
		$option = JRequest::getCmd('option');
		
		$this->setRedirect('index.php?option=' . $option . '&act=groups');

		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		$count = count($cid);
		
		if ($count)
		{
		  $i=0;
		  while ($i < $count) {
  			$db->setQuery('DELETE FROM #__mailing_groups WHERE id = '.$cid[$i]);
        $db->query();
  			$db->setQuery('DELETE FROM #__mailing_emails WHERE groupid = '.$cid[$i]);
        $db->query();
        $i++;
      }
			if (!$db->query()) {
				$this->setMessage('Error in sql query');
			} else {
  			if ($count > 1) {
  				$s = 's';
  			} else {
  				$s = '';
  			}
  			
  			$this->setMessage('Contact List' . $s . ' removed');
  		}
		}
	}
	
	function deleteemail()
	{
		$option = JRequest::getCmd('option');
		
		$this->setRedirect('index.php?option=' . $option);

		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		$count = count($cid);
		
		if ($count)
		{
		  $i=0;
		  while ($i < $count) {
  			$db->setQuery('DELETE FROM #__mailing_emails WHERE id = '.$cid[$i]);
        $db->query();
        $i++;
      }
			if (!$db->query()) {
				$this->setMessage('Error in sql query');
			} else {
  			if ($count > 1) {
  				$s = 'es';
  			} else {
  				$s = '';
  			}
  			
  			$this->setMessage('Contact' . $s . ' removed');
  		}
		}
	}
	
	function deletelog()
	{
		$option = JRequest::getCmd('option');
		
		$this->setRedirect('index.php?option=' . $option . '&act=success');

		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		$count = count($cid);
		
		if ($count)
		{
		  $i=0;
		  while ($i < $count) {
  			$db->setQuery('DELETE FROM #__mailing_email_log WHERE id = '.$cid[$i]);
        $db->query();
        $i++;
      }
			if (!$db->query()) {
				$this->setMessage('Error in sql query');
			} else {
  			if ($count > 1) {
  				$s = 's';
  			} else {
  				$s = '';
  			}
  			
  			$this->setMessage('Email' . $s . ' removed');
  		}
		}
	}
	
	function assign()
	{
	 global $defaultgroup;
	 
		$option = JRequest::getCmd('option');
		
		$this->setRedirect('index.php?option=' . $option);

		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
    $defaultgroup = $cid[0];
		$query = "UPDATE #__mailing_conf SET defaultgroup=".$defaultgroup." WHERE id=1";
    $db->setQuery( $query );
    $db->Query( $query );
		$query = "SELECT * FROM #__mailing_groups WHERE id=".$defaultgroup;
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
    $name = $rows[0]->name;

		$this->setMessage('Contact List "' . $name . '" set as default');
	}
	
	function listGroups()
	{
  global $mainframe;
  $limit = JRequest::getVar('limit',$mainframe->getCfg('list_limit'));
  $limitstart = JRequest::getVar('limitstart', 0);

		$option = JRequest::getCmd('option');
		
		$db	=& JFactory::getDBO();
    $query = "SELECT count(*) FROM #__mailing_groups";
    $db->setQuery( $query );
    $total = $db->loadResult();
    
		$query = "SELECT * FROM #__mailing_groups ORDER BY name";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		
    jimport('joomla.html.pagination');
    //$pageNav = new JPagination($total, $limitstart, $limit);
    
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::listGroups( $option, $rows );
	}

	function listEmails()
	{
  global $mainframe,$defaultgroup;
  $limit = JRequest::getVar('limit',$mainframe->getCfg('list_limit'));
  $limitstart = JRequest::getVar('limitstart', 0);

		$option = JRequest::getCmd('option');
		
		$db	=& JFactory::getDBO();
    $query = "SELECT count(*) FROM #__mailing_emails WHERE groupid=".$defaultgroup;
    $db->setQuery( $query );
    $total = $db->loadResult();
    
		$query = "SELECT * FROM #__mailing_emails WHERE groupid=".$defaultgroup." ORDER BY email";
    $db->setQuery( $query, $limitstart, $limit );
		$rows = $db->loadObjectList();
		
    jimport('joomla.html.pagination');
    $pageNav = new JPagination($total, $limitstart, $limit);
    
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::listEmails( $option, $rows, $pageNav );
	}

	function listSuccess()
	{
  global $mainframe,$defaultgroup;
  $limit = JRequest::getVar('limit',$mainframe->getCfg('list_limit'));
  $limitstart = JRequest::getVar('limitstart', 0);

		$option = JRequest::getCmd('option');
		
		$db	=& JFactory::getDBO();
    $query = "SELECT a.* FROM #__mailing_email_log a, #__mailing_recipients b WHERE a.id=b.email_id AND b.success=1 GROUP BY a.id";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
    $total = count($rows);
    
		$query = "SELECT a.id, a.date, a.title FROM #__mailing_email_log a, #__mailing_recipients b WHERE a.id=b.email_id AND b.success=1 GROUP BY a.id ORDER BY a.date DESC";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		
    jimport('joomla.html.pagination');
    //$pageNav = new JPagination($total, $limitstart, $limit);
    
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::listEmailLog( $option, $rows, 'success' );
	}

	function listFail()
	{
  global $mainframe,$defaultgroup;
  $limit = JRequest::getVar('limit',$mainframe->getCfg('list_limit'));
  $limitstart = JRequest::getVar('limitstart', 0);

		$option = JRequest::getCmd('option');
		
		$db	=& JFactory::getDBO();
    $query = "SELECT a.* FROM #__mailing_email_log a, #__mailing_recipients b WHERE a.id=b.email_id AND b.success=0 GROUP BY a.id";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
    $total = count($rows);
    
		$query = "SELECT a.id, a.date, a.title FROM #__mailing_email_log a, #__mailing_recipients b WHERE a.id=b.email_id AND b.success=0 GROUP BY a.id ORDER BY a.date DESC";
    $db->setQuery( $query );
		$rows = $db->loadObjectList();
		
    jimport('joomla.html.pagination');
    //$pageNav = new JPagination($total, $limitstart, $limit);
    
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::listEmailLog( $option, $rows, 'fail' );
	}

	function editgroup()
	{
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		
		$row =& JTable::getInstance('EditGroup', 'Table');
					
		if (isset($cid[0])) {
			$row->load($cid[0]);
		}
		
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		$option = JRequest::getCmd('option');
		HTML_mailing::editgroup($option, $row);
	}
	
	function editemail()
	{
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		
		$row =& JTable::getInstance('EditEmail', 'Table');
					
		if (isset($cid[0])) {
			$row->load($cid[0]);
		}
		
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		$option = JRequest::getCmd('option');
		HTML_mailing::editemail($option, $row);
	}
	
	function publish()
	{
		$option = JRequest::getCmd('option');
		
		$this->setRedirect('index.php?option=' . $option);
		
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		
		if ($this->getTask() == 'publish') {
			$publish = 1;
		} else {
			$publish = 0;
		}
		
		$db	=& JFactory::getDBO();
		foreach ($cid as $id) {
      $query = "UPDATE #__mailing_groups SET published=$publish WHERE id=$id";
      $db->setQuery( $query );
      $db->Query();
    }
		
		if (count($cid) > 1) {
			$s = 's';
		} else {
			$s = '';
		}
		
		$action = ucfirst($this->getTask()) . 'ed';
		
		$this->setMessage('Contact List' . $s . ' ' . $action);
	}

  function addusers() 
  {
		$option = JRequest::getCmd('option');
		$this->setRedirect('index.php?option=' . $option);		
		$cid = JRequest::getVar('cid', array(), 'request', 'array');
		
		$db	=& JFactory::getDBO();
		foreach ($cid as $groupid) {
        $query = "SELECT email, name, usertype FROM #__users WHERE block=0";
        //$message.=$lastdate." | <br/><br />";
        $db->setQuery( $query );
        $newusers = $db->loadObjectList();
        foreach ($newusers as $newuser) {
          $usertype = $newuser->usertype;
          $email = $newuser->email;
          $name = $newuser->name;
            $db->setQuery("SELECT id FROM #__mailing_emails WHERE groupid=$groupid AND email='$email'");
            $exist = $db->loadResult();
            if (!$exist) {
              //$message.="user not found in $groupid | INSERTED";
              $db->setQuery("INSERT INTO #__mailing_emails (`email`,`name`,`notes`,`groupid`) VALUES ('$email','$name','$usertype',$groupid)");
              $db->Query();
            }
        }
    }
  
		$this->setMessage('Users Added!');
  }
	
  function maintenance()
	{
		$option = JRequest::getCmd('option');
		
		$db	=& JFactory::getDBO();
    $query = "SELECT count(*) FROM #__mailing_email_log a, #__mailing_recipients b WHERE  a.id=b.email_id AND b.success=0 ";
    $db->setQuery( $query );
    $total = $db->loadResult();
    
		$query = "SELECT a.id, a.date, a.title, b.recipient FROM #__mailing_email_log a, #__mailing_recipients b WHERE a.id=b.email_id AND b.success=0 ORDER BY a.date DESC";
    $db->setQuery( $query, $limitstart, $limit );
		$rows = $db->loadObjectList();
		
		require_once(JPATH_COMPONENT.DS.'admin.mailing.html.php');
		HTML_mailing::maintenance( $option );
	}

	function do_maintenance()
	{
		$db	=& JFactory::getDBO();

		$option = JRequest::getCmd('option');
		$post = JRequest::get('post');
		$this->setRedirect('index.php?option=' . $option);

    if ($post[synchronize]!='') {
        //$message = "Started synchro | ";
        if ($post[lastdate]!='') $lastdate = "AND registerDate >= '".$post[lastdate]."'";
        $query = "SELECT email, name, usertype FROM #__users WHERE block=0 $lastdate";
        //$message.=$lastdate." | <br/><br />";
        $db->setQuery( $query );
        $newusers = $db->loadObjectList();
        foreach ($newusers as $newuser) {
          $usertype = $newuser->usertype;
          $email = $newuser->email;
          $name = $newuser->name;
          //$message.= "name: $name, email:$email, type:$usertype | ";          
          $db->setQuery("SELECT id FROM #__mailing_groups WHERE name='".$usertype."'");
          $groupid = $db->loadResult();
          //$message.="result: ".$groupid;
          if (!$groupid) {
            //$message.= "not found, new groupID:";
            $db->setQuery("INSERT INTO #__mailing_groups (`name`) VALUES ('".$usertype."')");
            $db->Query();
            $groupid = "LAST_INSERT_ID()";
            //$message.= $groupid;          
          }
          $db->setQuery("SELECT id FROM #__mailing_emails WHERE groupid=$groupid AND email='$email'");
          $exist = $db->loadResult();
          if (!$exist) {
            //$message.=" | INSERTED";
            $db->setQuery("INSERT INTO #__mailing_emails (`email`,`name`,`groupid`) VALUES ('$email','$name',$groupid)");
            $db->Query();
          }
          //$message.="  <br />";
        }
    		$query = "UPDATE #__mailing_conf SET sync='".date("Y-m-d H:i:s", time())."' WHERE id=1";
        $db->setQuery( $query );
        $db->Query( $query );
    } else {
      if ($post[table]!='' && $post[field]!='') {
          $table = str_replace("#__","",$post[table]);
          $table = str_replace("jos_","",$table);
          //$message = "TABLE:$table <br />";
          $field = $post[field];
          $name = $post[name];
          $notes = $post[notes];
          $distinct = $post[distinct];
          if ($name) $name = ", $name as 'username'";
          if ($distinct) $distinct = ", $distinct as 'distinct'";
          if ($notes) $notes = ", $notes as 'notes'";
          //$message.="field: $field | name: $name | distinct: $distinct | <br />";
          $query = "SELECT $field as 'email' $notes $distinct $name FROM #__$table";
          $db->setQuery( $query );
          //if (!$db->Query()) $message.=$query."<br />";
          $newusers = $db->loadObjectList();
          foreach ($newusers as $newuser) {
            $distinct = $newuser->distinct;
            if ($distinct=='') $distinct = $table; //if no field distinction, create contact list with table name
            $email = $newuser->email;
            $name  = $newuser->username;
            $notes = $newuser->notes;
            //$message.="USER DATA | name: $name | email: $email | distinct: $distinct | ";
            $db->setQuery("SELECT id FROM #__mailing_groups WHERE name='".$distinct."'");
            $groupid = $db->loadResult();
            if (!$groupid) {
              $db->setQuery("INSERT INTO #__mailing_groups (`name`) VALUES ('".$distinct."')");
              $db->Query();
              $groupid = "LAST_INSERT_ID()";          
            }
            $db->setQuery("SELECT id FROM #__mailing_emails WHERE groupid=$groupid AND email='$email'");
            $exist = $db->loadResult();
            if (!$exist) {
              //$message.="user not found in $groupid | INSERTED";
              $db->setQuery("INSERT INTO #__mailing_emails (`email`,`name`,`notes`,`groupid`) VALUES ('$email','$name','$notes',$groupid)");
              $db->Query();
            }
            //$message.="<br />";
          }
      }
    }
    
		$this->setMessage('Maintenance Complete!');
	}
	
}

?>