<?php
/**
 * @version $Id: waticketsystem.html.php 193 2009-11-27 13:55:33Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL
 * @package wats
 */

// Don't allow direct linking
defined('_JEXEC') or die('Restricted Access');

// include classes
require_once(JPATH_COMPONENT_SITE . DS . "waticketsystem.class.php");
//require_once("components/com_waticketsystem/waticketsystem.class.php");

/**
 * @version 1.0
 * @created 06-Dec-2005 21:42:51
 */
class watsUserHTML extends watsUser
{
	/**
	 *
	 * @param watsId
	 */
	 function watsUserHTML() {
	 	$this->__construct();
	 }

	/**
	 *
	 * @param watsId
	 */
	function view()
	{
		echo "<div class=\"watsUserView\">
				<table>
				  <tr>";
		// add image
		if ( $this->image != null )
		{
			echo "  <td>
			        <img border=\"0\" src=\"".$this->image."\" alt=\"".JText::_('WATS_USER')."\"/>
					</td>";
		} // end add image
		echo "		<td>".JText::_("WATS_USER_USERNAME").": ".$this->username."<br />"
						 .JText::_("WATS_USER_GROUP").": ".$this->groupName."<br />"
						 .JText::_("WATS_USER_ORG").": ".$this->organisation."
			        </td>
				  </tr>
				</table>
			  </div>";
	}
	
	/**
	 *
	 * @param watsId
	 */
	function viewEdit()
	{
		global $Itemid;
		echo "<div class=\"watsUserEditView\">
				<table>
				  <tr>";
		// add image
		if ( $this->image != null )
		{
			echo "  <td>
			        <img border=\"0\" src=\"".$this->image."\" alt=\"".JText::_('WATS_USER')."\"/>
					</td>";
		} // end add image
		echo "		<td>".JText::_("WATS_USER_USERNAME").": ".$this->username."
			        </td>
				  </tr>
				</table>
				<div id=\"watsUserEdit\">
				<form name=\"watsUserMake\" method=\"post\" action=\"index.php?Itemid=".$Itemid."&act=user&task=editComplete\">
				<input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
				<input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
				<input name=\"act\" type=\"hidden\" value=\"user\" />
				<input name=\"task\" type=\"hidden\" value=\"editComplete\" />
				<input name=\"userid\" type=\"hidden\" value=\"".$this->id."\" />
			      <p>
				    <span class=\"watsHeading2\">".JText::_("WATS_USER_GROUP")."</span>
					<select name=\"grpId\" size=\"10\">";
		// groups
		$db =& JFactory::getDBO();
		
		$db->setQuery("SELECT " . WDBHelper::nameQuote("g.grpid") . ", " .
		                          WDBHelper::nameQuote("g.name") . " " .
					  "FROM " . WDBHelper::nameQuote("#__wats_groups") . " AS " . WDBHelper::nameQuote("g") . " " .
					  "ORDER BY " . WDBHelper::nameQuote("g.name") . " /* watsUserHTML::viewEdit() */ " );
		$groups = $db->loadObjectList();
		$noOfGroups = count( $groups );
		$i = 0;
		while ( $i < $noOfGroups )
		{
			if ( $groups[$i]->grpid == $this->group )
			{
				// cuurent group
				 echo "<option value=\"".$groups[$i]->grpid."\ selected=\"selected\">".$groups[$i]->name."</option>";
			}
			else
			{
				// other groups
				echo "<option value=\"".$groups[$i]->grpid."\">".$groups[$i]->name."</option>";
			}
			$i ++;
		}
		echo "    </select>
					<br />
				    <span class=\"watsHeading2\">".JText::_("WATS_USER_ORG")."</span>
					<input name=\"organisation\" type=\"text\" maxlength=\"255\" value=\"".$this->organisation."\" />
				  </p>
				  <p>
					<input type=\"submit\" name=\"watsTicketReopen\" value=\"".JText::_("WATS_USER_EDIT")."\" class=\"watsFormSubmit\" />
				  </p>
				 </form>
				</div>
			  </div>";
	}
	
	/**
	 *
	 * @param watsId
	 */
	function viewDelete()
	{
		global $Itemid;
		echo "<div id=\"watsUserView\">
				<div id=\"watsUserDelete\">
				<form name=\"watsUserDelete\" method=\"post\" action=\"index.php?Itemid=".$Itemid."&act=user&task=delete\">
				<input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
				<input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
				<input name=\"act\" type=\"hidden\" value=\"user\" />
				<input name=\"task\" type=\"hidden\" value=\"delete\" />
				<input name=\"userid\" type=\"hidden\" value=\"".$this->id."\" />
			      <p>
				    ".JText::_("WATS_USER_DELETE_REC")."<br />
					<input name=\"remove\" type=\"radio\" value=\"removetickets\" checked=\"checked\" />
				  </p>
				  <p>
					".JText::_("WATS_USER_DELETE_NOTREC")."<br />
					<input name=\"remove\" type=\"radio\" value=\"removeposts\" />
				  </p>
				  <p>
					<input type=\"submit\" name=\"watsTicketReopen\" value=\"".JText::_("WATS_USER_DELETE")."\" class=\"watsFormSubmit\" />
				  </p>
				 </form>
				</div>
			  </div>";
	}

	/**
	 *
	 * @param watsId
	 */
	function viewSimple()
	{
		echo $this->username."<br />".$this->groupName." - ".$this->organisation;
	}
	
	/**
	 * static
	 */
	 function makeButton() {
	 	global $Itemid;
	 	echo "<form name=\"watsUserMake\" method=\"get\" action=\"index.php\">
				<input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
				<input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
				<input name=\"act\" type=\"hidden\" value=\"user\" />
				<input name=\"task\" type=\"hidden\" value=\"make\" />
				<input type=\"submit\" name=\"watsAddUser\" value=\"".JText::_("WATS_USER_ADD")."\" class=\"watsFormSubmit\" />
			  </form>";
	 }
	 
	/**
	 * static
	 * @param database
	 */
	 function makeForm() {
	 	global $Itemid;
		
		$wats =& WFactory::getConfig();
		$database =& JFactory::getDBO();
		
	 	echo "<div id=\"watsReply\" class=\"watsReply\">
		      <form name=\"watsUserMake\" method=\"get\" action=\"index.php\" onsubmit=\"return watsValidateNewUser( this, document.getElementById('user'), '".JText::_("WATS_ERROR_NODATA")."' );\">
				<input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
				<input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
				<input name=\"act\" type=\"hidden\" value=\"user\" />
				<input name=\"task\" type=\"hidden\" value=\"makeComplete\" />
				<p>
				  <span class=\"watsHeading2\">".JText::_("WATS_USER_SELECT")."</span>
				  <select name=\"user[]\" size=\"10\" multiple=\"multiple\" id=\"user\">";
		// potential users
		$database->setQuery("SELECT " . WDBHelper::nameQuote("u.username") . ", " .
		                                WDBHelper::nameQuote("u.id") . ", " .
										WDBHelper::nameQuote("u.name") . " " .
							"FROM " . WDBHelper::nameQuote("#__users") . " AS " . WDBHelper::nameQuote("u") . " " .
							"LEFT OUTER JOIN " . WDBHelper::nameQuote("#__wats_users") . " AS " . WDBHelper::nameQuote("wu") . " ON " . WDBHelper::nameQuote("u.id") . " = " . WDBHelper::nameQuote("wu.watsid") . " " .
							"WHERE " . WDBHelper::nameQuote("wu.watsid") . " IS NULL /* watsUserHTML::makeForm() */" );
		$users = $database->loadObjectList();
		$noOfNullUsers = count( $users );
		$i = 0;
		while ( $i < $noOfNullUsers )
		{
			echo "<option value=\"".$users[$i]->id."\">".$users[$i]->username." (".$users[$i]->name.")</option>";
			$i ++;
		}
		echo "    </select>
		        </p>
				<p>
				  <span class=\"watsHeading2\">".JText::_("WATS_GROUP_SELECT")."</span>
		          <select name=\"grpId\" size=\"10\">";
		// potential groups
		$database->setQuery("SELECT " . WDBHelper::nameQuote("g.grpid") . ", " .
		                                WDBHelper::nameQuote("g.name") . " " .
							"FROM " . WDBHelper::nameQuote("#__wats_groups") . " AS " . WDBHelper::nameQuote("g") . " " .
							"ORDER BY " . WDBHelper::nameQuote("g.name") . " /* watsUserHTML::makeForm() */ " );
		$groups = $database->loadObjectList();
		$noOfGroups = count( $groups );
		$i = 0;
		while ( $i < $noOfGroups )
		{
			echo "<option value=\"".$groups[$i]->grpid."\">".$groups[$i]->name."</option>";
			$i ++;
		}
		echo "    </select>
		        </p>
				<p>
					<span class=\"watsHeading2\">".JText::_("WATS_USER_ORG_SELECT")."</span>
					<input name=\"organisation\" type=\"text\" maxlength=\"255\" value=\"".$wats->get( 'dorganisation' )."\" />
				</p>
				<p>
		          <input type=\"submit\" name=\"watsAddUser\" value=\"".JText::_("WATS_USER_ADD")."\" class=\"watsFormSubmit\" />
				</p>
			  </form>
			  </div>";
	 }
}

/**
 * @version 1.0
 * @created 09-Jan-2006 15:54
 */
class watsUserSetHTML extends watsUserSet
{
	/**
	 * 
	 * @param finish
	 * @param start
	 */
	function view( $finish = -1, $start = 0 )
	{
		global $Itemid;
		
		$wats =& WFactory::getConfig();
		
		// header
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"watsUsersView\">
			    <tr>
				  <th scope=\"col\"><a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=user&page=1\">".JText::_("WATS_USER_SET")."</a></th>
			    </tr>
			    <tr>
				  <td>".JText::_("WATS_USER_SET_DESCRIPTION")."</td>
			    </tr>
			  </table>";
		// end header
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"watsUserSetView\">
				    <tr>
					  <th scope=\"col\" width=\"20\">&nbsp;</th>
					  <th scope=\"col\">".JText::_("WATS_USER_USERNAME")."</th>
					  <th scope=\"col\">".JText::_("WATS_USER_ORG")."</th>
					  <th scope=\"col\">".JText::_("WATS_USER_GROUP")."</th>
					  <th scope=\"col\">".JText::_("WATS_USER_EMAIL")."</th>
					  <th scope=\"col\" width=\"20\">&nbsp;</th>
				    </tr>";
		// if view all set finish to maximum
		if ( $finish == -1 )
		{
			$finish = $this->noOfUsers;
			$i = 0;
		} 
		else
		{
			// else check start and finish points are within userSet
			if ( $start > $this->noOfUsers )
			{
				// prevent viewing
				$finish = 0;
			}
			else if ( $finish > $this->noOfUsers )
			{
				// set finish to last user
				$finish = $this->noOfUsers;
			}
			// set start
			$i = $start;
		}
		// itterate through users
		while ( $i < $finish )
		{
			echo "<tr class=\"watsUserSetViewRow".($i % 2)."\">
					<td>
				      <img src=\"components/com_waticketsystem/images/".$wats->get( 'iconset' )."user1616.gif\" height=\"16\" width=\"16\" border=\"0\" alt=\"".JText::_('WATS_USER')."\">
			        </td>
					<td>";
			echo "<a href=\"index.php?option=com_waticketsystem&Itemid=".$Itemid."&act=user&task=edit&userid=".$this->userSet[$i]->id."\">".$this->userSet[$i]->username."</a><br />(".$this->userSet[$i]->name.")
			        </td>
					<td>".$this->userSet[$i]->organisation."</td>
					<td>".$this->userSet[$i]->groupName."</td>
					<td>".$this->userSet[$i]->email."</td>
					<td>";
			echo "</td>
				  </tr>";
			$i ++;
		} // end itterate through users
		echo "</table>";
	}

	/**
	 * @param database
	 */
	function pageNav( $usersPerPage, $currentPage = 0, $currentUsersPerPage = 0 )
	{
		global $Itemid;
		// determine users on page
		if ( $currentUsersPerPage == 0 )
		{
			$currentTicketsPerPage = $usersPerPage;
		}
		echo "<div class=\"watsPageNav\">";
		// check is valid to show
		if ( $currentUsersPerPage < $this->noOfUsers )
		{
			echo JText::_("WATS_TICKETS_PAGES").": ";
			// determine number of pages
			$numberOfPages = 0;
			$numberOfPages = intval( $this->noOfUsers / $usersPerPage );
			// check for remainder
			if ( ( $this->noOfUsers % $usersPerPage ) > 0 )
			{
				$numberOfPages ++;
			}
			// previous
			if ( $currentPage > 1 )
			{
				echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=user&page=".($currentPage - 1)."\">&lt;</a>";
			} // end previous
			// itterate through pages
			$i = 1;
			while ( $i <= $numberOfPages )
			{
				if ( $i != $currentPage)
				{
					echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=user&page=".$i."\">".$i."</a>";
				}
				else
				{
					echo " <span class=\"watsPageNavCurrentPage\">".$i."</span>";
				}
				$i ++;
			} // end itterate through pages
			// next
			if ( $currentPage < $numberOfPages )
			{
				echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=user&page=".($currentPage + 1)."\">&gt;</a>";
			} // end next
		}
		echo " </div>";
	}
	
}
 
 /**
 * @version 1.0
 * @created 06-Dec-2005 21:43:25
 */
class watsTicketHTML extends watsTicket
{

	/**
	 * 
	 * @param watsUser
	 */	
	function view( &$watsUser )
	{
		global $Itemid;
		
		$wats =& WFactory::getConfig();
		
		// update highlight setting
		$this->_highlightUpdate( $watsUser->id );
		// echo out
		echo "<div id=\"watsTicketView\" class=\"watsTicketView\">";
		echo "<span class=\"watsHeading1\">".$this->name."</span>";
		echo "<span class=\"watsTicketId\">".JText::_("WATS_TICKETS_ID").": WATS-".$this->ticketId."</span>";
		// itterate through messages
		$i = 0;
		while ( $i < $this->msgNumberOf )
		{
			// check if user already setup
			if ( $watsUser->id == $this->_msgList[$i]->watsId )
			{
				// use existing user
				$msgUser = $watsUser;
			}
			else
			{
				// create new user
				$msgUser = new watsUserHTML();
				$msgUser->loadWatsUser( $this->_msgList[$i]->watsId  );
			}
			// print message
			echo "<div class=\"watsMsgView\">";
			echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"watsMsgViewTable\">
				    <tr>
					  <th scope=\"col\">";
			$msgUser->viewSimple();
		 	echo "<br /><span class=\"watsDate\">".JHTML::_('date', $this->_msgList[$i]->datetime, $wats->get('date'))."</span></th>
				    </tr>
				    <tr>
					  <td>".$this->_msgList[$i]->msg."</td>
				    </tr>
				  </table>\n";
			echo "</div>";
			// end print message
			$i ++;
		} // end itterate through messages
		// check for lifeCycle status
		if ( $this->lifeCycle == 1 )
		{
			$riteR =  $watsUser->checkPermission( $this->category, "r" );
			$riteC =  $watsUser->checkPermission( $this->category, "c" );
			if ( ( ( $this->watsId == $watsUser->id AND $riteR > 0 ) OR ( $riteR == 2 ) ) )
			{ // reply rites
				echo "<div id=\"watsReply\" class=\"watsReply\">
					<form name=\"submitmsg\" method=\"post\" action=\"index.php?option=com_waticketsystem&Itemid=".$Itemid."&act=ticket&task=reply\" onsubmit=\"return watsValidateTicketReply( this, '".JText::_("WATS_ERROR_NODATA")."', '".$wats->get( 'defaultmsg' )."' );\">
					  <table border=\"0\" class=\"wats_form\" width=\"100%\">
						<tr> 
						  <td>".JText::_("WATS_TICKETS_REPLY")."</td>
						  <td>";

                // message box
				if ( $wats->get( 'msgbox' ) == "editor" ) {
					$editor =& JFactory::getEditor();
                } else {
                    $editor =& JFactory::getEditor("none");
                }
                echo $editor->display("msg",
                                      $wats->get('defaultmsg'),
                                      $wats->get('msgboxw')*8.5,
                                      $wats->get( 'msgboxh' )*18,
                                      45,
                                      5,
                                      false);
                // add JS
                $script = 'function watsValidateTicketReply(form, errorMessage, defaultMsg) {
                               var returnValue = true;
                               var message = '.$editor->getContent("msg").'

                               // check fields
                               if (trim(message) == "" || message == defaultMsg) {
                                   alert( errorMessage );
                                   form.msg.focus();
                                   returnValue = false;
                               } // end check fields
                               return returnValue;
                           }';
                $document =& JFactory::getDocument();
                //$document->addScriptDeclaration($script);
                $document->addCustomTag('<script type="text/javascript" defer="defer">'.$script.'</script>');
                // end message box

				echo "   </td>
						</tr>";
                // check for close  rites
				if ( ( ( $this->watsId == $watsUser->id AND $riteR > 0 ) OR ( $riteR == 2 ) ) AND ( ( $this->watsId == $watsUser->id AND $riteC > 0 ) OR ( $riteC == 2 ) ) )
				{ // reply and close
					echo "<tr> 
						  <td>" . JText::_("WATS_TICKETS_CLOSE") . "</td>
						  <td><input type=\"radio\" name=\"close\" value=\"0\" checked /> " . JText::_("NO") . "<input type=\"radio\" name=\"close\" value=\"1\" />" . JText::_("YES") . "
                          </td>
                        </tr>";
				} // end reply and close
                echo "  <tr> 
						  <td>&nbsp;</td>
						  <td>
							<input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
							<input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
							<input name=\"act\" type=\"hidden\" value=\"ticket\" />
							<input name=\"task\" type=\"hidden\" value=\"reply\" />
							<input name=\"ticketid\" type=\"hidden\" value=\"".$this->ticketId."\" />
							<input type=\"submit\" name=\"submit\" value=\"".JText::_("WATS_TICKETS_REPLY")."\" class=\"watsFormSubmit\" />";
				echo "        </td>
							</tr>
						  </table>
						</form>
					  </div>";
				echo ( $wats->get( 'msgbox' ) == "bbcode" AND $wats->get( 'msgboxt' ) == "1" ) ? JText::_("WATS_BB_HELP") : "";
			} // end reply rites
			
			// show assignment
			if ( $this->assignId != null )
			{
				echo "<span class=\"watsTicketAssign\">(".JText::_("WATS_TICKETS_ASSIGNEDTO")." ".$this->getAssignedUsername().")</span>";
			} // end show assignment
			// check for assign rites
			$riteA =  $watsUser->checkPermission( $this->category, "a" );
			if ( ( $this->assignId == $watsUser->id AND $riteA > 0 ) OR ( $riteA == 2 ) )
			{
				$this->viewAssignTo();
			} // end chck for assign rites
			// end create form
		} // end reply rites
		else if ( $this->lifeCycle == 2 )
		{ // reopen rites
			$rite =  $watsUser->checkPermission( $this->category, "o" );
			if ( ( $this->watsId == $watsUser->id AND $rite > 0 ) OR ( $rite == 2 ) )
			{ // reopen
				echo "<form name=\"watsTicketMake\" method=\"get\" action=\"index.php\">";
				echo "  <input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
					    <input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
					    <input name=\"act\" type=\"hidden\" value=\"ticket\" />
					    <input name=\"task\" type=\"hidden\" value=\"reopen\" />
						<input name=\"ticketid\" type=\"hidden\" value=\"".$this->ticketId."\" />
					    <input type=\"submit\" name=\"watsTicketReopen\" value=\"".JText::_("WATS_TICKETS_REOPEN")."\" class=\"watsFormSubmit\" />
					  </form>";
			} // end reopen
		} // end reopenrites
		// end check for lifeCycle status
		echo "</div>";
	}

	/**
	 * 
	 */
	function viewAssignTo( )
	{
		global $Itemid;
		
		$wats =& WFactory::getConfig();
	
		$assignees = watsCategory::getAssignee($this->category);
		// check for useres to assign to
		if ( $assignees != null )
		{
			echo "<div id=\"watsViewAssignTo\" class=\"watsViewAssignTo\">
					<form name=\"submitassign\" method=\"post\" action=\"index.php?option=com_waticketsystem&Itemid=".$Itemid."&act=assign&task=assignto\" onsubmit=\"return watsValidateTicketAssign( this, '".JText::_("WATS_ERROR_NODATA")."', '".$wats->get( 'defaultmsg' )."' );\">
					<input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
					<input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
					<input name=\"act\" type=\"hidden\" value=\"assign\" />
					<input name=\"task\" type=\"hidden\" value=\"assignto\" />
					<input name=\"ticketid\" type=\"hidden\" value=\"".$this->ticketId."\" />
					<select name=\"assignee\">";
			$assigneeCount = count( $assignees );
			$i = 0;
			while ( $i < $assigneeCount )
			{
				// check is not already assigned to
				if ( $assignees[$i]->watsid != $this->assignId )
				{
					echo "<option value=\"".$assignees[$i]->watsid."\">".$assignees[$i]->username."</option>";
				}
				$i ++;
			}
			echo "  </select>
					<input type=\"submit\" name=\"watsTicketAssignTo\" value=\"".JText::_("WATS_TICKETS_ASSIGN")."\" class=\"watsFormSubmit\" />
					</form>
				  </div>";
		} // end check for useres to assign to
	}

	/**
	 * 
	 */
	function make( &$categorySet, &$watsUser )
	{
		global $Itemid;
		
		$wats =& WFactory::getConfig();
		
		// header and ticket name
		echo "<span class=\"watsHeading1\">".JText::_("WATS_TICKETS_SUBMIT")."</span>
			  <div class=\"watsTicketMake\" id=\"watsTicketMake\">
			  <form name=\"submitticket\" method=\"post\" action=\"index.php?option=com_waticketsystem&Itemid=".$Itemid."&act=ticket&task=makeComplete\" onsubmit=\"return watsValidateTicketMake( this, '".JText::_("WATS_ERROR_NODATA")."', '".$wats->get( 'defaultmsg' )."' );\">"
			  .JText::_("WATS_TICKETS_NAME").
			  "<input name=\"ticketname\" type=\"text\" id=\"ticketname\" maxlength=\"255\" />";
		// itterate through categories
		echo JText::_("WATS_CATEGORY")."<select name=\"catid\" class=\"watsCategorySetSelect\">";
	    foreach( $categorySet->categorySet as $category )
		{
			if ( $watsUser->checkPermission( $category->catid, "m" ) > 0 )
			{
				// allow user to submit ticket to category ticket
				echo "<option value=\"".$category->catid."\">".$category->name."</option>\n";
			}
		}
		// end itterate through categories
		echo "</select>";
		// message box
		echo JText::_("WATS_TICKETS_DESC");
		// message box
        if ( $wats->get( 'msgbox' ) == "editor" ) {
            $editor =& JFactory::getEditor();
        } else {
            $editor =& JFactory::getEditor("none");
        }
        echo $editor->display("msg",
                              $wats->get('defaultmsg'),
                              $wats->get('msgboxw')*8.5,
                              $wats->get( 'msgboxh' )*18,
                              45,
                              5,
                              false);
        // add JS
        $script = 'function watsValidateTicketMake(form, errorMessage, defaultMsg) {
                       var returnValue = true;
                       var message = '.$editor->getContent("msg").';

                       // check fields
                       if ( trim( form.ticketname.value ) == "" ) {
                           returnValue = false;
                           form.ticketname.focus();
                           alert( errorMessage );  
                       } else if (trim(message) == "" || message == defaultMsg) {
                           alert( errorMessage );
                           returnValue = false;
                           form.msg.focus();
                       } // end check fields
                       return returnValue;
                   }';
        $document =& JFactory::getDocument();
        //$document->addScriptDeclaration($script);
        $document->addCustomTag('<script type="text/javascript" defer="defer">'.$script.'</script>');
        // end message box

		// submit button
		echo "<input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
			  <input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
			  <input name=\"act\" type=\"hidden\" value=\"ticket\" />
			  <input name=\"task\" type=\"hidden\" value=\"makeComplete\" />
			  <input type=\"submit\" name=\"Submit\" value=\"".JText::_("WATS_TICKETS_SUBMIT")."\" class=\"watsFormSubmit\" />
			  </form>
			  </div>";
		echo ( $wats->get( 'msgbox' ) == "bbcode" AND $wats->get( 'msgboxt' ) == "1" ) ? JText::_("WATS_BB_HELP") : "";
	}

	function reopen()
	{
		global $Itemid;
		
		$wats =& WFactory::getConfig();
		
		echo "<div id=\"watsReply\" class=\"watsReply\">
		      <form name=\"submitmsg\" method=\"post\" action=\"index.php?option=com_waticketsystem&Itemid=".$Itemid."&act=ticket&task=completeReopen&ticketid=".$this->ticketId."\" onsubmit=\"return watsValidateTicketReopen( this, '".JText::_("WATS_ERROR_NODATA")."', '".$wats->get( 'defaultmsg' )."' );\">
			  ".JText::_("WATS_TICKETS_REOPEN_REASON");
		// message box
        if ( $wats->get( 'msgbox' ) == "editor" ) {
            $editor =& JFactory::getEditor();
        } else {
            $editor =& JFactory::getEditor("none");
        }
        echo $editor->display("msg",
                              $wats->get('defaultmsg'),
                              $wats->get('msgboxw')*8.5,
                              $wats->get( 'msgboxh' )*18,
                              45,
                              5,
                              false);
        // add JS
        $script = 'function watsValidateTicketReopen( form, errorMessage, defaultMsg ) {
	returnValue = true;
    var message = '.$editor->getContent("msg").';
	// check fields
	if (trim(message) == "" || message == defaultMsg) {
		alert( errorMessage );
		form.msg.focus();
		returnValue = false;
	} // end check fields
	return returnValue;
}';
        $document =& JFactory::getDocument();
        //$document->addScriptDeclaration($script);
        $document->addCustomTag('<script type="text/javascript" defer="defer">'.$script.'</script>');
        // end message box
		echo "  <input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
			    <input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
			    <input name=\"act\" type=\"hidden\" value=\"ticket\" />
			    <input name=\"task\" type=\"hidden\" value=\"completeReopen\" />
			    <input name=\"ticketid\" type=\"hidden\" value=\"".$this->ticketId."\" />";
		echo "  <input type=\"submit\" name=\"watsTicketReopen\" value=\"".JText::_("WATS_TICKETS_REOPEN")."\" class=\"watsFormSubmit\" />
			  </form>
		      </div>";
	}
}

/**
 * @version 1.0
 * @created 06-Dec-2005 21:43:53
 */
class watsTicketSetHTML extends watsTicketSet
{
	/**
	 * 
	 * @param finish
	 * @param start
	 */
	function view( $finish, $start = 0 )
	{
		global $Itemid, $watsUser;
		
		$wats =& WFactory::getConfig();
		
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"watsTicketSetView\">
				    <tr>
					  <th scope=\"col\" width=\"20\">&nbsp;</th>
					  <th scope=\"col\">".JText::_("WATS_TICKETS_NAME")."</th>
					  <th scope=\"col\">".JText::_("WATS_TICKETS_POSTS")."</th>
					  <th scope=\"col\">".JText::_("WATS_TICKETS_DATETIME")."</th>
					  <th scope=\"col\" width=\"20\">&nbsp;</th>
				    </tr>";
		// if view all set finish to maximum
		if ( $finish == -1 )
		{
			$finish = count( $this->_ticketList );
			$i = 0;
		} 
		else
		{
			// else check start and finish points are within ticketSet
			if ( $start > count( $this->_ticketList ) )
			{
				// prevent viewing
				$finish = 0;
			}
			else if ( $finish > count( $this->_ticketList ) )
			{
				// set finish to last ticket
				$finish = count( $this->_ticketList );
			}
			// set start
			$i = $start;
		}
		// prepare previous link
		$returnUrl = base64_encode(JRoute::_("index.php".substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "?"))));
        
		// itterate through tickets
		while ( $i < $finish )
		{
			echo "<tr class=\"watsTicketSetViewRow".($i % 2)."\">
					<td>";
			// check if open
			if ( $this->_ticketList[$i]->lifeCycle == 1 )
			{
				echo "<img src=\"components/com_waticketsystem/images/".$wats->get( 'iconset' )."ticket1616.gif\" height=\"16\" width=\"16\" border=\"0\" alt=\"".JText::_('WATS_TICKET')."\">";
			}
			else
			{
				echo "&nbsp;";
			}// end check if open
			echo "  </td>
					<td>";
			// highlight
            if (($this->_ticketList[$i]->lastView) < ($this->_ticketList[$i]->lastMsg)) {
				echo "<span class=\"watsTicketHighlight\">".$wats->get( 'highlight' )."</span> ";
			}
			// end highlight
			echo "<a href=\"index.php?option=com_waticketsystem&Itemid=".$Itemid."&act=ticket&task=view&ticketid=".$this->_ticketList[$i]->ticketId."\">".$this->_ticketList[$i]->name."</a><br />".$this->_ticketList[$i]->username.": <span class=\"watsDate\">".JHTML::_('date', $this->_ticketList[$i]->datetime, $wats->get('dateshort'))."</span></td>
					<td>".$this->_ticketList[$i]->msgNumberOf."</td>
					<td><span class=\"watsDate\">" . JHTML::_('date', $this->_ticketList[$i]->lastMsg, $wats->get('date')) . "</span></td>
					<td>";
            $canDelete =  $watsUser->checkPermission($this->_ticketList[$i]->category, 'd');
			if (
                $this->_ticketList[$i]->lifeCycle != 1 && 
                (
                    $canDelete == 2 ||
                    (
                        $canDelete == 1 &&
                        $this->_ticketList[$i]->watsId == $watsUser->id
                    )
                )
                )
			{
				echo "<a href=\"" . JRoute::_("index.php?option=com_waticketsystem&act=ticket&task=delete&ticketid=".$this->_ticketList[$i]->ticketId."&returnUrl=".$returnUrl) . "\" onClick=\"return confirm( '".JText::_("WATS_MISC_DELETE_VERIFY")."' );\"><img src=\"components/com_waticketsystem/images/".$wats->get( 'iconset' )."delete1616.gif\" height=\"16\" width=\"16\" border=\"0\" alt=\"".JText::_('DELETE')."\"></a>";
            }
			echo "</td>
				  </tr>";
			$i ++;
		} // end itterate through tickets
		echo "</table>";
	}
}

/**
 * @version 1.0
 * @created 06-Dec-2005 21:44:17
 */
class watsCategoryHTML extends watsCategory {

	/**
	 * 
	 */
	function view( )
	{
		global $Itemid;
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"watsCategoryView\">
			    <tr>
				  <th colspan=\"2\" scope=\"col\"><a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=1&lifecycle=a\">".htmlspecialchars($this->name)."</a></th>
			    </tr>
			    <tr>
				  <td>";
		/*if ($this->image !== null)
		{
			echo "";
		}*/
		echo "</td>
				  <td>".htmlspecialchars($this->description)."</td>
			    </tr>
			  </table>";
	}

	/**
	 * 
	 */
	function viewTicketSet( $finish, $start )
	{
		// display category details
		$this->view();
		// display ticketSet
		if ( isset($this->ticketSet) )
		{
			$this->ticketSet->view( $finish, $start );
		}
		else
		{
			echo "error ticket set not loaded";
		}
	}

	/**
	 * 
	 */	
	function pageNav( $ticketsPerPage, $currentPage = 0, $currentTicketsPerPage = 0, &$watsUser )
	{
		global $Itemid;
		if ( $currentTicketsPerPage == 0 )
		{
			$currentTicketsPerPage = $ticketsPerPage;
		}
		echo "<div class=\"watsPageNav\">";
        
        // prepare lifecycle
        $lifecycle = JRequest::getVar('lifecycle', "a");
        
		// check is valid to show
		if ( $currentTicketsPerPage < $this->ticketSet->ticketNumberOf )
		{
			echo JText::_("WATS_TICKETS_PAGES").": ";
			$numberOfPages = 0;
			$numberOfPages = intval( $this->ticketSet->ticketNumberOf / $ticketsPerPage );
			if ( ( $this->ticketSet->ticketNumberOf % $ticketsPerPage ) > 0 )
			{
				$numberOfPages ++;
			}
			
			// previous
			if ( $currentPage > 1 )
			{
				echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=".($currentPage - 1)."&lifecycle=".$lifecycle."\">&lt;</a>";
			} // end previous
			// itterate through pages
			$i = 1;
			while ( $i <= $numberOfPages )
			{
				if ( $i != $currentPage)
				{
					echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=".$i."&lifecycle=".$lifecycle."\">".$i."</a>";
				}
				else
				{
					echo " <span class=\"watsPageNavCurrentPage\">".$i."</span>";
				}
				$i ++;
			} // end itterate through pages
			// next
			if ( $currentPage < $numberOfPages )
			{
				echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=".($currentPage + 1)."&lifecycle=".$lifecycle."\">&gt;</a>";
			} // end next
		}
		// view type
		// all
		// check current selection
		if ( $lifecycle == 'a' ) {
			echo " (<span class=\"watsSelectedPage\">".JText::_("WATS_TICKETS_STATE_ALL")."</span>";
		}
		else
		{
			echo " (<a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=1&lifecycle=a\">".JText::_("WATS_TICKETS_STATE_ALL")."</a>";
		} // end all
		// check for full rites
		$rite =  $watsUser->checkPermission( $this->catid, "v" );
		if ( $rite == 2 )
		{
			// check current selection
			if ( $lifecycle == 'p' ) {
				echo ", <span class=\"watsSelectedPage\">".JText::_("WATS_TICKETS_STATE_PERSONAL")."</span>";
			}
			else
			{
				echo ", <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=1&lifecycle=p\">".JText::_("WATS_TICKETS_STATE_PERSONAL")."</a>";
			}
		}
		//open
		// check current selection
		if ( $lifecycle == 1 ) {
			echo ", <span class=\"watsSelectedPage\">".JText::_("WATS_TICKETS_STATE_OPEN")."</span>";
		}
		else
		{
			echo ", <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=1&lifecycle=1\">".JText::_("WATS_TICKETS_STATE_OPEN")."</a>";
		} // end open
		// check for delete rites
		$rite =  $watsUser->checkPermission( $this->catid, "d" );
		if ( $rite > 0 )
		{
			// check current selection
			if ( $lifecycle == 2 ) {
				echo ", <span class=\"watsSelectedPage\">".JText::_("WATS_TICKETS_STATE_CLOSED")."</span>";
			}
			else
			{
				echo ", <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=1&lifecycle=2\">".JText::_("WATS_TICKETS_STATE_CLOSED")."</a>";
			}
		}
		// check for purge rites
		$rite =  $watsUser->checkPermission( $this->catid, "p" );
		if ( $rite > 0 )
		{
			// check current selection
			if ( $lifecycle == 3 ) {
				echo ", <span class=\"watsSelectedPage\">".JText::_("WATS_TICKETS_STATE_DEAD")."</span>";
			}
			else
			{
				echo ", <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=category&catid=$this->catid&page=1&lifecycle=3\">".JText::_("WATS_TICKETS_STATE_DEAD")."</a>";
			}
		}
		echo ")";
		// end view type
		echo " </div>";
	}

	function viewPurge()
	{
		global $Itemid;
		echo "<p>
				<form name=\"watsTicketMake\" method=\"get\" action=\"index.php\">
				  <input name=\"option\" type=\"hidden\" value=\"com_waticketsystem\" />
				  <input name=\"Itemid\" type=\"hidden\" value=\"".$Itemid."\" />
				  <input name=\"act\" type=\"hidden\" value=\"category\" />
				  <input name=\"task\" type=\"hidden\" value=\"purge\" />
				  <input name=\"catid\" type=\"hidden\" value=\"".$this->catid."\" />
				  <input name=\"lifecycle\" type=\"hidden\" value=\"a\" />
				  <input name=\"page\" type=\"hidden\" value=\"1\" />
				  <input type=\"submit\" name=\"watsTicketMake\" value=\"".JText::_("WATS_TICKETS_PURGE").$this->name."\" class=\"watsFormSubmit\" />
			    </form>
			  </p>";
	}
}

/**
 * @version 1.0
 * @created 12-Dec-2005 13:54:49
 */
class watsAssignHTML extends watsAssign
{
	/**
	 * 
	 */
	function view( )
	{
		global $Itemid;
		
		$wats =& WFactory::getConfig();
		
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"watsCategoryView\">
			    <tr>
				  <th colspan=\"2\" scope=\"col\"><a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=assign&task=view&page=1\">".$wats->get( 'assignname' )."</a></th>
			    </tr>
			    <tr>
				  <td>";
		if ( $wats->get( 'assignimage' ) !== null )
		{
			echo "";
		}
		echo "</td>
				  <td>".$wats->get( 'assigndescription' )."</td>
			    </tr>
			  </table>";
	}

	/**
	 * 
	 */
	function viewTicketSet( $finish, $start )
	{
		// display category details
		$this->view();
		// display ticketSet
		if ( isset($this->ticketSet) )
		{
			$this->ticketSet->view( $finish, $start );
		}
		else
		{
			echo "error ticket set not loaded";
		}
	}

	/**
	 * 
	 */	
	function pageNav( $ticketsPerPage, $currentPage = 0, $currentTicketsPerPage = 0 )
	{
		global $Itemid;
		if ( $currentTicketsPerPage == 0 )
		{
			$currentTicketsPerPage = $ticketsPerPage;
		}
		echo "<div class=\"watsPageNav\">";
		// check is valid to show
		if ( $currentTicketsPerPage < $this->ticketSet->ticketNumberOf )
		{
			echo JText::_("WATS_TICKETS_PAGES").": ";
			$numberOfPages = 0;
			$numberOfPages = intval( $this->ticketSet->ticketNumberOf / $ticketsPerPage );
			if ( ( $this->ticketSet->ticketNumberOf % $ticketsPerPage ) > 0 )
			{
				$numberOfPages ++;
			}
			// previous
			if ( $currentPage > 1 )
			{
				echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=assign&task=view&page=".($currentPage - 1)."\">&lt;</a>";
			} // end previous
			// itterate through pages
			$i = 1;
			while ( $i <= $numberOfPages )
			{
				if ( $i != $currentPage)
				{
					echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=assign&task=view&page=".$i."\">".$i."</a>";
				}
				else
				{
					echo " <span class=\"watsPageNavCurrentPage\">".$i."</span>";
				}
				$i ++;
			} // end itterate through pages
			// next
			if ( $currentPage < $numberOfPages )
			{
				echo " <a href=\"index.php?option=com_waticketsystem&Itemid=$Itemid&act=assign&task=view&page=".($currentPage + 1)."\">&gt;</a>";
			} // end next
		}
		echo " </div>";
	}
}

/**
 * @version 1.0
 * @created 06-Dec-2005 21:43:13
 */
class watsCategorySetHTML extends watsCategorySet
{

	/**
	 * 
	 */	
	function viewWithTicketSet( $finish, $start = 0, &$watsUser )
	{
		$wats =& WFactory::getConfig();
		
		foreach($this->categorySet as $category) {
            // onyl display a category if there are any tickets in the category
            if ($category->ticketSet->ticketNumberOf) {
                echo "<div class=\"watsCategoryViewWithTicketSet\" id=\"watsCategory".$category->catid."\">";
                $category->viewTicketSet( $finish, $start );
                $category->pageNav( $wats->get( 'ticketssub' ), 0, $wats->get( 'ticketsfront' ), $watsUser );
                echo "</div>";
            }
		}
	}


	/**
	 * Association: post12007
	 * @current current selected option: -1 = main page, null = no page
	 */		
	function select( $current )
	{
		global $Itemid;
		
		$wats =& WFactory::getConfig();
		
		echo "<select name=\"option\" id=\"watsCategorySetSelect\" class=\"watsCategorySetSelect\" onchange=\"MM_jumpMenu('parent',this,0)\">";
		echo ( $current == null ) ? "<option selected=\"selected\"> " : "" ;
		echo"<option value=\"index.php?option=com_waticketsystem&Itemid=".$Itemid."\">".$wats->get( 'name' )."</option>\n";
	    foreach( $this->categorySet as $category )
		{
		    echo "<option";
			echo ( $category->catid == $current ) ? " selected=\"selected\" " : "" ;
			echo " value=\"index.php?option=com_waticketsystem&Itemid=".$Itemid."&act=category&catid=".$category->catid."&page=1&lifecycle=a\">".htmlspecialchars($category->name)."</option>\n";
		}
		echo "</select>";
	}

}
?>