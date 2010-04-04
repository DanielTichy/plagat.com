<?php
/**
 * @version $Id: waticketsystem.php 180 2009-10-06 11:24:12Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL
 * @package wats
 */

// Don't allow direct linking
defined('_JEXEC') or die('Restricted Access');

echo '<div class="wats">';

//add custom classes and functions
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "classes" . DS . "config.php");
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "classes" . DS . "factory.php");
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "classes" . DS . "dbhelper.php");
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "classes" . DS . "tablehelper.php");
require_once(JPATH_COMPONENT_SITE . DS . "waticketsystem.html.php");

// get settings
$wats =& WFactory::getConfig();

// add css link if turned on
if ($wats->get( 'css' ) == 'enable') {
    $doc =& JFactory::getDocument();
    $doc->addStyleSheet('components/com_waticketsystem/wats.css');
}

// create watsUser
// check id is set and watsUser exists

prevArray( $_GET );
prevLink( $_GET );

$my =& JFactory::getUser();

if ( $my->id == 0 OR ( $watsUser = new watsUserHTML() AND $watsUser->loadWatsUser( $my->id  ) == false ))
{
	echo JText::_("WATS_ERROR_NOUSER");
}
else
{
	$GLOBALS["watsUser"] =& $watsUser;

	// check for agreement
	if ( $wats->get( 'agree' ) == 1 && $watsUser->agree == 0 && !JRequest::getVar('agree', false) )
	{
		// needs to sign agreement
		echo '<p>'.$wats->get( 'agreelw' ).'</p>';
		echo '<p><a href="index.php?option=com_content&task=view&id='.$wats->get( 'agreei' ).'">'.$wats->get( 'agreen' ).'</a></p>';
		echo '<p>'.$wats->get( 'agreela' ).'</p>';
		echo '<form name="agree" method="post" action="index.php?option=com_waticketsystem"><input type="submit" name="agree" value="'.$wats->get( 'agreeb' ).'"></form>';		
	}
	elseif ( JRequest::getVar('agree', false) !== false )
	{
		// user has agreed
		$watsUser->agree = 1;
		$watsUser->updateUser();
		// redirect
		watsredirect( "index.php?option=com_waticketsystem" );
	}// end check for agreement
	else
	{
		$watsUser->view();
		
		//check user exists and has agreed to contract
		
		// parse GET action
		$act = JRequest::getCmd("act", null);
		
		// add javaScript
		echo "<script language=\"javascript\" type=\"text/javascript\" src=\"components/com_waticketsystem/wats.js\"></script>";
		// create category set
		$watsCategorySet = new watsCategorySetHTML($watsUser);
		$GLOBALS["watsCategorySet"] =& $watsCategorySet;
		
		// create new navigation
		if ( $act != 'ticket' || $task != 'make' )
        {
			echo "<div id=\"watsNavigation\">
					<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					  <tr>
						<th>".JText::_("WATS_NAV_NEW")."</th>
						<th>".JText::_("WATS_NAV_CATEGORY")."</th>
						<th>".JText::_("WATS_NAV_TICKET")."</th>
					  </tr>
					  <tr>
						<td width=\"33%\">
						  <form name=\"watsTicketMake\" method=\"get\" action=\"index.php\">
						  <input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\">
						  <input name=\"act\" type=\"hidden\" value=\"ticket\">
						  <input name=\"task\" type=\"hidden\" value=\"make\">
						  <input type=\"submit\" name=\"watsTicketMake\" value=\"".JText::_("WATS_TICKETS_SUBMIT")."\" class=\"watsFormSubmit\">
					</form>
					</td>
					<td>";
			// dispaly navigation drop down menu
			if ( $act == '' )
			{
				$watsCategorySet->select( -1 );
			}
			else if ( $act == 'category' )
			{
				// send viewing category ID
				$watsCategorySet->select(JRequest::getInt('catid'));
			}
			else
			{
				// not viewing category
				$watsCategorySet->select( null );
			}
			echo       "</td>
						<td width=\"33%\">
							<form name=\"watsTicketMake\" method=\"get\" action=\"index.php\">
							  <input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\">
							  <input name=\"act\" type=\"hidden\" value=\"ticket\">
							  <input name=\"task\" type=\"hidden\" value=\"view\">
							  WATS-
							  <input name=\"ticketid\" type=\"text\" id=\"ticketid\" maxlength=\"255\" size=\"6\" />
							  <input type=\"submit\" name=\"watsTicketMake\" value=\"".JText::_("WATS_MISC_GO")."\" class=\"watsFormSubmit\">
							</form> 
						</td>
					  </tr>
					</table>";
			echo "</div>";
		}
	
		
		// perform selected operation
		watsOption( $task, $act );
	}

}
?>
<p class="watsCopyright"><?php echo $wats->get( 'copyright' )?></p>
</div>
<?php
function watsOption( $task, $act )
{
	global $watsUser, $Itemid, $watsCategorySet;
	
	$wats =& WFactory::getConfig();
	$db =& JFactory::getDBO();

	switch ($act) {
		/**
		 * ticket
		 */	
		case 'ticket':
			switch ($task) {
				/**
				 * ticket view
				 */	
				case 'view':
					// create ticket object
					$ticket = watsObjectBuilder::ticket(JRequest::getInt("ticketid"));
					// check there is a ticket
					if ( $ticket != null )
					{
						// check rites
						$rite =  $watsUser->checkPermission( $ticket->category, "v" );
						if ( ( $ticket->watsId == $watsUser->id AND $rite > 0 ) OR ( $rite == 2 ) )
						{
							// allow user to view ticket
							$ticket->loadMsgList();
							$ticket->view( $watsUser );
						}
						else
						{
							echo JText::_("WATS_ERROR_ACCESS");
						}
					}
					else
					{
						echo JText::_("WATS_ERROR_NOT_FOUND");
					} // end check rites
					break;
				/**
				 * ticket make
				 */	
				case 'make':
					watsTicketHTML::make( $watsCategorySet, $watsUser );
					break;
				/**
				 * ticket make complete
				 */	
				case 'makeComplete':
					// check rites
					$rite =  $watsUser->checkPermission( JRequest::getInt('catid'), 'm' );
					if ( $rite > 0 )
					{
						// allow user make ticket
						$createDatetime = JFactory::getDate();
                        $createDatetime = $createDatetime->toMySQL();
						$parsedMsg = parseMsg( JRequest::getString('msg', '', 'REQUEST', JREQUEST_ALLOWRAW) );
						$ticket = new watsTicketHTML(null, null, JRequest::getString('ticketname'), $watsUser->id, null, $createDatetime, 1, null, null, 1, JRequest::getInt('catid') );
						$ticket->_msgList[0] = new watsMsg( null, $parsedMsg, $watsUser->id, $createDatetime );
						$ticket->msgNumberOf ++;
						$ticket->save();
						
                        // trigger onTicketNew event
                        JPluginHelper::importPlugin('waticketsystem');
                        $app =& JFactory::getApplication();
                        $args = array(&$ticket);
                        $app->triggerEvent('onTicketNew', $args);
						
						// view new ticket
						watsredirect('index.php?option=com_waticketsystem&Itemid='.$Itemid.'&act=ticket&task=view&ticketid='.$ticket->ticketId );
					}
					else
					{
						// do not allow make ticket
						echo JText::_("WATS_ERROR_ACCESS");
					} // end check rites
					break;
				/**
				 * ticket deactivate
				 */	
				case 'delete':
					// find ticket to delete
					$ticket = watsObjectBuilder::ticket(JRequest::getInt('ticketid'));
					// check delete rite
					$rite =  $watsUser->checkPermission( $ticket->category, "d" );
					if ( (  $ticket->watsId == $watsUser->id AND $rite > 0 ) OR $rite == 2 )
					{
						$ticket->deactivate();
						// return to previous view
                        watsredirect( base64_decode(JRequest::getVar("returnUrl")) );
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					}
					break;
				/**
				 * ticket reply
				 */	
				case 'reply':
					// find ticket to reply to
					$ticket = watsObjectBuilder::ticket(JRequest::getInt('ticketid') );
					// check rite to view
					$rite =  $watsUser->checkPermission( $ticket->category, "v" );
					if ( ( $ticket->watsId == $watsUser->id AND $rite > 0 ) OR ( $rite == 2 ) )
					{
						// allow user to view ticket
						$ticket->loadMsgList();
						// check rites to reply
						$rite =  $watsUser->checkPermission( $ticket->category, "r" );
						if ( $rite == 2 OR ( $rite == 1 AND $ticket->watsId == $watsUser->id ) )
						{
							// allow user to reply
							$parsedMsg = parseMsg(JRequest::getString("msg", "", "REQUEST", JREQUEST_ALLOWRAW));

                            $updatedDatetime = JFactory::getDate();
                            $updatedDatetime = $updatedDatetime->toMySQL();
							$ticket->addMsg( $parsedMsg, $watsUser->id, $updatedDatetime );
							// check for close
							if ( JRequest::getInt('close', 0) == 1 )
							{
								// check rites to close
								$rite =  $watsUser->checkPermission( $ticket->category, "c" );
								if ( $rite == 2 OR ( $rite == 1 AND $ticket->watsId == $watsUser->id ) )
								{
									// close ticket
									$ticket->deactivate();
								}
								else
								{
									echo JText::_("WATS_ERROR_ACCESS");
								}// end check rites to close
							} // end check for close
							
                            // trigger onTicketReply event
                            JPluginHelper::importPlugin("waticketsystem");
                            $app =& JFactory::getApplication();
                            $args = array(&$ticket);
                            $app->triggerEvent("onTicketReply", $args);
                            
                            
							// return to ticket
							if ( function_exists( 'watsredirect' ) )
							{
							   watsredirect( "index.php?option=com_waticketsystem&Itemid=".$Itemid."&act=ticket&task=view&ticketid=".$ticket->ticketId );
							}
							else
							{
								$ticket->view( $watsUser );
							}
						} 
						else
						{
							echo JText::_("WATS_ERROR_ACCESS");
						} // end check rites to reply
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					} // end check rite to view
					break;
					
				/**
				 * ticket reopen
				 */	
				case 'reopen':
					// find ticket to reopen
					$ticket = watsObjectBuilder::ticket(JRequest::getInt('ticketid'));
					// check for reopen rites
					$rite =  $watsUser->checkPermission( $ticket->category, "o" );
					if ( ( $ticket->watsId == $watsUser->id AND $rite > 0 ) OR ( $rite == 2 ) )
					{
						$ticket->reopen();
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					}// end check for reopen rites
					break;
					
				/**
				 * ticket completeReopen
				 */	
				case 'completeReopen':
					// find ticket to reopen
					$ticket = watsObjectBuilder::ticket(JRequest::getInt('ticketid') );
					// check for reopen rites
					$rite =  $watsUser->checkPermission( $ticket->category, "o" );
					if ( ( $ticket->watsId == $watsUser->id AND $rite > 0 ) OR ( $rite == 2 ) )
					{
						// reactivate
						$parsedMsg = parseMsg(JRequest::getString("msg", "", "REQUEST", JREQUEST_ALLOWRAW));
						
						$ticket->reactivate();
						$ticket->addMsg( $parsedMsg, $watsUser->id, date( 'YmdHis' ) );
						$ticket->loadMsgList();
						$ticket->view( $watsUser );
						
                        // trigger onTicketReply event
                        JPluginHelper::importPlugin("waticketsystem");
                        $app =& JFactory::getApplication();
                        $args = array(&$ticket);
                        $app->triggerEvent("onTicketReopen", $args);
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					}// end check for reopen rites
					break;
				}
			break;
		/**
		 * category
		 */	
		case 'category':
			switch ($task) {
				/**
				 * purge dead tickets from category
				 */	
				case 'purge':
					// check for purge rite
					$rite =  $watsUser->checkPermission(JRequest::getInt('catid'), "p");
					if ( $rite == 2 )
					{
						// create category object
						$catPurge = new watsCategoryHTML();
						// load details
						$catPurge->load(JRequest::getInt('catid'));
						// load dead tickets
						$catPurge->loadTicketSet( 3, $watsUser->id, true );
						// purge dead tickets
						$catPurge->purge();
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					} // end check for purge rite
					//break;
				/**
				 * view category
				 */	
				default:
					//check rites
					$rite =  $watsUser->checkPermission( JRequest::getInt('catid'), "v" );
					if ( $rite > 0 )
					{
						// create category object
						$cat = new watsCategoryHTML();
						// load details
						$cat->load( JRequest::getInt('catid') );
						// get lifecycle
						$lifecycle = 0;
						if (JRequest::getCmd('lifecycle', null) != null) {
							if (in_array(JRequest::getCmd('lifecycle'), array("p", "a", "0", "1", "2", "3"))) {
								$lifecycle = JRequest::getCmd('lifecycle');
							}
						} // end get lifecycle
						// check for level of rites
						if ( $rite == 2 AND JRequest::getCmd('lifecycle') != 'p' )
						{
							// allow user to view category with ALL tickets
							$cat->loadTicketSet( $lifecycle, $watsUser->id, true );
						}
						else if ( $rite > 0  )
						{
							// allow user to view category with OWN tickets
							$cat->loadTicketSet( $lifecycle, $watsUser->id, false );
						}
						// end check for level of rites
						// view tickets
						
						$wats =& WFactory::getConfig();
						
						$start = (JRequest::getInt('page', 1) - 1 ) *  $wats->get( 'ticketssub' );
						$finish = $start + $wats->get( 'ticketssub' );
						$cat->pageNav( $wats->get( 'ticketssub' ), JRequest::getInt('page'), 0, $watsUser );
						$cat->viewTicketSet( $finish, $start );
						$cat->pageNav( $wats->get( 'ticketssub' ), JRequest::getInt('page'), 0, $watsUser );
						// check purge rites
						if ( JRequest::getInt('lifecycle', null) == 3 AND $watsUser->checkPermission(JRequest::getInt('catid', null), "p") == 2)
						{
							$cat->viewPurge();
						} // end check purge rites
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					} // end check rites
					break;
				}
			break;
		/**
		 * assign
		 */	
		case 'assign':
			switch ($task) {
				/**
				 * view assigned tickets
				 */	
				case 'view';
					// create assigned object
					$assignedTickets = new watsAssignHTML();
					// load tickets
					$assignedTickets->loadAssignedTicketSet( $watsUser->id );
					// view tickets
					$start = (JRequest::getInt('page', 1) - 1) *  $wats->get( 'ticketssub' );
					$finish = $start + $wats->get( 'ticketssub' );	
					$assignedTickets->viewTicketSet( $finish, $start );
					// display page navigation
					$assignedTickets->pageNav( $wats->get( 'ticketssub' ), JRequest::getInt('page', 1), $wats->get( 'ticketssub' ) );
					break;
				/**
				 * assign ticket to
				 */	
				case 'assignto':
					// create ticket object
					$ticket = watsObjectBuilder::ticket(JRequest::getInt('ticketid'));
					// check for assign rites
					$riteA =  $watsUser->checkPermission( $ticket->category, "a" );
					if ( ( $ticket->assignId == $watsUser->id AND $riteA > 0 ) OR ( $riteA == 2 ) )
					{
						$ticket->setAssignId( JRequest::getInt('assignee') );
					} // end check for assign rites
					// check rites
					$rite =  $watsUser->checkPermission( $ticket->category, "v" );
					if ( ( $ticket->watsId == $watsUser->id AND $rite > 0 ) OR ( $rite == 2 ) )
					{
						// allow user to view ticket
						$ticket->loadMsgList();
						$ticket->view( $watsUser );
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					} // end check rites
					break;
				}
			break;
		/**
		 * user
		 */	
		case 'user':
			switch ($task) {
				/**
				 * user edit
				 */	
				case 'edit';
					echo "<span class=\"watsHeading1\">".JText::_("WATS_USER_EDIT")."</span>";
					// check for view rites
					if ( $watsUser->checkUserPermission( 'v' ) )
					{
						$editUser = new watsUserHTML();
						$editUser->loadWatsUser( JRequest::getInt('userid') );
						// check for edit rites
						if ( $watsUser->checkUserPermission( 'e' ) == 2 )
						{
							$editUser->viewEdit();
							// check for delete rites
							if ( $watsUser->checkUserPermission( 'd' ) == 2 )
							{
								echo "<span class=\"watsHeading1\">".JText::_("WATS_USER_DELETE")."</span>";
								$editUser->viewDelete();
							}
						}
						else
						{
							$editUser->view();	
						} // end check for edit rites
					}
					// no rites
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					}
					break;
				/**
				 * user complete edit
				 */	
				case 'editComplete':
					echo "<span class=\"watsHeading1\">".JText::_("WATS_USER_EDIT")."</span>";
					// check for view rites
					if ( $watsUser->checkUserPermission( 'v' ) )
					{
						$editUser = new watsUserHTML();
						$editUser->loadWatsUser( JRequest::getInt('userid') );
						// check for edit rites
						if ( $watsUser->checkUserPermission( 'e' ) == 2 )
						{
							// complete edit user
							$editUser->organisation = JRequest::getString('organisation');
							$editUser->setGroup( JRequest::getInt('grpId') );
							$editUser->updateUser();
							$editUser->view();
							
						}
						else
						{
							$editUser->view();	
						} // end check for edit rites
					}
					// no rites
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					}
					break;
				/**
				 * user delete
				 */	
				case 'delete':
					if (JRequest::getVar('userid', false) AND JRequest::getVar('remove', false) )
					{
						// create user object
						$deleteUser = new watsUser();
						$deleteUser->load(JRequest::getInt('userid'));
						// delete user
						$deleteUser->delete(JRequest::getVar('remove'));
					}
					// return to home
					defaultAction( $watsCategorySet, $watsUser );
					break;
				/**
				 * user make
				 */	
				case 'make':
					// check for make user rites
					if ( $watsUser->checkUserPermission( 'm' ) == 2 )
					{
						echo "<span class=\"watsHeading1\">".JText::_("WATS_USER_ADD")."</span>";
						watsUserHTML::makeForm();
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					}
					break;
				/**
				 * user make complete
				 */	
				case 'makeComplete':
					// check for make user rites
					if ( $watsUser->checkUserPermission( 'm' ) == 2 )
					{
						// check for input
						if ( JRequest::getString('user') !== null &&
                             JRequest::getString('grpId') !== null &&
                             JRequest::getString('organisation') !== null )
						{
							// make users
							echo "<span class=\"watsHeading1\">".JText::_("WATS_USER_ADD")."</span>".JText::_("WATS_USER_ADD_LIST");
							$users = JRequest::getVar('user', array(), "REQUEST", "ARRAY");
                            $noOfNewUsers = count( $users );
                            $i = 0;
                            while ( $i < $noOfNewUsers )
							{
								// check for successful creation
								if ( watsUser::makeUser( intval($users[ $i ]), JRequest::getInt("grpId"), JRequest::getString('organisation') ) )
                                {
                                    // give visual confirmation
                                    $newUser = new watsUserHTML();
                                    $newUser->loadWatsUser(intval($users[ $i ]));
                                    $newUser->view();
                                }
								else
								{
									echo "<p>".intval($newUsers['user'][ $i ])." -> ".JText::_("WATS_ERROR")."</p>";
								} // check for successful creation
								$i ++;
							}
							// end make users
						}
						else
						{
							// display error
							echo "<span class=\"watsHeading1\">".JText::_("WATS_USER_ADD")."</span>";
							echo JText::_("WATS_ERROR_NODATA");
							watsUserHTML::makeForm();
							// end display error
						}
						// end check for input
					}
					else
					{
						echo JText::_("WATS_ERROR_ACCESS");
					}
					break;
				/**
				 * user page view
				 */	
				default:
					// check for all user view rites
					if ( $watsUser->checkUserPermission( 'v' ) == 2 )
					{
						// determine number of users to show
						$start = (JRequest::getInt("page", 1) - 1 ) * $wats->get( 'ticketssub' );
						$currentPage = JRequest::getInt('page');

						$finish = $start + $wats->get( 'ticketssub' );
						// make user set and load
						$watsUserSet = new watsUserSetHTML();
						$watsUserSet->load();
						// view user set
						$watsUserSet->view( $finish, $start );
						$watsUserSet->pageNav( $wats->get( 'ticketssub' ), $currentPage );
						// check for make user rites
						if ( $watsUser->checkUserPermission( 'm' ) == 2 )
						{
							watsUserHTML::makeButton();
						}
					}
					else
					{
					echo JText::_("WATS_ERROR_ACCESS");
					}
					break;
				}
			break;
		/**
		 * default
		 */	
		default:
			defaultAction( $watsCategorySet, $watsUser );
			break;
	}
}

function defaultAction( &$watsCategorySet, &$watsUser )
{
	global $Itemid;
	
	$wats =& WFactory::getConfig();
	$db =& JFactory::getDBO();
	
	// load tickets to categoryies
	$watsCategorySet->loadTicketSet( 0, $watsUser );
	// view tickets
	$watsCategorySet->viewWithTicketSet( $wats->get( 'ticketsfront' ), 0, $watsUser );
	// check for assigned tickets
	$assignedTickets = new watsAssignHTML();
	$assignedTickets->loadAssignedTicketSet( $watsUser->id );
	if ( count( $assignedTickets->ticketSet->_ticketList ) > 0 )
	{
		// view assigned tickets
		echo "<div id=\"watsAssignedTickets\">";
		$assignedTickets->viewTicketSet( $wats->get( 'ticketsfront' ), 0 );
		$assignedTickets->pageNav( $wats->get( 'ticketssub' ), 0, $wats->get( 'ticketsfront' ) );
		echo "</div>";
	}
	// check for all user view rites
	if ( $watsUser->checkUserPermission( 'v' ) == 2 )
	{
		// determine number of users to show
		$start = 0;
		$finish = $wats->get( 'ticketsfront' );
		// create user set and load
		$watsUserSet = new watsUserSetHTML();
		$watsUserSet->load();
		// view user set
		$watsUserSet->view( $finish, $start );
		$watsUserSet->pageNav( $wats->get( 'ticketssub' ), 0, $wats->get( 'ticketsfront' ) );
		// check for make user rites
		if ( $watsUser->checkUserPermission( 'm' ) == 2 )
		{
			watsUserHTML::makeButton();
		}
	}
}

/*
 * prev Array
 * generates an array of vales with added 'prev' in front of keys
 * @param array (normally $_GET)
 */
function prevArray( $oldArray )
{
	// create new array
	$newArray = array();
	// find keys
	$keys = array_keys( $oldArray );
	// loop through keys
	while ( $key = array_pop( $keys ) )
	{
		// add prev item to new array
		$newArray[ 'prev'.$key ] = $oldArray[ $key ];
	}
	return $newArray;
}

/*
 * prev Link
 * creates a get string based on getArray
 * @param array (usually prevArray)
 */
function prevLink( $getArray )
{
	// create get link
	$link = "prevLink=true";
	// find keys
	$keys = array_keys( $getArray );
	// loop through keys
	while ( $key = array_pop( $keys ) )
	{
		// check is previous
		if ( strncmp ( $key, 'prev', 4 ) === 0 )
		{
			//$newKey = substr( $key, 4 );
			$link = $link.'&'.$key.'='.JRequest::getVar($key, "", "REQUEST", "STRING", JREQUEST_ALLOWRAW | JREQUEST_NOTRIM);
			//$getArray[ $key ];
		}
	}
	return $link;
}

function parseMsg( $msg )
{
	$wats =& WFactory::getConfig();
	
	if ( $wats->get( 'msgbox' ) == 'editor' ) {
        // make safe
        $filter =& JFilterInput::getInstance(array(), array(), 1, 1, 1);
		$msg = $filter->clean($msg);
	} else if ( $wats->get( 'msgbox' ) == 'bbcode' ) {
		// include bbcode class
		include_once( 'components/com_waticketsystem/bbcode.inc.php' );
		// create bbcode instance
		$bbcode = new bbcode();
		// add tags
		$bbcode->add_tag(array('Name'=>'code','HtmlBegin'=>'<span style="font-family: Courier New, Courier, mono;">','HtmlEnd'=>'</span>'));
		$bbcode->add_tag(array('Name'=>'b','HtmlBegin'=>'<span style="font-weight: bold;">','HtmlEnd'=>'</span>'));
		$bbcode->add_tag(array('Name'=>'i','HtmlBegin'=>'<span style="font-style: italic;">','HtmlEnd'=>'</span>'));
		$bbcode->add_tag(array('Name'=>'u','HtmlBegin'=>'<span style="text-decoration: underline;">','HtmlEnd'=>'</span>'));
		$bbcode->add_tag(array('Name'=>'link','HasParam'=>true,'HtmlBegin'=>'<a href="%%P%%">','HtmlEnd'=>'</a>'));
		$bbcode->add_tag(array('Name'=>'color','HasParam'=>true,'ParamRegex'=>'[A-Za-z0-9#]+','HtmlBegin'=>'<span style="color: %%P%%;">','HtmlEnd'=>'</span>','ParamRegexReplace'=>array('/^[A-Fa-f0-9]{6}$/'=>'#$0')));
		$bbcode->add_tag(array('Name'=>'email','HasParam'=>true,'HtmlBegin'=>'<a href="mailto:%%P%%">','HtmlEnd'=>'</a>'));
		$bbcode->add_tag(array('Name'=>'size','HasParam'=>true,'HtmlBegin'=>'<span style="font-size: %%P%%pt;">','HtmlEnd'=>'</span>','ParamRegex'=>'[0-9]+'));
		$bbcode->add_alias('url','link');
		// parse message into bbcode
		$msg = strip_tags( $msg, '<code>');
		$msg = htmlspecialchars( $msg );
		$msg = $bbcode->parse_bbcode( $msg );
		$msg = nl2br( $msg );
	} else {
		$msg = htmlspecialchars($msg, ENT_QUOTES, "UTF-8");
		$msg = nl2br($msg);
	}
	// return parsed message
	return $msg;
}

function watsredirect( $dest )
{
	global $mainframe;
	
	$wats =& WFactory::getConfig();
	
	if ( $wats->get( 'debug' ) == 0 )
	{
		$mainframe->redirect( $dest );
	}
	else
	{
		echo "<a href=\"".$dest."\">".$wats->get( 'debugmessage' )."</a>";
	}
}
?>