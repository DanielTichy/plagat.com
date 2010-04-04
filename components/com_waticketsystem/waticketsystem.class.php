<?php
/**
 * @version $Id: waticketsystem.class.php 192 2009-11-22 10:48:35Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL
 * @package wats
 */

// Don't allow direct linking
defined('_JEXEC') or die('Restricted Access');

/**
 * watsUser
 * @version 1.0
 */
class watsUser extends JUser
{
	var $groupName;
	var $agree;
	var $organisation;
	var $group;
	var $image;
	var $_userRites;
	
	/**
	 *
	 * @param watsId
	 */
	function loadWatsUser( $uid )
	{
		$wats = WFactory::getConfig();
		
		$db =& JFactory::getDBO();
		
		$returnValue = false;
		// load mosUser
		$this->load( $uid );
		// loadmosWatsUser
		$db->setQuery("SELECT  " . WDBHelper::nameQuote("u") . ".*, " .
		                           WDBHelper::nameQuote("g.name") . ", " .
								   WDBHelper::nameQuote("g.userrites") . ", " .
								   WDBHelper::nameQuote("g.image") . ", " .
								   WDBHelper::nameQuote("g.name") . " AS " . WDBHelper::nameQuote("groupname") . " " .
					  "FROM " . WDBHelper::nameQuote("#__wats_users") . " AS " . WDBHelper::nameQuote("u") . " " .
					  "LEFT  JOIN " . WDBHelper::nameQuote("#__wats_groups") . " AS " . WDBHelper::nameQuote("g") ." ON " . WDBHelper::nameQuote("g.grpid") . " = " . WDBHelper::nameQuote("u.grpid") . " " .
					  "WHERE " . WDBHelper::nameQuote("u.watsid") . " = " . intval($uid) . " /* watsUser::loadWatsUser() */");
		$vars = $db->loadObjectList();
		// set attributes
		if ( isset( $vars[0] ) )
		{
		    $this->groupName = $vars[0]->groupname ;
		    $this->agree = $vars[0]->agree;
		    $this->organisation = $vars[0]->organisation;
			$this->group = $vars[0]->grpid;
			$this->image = $vars[0]->image;
			$this->groupName = $vars[0]->name;
			$this->userRites = $vars[0]->userrites;
			$returnValue = true;
		}
		elseif ($wats->get( 'users' ) == 1)
		{
			// allow all user access enabled
			// get default group
			$db->setQuery( "SELECT  " . WDBHelper::nameQuote("g.grpid") . ", " .
			                            WDBHelper::nameQuote("g.name") . ", " .
										WDBHelper::nameQuote("g.userrites") . ", " .
										WDBHelper::nameQuote("g.image") . ", " .
										WDBHelper::nameQuote("g.name") . " AS " . WDBHelper::nameQuote("groupname") . " " .
						   "FROM " . WDBHelper::nameQuote("#__wats_groups") . " AS " . WDBHelper::nameQuote("g") . " " .
						   "WHERE " . WDBHelper::nameQuote("g.grpid") . " = " . intval($wats->get('userdefault')) . " /* watsUser::loadWatsUser() */");
			$vars = $db->loadObjectList();
			// setup user vars
		    $this->groupName = $vars[0]->groupname ;
		    $this->agree = "";
		    $this->organisation = $wats->get( 'dorganisation' );
			$this->group = $vars[0]->grpid;
			$this->image = $vars[0]->image;
			$this->groupName = $vars[0]->name;
			$this->userRites = $vars[0]->userrites;
			// check for import
			if ( $wats->get( 'usersimport' ) == 1 )
			{
				// import user to default group
				watsUser::makeUser($this->id, $this->group, $this->organisation);
			}
			$returnValue = true;
		}
		return $returnValue;
	}
	
	/**
	 *
	 * @param catid
	 * @param rite
	 */
	function checkPermission($catid, $rite) {
		static $cache;
		
		if (!$cache) {
			$cache = array();
		}
		if (!isset($cache[$this->group])) {
			$cache[$this->group] = array();
		}
		
		if (isset($cache[$this->group][$catid.":".$rite])) {
			return $cache[$this->group][$catid.":".$rite];
		}
	
		$db =& JFactory::getDBO();
	
		// prepare for no rite
		$cache[$this->group][$catid.":".$rite] = 0;
		// run SQL to find permission
		$db->setQuery( "SELECT " . WDBHelper::nameQuote("type") . " " .
                       "FROM " . WDBHelper::nameQuote("#__wats_permissions") . " " .
					   "WHERE " . WDBHelper::nameQuote("catid") . " = " . intval($catid) . " AND " .
					              WDBHelper::nameQuote("grpid") . " = " . intval($this->group) . " /* watsUser::checkPermission() */");
		$vars = $db->loadObjectList();
		// check for result
		if ( isset( $vars[0] ) ) {
			// find rite in string
			// checks type as well because could return 0
			if ( strpos( $vars[0]->type, strtolower( $rite) ) !== false )
			{
				// check for OWN rite
				$cache[$this->group][$catid.":".$rite] = 1;
			}
			else if ( strpos( $vars[0]->type, strtoupper( $rite) ) !== false )
			{
				// check for ALL rite
				$cache[$this->group][$catid.":".$rite] = 2;
			} // end find rite in string
		} // end check for result
		return $cache[$this->group][$catid.":".$rite];
	}

	/**
	 *
	 * @param watsId
	 */
	function checkUserPermission( $rite )
	{
		// prepare for no rite
		$returnValue = 0;
		// find rite in string
		// checks type as well because could return 0
		if ( strpos( $this->userRites, strtolower( $rite) ) !== false )
		{
			// check for OWN rite
			$returnValue = 1;
		}
		else if ( strpos( $this->userRites, strtoupper( $rite) ) !== false )
		{
			// check for ALL rite
			$returnValue = 2;
		} // end find rite in string
		return $returnValue;
	}
	
	/**
	 *
	 * @param watsId
	 */
	function makeUser( $watsId, $grpId, $organisation) {
		$database =& JFactory::getDBO();
	
		// check doesn't already exist
		$database->setQuery( "SELECT " . WDBHelper::nameQuote("wu.watsid") .
                             "FROM " . WDBHelper::nameQuote("#__wats_users") . " AS " . WDBHelper::nameQuote("wu") . " " .
							 "WHERE " . WDBHelper::nameQuote("watsid") . " = " . intval($watsId) . " /* watsUser::makeUser() */");
		$database->query();
		if ( $database->getNumRows() == 0 )
		{
			// create SQL
			$database->setQuery( "INSERT INTO " . WDBHelper::nameQuote("#__wats_users") . " " .
							     "          ( " . WDBHelper::nameQuote("watsid") . ", " .
												  WDBHelper::nameQuote("organisation") . ", " .
												  WDBHelper::nameQuote("agree") . ", " .
												  WDBHelper::nameQuote("grpid") ." ) " . 
								 "VALUES ( " . intval($watsId) . ", " .
								               $database->Quote($organisation) . ", " .
											   $database->Quote("0000-00-00") . ", " . 
											   intval($grpId) . " ) /* watsUser::makeUser */" );
			// execute
			$database->query();
			return true;
		}
		else
		{
			return false;
		} // end check doesn't already exist
	}
	
	/**
	 *
	 */
	function updateUser()
	{
		$db =& JFactory::getDBO();
		
		// check already exists
		$db->setQuery("SELECT " . WDBHelper::nameQuote("wu.watsid") .
					  "FROM " . WDBHelper::nameQuote("#__wats_users") . " AS " . WDBHelper::nameQuote("wu") . " " .
					  "WHERE " . WDBHelper::nameQuote("watsid") . " = " . intval($this->id) . " /* watsUser::updateUser() */ ");
		$db->query();
		if ($db->getNumRows() == 1) {
			// update SQL
			$db->setQuery("UPDATE " . WDBHelper::nameQuote("#__wats_users") . " " .
			                     "SET " . WDBHelper::nameQuote("organisation") . " = " . $db->Quote($this->organisation) . ", " .
								          WDBHelper::nameQuote("agree") . " = " . intval($this->agree) . ", " .
										  WDBHelper::nameQuote("grpid") . " = " . intval($this->group) . " " .
								 "WHERE " . WDBHelper::nameQuote("watsid") . " = " . intval($this->id) . " /* watsUser::updateUser() */" );
			// execute
			return $db->query();	
		}
		else
		{
			return false;
		} // end check doesn't already exist
	}
	
	/**
	 *
	 * @param groupId
	 */
	function setGroup( $groupId ) {
		$db =& JFactory::getDBO();
		
		// check group exists and get name
		$db->setQuery("SELECT " . WDBHelper::nameQuote("g.name") .", " .
		                          WDBHelper::nameQuote("g.image") . " " .
					  "FROM " . WDBHelper::nameQuote("#__wats_groups") . " AS " . WDBHelper::nameQuote("g") ." " .
					  "WHERE " . WDBHelper::nameQuote("grpid") . " = " . intval($groupId) . " /* watsUser::setGroup() */");
		$groupDetails = $db->loadObjectList();
		if ( count( $groupDetails ) != 0 )
		{
			// update object
			$this->group = $groupId;
			$this->groupName = $groupDetails[0]->name;
			$this->image = $groupDetails[0]->image;
			// update SQL
			$db->setQuery("UPDATE " . WDBHelper::nameQuote("#__wats_users") . " " .
			              "SET " . WDBHelper::nameQuote("organisation") . " = " . $db->Quote($this->organisation) . ", " .
							       WDBHelper::nameQuote("agree") . " = " . intval($this->agree) . ", " .
								   WDBHelper::nameQuote("grpid") . " = " . intval($this->group) . " " .
						  "WHERE " . WDBHelper::nameQuote("watsid") . " = " . intval($this->id) . " /* watsUser::setGroup() */" );
			// execute
			return $db->query();
		}
		else
		{
			return false;
		} // end check doesn't already exist
	}

	/**
	 *
	 * @param groupId
	 */
	function delete( $remove ) {
		$db =& JFactory::getDBO();
	
		// find tickets
		$db->setQuery("SELECT " . WDBHelper::nameQuote("ticketid") . " " .
				      "FROM " . WDBHelper::nameQuote("#__wats_ticket") . " " .
					  "WHERE " . WDBHelper::nameQuote("watsid") . " = " . intval($this->id) . " /* watsUser::delete() */ ");
		$tickets = $db->loadObjectList();
		$noOfTickets = count( $tickets );
		$i = 0;
		while ( $i < $noOfTickets )
		{
			// remove ticket messages
			$db->setQuery("DELETE FROM " . WDBHelper::nameQuote("#__wats_msg") . " " .
		                  "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($tickets[$i]->ticketid) . " /* watsUser::delete() */ ");
			$db->query();
			// remove highlights
			$db->setQuery("DELETE FROM " . WDBHelper::nameQuote("#__wats_highlight") . " " .
		                  "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($tickets[$i]->ticketid) . " /* watsUser::delete() */");
			$db->query();
			$i ++;
		}
		// remove tickets
		$db->setQuery("DELETE FROM " . WDBHelper::nameQuote("#__wats_ticket") . " " .
		              "WHERE " . WDBHelper::nameQuote("watsid") . " = " . intval($this->id) . " /* watsUser::delete() */ ");
		$db->query();
		// remove all posts
		if ( $remove == "removeposts" )
		{
			$db->setQuery("DELETE FROM " . WDBHelper::nameQuote("#__wats_msg") . " " .
						  "WHERE " . WDBHelper::nameQuote("watsid") . " = " . intval($this->id) . " /* watsUser::delete() */");
			$db->query();
		} // end remove all posts
		// delete users highlights
		$db->setQuery("DELETE FROM " . WDBHelper::nameQuote("#__wats_highlight") . " " .
		              "WHERE " . WDBHelper::nameQuote("watsid") ." = " . intval($this->id));
		$db->query();
		// delete user
		$db->setQuery("DELETE FROM " . WDBHelper::nameQuote("#__wats_users") . " " .
		              "WHERE " . WDBHelper::nameQuote("watsid") . " = " . intval($this->id) . " /* watsUser::delete() */");
		$db->query();
	}
}

/**
 * @version 1.0
 * @created 09-Jan-2006 15:30
 */
class watsUserSet
{
	var $userSet;
	var $noOfUsers;
	
	/**
	 * @param groupId
	 */
	function load( $groupId = null )
	{
		$db =& JFactory::getDBO();
	
		// load all users
	    if ( $groupId === null )
		{
			$db->setQuery("SELECT u.*, wu.organisation, g.name as " . WDBHelper::nameQuote("groupname") . " " .#
                          "FROM " . WDBHelper::nameQuote("#__wats_users") . " AS " . WDBHelper::nameQuote("wu") . " " .
                          "JOIN " . WDBHelper::nameQuote("#__users") . " AS " . WDBHelper::nameQuote("u") . " ON " . WDBHelper::nameQuote("u.id") . " = " . WDBHelper::nameQuote("wu.watsid") .
                          "JOIN " . WDBHelper::nameQuote("#__wats_groups") . " AS " . WDBHelper::nameQuote("g") . " ON " . WDBHelper::nameQuote("wu.grpid") . " = " . WDBHelper::nameQuote("g.grpid") .
			              "ORDER BY " . WDBHelper::nameQuote("username") . " /* watsUserSet::load() */" );
			$set = $db->loadObjectList();
			$this->noOfUsers = count( $set );
			$i = 0;
			// create users
			while ( $i < $this->noOfUsers )
			{
				$this->userSet[$i] = new watsUserHTML();
				$this->userSet[$i]->id = $set[$i]->id;
                $this->userSet[$i]->name = $set[$i]->name;
                $this->userSet[$i]->username = $set[$i]->username;
                $this->userSet[$i]->email = $set[$i]->email;
                $this->userSet[$i]->usertype = $set[$i]->usertype;
                $this->userSet[$i]->block = $set[$i]->block;
                $this->userSet[$i]->sendEmail = $set[$i]->sendEmail;
                $this->userSet[$i]->gid = $set[$i]->gid;
                $this->userSet[$i]->registerDate = $set[$i]->registerDate;
                $this->userSet[$i]->lastvisitDate = $set[$i]->lastvisitDate;
                $this->userSet[$i]->activation = $set[$i]->activation;
                $this->userSet[$i]->params = $set[$i]->params;
                $this->userSet[$i]->organisation = $set[$i]->organisation;
                $this->userSet[$i]->groupName = $set[$i]->groupname;
                $this->userSet[$i]->guest = 0;
				$i ++;
			} // end create users
		} // end load all users
	}
}

/**
 * @version 1.0
 * @created 06-Dec-2005 21:42:51
 */
class watsObjectBuilder
{
	/**
	 *
	 * @param database
	 * @param ticketId
	 */
	 function ticket($ticketId ) {
		$database =& JFactory::getDBO();
		
		// create query
		$query = "SELECT * FROM " . WDBHelper::nameQuote("#__wats_ticket") . " " .
				 "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($ticketId) . " /* watsObjectBuilder::ticket() */ ";
		// execute query
		$database->setQuery( $query );
		$set = &$database->loadObjectList();
		// check there are results
		if ( $set != null )
		{
			// create ticket object
			return new watsTicketHTML(null, null, $set[0]->ticketname, $set[0]->watsid, null, null, $set[0]->lifecycle, $set[0]->ticketid, null, null, $set[0]->category, $set[0]->assign);
		} // end check there are results
		return null;
	 }
}

/**
 * Individual WATS Ticket Class
 * @version 1.0
 * @created 06-Dec-2005 21:42:32
 */
class watsTicket
{
	var $watsId;
	var $username;
	var $ticketId;
	var $name;
	var $category;
	var $lifeCycle;
	var $datetime;
	var $lastMsg;
	var $lastView;
	var $lastWatsId;
	var $assignId;
	var $msgNumberOf;
	var $_msgList;

	/**
	 * 
	 * @param database
	 * @param username
	 * @param lastWatsId
	 * @param name
	 * @param watsId
	 * @param lastMsg
	 * @param datetime
	 * @param lifeCycle
	 * @param ticketId
	 * @param lastView
	 * @param create
	 */
	function watsTicket($username, $lastWatsId, $name, $watsId, $lastMsg, $datetime, $lifeCycle, $ticketId, $lastView, $msgNumberOf, $catId, $assignId = null)
	{
		$this->username = $username;
		$this->lastWatsId = $lastWatsId;
		$this->name = $name;
		$this->watsId = $watsId;
		$this->lastMsg = $lastMsg;
		$this->datetime = $datetime;
		$this->lifeCycle = $lifeCycle;
		$this->ticketId = $ticketId;
		$this->lastView = $lastView;
		$this->msgNumberOf = $msgNumberOf;
		$this->_msgList = array();
		$this->category = $catId;
		$this->assignId = $assignId;
	}

	/**
	 * returns username of assigned user.
	 */
	function getAssignedUsername() {
		$db =& JFactory::getDBO();
	
		// check for assignment
	    if ( $this->assignId != null )
		{
			// find username
			$db->setQuery("SELECT " . WDBHelper::nameQuote("u.username") . " " .
			                     "FROM " .WDBHelper::nameQuote("#__users") . " AS " . WDBHelper::nameQuote("u") . " " .
								 "WHERE " . WDBHelper::nameQuote("u.id") . " = " . intval($this->assignId) . " /* watsTicket::watsTicket() */ ");
			$user = $db->loadObjectList();
			$returnValue = $user[0]->username;
		}
		else
		{
			// return no assigned user
			$returnValue = "not assigned";
		}
		
		return $returnValue;
	}

	/**
	 * saves ticket to database
	 */
	function save()
	{
		$db =&JFactory::getDBO();
	
		// ticket
		$queryTicket = "INSERT INTO " . WDBHelper::nameQuote("#__wats_ticket") . " " .
					   "SET " . WDBHelper::nameQuote("watsid") . " = " . intval($this->watsId) . ", " .
					            WDBHelper::nameQuote("ticketname") . " = " . $db->Quote($this->name) . ", " .
								WDBHelper::nameQuote("lifecycle") . " = " . intval($this->lifeCycle) . ", " .
								WDBHelper::nameQuote("datetime") . " = " . $db->Quote($this->datetime) . ", " .
								WDBHelper::nameQuote("category") . " = " . intval($this->category) . " /* watsTicket::save() */ ";
		$db->setQuery( $queryTicket );
		$db->query();
		$this->ticketId = $db->insertid();
		// message
		$queryMsg = "INSERT INTO " . WDBHelper::nameQuote("#__wats_msg") . " " .
		            "SET " . WDBHelper::nameQuote("watsid") . " = " . intval($this->watsId) . ", " .
					         WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . ", " .
							 WDBHelper::nameQuote("msg") . " = " . $db->Quote($this->_msgList[0]->msg) . ", " .
							 WDBHelper::nameQuote("datetime") . " = " . $db->Quote($this->datetime) . " /* watsTicket::save() */";
		$db->setQuery( $queryMsg );
		$db->query();
	}

	/**
	 * decreases view level
	 */
	function deactivate()
	{
		$db =& JFactory::getDBO();
	
		// check is not dead
		if ( $this->lifeCycle < 3 )
		{
			// update lifeCycle
			$this->lifeCycle ++;
			$queryDeactivateTicket = "UPDATE " . WDBHelper::nameQuote("#__wats_ticket") . " " . 
			                         "SET " . WDBHelper::nameQuote("lifecycle") . " = " . intval($this->lifeCycle) . " " .
									 "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . " /* watsTicket::deactivate() */ "; 
		}
		else
		{
			// remove ticket
			$queryDeactivateTicket = "DELETE FROM " . WDBHelper::nameQuote("#__wats_ticket") . " " .
			                         "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . " /* watsTicket::deactivate()*/ ";
			// remove all messages in ticket
			$queryDeactivateMsg = "DELETE FROM " . WDBHelper::nameQuote("#__wats_msg") . " " . 
								  "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intVal($message->ticketId) . " /* watsTicket::deactivate() */ ";
			$db->setQuery( $queryDeactivateMsg );
			$db->query();
		}
		$db->setQuery( $queryDeactivateTicket );
		$db->query();
	}

	/**
	 * Updates database to reflect viewing of ticket
	 */
	function _highlightUpdate( $watsId )
	{
		$db =& JFactory::getDBO();
        $datetime = JFactory::getDate();
        $datetime = $datetime->toMySQL();
	
		// check for existing record
		$queryHighlight = "SELECT " . WDBHelper::nameQuote("datetime") . " " .
		                  "FROM " . WDBHelper::nameQuote("#__wats_highlight") . " " .
						  "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . " AND " .
						             WDBHelper::nameQuote("watsid") . " = " . intval($watsId) . " /* watsTicket::_highlightUpdate() */ ";
		$db->setQuery( $queryHighlight );
		$db->query();
		if ( $db->getNumRows() > 0 )
		{
			// update record
			$queryHighlight = "UPDATE " . WDBHelper::nameQuote("#__wats_highlight") . " " .
			                  "SET " . WDBHelper::nameQuote("datetime") . " = " . $db->Quote($datetime) . " " .
							  "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . " AND " .
							             WDBHelper::nameQuote("watsid") . " = " . intval($watsId) . " /* watsTicket::_highlightUpdate*/";
		}
		else
		{
			// insert record
			$queryHighlight = "INSERT INTO " . WDBHelper::nameQuote("#__wats_highlight") . " " .
			                  "SET " . WDBHelper::nameQuote("watsid") . " = " . intval($watsId) . ", " . 
							           WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . ", " .
									   WDBHelper::nameQuote("datetime") . " = " . $db->Quote($datetime) . " /* watsTicket::_highlightUpdate() */";
		}
		// perform query
		$db->setQuery( $queryHighlight );
		$db->query();

	}
	
	/**
	 * Reactivate ticket and updates database
	 */
	function reactivate()
	{
		$db =& JFactory::getDBO();
		$this->lifeCycle = 1;
		$queryDeactivateMsg = "UPDATE " . WDBHelper::nameQuote("#__wats_ticket") . " " .
		                      "SET " . WDBHelper::nameQuote("lifecycle") . " = 1 " .
							  "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . " /* watsTicket::reactivate() */ ";
		$db->setQuery( $queryDeactivateMsg );
		$db->query();
	}

	/**
	 * Populates _msgList with all related messages
	 */
	function loadMsgList()
	{
		$db =& JFactory::getDBO();
		
		// reset number of messages
		$this->msgNumberOf = 0;
		// load categories
		$db->setQuery("SELECT * " .
		              "FROM " . WDBHelper::nameQuote("#__wats_msg") . " AS " . WDBHelper::nameQuote("m") . " " .
					  "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . " " .
					  "ORDER BY " . WDBHelper::nameQuote("datetime") . " /* watsTicket::loadMsgList() */ " );
		$messages = $db->loadObjectList();
		// create message objects
		$i = 0;
		foreach( $messages as $message )
		{
			// create object
		    $this->_msgList[$i] = new watsMsg( $message->msgid, $message->msg, $message->watsid, $message->datetime );
			// increment counter
			$i ++;
			$this->msgNumberOf ++;
		}
	}
	
	/**
	 * Add message to _msgList and database
	 */
	function addMsg( $msg, $watsId, $datetime )
	{
		$db =& JFactory::getDBO();
	
		// create SQL and execute
		$db->setQuery( "INSERT INTO " . WDBHelper::nameQuote("#__wats_msg") . 
		 			   "    ( " . WDBHelper::nameQuote("ticketid") . ", " .
						          WDBHelper::nameQuote("watsid") . ", " .
						 		  WDBHelper::nameQuote("msg") . ", " .
								  WDBHelper::nameQuote("datetime") . 
					   "    ) " .
					   "VALUES ( " . intval($this->ticketId) . ", " . 
					                 intval($watsId) . ", " . 
									 $db->Quote($msg) . ", " . 
									 $db->Quote($datetime) . 
					   "       ) /* watsTicket::addMsg */ " );
		$db->query();
		$this->_msgList[ count( $this->_msgList ) ] = new watsMsg( $this->ticketId, $msg, $watsId, $datetime );
		$this->msgNumberOf ++;
	}
	
	/**
	 * Add message to _msgList and database
	 */
	function setAssignId( $assignId )
	{
		$db =& JFactory::getDBO();
		$this->assignId = $assignId;
		// create SQL and execute
		$db->setQuery("UPDATE " . WDBHelper::nameQuote("#__wats_ticket") . " " .
		              "SET " . WDBHelper::nameQuote("assign") . " = " . intval($this->assignId) . " " .
					  "WHERE " . WDBHelper::nameQuote("ticketid") . " = " . intval($this->ticketId) . " /* watsTicket::setAssignId() */ " );
		$db->query();

        // trigger onTicketAssign event
        JPluginHelper::importPlugin("waticketsystem");
        $app =& JFactory::getApplication();
        $args = array(&$this);
        $app->triggerEvent("onTicketAssign", $args);
	}
}

/**
 * @version 1.0
 * @created 06-Dec-2005 21:43:47
 */
class watsTicketSet
{
	var $ticketNumberOf = 0;
	var $_ticketList;
	var $_ticketListPointer = 0;

	/**
	 * 
	 * @param lifeCycle (0 = open and closed, 1 = open, 2 = closed, 3 = dead)
	 * @param watsid
	 * @param category (id of category, -1 = all categories)
	 * @param riteAll (true = show all users tickets)
	 * @param assign ( true = assigned tickets only)
	 */
	 //$this->ticketSet->loadTicketSet( 0, $this->watsId, -1, true, true );
	function loadTicketSet( $lifecycle, $watsid, $category = null, $riteAll = false, $assign = false )
	{
		$db =& JFactory::getDBO();
	
		// create query
		$query = $sql = "SELECT COUNT(*) AS " . WDBHelper::nameQuote("posts") . ", " .
		                       WDBHelper::nameQuote("t.ticketid") . ", " .
							   WDBHelper::nameQuote("t.assign") . ", " .
							   WDBHelper::nameQuote("t.watsid") . " AS " . WDBHelper::nameQuote("ownerid") . ", " .
							   WDBHelper::nameQuote("t.ticketname") . ", " .
							   WDBHelper::nameQuote("t.category") . ", " .
							   WDBHelper::nameQuote("t.lifecycle") . ", " .
							   WDBHelper::nameQuote("t.datetime") . " AS " . WDBHelper::nameQuote("firstpost") . ", " .
							   WDBHelper::nameQuote("h.datetime") . " AS " . WDBHelper::nameQuote("lastview") . ", " .
							   "SUBSTRING(MIN(CONCAT(DATE_FORMAT(" . WDBHelper::nameQuote("m1.datetime") . ", " . $db->Quote("%Y-%m-%d %H:%i:%s") . "), " . WDBHelper::nameQuote("m1.msgid") . ")), 20) AS " . WDBHelper::nameQuote("firstmsg") . ", " .
							   "SUBSTRING(MAX(CONCAT(DATE_FORMAT(" . WDBHelper::nameQuote("m1.datetime") . ", " . $db->Quote("%Y-%m-%d %H:%i:%s") . "), " . WDBHelper::nameQuote("m1.msgid") . ")), 20) AS " . WDBHelper::nameQuote("lastpostid") . ", " .
							   "SUBSTRING(MAX(CONCAT(DATE_FORMAT(" . WDBHelper::nameQuote("m1.datetime") . ", " . $db->Quote("%Y-%m-%d %H:%i:%s") . "), " . WDBHelper::nameQuote("m1.watsid") . ")), 20) AS " . WDBHelper::nameQuote("lastid") . ", " .
							   "MAX( " . WDBHelper::nameQuote("m1.datetime") . ") AS " . WDBHelper::nameQuote("lastdate") . ", " .
							   WDBHelper::nameQuote("o.username") . " AS " . WDBHelper::nameQuote("username") . ", " .
							   "SUBSTRING(MAX(CONCAT(DATE_FORMAT(" . WDBHelper::nameQuote("m1.datetime") . ", " . $db->Quote("%Y-%m-%d %H:%i:%s") . "), " .
							   WDBHelper::nameQuote("p.username") . ")), 20) AS " . WDBHelper::nameQuote("poster") . " " .
							   "FROM " . WDBHelper::nameQuote("#__wats_ticket") . " AS " . WDBHelper::nameQuote("t") . " " .
							   "LEFT JOIN " . WDBHelper::nameQuote("#__wats_highlight") . " AS " . WDBHelper::nameQuote("h") . " ON " . WDBHelper::nameQuote("t.ticketid") . " = " . WDBHelper::nameQuote("h.ticketid") . " AND " . WDBHelper::nameQuote("h.watsid") . " = " . intval($watsid) . " " .
							   "LEFT JOIN " . WDBHelper::nameQuote("#__wats_msg") . " AS " . WDBHelper::nameQuote("m1") . " ON " . WDBHelper::nameQuote("t.ticketid") . " = " . WDBHelper::nameQuote("m1.ticketid") . " " .
							   "LEFT JOIN " . WDBHelper::nameQuote("#__users") . " AS " . WDBHelper::nameQuote("o") . " ON " . WDBHelper::nameQuote("t.watsid") . " = " . WDBHelper::nameQuote("o.id") . " " .
							   "LEFT JOIN " . WDBHelper::nameQuote("#__users") . " AS " . WDBHelper::nameQuote("p") . " ON " . WDBHelper::nameQuote("m1.watsid") . " = " . WDBHelper::nameQuote("p.id") . " /* watsTicketSet::loadTicketSet() */ ";
		// check lifeCycle
		if ( $lifecycle == 0 )
		{
			$query .= "WHERE ( " . WDBHelper::nameQuote("t.lifecycle") . " = 1 OR " . WDBHelper::nameQuote("t.lifecycle") . " = 2 )";
		}
		else
		{
			$query .= "WHERE " . WDBHelper::nameQuote("t.lifecycle") . " = " . intval($lifecycle);
		}
		if ( $riteAll == false )
		{
			// set wats id
			$query .= " AND " . WDBHelper::nameQuote("t.watsid") . " = " . intval($watsid);
		}
		if ( $category != null AND $category != -1 )
		{
			// set category
			$query .= " AND " . WDBHelper::nameQuote("category") . " = " . intval($category);
		}
		if ( $assign )
		{
			// set assigned tickets only
			$query .= " AND " . WDBHelper::nameQuote("t.assign") . " = " . intval($watsid);
		}
		// end create query
		$query .= " GROUP BY " . WDBHelper::nameQuote("t.ticketid") . ", " .
		                         WDBHelper::nameQuote("t.watsid") . ", " .
								 WDBHelper::nameQuote("t.ticketname") . ", " .
								 WDBHelper::nameQuote("t.datetime") . " " .
				  "ORDER BY " . WDBHelper::nameQuote("lastdate") . " DESC;";
		$db->setQuery( $query );
		$set = $db->loadObjectList();
		// check there are results
		if ( $set != null )
		{
			// create ticket objects
			foreach( $set as $ticket )
			{
				// create object
				$this->_ticketList[$this->ticketNumberOf] = new watsTicketHTML($ticket->username, $ticket->lastid, $ticket->ticketname, $ticket->ownerid, $ticket->lastdate, $ticket->firstpost, $ticket->lifecycle, $ticket->ticketid, $ticket->lastview, $ticket->posts, $ticket->category, $ticket->assign);
				// increment counter
				$this->ticketNumberOf ++;
			}// end create ticket objects
		} // end check there are results
	}
}

/**
 * @version 1.0
 * @created 06-Dec-2005 21:43:13
 */
class watsMsg
{
	var $msgId;
	var $msg;
	var $watsId;
	var $datetime;

	/**
	 * Populates msgId, msg, watsId and datetime with corresponding values
	 *
	 * @param msgId
	 */
	function watsMsg( $msgId, $msg = null, $watsId = null, $datetime = null )
	{
		$this->msgId    = $msgId;
		$this->msg      = $msg;
		$this->watsId   = $watsId;
		$this->datetime = $datetime;
	}
	
	/**
	 * saves message to database
	 * 
	 * @param database
	 */
	function save( &$database )
	{
	}
}

/**
 * @version 1.0
 * @created 06-Dec-2005 21:44:11
 */
class watsCategory extends JTable
{
    var $catid;
	var $name;
	var $ticketSet;
	var $description;
	var $image;

	/**
	 * 
	 * @param database
	 */
	function watsCategory()
	{
		$database =& JFactory::getDBO();
	    $this->__construct( '#__wats_category', 'catid', $database );
	}

	/**
	 * Loads this->ticketSet
	 *
	 * @param database
	 * @param lifecycle
	 * @param watsid
	 * @param category
	 */
	function loadTicketSet( $lifecycle, $watsid, $riteAll = false ) {
		// create new ticketset
		$this->ticketSet = new watsTicketSetHTML();
		// load tickets
		$this->ticketSet->loadTicketSet( $lifecycle, $watsid, $this->catid, $riteAll );
	}

	/**
	 * Purges loaded tickets
	 *
	 */
	function purge()
	{
		$ticketCount = count($this->ticketSet->_ticketList);
		$i = 0;
		while ( $i < $ticketCount )
		{
			$this->ticketSet->_ticketList[$i]->deactivate();
			$i ++;
		}
	}
	
	/**
	 * Returns an array of users who can have tickets assigned to.
	 *
	 */
	function getAssignee($catid = null)
	{
		if ( $catid == null ) {
			$catid = $this->catid;
		}
		
		$database =& JFactory::getDBO();
		
		$database->setQuery("SELECT " . WDBHelper::nameQuote("wu.watsid") . ", " .
		                                WDBHelper::nameQuote("u.username") . " " .
							"FROM " . WDBHelper::nameQuote("#__wats_permissions") . " AS " . WDBHelper::nameQuote("p") . " " .
							"LEFT  JOIN " . WDBHelper::nameQuote("#__wats_users") . " AS " . WDBHelper::nameQuote("wu") . " ON " . WDBHelper::nameQuote("wu.grpid") . " = " . WDBHelper::nameQuote("p.grpid") . " " .
							"LEFT  JOIN " . WDBHelper::nameQuote("#__users") . " AS " . WDBHelper::nameQuote("u") . " ON " . WDBHelper::nameQuote("wu.watsid") . " = " . WDBHelper::nameQuote("u.id") . " " .
							"WHERE " . WDBHelper::nameQuote("p.catid") . " = " . intval($catid) . " AND " .
							"          (" . WDBHelper::nameQuote("p.type") . " LIKE " . $database->Quote("%a%", false) . " OR " .
											WDBHelper::nameQuote("p.type") . " LIKE " . $database->Quote("%A%", false) . ") " .
							" /* watsCategory::getAssignee() */ ");
		$assignees = $database->loadObjectList( );
		// check for reults
		if ( count( $assignees ) == 0 )
		{
			return null;
		}
		else
		{
			return $assignees;
		} // end check for reults
	}
}

/**
 * @version 1.0
 * @created 12-Dec-2005 13:32:13
 */
class watsAssign
{
	var $ticketSet;
	var $watsId;

	/**
	 * Loads this->ticketSet
	 *
	 * @param watsid
	 */
	function loadAssignedTicketSet( $watsId )
	{
		// set watsId
		$this->watsId = $watsId;
		// create new ticketset
		$this->ticketSet = new watsTicketSetHTML();
		// load tickets
		$this->ticketSet->loadTicketSet(0, $this->watsId, -1, true, true);
	}
}

/**
 * @version 1.0
 * @created 06-Dec-2005 21:43:13
 */
class watsCategorySet
{
    var $categorySet;

	/**
	 * 
	 * @param database
	 */	
	function watsCategorySet(&$watsUser )
	{
		$db =& JFactory::getDBO();
		
		$this->categorySet = array();
		// load categories
		$db->setQuery( "SELECT * FROM " . WDBHelper::nameQuote("#__wats_category") . ' ORDER BY ' . WDBHelper::nameQuote("name") . " /* watsCategorySet::watsCategorySet() */ " );
		$vars = $db->loadObjectList();
		// create category objects
		$i = 0;
		foreach( $vars as $var )
		{
			// check for viewing rite
			if ( $watsUser->checkPermission( $var->catid, "v" ) > 0 )
			{
				// create object
				$this->categorySet[$i] = new watsCategoryHTML();
				// load object
				$this->categorySet[$i]->load( $var->catid );
				// increment counter
				$i ++;
			} // end check for viewing rite
		} //end  create category object
	}

	/**
	 * 
	 * @param database
	 */	
	function loadTicketSet( $lifecycle, &$watsUser )
	{
		// itterate through categories
		$numberOfCategories = count($this->categorySet);
		$i = 0;
		while ( $i < $numberOfCategories )
		{
			// check view rites
			$rite =  $watsUser->checkPermission( $this->categorySet[$i]->catid, "v" );
			if ( $rite == 2 )
			{
				// allow user to load all tickets
				$this->categorySet[$i]->loadTicketSet( $lifecycle, $watsUser->id, true );
			}
			else if ( $rite = 1 )
			{
				// allow user to load own tickets only
				$this->categorySet[$i]->loadTicketSet( $lifecycle, $watsUser->id );
			}
			// increment counter
			$i ++;
		} // end itterate through categories
	}
}

?>