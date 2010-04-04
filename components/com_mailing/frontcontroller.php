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

class subscriptionController extends JController
{
	function __construct()
	{
		parent::__construct();
//		$this->registerTask('unpublish', 'publish');
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

  function s_list() {
    $grFactory = new JFactory();
    $grUser =& $grFactory->getUser(); 
    $grguest = $grUser->guest;
    $myid = $grUser->id;
		$db	= $grFactory->getDBO();
		$option = JRequest::getCmd('option');
//		$this->setRedirect('index.php?option=' . $option);
/*****************************************************************************************************/    
/************************************** Process email-link *******************************************/    
/*****************************************************************************************************/    
    if (isset($_GET['contact'])) {
      $contact = $_GET['contact'];
      $db->setQuery("SELECT * FROM #__users WHERE email='$contact'");
      $me = $db->loadObjectList();
      if (count($me)==1) {
        $myid = $me[0]->id;
        $myname = $me[0]->name;
        $grguest = false;
      }
      $myemail = $contact;
    }
/*****************************************************************************************************/    
/************************************** Process user-input *******************************************/    
/*****************************************************************************************************/
    if (isset($_POST['name'])) {
      $db->setQuery("SELECT id FROM #__mailing_groups");
      $allgroups = $db->loadObjectList();
      $groups = $_POST['cid'];
      $email = $this->check_email_address($_POST['email']);
      if (strstr($email, 'email address:')) {
   		  echo(_MAIL_INVALID.'<a href="'.$_SERVER['REQUEST_URI'].'">'._MAIL_TRYAGAIN.'</a>');
   		  return;
      }    
      $name = mysql_real_escape_string($_POST['name']);
      
      foreach ($allgroups as $topgroup) {
        if (count($groups)==0) { // if user has not selected ANY group
          $db->setQuery("UPDATE #__mailing_emails SET subscription=-1 WHERE email='$email' AND groupid=".$topgroup->id);
          $db->Query();                      
        } else {
          $saved = false;//echo "groupid: ".$topgroup->id;
          $db->setQuery("SELECT * FROM #__mailing_emails WHERE email='$email' AND groupid=".$topgroup->id);
          $belongs = $db->loadObjectList(); 
          if (count($belongs)) { //user already belongs to this group. Searching for user in group
            foreach ($groups as $groupid) { // groups user selected
              //echo "<br />$groupid";
              if ($groupid == $belongs[0]->groupid) { //user already belongs to this group and has selected it also
                $db->setQuery("UPDATE #__mailing_emails SET subscription=1 WHERE email='$email' AND groupid=$groupid");
                $db->Query();
                $saved = true;
              }
            }
            if (!$saved) { // if user has not selected this group but belongs to it, we have to unsubscribe him
              $db->setQuery("UPDATE #__mailing_emails SET subscription=-1 WHERE email='$email' AND groupid=".$topgroup->id);
              $db->Query();                      
            }
          } else { // user does not belong to this group
            foreach ($groups as $groupid) { // groups user selected
              if ($groupid == $topgroup->id) { // this is the group where user does not belong and we must insert him/her
                $db->setQuery("INSERT INTO #__mailing_emails (`email`, `name`, `groupid`, `subscription`) VALUES ('$email', '$name', $groupid, 1)");
                $db->Query();
              }
            }
          }
        }
        //echo "<br />";
      }

      echo _MAIL_SAVED;
      if (count($groups)!=0) {
        echo _MAIL_SUBSCRIPTIONS;
        foreach ($groups as $groupid) { // groups user selected
          $db->setQuery("SELECT name FROM #__mailing_groups WHERE id=$groupid");
          $groupname = $db->loadObjectList();
          echo "<br /><b>".$groupname[0]->name."</b>";
        }
      }
   		  echo('<br /><br /><a href="'.$_SERVER['REQUEST_URI'].'">'._MAIL_RETURN.'</a>');
   		  return;
    }
/*****************************************************************************************************/    
/************************************** START OF FUNCTION ********************************************/    
/*****************************************************************************************************/    
    $db->setQuery("SELECT * FROM #__mailing_groups WHERE published=1");
    $groups = $db->loadObjectList();
    
    if (!$grguest) {
      $db->setQuery("SELECT * FROM #__users WHERE id=$myid");
      $me = $db->loadObjectList();
      $myemail = $me[0]->email;
      $myname = $me[0]->name;
    }?>
    <form method="post" action="">
    <h1><?=_MAIL_TITLE;?></h1><table width='100%'><?php
    foreach($groups as $group) {
      echo "<tr><td colspan=2><hr /></td></tr><tr><td width='5%'>";
      $db->setQuery("SELECT * FROM #__mailing_emails WHERE email='$myemail' AND groupid=".$group->id);
      $subscribed = $db->loadObjectList();
      if ($subscribed[0]->subscription > -1) $checked = true; else $checked = false;
      //if (count($subscribed)==0) $checked = true;
      ?>
      <input type="checkbox" name="cid[<?=$group->id;?>]" value="<?=$group->id;?>" <?php if ($checked) { ?>checked="checked"<?php } ?> /></td><td><?php
      echo "<b>".$group->name."</b></td></tr><tr><td></td><td>".$group->description."</td></tr>";
    }?>
    </table><hr />
    <div style="text-align:center;"><?php echo _MAIL_SELECT;
    if ($grguest) { echo _MAIL_CONTACT._MAIL_NAME; ?>
      <input type="text" name="name" value="<?=$myname;?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=_MAIL_ADDRESS;?><input type="text" name="email" value="<?=$myemail;?>" /><br /><?php
    } else { ?>
    <input type="hidden" name="name" value="<?=$myname;?>" />
    <input type="hidden" name="email" value="<?=$myemail;?>" /><?php
    } ?>
    <br />
    <input type="submit" value="<?=_MAIL_SUBMIT;?>" /></div>
    </form><?php
  }

  function unlist() {
    $grFactory = new JFactory();
    $grUser =& $grFactory->getUser(); 
    $grguest = $grUser->guest;
    $myid = $grUser->id;
		$db	= $grFactory->getDBO();
		$option = JRequest::getCmd('option');
    if (isset($_GET['contact'])) {
      $contact = $_GET['contact'];
      $db->setQuery("UPDATE #__mailing_emails SET subscription=-1 WHERE id=".$contact);
      $db->Query();                      
      echo "You've succesfully unsubscribed from this newsletter.";
    }
  }
  // ************************
  // LOAD LANGUAGE
  // ************************
  function newgrloadLanguage($pathtouser) {
  global $mosConfig_lang;
  
  if (!$mosConfig_lang) {
    $config = &JFactory::getConfig();
    $mosConfig_lang = $config->getValue('language');
  }
  	// Get right language file or use english
  	$usedlanguage="";
  if (!defined('_MAIL_SAVED')) {
  	if (file_exists($pathtouser.'/language/'.$mosConfig_lang.'.php')) {
  		include_once($pathtouser.'/language/'.$mosConfig_lang.'.php');
  		$usedlanguage='/language/'.$mosConfig_lang.'.php';
  	} elseif (file_exists($pathtouser.'/language/english.php')) {
  		include_once($pathtouser.'/language/english.php');
  		$usedlanguage='/language/english.php';
  	}
  }
  	// register vars from language file
  	return $usedlanguage;
  }
  // ************************
}
?>
