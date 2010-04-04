<?php
/*
* @name ULTIMATE MAILING LISTS
* Created By Chris Michaelides <info@axxis.gr>
* http://www.axxis.gr
* @copyright AXXIS Internet Solutions Copyright (C) 2008  www.Axxis.gr / All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die(dirname(__FILE__).DS.'Restricted access');
//global $grFactory;
//$grFactory = new JFactory();

// ************************
function com_install() {
$database =& JFactory::getDBO();

echo "<table><tr><td><img src=\"http://www.axxis.gr/images/Junk_Mail_mailbox.jpg\" height=100></td><td><br/>Ultimate Mailing Lists written by Chris grVulture Michaelides<br/><br/>";
//echo "For any questions, bug reporting, etc. visit the <a href='http://www.axxis.gr/super_activity/index.php/joomla-forum'>CB Super Activity Forum</a><br/><br/>";
echo "<small>Copyright © 2009 www.axxis.gr	All rights reserved.</small><br/><br/></td></tr></table>";
//    $db		=& $grFactory->getDBO();

/*
    $drop = "DROP TABLE IF EXISTS #__mailing_emails";
    		$database->setQuery($drop);
        $database->query();
        echo "Re-initialising configuration values. Please re-configure the component...<br/>";
*/
    $query = "CREATE TABLE IF NOT EXISTS `#__mailing_tmp` (`id` int(11))";
		$database->setQuery($query);
		if (!$database->query()) {
      echo "Error creating temporary table. Please contact support.<br/>";
    }

    $query = "	CREATE TABLE IF NOT EXISTS `#__mailing_groups` (
    	`id` int(11) NOT NULL auto_increment,
    	`name` TINYTEXT,
    	`description` TEXT,
    	`published` int(11),
      PRIMARY KEY  (`id`)
    	)";
		$database->setQuery($query);
		if (!$database->query()) {
      echo "Error creating contact lists table. Please contact support.<br/>";
      return FALSE;
    }

    $query = "	CREATE TABLE IF NOT EXISTS `#__mailing_emails` (
    	`id` int(11) NOT NULL auto_increment,
    	`email` TINYTEXT,
    	`name` TINYTEXT,
    	`notes` TINYTEXT,
    	`groupid` int(11),
    	`subscription` int(11),
      PRIMARY KEY  (`id`)
    	)";
		$database->setQuery($query);
		if (!$database->query()) {
      echo "Error creating email addresses table. Please contact support.<br/>";
      return FALSE;
    }

    $query = "	CREATE TABLE IF NOT EXISTS `#__mailing_email_log` (
    	`id` int(11) NOT NULL auto_increment,
    	`date` DATETIME,
    	`title` VARCHAR(255),
    	`body` TEXT,
    	`text` MEDIUMTEXT,
    	`attachment` VARCHAR(255),
      `sendername` TINYTEXT, 
      `senderemail` TINYTEXT, 
      `file_path` TINYTEXT,
      `file_name` TINYTEXT,
      `file_type` TINYTEXT, 
      PRIMARY KEY  (`id`)
    	)";
		$database->setQuery($query);
		if (!$database->query()) {
      echo "Error creating email log table. Please contact support.<br/>";
      return FALSE;
    }

    $query = "	CREATE TABLE IF NOT EXISTS `#__mailing_to_go` (
      `id` int(11) NOT NULL auto_increment,
      `name` TINYTEXT, 
      `email` TINYTEXT, 
      `file_path` TINYTEXT,
      `file_name` TINYTEXT,
      `file_type` TINYTEXT, 
      `receiver` TINYTEXT, 
      `email_id` int(11),
      PRIMARY KEY  (`id`)
    	)";
		$database->setQuery($query);
		if (!$database->query()) {
      echo "Error creating to_go table. Please contact support.<br/>";
      return FALSE;
    }

    $query = "	CREATE TABLE IF NOT EXISTS `#__mailing_recipients` (
    	`id` int(11) NOT NULL auto_increment,
    	`recipient` VARCHAR(255),
    	`email_id` int(11),
    	`success` int(11),
      PRIMARY KEY  (`id`)
    	)";
		$database->setQuery($query);
		if (!$database->query()) {
      echo "Error creating recipients table. Please contact support.<br/>";
      return FALSE;
    }

    $query = "	CREATE TABLE IF NOT EXISTS `#__mailing_conf` (
    	`id` int(11) NOT NULL auto_increment,
    	`defaultgroup` int(11),
    	`server` tinytext,
    	`smtp_user` tinytext,
    	`password` tinytext,
     	`verikey` tinytext,
    	`limit` tinytext,
    	`sync` datetime,
      PRIMARY KEY  (`id`)
    	)";
		$database->setQuery($query);
		if (!$database->query()) {
      echo "Error creating email addresses table. Please contact support.<br/>";
      return FALSE;
    }

    $query = "	INSERT INTO `#__mailing_conf` (
      `id`, `defaultgroup`, `server`, `smtp_user`, `password`, `verikey`, `limit`) VALUES
      (1, 0, '', '', '', '', '100')";
		$database->setQuery($query);
		if (!$database->query()) {
      echo "Your default contact list is already configured. Not initializing value. Probably this is not your first installation of the Mailing Component.<hr/>";
    } else {
      echo "Thank you for choosing Ultimate Mailing Lists!"; /* <b>If you want to create Contact Lists based on your website users please click this button: </b>";
      <form action="index.php" method="post" style="display:inline;">
        <input type="hidden" name="synchronize" id="synchronize" />
        <input type="hidden" name="option" value="com_mailing" />
        <input type="hidden" name="lastdate" value="" />
        <input type="hidden" name="task" value="do_maintenance" />
        <input type="hidden" name="boxchecked" value="1" />
        <input type="submit" value="Create Contact Lists" onmousedown="document.getElementById('synchronize').value='true';" />
      </form><hr /><?php*/
    }

//$success = grInstallField("cb_gender_field", "TINYTEXT", "#__supera_conf");    
//$success = grUpdateField("cb_gender_field", "cb_gender", "configid = 1" , "#__supera_conf");

//    if ($success) return TRUE; else return FALSE;
 return TRUE;
}  
	
?>