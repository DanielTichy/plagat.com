<?php
/*
* @name ULTIMATE MAILING LISTS
* Created By Chris Michaelides <info@axxis.gr>
* http://www.axxis.gr
* @copyright AXXIS Internet Solutions Copyright (C) 2009  www.Axxis.gr / All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class TableEditEmail extends JTable
{
	var $email = null;
	var $name = null;
	var $notes = null;
	var $groupid = null;
  var $subscription = null;
  var $id = null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__mailing_emails', 'id', $db );
		
	}
}


?>