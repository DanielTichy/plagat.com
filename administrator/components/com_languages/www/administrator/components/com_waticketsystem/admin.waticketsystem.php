<?php
/**
 * @version $Id: admin.waticketsystem.php 201 2009-11-29 08:37:36Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL, see LICENSE.php
 * @package wats
 */

// Don't allow direct linking
defined('_JEXEC') or die('Restricted Access');

echo "<script language=\"javascript\" type=\"text/javascript\" src=\"components/com_waticketsystem/admin.wats.js\"></script>";
echo '<div class="wats">';

//add custom classes and functions
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "classes" . DS . "config.php");
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "classes" . DS . "dbhelper.php");
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "classes" . DS . "factory.php");
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . "admin.waticketsystem.html.php");

// add javaScript
$document =& JFactory::getDocument();
$document->addScript("../components/com_waticketsystem/wats.js");

// add CSS
$document->addStyleDeclaration(".icon-48-wats { background-image:url(components/com_waticketsystem/images/icon-48-watshead.png );}");

// set heading
JToolBarHelper::title("Webamoeba Ticket System", "wats");

// get settings
$wats = WFactory::getConfig();

$act = JRequest::getCmd("act");
require_once("toolbar.waticketsystem.php");

// perform selected operation
watsOption($task, $act);
	
?> 
</div> 
<?php
function watsOption( &$task, &$act )
{
	global $wats, $option, $mainframe;

	switch ($act) {
		/**
		 * ticket
		 */	
		case 'ticket':
			JToolbarHelper::title("Ticket Viewer", "wats");
			echo "<form action=\"index.php\" method=\"post\" name=\"adminForm\">";
			switch ($task) {
				/**
				 * view
				 */	
				case 'view':
					$ticket = watsObjectBuilder::ticket(JRequest::getInt('ticketid'));
					$ticket->loadMsgList();
					$ticket->view( );
					break;
				default:
                    $limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
                    $limitstart	= $mainframe->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

                    // In case limit has been changed, adjust limitstart accordingly
                    $limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );
                    
					$ticketSet = new watsTicketSetHTML();
					$ticketSet->loadTicketSet( -1 );
					$ticketSet->view( $limit, $limitstart );
					// key
					echo "<p><img src=\"images/tick.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"Open\" /> = Open <img src=\"images/publish_x.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"Closed\" /> = Closed <img src=\"images/checked_out.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"Closed\" /> = Dead</p>";
					break;
			}
			echo "</form>";
			break;
		/**
		 * category
		 */	
		case 'category':
			JToolbarHelper::title("Category Manager", "wats");
			echo "<form action=\"index.php\" method=\"post\" name=\"adminForm\">";
			switch ($task) {
				/**
				 * view
				 */	
				case 'view':
					$category = new watsCategoryHTML();
					$category->load(JRequest::getInt('catid'));
					echo "<table width=\"100%\">
							<tr>
							  <td width=\"60%\" valign=\"top\">";
					$category->viewEdit();
					echo "	  </td>
							  <td valign=\"top\">";
					$category->viewDelete();
					echo "	  </td>
							</tr>
						  </table>";
					break;
				/**
				 * view
				 */	
				case 'apply':
					// check input
					if ( JRequest::getInt('catid', false) &&
                         (JRequest::getString('name') !== null) &&
                         (JRequest::getString('description') !== null) &&
                         (JRequest::getString('image') !== null) &&
                         (JRequest::getString('remove') !== null) )
					{
						if ( strlen(JRequest::getString('name')) &&
							 strlen(JRequest::getString('description')))
						{
							// check is numeric
							if ( JRequest::getInt('catid') )
							{
								// create category
								$editCategory = new watsCategory();
								$editCategory->load( JRequest::getInt("catid") );
								// check if deleting
								if ( JRequest::getString('remove') == 'removetickets' )
								{
									// delete category
									$editCategory->delete( );
									watsredirect( "index.php?option=com_waticketsystem&act=category", "Category Removed" );
								}
								else
								{
									// update name
									$editCategory->name = JRequest::getString('name');
									// update description
									$editCategory->description = JRequest::getString('description');
									// update image
									$editCategory->image = JRequest::getString('image');
                                    // update emails
									$editCategory->emails = JRequest::getString('emails');
									// save changes
									$editCategory->updateCategory();
									// success
									watsredirect( "index.php?option=com_waticketsystem&act=category", "Category Updated" );
								}
								break;
							}
							// end check is numeric
						} else {
							watsredirect( "index.php?option=com_waticketsystem&act=category&task=new", "Please fill in the form correctly" );
						}
					}
					// end check input
					// redirect input error
					watsredirect( "index.php?option=com_waticketsystem&act=category", "Error updating category" );
					break;
				/**
				 * new
				 */	
				case 'save':
					// save new category
					// check for input;
                    if ( strlen(JRequest::getString('name')) &&
                         strlen(JRequest::getString('description')))
					{
						// check input length
						if ( strlen( JRequest::getString('name') ) > 0 && strlen( JRequest::getString('description') ) > 0 )
						{
							// parse input
							$name = JRequest::getString('name');
							$description = JRequest::getString('description');
							$image = JRequest::getString('image');
                            $emails = JRequest::getString('emails');
							if ( watsCategory::newCategory($name, $description, $image, $emails) )
							{
								// success
								watsredirect( "index.php?option=com_waticketsystem&act=category", "Category Added" );
							}
							else
							{
								// already exists
								watsredirect( "index.php?option=com_waticketsystem&act=category&task=new&", "The specified name already exists" );
							}
						}
					}
					else
					{
						watsredirect( "index.php?option=com_waticketsystem&act=category&task=new", "Please fill in the form correctly" );
					}
					break;
				/**
				 * new
				 */	
				case 'add':
					watsCategoryHTML::newForm();
					break;
				default:
					$limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
                    $limitstart	= $mainframe->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

                    // In case limit has been changed, adjust limitstart accordingly
                    $limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );  
                    
					$categorySet = new watsCategorySetHTML();
					$categorySet->view( $limit, $limitstart );
					
					break;
			}
			echo "<input type=\"hidden\" name=\"task\" value=\"\" /><input type=\"hidden\" name=\"option\" value=\"com_waticketsystem\" /><input type=\"hidden\" name=\"act\" value=\"category\" /></form>";
			break;
		/**
		 * CSS
		 */	
		case 'css':
			JToolbarHelper::title("CSS", "wats");
			echo "<form action=\"index.php\" method=\"post\" name=\"adminForm\">";
			$watsCss = new watsCssHTML();
			$watsCss->open('../components/com_waticketsystem/wats.css');

			switch ($task) {
				/**
				 * apply
				 */	
				case 'apply':
					// check if is restoring
					if ( JRequest::getString('restore') == 'restore' )
					{
						// restore css
						if ( $watsCss->restore( '../components/com_waticketsystem/wats.restore.css' ) )
						{
							// redirect success
							watsredirect( "index.php?option=com_waticketsystem&act=css", "CSS Restored" );
						}
						else
						{
							// redirect failure
							watsredirect( "index.php?option=com_waticketsystem&act=css", "CSS Restore Failed" );
						}
					}
					else
					{
						// save changes
						$watsCss->processSettings();
						$watsCss->save();
						// redirect
						watsredirect( "index.php?option=com_waticketsystem&act=css", "Changes Saved" );
					}
					break;
				/**
				 * cancel
				 */	
				case 'cancel':
					watsredirect( "index.php?option=com_waticketsystem" );
					break;
				/**
				 * backup
				 */	
				case 'backup':
					// open window
					echo "<script>popup = window.open ('../components/com_waticketsystem/wats.css','watsCSS','resizable=yes,scrollbars=1,width=500,height=500');</script>";
				/**
				 * default
				 */	
				default:
					// start Tab Pane
					{
						echo JHTML::_("behavior.mootools");
						
						
						// table
						echo "<table width=\"100%\">
								<tr>
								  <td width=\"60%\" valign=\"top\">";
						echo "<table class=\"adminform\">
									<tr>
										<th>
											Edit CSS
										</th>
									</tr>
									<tr>
										<td>";
										$watsCss->editSettings();
						if ( $watsCss->css == "enable" )
						{
							// prepare tabs
							jimport("joomla.html.pane");
							$cssTabs = JPane::getInstance("tabs");
							$cssTabs->startPane('cssTabs');
							// fill tabs
							{
								// general
								$cssTabs->startPanel( 'General', 'cssTabs' );
								$watsCss->editGeneral();
								$cssTabs->endPanel();
								// navigation
								$cssTabs->startPanel( 'Navigation', 'cssTabs' );
								$watsCss->editNavigation();
								$cssTabs->endPanel();
								// categories
								$cssTabs->startPanel( 'Categories', 'cssTabs' );
								$watsCss->editCategories();
								$cssTabs->endPanel();
								// tickets
								$cssTabs->startPanel( 'Tickets', 'cssTabs' );
								$watsCss->editTickets();
								$cssTabs->endPanel();
								// assigned tickets
								$cssTabs->startPanel( 'Assigned', 'cssTabs' );
								$watsCss->editAssignedTickets();
								$cssTabs->endPanel();
								// users
								$cssTabs->startPanel( 'Users', 'cssTabs' );
								$watsCss->editUsers();
								$cssTabs->endPanel();
							}
							// end fill tabs
							$cssTabs->endPane();
						}
						echo "      	</td>
									</tr>
								</table>
						          </td>
								  <td valign=\"top\">";
						$watsCss->viewRestore();
						echo "	  </td>
								</tr>
						  </table>";
					}
					// end tab pane
					break;
			}
			echo "<input type=\"hidden\" name=\"option\" value=\"com_waticketsystem\" /><input type=\"hidden\" name=\"act\" value=\"css\" /><input type=\"hidden\" name=\"task\" value=\"\" /></form>";
			break;
		/**
		 * rites
		 */	
		case 'rites':
			JToolbarHelper::title("Rights Manager", "wats");
			echo "<form action=\"index.php\" method=\"post\" name=\"adminForm\">";
			switch ($task) {
				/**
				 * new
				 */	
				case 'add':
					watsUserGroupHTML::newForm();
					break;
				/**
				 * save
				 */	
				case 'save':
					// save new group
					// check for input;
					if ( (JRequest::getString('name') !== null) && (JRequest::getString('image') !== null) )
					{
						// check input is valid
						if ( strlen( JRequest::getString('name') ) !== 0 )
						{
							// create new group
							$newCategory = watsUserGroup::makeGroup( htmlspecialchars( JRequest::getString('name') ), htmlspecialchars( JRequest::getString('image') ) );
							// redirect
							watsredirect( "index.php?option=com_waticketsystem&act=rites&task=view&groupid=".$newCategory->grpid );
						}
						else
						{
							watsredirect( "index.php?option=com_waticketsystem&act=rites&task=new", "Please fill in the form correctly" );
						}
					}
					else
					{
						// redirect to add
						watsredirect( "index.php?option=com_waticketsystem&act=rites&task=new", "Form Contents not recognised" );
						// end display error
					}
					// end check for input
					break;
				/**
				 * view
				 */	
				case 'view':
					echo "<input type=\"hidden\" name=\"groupid\" value=\"".JRequest::getInt('groupid')."\" />";
					$userGroup = new watsUserGroupHTML( JRequest::getInt("groupid") );
					
					echo "<table width=\"100%\">
							<tr>
							  <td width=\"60%\" valign=\"top\">";
					$userGroup->viewEdit();
					echo "	  </td>
							  <td valign=\"top\">";
					$userGroup->viewDelete();
					echo "	  </td>
							</tr>
						  </table>";
					break;
				/**
				 * apply
				 */	
				case 'apply':
					$userGroup = new watsUserGroupHTML( JRequest::getInt("groupid") );
					
					// check if deleting
					if ( JRequest::getString('remove') == 'remove' || JRequest::getString('remove') == 'removetickets' || JRequest::getString('remove') == 'removeposts' )
					{
						// delete group
						$userGroup->delete( JRequest::getString('remove') );
                        watsredirect("index.php?option=com_waticketsystem&act=rites", "Group Updated" );
					}
					else
					{
						// process form
						$userGroup->processForm();
						$userGroup->save();
						// redirect on completion
						watsredirect( "index.php?option=com_waticketsystem&act=rites", "Group Updated" );
					}
					break;
				default:
					$limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
                    $limitstart	= $mainframe->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

                    // In case limit has been changed, adjust limitstart accordingly
                    $limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );
                    
					$userGroupSet = new watsUserGroupSetHTML();
					$userGroupSet->loadUserGroupSet();
					$userGroupSet->view( $limitstart, $limit );
					break;
			}
			echo "<input type=\"hidden\" name=\"task\" value=\"\" /><input type=\"hidden\" name=\"option\" value=\"com_waticketsystem\" /><input type=\"hidden\" name=\"act\" value=\"rites\" /></form>";
			break;
		/**
		 * user
		 */	
		case 'user':
			JToolbarHelper::title("User Manager", "wats");
			echo "<form action=\"index.php\" method=\"post\" name=\"adminForm\">";
			switch ($task) {
				/**
				 * edit
				 */	
				case 'edit':
					$editUser = new watsUserHTML();
					$editUser->loadWatsUser( JRequest::getInt("userid") );
					echo "<table width=\"100%\">
							<tr>
							  <td width=\"60%\" valign=\"top\">";
					$editUser->viewEdit();
					echo "	  </td>
							  <td valign=\"top\">";
					$editUser->viewDelete();
					echo "	  </td>
							</tr>
						  </table>";
					break;
				/**
				 * new
				 */	
				case 'add':
					watsUserHTML::newForm();
					break;
				/**
				 * apply
				 */	
				case 'apply':
					// check input
					if ( JRequest::getInt('userid') !== null &&
                         JRequest::getString('grpId') !== null &&
                         JRequest::getString('organisation') !== null &&
                         JRequest::getString('remove') !== null )
					{
						// check is numeric
						if ( is_numeric( JRequest::getInt('userid') ) )
						{
							// create user
							$editUser = new watsUserHTML();
							$editUser->loadWatsUser( JRequest::getInt("userid") );
							// check if deleting
							if ( JRequest::getCmd('remove') == 'removetickets' || JRequest::getCmd('remove') == 'removeposts' )
							{
								// delete user
								$editUser->delete( JRequest::getCmd('remove') );
								watsredirect( "index.php?option=com_waticketsystem&act=user", "User Removed" );
							}
							else
							{
								// check is numeric
								if ( is_numeric( JRequest::getInt('grpId') ) )
								{
									$editUser->group = JRequest::getInt("grpId");
								}
								// update organistation
								$editUser->organisation = htmlspecialchars( addslashes( JRequest::getString('organisation') ) );
								// save changes
								if ( $editUser->updateUser() )
								{
									// success
									watsredirect( "index.php?option=com_waticketsystem&act=user", "User Updated" );
								}
								else
								{
									// failure
									watsredirect( "index.php?option=com_waticketsystem&act=user", "Update failed, user not found" );
								}
							}
						}
						// end check is numeric
					}
					else
					{
						// redirect input error
						watsredirect( "index.php?option=com_waticketsystem&act=user", "Error updating user" );
					}// end check input
					break;
				/**
				 * save
				 */	
				case 'save':
					// save new users
					// check for input;
					if ( JRequest::getString('user') !== null &&
                         JRequest::getString('grpId') !== null &&
                         JRequest::getString('organisation') !== null )
					{
						// make users
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
							$i ++;
						}
						// end make users
						// redirect to list on completion
						watsredirect( "index.php?option=com_waticketsystem&act=user", "Users Added" );
					}
					else
					{
						// redirect to add
						watsredirect( "index.php?option=com_waticketsystem&act=user&task=new", "Please fill in the form correctly" );
						// end display error
					}
					// end check for input
					break;
				/**
				 * default
				 */	
				default:
					// get limits
                    $limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
                    $limitstart	= $mainframe->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

                    // In case limit has been changed, adjust limitstart accordingly
                    $limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );          
                    
					$watsUserSet = new watsUserSetHTML();
					$watsUserSet->load();
					$watsUserSet->view( $limitstart, $limit );
					break;
			}
			echo "<input type=\"hidden\" name=\"act\" value=\"user\" /><input type=\"hidden\" name=\"option\" value=\"com_waticketsystem\" /><input type=\"hidden\" name=\"task\" value=\"\" /></form>";
			break;
		/**
		 * about
		 */	
		case 'about':
			JToolbarHelper::title("About", "wats");
			$watsSettings = new watsSettingsHTML();
			$watsSettings->about();
			break;
		/**
		 * database
		 */	
		case 'database':
			JToolbarHelper::title("Database Maintenance", "wats");
			$watsDatabaseMaintenance = new watsDatabaseMaintenanceHTML();
			$watsDatabaseMaintenance->performMaintenance();
			break;
		/**
		 * configuration
		 */	
		case 'configure':
			JToolbarHelper::title("Configuration", "wats");
			echo "<form action=\"index.php\" method=\"post\" name=\"adminForm\">";
			switch ($task) {
				/**
				 * save
				 */	
				case 'apply':
					// create settings object
					$watsSettings = new watsSettingsHTML();
					// process form
					$watsSettings->processForm();
					// save
					$watsSettings->save();
					// redirect
					watsredirect( "index.php?option=com_waticketsystem&act=configure" );
					break;
				/**
				 * cancel
				 */	
				case 'cancel':
					watsredirect( "index.php?option=com_waticketsystem" );
					break;
				/**
				 * default
				 */	
				default:
					// load overlib
					JHTML::_("behavior.mootools");
					jimport("joomla.html.pane");
					
					
					$watsSettings = new watsSettingsHTML();
					// start Tab Pane
					{
						$settingsTabs = JPane::getInstance("tabs");
						echo $settingsTabs->startPane('settingsTabs');
						// fill tabs
						{
							// general
							echo $settingsTabs->startPanel( 'General', 'settingsTabs' );
							$watsSettings->editGeneral();
							echo $settingsTabs->endPanel();
							// Users
							echo $settingsTabs->startPanel( 'Users', 'settingsTabs' );
							$watsSettings->editUser();
							echo $settingsTabs->endPanel();
							// Agreement
							echo $settingsTabs->startPanel( 'Agreement', 'settingsTabs' );
							$watsSettings->editAgreement();
							echo $settingsTabs->endPanel();
							// Notification
							echo $settingsTabs->startPanel( 'Notification', 'settingsTabs' );
							echo "<p>".JText::_("NOTIFICATION MOVED TO PLUGIN")."</p>";
							echo $settingsTabs->endPanel();
							// Upgrade
							echo $settingsTabs->startPanel( 'Upgrade', 'settingsTabs' );
							$watsSettings->editUpgrade();
							echo $settingsTabs->endPanel();
							// Debug
							echo $settingsTabs->startPanel( 'Debug', 'settingsTabs' );
							$watsSettings->editDebug();
							echo $settingsTabs->endPanel();
						}
						// end fill tabs
						echo $settingsTabs->endPane();
					}
					// end tab pane
					break;
			}
			echo "<input type=\"hidden\" name=\"act\" value=\"configure\" /><input type=\"hidden\" name=\"option\" value=\"com_waticketsystem\" /><input type=\"hidden\" name=\"task\" value=\"\" /></form>";
			break;
		/**
		 * default (configuration)
		 */	
		default:
			// stats
			$db =& JFactory::getDBO();
			
			$db->setQuery( "SELECT COUNT(*) as count FROM #__wats_ticket" );
			$set = $db->loadObjectList();
			$watsStatTickets = $set[0]->count;
			$watsStatTicketsRaw = $watsStatTickets;
			if ( $watsStatTickets == 0 )
				$watsStatTickets = 1;
			$db->setQuery( "SELECT COUNT(*) as count FROM #__wats_ticket WHERE lifeCycle=1" );
			$set = $db->loadObjectList();
			$watsStatTicketsOpen = $set[0]->count;
			$db->setQuery( "SELECT COUNT(*) as count FROM #__wats_ticket WHERE lifeCycle=2" );
			$set = $db->loadObjectList();
			$watsStatTicketsClosed =  $set[0]->count;;
			$db->setQuery( "SELECT COUNT(*) as count FROM #__wats_ticket WHERE lifeCycle=3" );
			$set = $db->loadObjectList();
			$watsStatTicketsDead = $set[0]->count;
			$db->setQuery( "SELECT COUNT(*) as count FROM #__wats_users" );
			$set = $db->loadObjectList();
			$watsStatUsers = $set[0]->count;
			$db->setQuery( "SELECT COUNT(*) as count FROM #__wats_category" );
			$set = $db->loadObjectList();
			$watsStatCategories = $set[0]->count;
			// end stats
			?> 
<table class="adminform"> 
  <tr> 
    <td width="55%" valign="top"> <div id="cpanel"> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem"> 
            <div class="iconimage"> <img src="images/frontpage.png" alt="Frontpage Manager" align="middle" name="image" border="0" /> </div> 
          Webamoeba Ticket System</a> </div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem&act=configure"> 
            <div class="iconimage"> <img src="images/config.png" alt="Configuration" align="middle" name="image" border="0" /> </div> 
          Configuration</a> </div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem&act=css"> 
            <div class="iconimage"> <img src="images/menu.png" alt="CSS" align="middle" name="image" border="0" /> </div> 
          CSS</a> </div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem&act=user"> 
            <div class="iconimage"> <img src="images/user.png" alt="User Manager" align="middle" name="image" border="0" /> </div> 
          User Manager</a> </div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem&act=rites"> 
            <div class="iconimage"> <img src="images/impressions.png" alt="Rites Manager" align="middle" name="image" border="0" /> </div> 
          Rites Manager</a> </div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem&act=category"> 
            <div class="iconimage"> <img src="images/categories.png" alt="Category Manager" align="middle" name="image" border="0" /> </div> 
          Category Manager</a> </div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem&act=ticket"> 
            <div class="iconimage"> <img src="images/addedit.png" alt="Ticket Viewer" align="middle" name="image" border="0" /> </div> 
            Ticket Viewer </a></div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem&act=database"> 
            <div class="iconimage"> <img src="images/systeminfo.png" alt="Database Maintenance" align="middle" name="image" border="0" /> </div> 
          Database Maintenance </a></div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_waticketsystem&act=about"> 
            <div class="iconimage"> <img src="images/cpanel.png" alt="About" align="middle" name="image" border="0" /> </div> 
          About </a></div> 
        </div> 
      </div></td> 
    <td width="45%" valign="top"> <div style="width=100%;"> 
        <table class="adminlist"> 
          <tr> 
            <th colspan="3"> Statistics </th> 
          </tr> 
          <tr> 
            <td width="80"> Tickets</td>  
            <td width="60"><?php echo $watsStatTicketsRaw; ?> / 100%</td> 
			<td><img src="components/com_waticketsystem/images/red.gif" style="height: 4px; width: 100%;"></td>
          </tr> 
          <tr> 
            <td> Open </td> 
            <td><?php echo $watsStatTicketsOpen; ?> / <?php echo intval((100/$watsStatTickets)*$watsStatTicketsOpen); ?>%</td> 
			<td><img src="components/com_waticketsystem/images/red.gif" style="height: 4px; width: <?php echo (100/$watsStatTickets)*$watsStatTicketsOpen; ?>%;"></td>
          </tr>
          <tr>
            <td>Closed</td>
            <td><?php echo $watsStatTicketsClosed; ?> / <?php echo intval((100/$watsStatTickets)*$watsStatTicketsClosed); ?>%</td>
            <td><img src="components/com_waticketsystem/images/red.gif" style="height: 4px; width: <?php echo (100/$watsStatTickets)*$watsStatTicketsClosed; ?>%;"></td>
          </tr>
          <tr>
            <td>Dead</td>
            <td><?php echo $watsStatTicketsDead; ?> / <?php echo intval((100/$watsStatTickets)*$watsStatTicketsDead); ?>%</td>
            <td><img src="components/com_waticketsystem/images/red.gif" style="height: 4px; width: <?php echo (100/$watsStatTickets)*$watsStatTicketsDead; ?>%;"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Users</td>
            <td><?php echo $watsStatUsers; ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Categories</td>
            <td><?php echo $watsStatCategories; ?></td>
			<td>&nbsp;</td>
          </tr> 
        </table> 
      </div></td> 
  </tr> 
</table> 
<?php
			break;
	}
}

function watsredirect($uri, $message = null, $level = "message") {
	global $mainframe;
	
	$wats =& WFactory::getConfig();
	
	if ( $wats->get( 'debug' ) == 0 ) {
		$mainframe->redirect($uri, $message, $level);
	} else {
		echo "<a href=\"".$uri."\">".$wats->get( 'debugmessage' )."</a><br />".$message;
	}
}
?> 
