<?php defined('_JEXEC')  or die('Direct Access to this location is not allowed.');

/*
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

// check access permissions (only superadmins & admins)
$acl =& JFactory::getACL();
$user =& JFactory::getUser();

if ( !( $acl->acl_check('administration', 'config', 'users', $user->usertype) )
    ||  $acl->acl_check('administration', 'edit', 'users', $user->usertype, 'components', 'com_comment') ) {
//    	global $mainframe;
//	$mainframe->redirect( 'index2.php', _NOT_AUTH );
}

require_once(JPATH_SITE."/administrator/components/com_comment/library.comment.php");
require_once(JPATH_SITE."/components/com_comment/joscomment/utils.php");
require_once(JPATH_SITE."/administrator/components/com_comment/class.config.comment.php");
require_once($mainframe->getPath('admin_html'));

$task		= JArrayHelper::getValue( $_REQUEST, 'task', '');
$option		= JArrayHelper::getValue( $_REQUEST, 'option', '');
$fromcomponent 	= JArrayHelper::getValue( $_REQUEST, 'fromcomponent', null );
$fromtable  	= JArrayHelper::getValue( $_REQUEST, 'fromtable', null );

$cid 	= JOSC_library::JOSCGetArrayInts( 'cid' ); /* id will be used if direct link  */
$id 	= intval(JArrayHelper::getValue( $_REQUEST, 'id', '0' )); /* id will be used if direct link  */
$set_id	= intval(JArrayHelper::getValue( $_REQUEST, 'id', '0' ));  /* need the same in toolbar ...*/

$set_id	= ( $set_id ? $set_id : intval( count($cid)>0 ? $cid[0] : 0 ) );

$component 	= JArrayHelper::getValue( $_REQUEST, 'component', '' ); /* for view */

/* TODO : improve the code below. 
 * not very beautifull code...
 */

if (strpos($task, "setting")===false)
$action = "";
else
$action = "setting";

switch($action) {

    case "setting":

	if ($task=="settingssimple") {
	    $set_id=0;
	    $component='';
	}

	if ($task=="settings" || $task=="settingsexpert") {
	    viewSettings($option, $task);

	} else {

	    $null=null;
	    $config = new JOSC_config($set_id,$null);

	    if (!$config->load()) {
		echo "Error: config for set_id=$set_id not found";

	    } else {
		switch ($task) {
		    case "settingsnew":
			case "settingsnewexpert":
			    $config->newConfig();
			    $config->execute($option, $task);
			    break;

		    case "settingsedit":
			case "settingseditexpert":
			    case "settingseditsimple":
				case "settingssimple":
				    $config->execute($option, $task);
				    break;

				case "settingsremove":
				    removeSettings($cid, $option, $config);
				    break;

				case "savesettings":
				    case "savesettingsexpert":
					case "savesettingssimple":
					    $config->save($option, $task);
					    break;

				    case "applysettings":
					case "applysettingsexpert":
					    case "applysettingssimple":
						$config->save($option, $task, true);
						break;
				    }
				}
			    }
			    break;

		    default:
		/*
		 * manage comments
		 */
			switch ($task) {

			    case "new":
				editComment($option, 0, $component);
				break;


			    case "edit":
				$cid[0]	= ( $id ? $id : intval( $cid[0] ) );
				editComment($option, $cid[0], $component);
				break;

			    case "save":
				saveComment($option, $component);
				break;

			    case "remove":
				removeComments($cid, $option, $component);
				break;

			    case "publish":
				publishComment($cid, 1, $option, $component);
				break;

			    case "unpublish":
				publishComment($cid, 0, $option, $component);
				break;

			/*
			 * captchatable for debug... if needed
			 */
			    case "captchatable":
				captchaTable($component);
				break;

			case "about":
			    JOSC_library::viewAbout(); /* library.comment.php */
			    break;

		/* import comment */
			case "importcomment" :
			    case "importcommentexpert" : /* do not use ! */
				HTML_comments::importPanel($option, $task, $fromtable, $fromcomponent, $component);
				break;

/*  not used for parentid reason
    case "importexecuteSel" :
	executeImport($cid, $option, $task, $fromtable, $fromcomponent);
	break;
*/

			    case "importexecuteAll" :
				//		    	executeImport( -1, $option, $task, $fromtable, $fromcomponent);
				executeImport( $option, $task, $fromtable, $fromcomponent, $component );
				break;

			    case "convertlcharset" :
				convertlcharset($cid, $option, $defaultconfig->_local_charset);
				break;

/*
    case "import":
	import($option);
	break;
*/
			    default:
				viewComments($option, $task, $component);
				break;
			}
			break;
	    }


	    function captchaTable($component)
	    {
		global $mainframe;
		$user =& JFactory::getUser();
		$database =& JFactory::getDBO();


		$config = new JOSC_defaultconfig($component);
		$config->load();  /* to get admin language */

		if (!$user->username || !isset($config->_debug_username) || $config->_debug_username!=$user->username) {
		    $mainframe->redirect('index.php', _NOT_AUTH );
		}
		 

		$query 	= "SELECT * FROM #__comment_captcha"
		. "\n ORDER BY id"
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if ($database->getErrorNum()) {
		    echo $database->stderr();
		    return false;
		}
		if ($rows)
		foreach($rows as $row)
		echo print_r($row,true)."<br />";
	    }

	    function convertlcharset($cid=null, $option, $local_charset) {
		$database =& JFactory::getDBO();


		if (!$local_charset)
                {
		    echo "<script> alert('Local charset is empty ! check your setting'); window.history.go(-1);</script>\n";
		    exit;
		}

		if (!is_array($cid) || $cid[0]<1)
                {
		    $action = "convert to $local_charset";
		    echo "<script> alert('Select at least an item to $action'); window.history.go(-1);</script>\n";
		    exit;
		}
		$cids = implode(',', $cid);

		$message = "";

	/*
	 * #__comment
	 */
/*	$tabname 	= "#__comment_utf8";
	$table 		= $database->getTableFields(explode(",", $tabname)); // columns[table][fieldname]
    $database->setQuery("SHOW COLUMNS FROM $tabname");
    $columns = $database->loadObjectList();
*/ 
		$columns = JOSC_TableUtils::TableColumnsGet( '#__comment_utf8' );

		$advise = "<b>Tips:<br />if you encountered problems after conversion, you could use the backup table <i>comment_utf8</i> to retrieve your original datas by export/import."
		. "<br /> It contains ALL and ONLY the originals comments you have converted.</b><br /><br />"
		;
		if (!$columns) {
	/*
	 * backup table does not exist = create
	 */
		    $message .= "Table comment_utf8 backup creation:";
		    $database->SetQuery("CREATE TABLE IF NOT EXISTS `#__comment_utf8` SELECT * FROM `#__comment` WHERE id=0 ");
		    $result = $database->query();
		    if(!$result) {
			echo "Error: " . $database->stderr() . "<br /><br />";
			return;
		    } else {
			$message .= "OK.<br />".$advise;
		    }
		} else
		$message .= $advise;


	/*
	 * convert lines
	 */
		$query  = "SELECT c.*, utf.id AS UTFID "
		. "\n FROM `#__comment` AS c "
		. "\n LEFT JOIN `#__comment_utf8` AS utf ON c.id = utf.id"
		. "\n    WHERE c.id IN ($cids) "
		;
		$database->SetQuery($query);
		$rows = $database->loadAssocList();
		if(!$rows) {
		    echo "Error: " . $database->stderr() . "<br /><br />";
		    return;
		} else {
		    $err=0;
		    $ok=0;
		    foreach($rows as $row) {
			if ($row['UTFID']) {
			    $message .= "Comment id=".$row['UTFID']." already converted.<br />";
			    continue;
			}
	    /* first time: INSERT FIRST IN UTF8 table if not already done */
			$query = "INSERT IGNORE INTO `#__comment_utf8` SELECT * FROM `#__comment` WHERE id=".$row['id'];
			$database->SetQuery($query);
			$result = $database->query();
			if(!$result) {
			    $message .= "Error: " . $database->stderr() . "  Complete query $query<br />";
			    $err++;
			    continue;
			}
			$query = "INSERT IGNORE INTO `#__comment_utf8` SELECT * FROM `#__comment` WHERE id=".$row['id'];
			$database->SetQuery($query);
			$result = $database->query();
			if(!$result) {
			    $message .= "Error on comment ". $row['id'] ." : " . $database->stderr() . "  Complete query $query<br />";
			    $err++;
			    continue;
			}
			$local_charset = strtoupper($local_charset);
			$name 	 = JOSC_utils::myiconv_decode($row['name'], $local_charset );
			$title 	 = JOSC_utils::myiconv_decode($row['title'], $local_charset );
			$comment = JOSC_utils::myiconv_decode($row['comment'], $local_charset );
			if ( 	   ($row['name'] && !$name)
			    || ($row['title'] && !$title)
			    || ($row['comment'] && !$comment)
			) {
			    $message .= "Error on comment ". $row['id'] ." during iconv conversion. Please verify if $local_charset is supported by iconv OR try to convert manually this comment. <br />";
			    $err++;
			    continue;
			}
			$query 	= "UPDATE `#__comment` SET "
			. "\n   name   ='".JOSC_utils::mysql_escape_string(strip_tags($name))."'"
			. "\n , title  ='".JOSC_utils::mysql_escape_string(strip_tags($title))."'"
			. "\n , comment='".JOSC_utils::mysql_escape_string(strip_tags($comment))."'"
			. "\n	WHERE id=".$row['id']." LIMIT 1 "
			;
			$database->SetQuery($query);
			$result = $database->query();
			if(!$result) {
			    $message .= "Error on comment ". $row['id'] ." : " . $database->stderr() . " Complete query $query<br />";
			    $err++;
			}
			$ok++;
		    }
		    if ($err)
		    $message .= "$err error(s) !<br />";
		    $message .= "$ok line(s) updated.<br /> Process is finished..<br />";

		    echo $message;
		}
	    }


	    function importMapping($option, $task, $fromtable='', $fromcomponent='', $component='') {
		$database =& JFactory::getDBO();

		$lists = array();
		$sel_columns = false;
		   
		$onchangecomponent =  JArrayHelper::getValue( $_REQUEST, 'onchangecomponent', null );


		if ($onchangecomponent) {
		/*
		* from component = propose automatic columns selection
		*/
		    if ($fromcomponent && function_exists("getImport_".$fromcomponent)) {
			if ($result = call_user_func( "getImport_".$fromcomponent)) {
			    $fromtable 		= $result['fromtable'];
			    $sel_columns	= $result['sel_columns']; 	 /* ['sel_columns'][joscolumn] = component_column */
			}
		    }
		} else {
	/*
		* get settings Parameters
		*/
		    $joscomment = JOSC_TableUtils::TableColumnsGet( '#__comment' );
		    foreach($joscomment as $col) {
			$param = JArrayHelper::getValue( $_REQUEST, $col->Field, null );
			//if ($param) { to avoid notice
			$sel_columns[$col->Field] = $param;
			//}
		    }
		    $param = JArrayHelper::getValue( $_REQUEST, 'componentfield', null );
		    $sel_columns['componentfield'] = JArrayHelper::getValue( $_REQUEST, 'componentfield', null );
		}

	/*
	 * get all joomla tables
	 */
		$tablelist = JOSC_TableUtils::getTableList();
		
		if ($fromtable && !in_array( $fromtable, $tablelist )) {
		    $fromtable = null;
		}

		if (checkExistFromTableComments($component, $fromtable)) {


		
/*       	global $mainframe; $mainframe->redirect(("index2.php?option=$option&component=".$lists['component'], 
					"Comments imported from ".$lists['fromtable']." and for ".JOSC_utils::getComponentName($lists['component'])." ALREADY EXIST !!"
					);*/
		    $url = "index.php?option=$option&component=$component&search=$fromtable";
		    echo "<b>!!!</b> Comments imported <b><u>from ".$fromtable."</u></b> and <b><u>for ".JOSC_utils::getComponentName($component)."</u> ALREADY EXIST !!</b>"
		    ."<br />This is not expected. Please <a href=\"$url\">CHECK</a> why there are alreay existing imported comments for this key."
		    ."<br />To be able to import, there must be no existing comment already imported from the (".$fromtable.",".JOSC_utils::getComponentName($component).") key."
		    ."<br /><br />";
		}

		$tablename = array();
		$columns = array();
		foreach ($tablelist as $tn) {
		    
		    // make sure we get the right tables based on prefix
		    if (!preg_match( "/^".$database->getPrefix()."/i", $tn )) {
			
			continue;
		    }
		    
		    if ($tn==$fromtable) {
			/*
			* get all fields of the selected table
			*/
			
			$tablecolumns = JOSC_TableUtils::TableColumnsGet( $tn );
			foreach($tablecolumns as $col){
			    $columns[] = JHTML::_('select.option',  $col->Field, $col->Field, 'Field', 'desc');
			}
		    }
		    $tablename[] = JHTML::_('select.option',  $tn, $tn, 'tablename', 'desc');
		}
		
		$selected = $lists['component'] = JOSC_utils::getComponentName($component);
		$componentlist = JOSC_library::getComponentList();
		$lists['componentlist'] = JHTML::_('select.genericlist',$componentlist, 'component', 'class="inputbox" onchange="document.adminForm.submit();"', 'value', 'text', $selected);

		$selected = $fromcomponent;
		$fromcomponents = setImport_ComponentList();
		array_unshift( $fromcomponents, JHTML::_('select.option',  '', '-- from component --', 'fromcomponent', 'desc' ) );
		$lists['fromcomponent'] = JHTML::_('select.genericlist', $fromcomponents, 'fromcomponent', ' class="inputbox" onchange="document.location.href=\'index2.php?option='.$option.'&task='.$task.'&onchangecomponent=1&fromcomponent=\'+document.adminForm.fromcomponent.value;" ', 'fromcomponent', 'desc', $selected );

		$selected = $lists['fromtable'] = $fromtable;
		array_unshift( $tablename, JHTML::_('select.option',  '', '-- Select a table name --', 'tablename', 'desc' ) );
		$lists['fromtablelist'] = JHTML::_('select.genericlist', $tablename, 'fromtable', ' class="inputbox" onchange="document.location.href=\'index2.php?option='.$option.'&task='.$task.'&fromtable=\'+document.adminForm.fromtable.value;" ', 'tablename', 'desc', $selected );

		$selected = '';
		array_unshift( $columns, JHTML::_('select.option',  '', '-- column --', 'Field', 'desc') );
		$lists['columns'] = $columns; /* ->field, ->desc */
		$lists['sel_columns'] = $sel_columns; 	 /* ['sel_columns][joscolumn] = component_column */

		$lists['savequeries'] = JHTML::_('select.booleanlist', 'savequeries', 'class="inputbox"', false);

		HTML_comments::importMapping( $lists );
	    }

	    function checkExistFromTableComments($component, $fromtable)
	    {
		if (!$fromtable) {
		    return false;
		}

		$database =& JFactory::getDBO();


	    /* check no comment for fromtable AND component */
		$query =  "SELECT id FROM #__comment "
		. "\n   WHERE component='$component'"
		. "\n     AND importedtable='$fromtable'"
		. "\n   LIMIT 1"
		;
		$database->setQuery($query);
		if ($database->loadResult()) {
		    return true;
		} else {
		    return false;
		}
		
	    }


	    function previewImportComments($option, $task, $fromtable=null, $component='')
	    {
		global $mainframe ;

		$database =& JFactory::getDBO();


		
		//	$component = '';

		$config = new JOSC_defaultconfig($component);
		$config->load();  /* to get admin language */

		if (!$fromtable) {
		    echo "<b>Select at least a table and apply.</b>";
		    return;
		}

			$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mainframe->getCfg('list_limit') ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcom{$option}limitstart", 'limitstart', 0 ) );

		$search = $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
		$search = $database->getEscaped(trim(strtolower($search)));
		$where = array();

	/*
	 * construct SELECT [FIELDS] FROM [FROMTABLE]
	 */
		$columns = JOSC_TableUtils::TableColumnsGet( '#__comment' );

	/*
	 * construct SELECT clause from GetParam
	 */
		$fields = array();
		$idfound = false;
		$contentidfound = false;
		$forcomponent = JOSC_utils::getComponentName($component);

		foreach($columns as $col) {
		    $param = JArrayHelper::getValue( $_REQUEST, $col->Field, null );
		    if ($param) {
			if (!($col->Field == 'component')) {
			    $fields[] = "f.$param AS $col->Field";
			    if ($col->Field == 'id') $idfound = true;
			    if ($col->Field == 'contentid') {
				$joincontentid = "f.$param"; /* for the left join content item */
				$contentidfound = true;
			    }
			}
			if ($search) {
			    $where[] = "LOWER(f.$param) LIKE '%$search%'";
			}
		    }
		}
		$comfield = JArrayHelper::getValue( $_REQUEST, 'componentfield', null );
		if ($comfield)
		$where[] = "f.$comfield='".$forcomponent."'";

		if (!$idfound) {
		    echo "<b>Id column is obligatory ! Please, select the column.</b>";
		    return;
		}

		if (!$contentidfound) {
		    echo "<b>ContentId column is obligatory ! Please, select the column.</b>";
		    return;
		}

	/*
	 * Queries
	 */


		$queryfrom			= $queryfromcount = "\n FROM $fromtable AS f";

		if ($joincontentid) {
		    $queryfrom .= "\n LEFT JOIN #__content AS ct ON ct.id = $joincontentid";
		    $fields[] 	= "ct.title AS ctitle"; /* for the left join content item */
		}

		$queryselect  		= "SELECT " . implode(', ', $fields);
		$queryselectcount  	= "SELECT count(*)";
		$querywhere			= (count($where) ? ("\n WHERE ".implode(' OR ', $where)) : "");
	/*
	 *
	 */
		$query = $queryselectcount . $queryfromcount . $querywhere;
		$database->setQuery($query);
		$total = $database->loadResult();
		echo $database->getErrorMsg();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);

		$query  = $queryselect
		. $queryfrom
		. $querywhere
		. "\n ORDER BY f.id " /* keep the creation order of fromtable */
		;
		$database->SetQuery($query, $pageNav->limitstart,$pageNav->limit);
		$rows = $database->loadObjectList();

		$lists = array();
		$lists['noedit'] = true; /* do not set edit link function */
		$lists['noform'] = true; /* adminform already set */
		$lists['checkread'] = true; /* do not use checkbox */
		$lists['component'] = $component;
		//$componentlist = JOSC_library::getComponentList(1);
		$lists['componentlist'] = $component;//JHTML::_('select.genericlist',$componentlist, 'component', 'class="inputbox" onchange="document.adminForm.submit();"', 'value', 'text', $component);
		$lists['title'] = "Preview of what will be the result of import of : $fromtable for $forcomponent";

		HTML_comments::viewComments($option, $rows, $lists, $search, $pageNav, $task);
	    }

	    function executeImportOLD($cid, $option, $task, $fromtable, $fromcomponent) {
		global $mainframe;
		$database =& JFactory::getDBO();

		if (!$fromtable) {
		    echo "<script> alert('Select at least a table. Check your setting.'); window.history.go(-1);</script>\n";
		    exit;
		}

		if ($cid!=-1 && (!is_array($cid) || $cid[0]<1)) {
		    $action = "import";
		    echo "<script> alert('Select at least an item to $action'); window.history.go(-1);</script>\n";
		    exit;
		}

	/*
	 * construct SELECT [FIELDS] FROM [FROMTABLE]
	 */
		$columns = JOSC_TableUtils::TableColumnsGet( '#__comment' );

	/*
	 * construct SELECT clause from GetParam
	 * 		$fromlist 	= from columns list (component)
	 * 		$tolist 	= to columns list  (joomlacomment)
	 */
		$tolist   = array();
		$fromlist = array();
		$idfound  = false;
		$contentidfound  = false;
		foreach($columns as $col) {
		    $param = JArrayHelper::getValue( $_REQUEST, $col->Field, null );
		    if ($param) {
			if ($col->Field == 'id') $idfound = true;
			if ($col->Field == 'contentid') $contentidfound = true;
			if ($col->Field == 'component') continue;
			$tolist[] 	= "$col->Field";
			$fromlist[] = "$param";
		    }
		}

		if (!$fromlist) {
		    echo "<script> alert('Select at least one field. Check your setting.'); window.history.go(-1);</script>\n";
		    exit;
		}

		if (!$idfound) {
		    echo "<script> alert('The Id field is obligatory. Check your setting.'); window.history.go(-1);</script>\n";
		    exit;
		}

		if (!$contentidfound) {
		    echo "<script> alert('The ContentId field is obligatory. Check your setting.'); window.history.go(-1);</script>\n";
		    exit;
		}

	/*
	 * Queries
	 */

		$where = array();

		$cids = "";
		if ($cid!=-1 && count($cid)) {
		    $cids =  implode(',', $cid);
		    $where[] = " id IN ($cids) ";
		}

		$savequeries = JArrayHelper::getValue( $_REQUEST, 'savequeries', false );
		$queries = ""; // will contain queries history

		$query 	= "INSERT INTO #__comment (".implode(',', $tolist).") "
		. "\n SELECT ".implode(',', $fromlist)." FROM $fromtable"
		. (count($where) ? ("\n WHERE ".implode('AND', $where)) : "")
		. " ORDER BY id"
		;
		$database->setQuery($query);    $queries .= "\n".$database->_sql.";";
		$result = $database->query();
		if ($result) {
		    $query 	= "UPDATE #__comment SET parentid=-1 WHERE parentid = 0";
		    $database->setQuery($query);    $queries .= "\n".$database->_sql.";";
		    $result = $database->query();
		}
		if(!$result) {
		    echo "Error: " . $database->stderr() . "<br /><br />";
		    echo "Please copy the error message and contact the joomlacomment support";
		} else {
		    $message = "Comments has been imported. Please verify the result below. ";
		    if ($savequeries) {
			if ($file = save_importQuery( $queries, $fromcomponent )) {
			    $message .= "---- sql queries has been saved in the $file.";
			} else {
			    $message .= "---- did not succeed to save sql queries in $file.";
			}
		    }
		    global $mainframe;
		    $mainframe->redirect("index.php?option=$option", $message);
		}

	    }


	    function executeImport( $option, $task, $fromtable, $fromcomponent, $component='')
	    {
		global $mainframe;
		$database =& JFactory::getDBO();

		if (!$fromtable) {
		    echo "<script> alert('Select at least a table. Check your setting.'); window.history.go(-1);</script>\n";
		    exit;
		}

		if (checkExistFromTableComments($component, $fromtable))
		global $mainframe;
		$mainframe->redirect("index.php?option=$option&component=$component&search=$fromtable",
					"Import cancelled ! Comments imported from ".$fromtable." and for ".JOSC_utils::getComponentName($component)." ALREADY EXIST !!"
		);

	/*
	 * construct SELECT [FIELDS] FROM [FROMTABLE]
	 */
		$columns = JOSC_TableUtils::TableColumnsGet( '#__comment' );

	/*
	 * construct SELECT clause from GetParam
	 * 		$fromlist 	= from columns list (component)
	 * 		$tolist 	= to columns list  (joomlacomment)
	 */
		$tolist   = array();
		$fromlist = array();
		$idfound  = false;
		$parentidfound  = false;
		$contentidfound  = false;
		$comfield = JArrayHelper::getValue( $_REQUEST, 'componentfield', null );
		foreach($columns as $col) {
		    $param = JArrayHelper::getValue( $_REQUEST, $col->Field, null );
		    if ($param) {
			if ($col->Field == 'id') {
			    $idfound = true;
			    $col->Field = "importid";
			}
			if ($col->Field == 'parentid') {
			    $parentidfound = true;
			    $col->Field = "importparentid";
			}
			if ($col->Field == 'contentid') $contentidfound = true;
			if ($col->Field == 'component') continue;
			$tolist[] 	= "$col->Field";
			$fromlist[] = "$param";
		    }
		}

		$tolist[] 	= "component";
		$fromlist[] = "'$component'";
		$tolist[] 	= "importtable"; /* the importable name in the importtable field - in case of problem... */
		$fromlist[] = "'$fromtable'";

		if (!$fromlist) {
		    echo "<script> alert('Select at least one field. Check your setting.'); window.history.go(-1);</script>\n";
		    exit;
		}

		if (!$idfound) {
		    echo "<script> alert('The Id field is obligatory. Check your setting.'); window.history.go(-1);</script>\n";
		    exit;
		}

		if (!$contentidfound) {
		    echo "<script> alert('The ContentId field is obligatory. Check your setting.'); window.history.go(-1);</script>\n";
		    exit;
		}


	/*
	 * Queries
	 */

		$where = array();

		if ($comfield)
		$where[] = "f.$comfield='".JOSC_utils::getComponentName($component)."'";

		$savequeries = JArrayHelper::getValue( $_REQUEST, 'savequeries', false );
		$queries = ""; // will contain queries history

	/*
	 * INSERT
	 *  and save source id and source parentid
	 * 			in importid/importparentid field.
	 */
		$query 	= "INSERT INTO #__comment (".implode(',', $tolist).") "
		. "\n SELECT ".implode(',', $fromlist)." FROM $fromtable AS f"
		. (count($where) ? ("\n WHERE ".implode('AND', $where)) : "")
		. " ORDER BY id"
		;
		$database->setQuery($query);    $queries .= "\n".$database->_sql.";";
		$result = $database->query();
		if ($result) {
	/* importedparentid > 0 and parentid <= 0
	 *      parentid = id of the importedid = parentid
	 */
		    $query = " UPDATE #__comment AS cupdate JOIN #__comment AS cselect ON cselect.importtable = cupdate.importtable AND cselect.importid = cupdate.importparentid"
		    ."\n	SET cupdate.parentid=cselect.id "
		    ."\n		WHERE cupdate.parentid <= 0 "
		    ."\n		 AND  cupdate.importparentid > 0 "
		    ;

		    $database->setQuery($query);    $queries .= "\n".$database->_sql.";";
		    $result = $database->query();
		}
		if ($result) {
		/*
		 * set -1 to parentid not found (or because in other component it is 0 and not -1)
		 * it must be -1 in joomlacomment.
		 */
		    $query 	= "UPDATE #__comment SET parentid=-1 WHERE parentid = 0";
		    $database->setQuery($query);    $queries .= "\n".$database->_sql.";";
		    $result = $database->query();
		}
		if(!$result) {
		    echo "Error: " . $database->stderr() . "<br /><br />";
		    echo "Please copy the error message and contact the joomlacomment support";
		} else {
		    $message = "Comments has been imported. Please verify the result below. ";
		    if ($savequeries) {
			if ($file = save_importQuery( $queries, $fromcomponent )) {
			    $message .= "---- sql queries has been saved in the $file.";
			} else {
			    $message .= "---- did not succeed to save sql queries in $file.";
			}
		    }
		    global $mainframe; $mainframe->redirect("index.php?option=$option&component=$component", $message);
		}

	    }

	    function viewComments($option, $task, $component='')
	    {
		global $mainframe;
		$database =& JFactory::getDBO();


		$config = new JOSC_defaultconfig($component);
		$config->load();  /* to get admin language */
		$null=null;
		$componentObj = JOSC_utils::ComPluginObject($component,$null);
		$ctitle 	= $componentObj->getViewTitleField();
		$leftjoin 	= $componentObj->getViewJoinQuery('ct', 'c.contentid');
		unset($componentObj);
		$component_exist = ($component=="" || @is_dir(JPATH_SITE."/components/".$component));

		$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mainframe->getCfg('list_limit') ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcom{$option}limitstart", 'limitstart', 0 ) );

		$search = $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
		$search = $database->getEscaped(trim(strtolower($search)));
		$where = array();
		if ($search) {
		    $where[] = "LOWER(c.comment) LIKE '%$search%'";
		    $where[] = "LOWER(ct.$ctitle) LIKE '%$search%'";
		    $where[] = "LOWER(c.name) LIKE '%$search%'";
		    $where[] = "LOWER(c.website) LIKE '%$search%'";
		    $where[] = "LOWER(c.email) LIKE '%$search%'";
		    $where[] = "LOWER(c.ip) LIKE '%$search%'";
		    $where[] = "LOWER(c.importedtable) LIKE '%$search%'";
		}

		if ($component_exist) {
		    $query	= "SELECT count(*) FROM #__comment AS c"
		    //    		. "\n LEFT JOIN #__content  AS ct ON ct.id = c.contentid "
		    . $leftjoin
		    . "\n WHERE c.component = '$component' "
		    . ( count($where) ? "\n  AND ( ".implode(' OR ', $where)." )"   :  "" )
		    ;
		    $database->setQuery($query);
		    $total = $database->loadResult();
		    echo $database->getErrorMsg();
		} else {
		    $total = 0;
		}

		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		//, ct.sectionid, ct.catid
		if ($component_exist) {
		    $query 	= "SELECT c.*, u.email AS usermail, ct.$ctitle AS ctitle FROM #__comment AS c"
		    . "\n LEFT JOIN #__users  AS u ON u.id = c.userid "
		    //         	. "\n LEFT JOIN #__content  AS ct ON ct.id = c.contentid "
		    . $leftjoin
		    . "\n WHERE c.component = '$component' "
		    . ( count($where) ? "\n  AND ( ".implode(' OR ', $where)." )"   :  "" )
		    . "\n ORDER BY c.id DESC"
		    . "\n LIMIT $pageNav->limitstart,$pageNav->limit"
		    ;
		    //$database->setQuery( $query , $pageNav->limitstart, $pageNav->limit ); for mambo compatibility -- thanks to Adi Setiawan http://forum.mambo-foundation.org
		    $database->setQuery( $query );
		    $rows = $database->loadObjectList();
		    if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		    }
		} else {
		    $rows = null;
		}
		$lists = array();
		$lists['component'] = $component;
		$componentlist = JOSC_library::getComponentList();

		$lists['componentlist'] = JHTML::_('select.genericlist',$componentlist, 'component', 'class="inputbox" onchange="document.adminForm.submit();"', 'value', 'text', $component);
//		$lists['componentlist'] = JHTML::_('select.option',$component, 'component', 'class="inputbox" onchange="document.adminForm.submit();"', 'value', 'text', $componentList);

		$lists['title'] = "List of joomlacomment Comments";

		HTML_comments::viewComments($option, $rows, $lists, $search, $pageNav, $task);
	    }

	    function publishComment($cid = null, $publish = 1, $option, $component)
	    {
		//    global $my;
		$user =& JFactory::getUser();
		$database =& JFactory::getDBO();

		$config = new JOSC_defaultconfig($component);
		if (!$config->load()) {
		    echo "<script> alert('default config not found for $component'); window.history.go(-1); </script>\n";
		    exit();
		}

		if (!is_array($cid) || count($cid) < 1) {
		    $action = $publish ? 'publish' : 'unpublish';
		    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
		    exit;
		}
		$cids = implode(',', $cid);
		$database->setQuery("UPDATE #__comment SET published='$publish' WHERE id IN ($cids)");
		if (!$database->query()) {
		    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
		    exit();
		}
		$config->_comObject->cleanComponentCache();
	/*
	 * mail to notify :
	 *   the writer (AT LEAST)
	 *   and the users of those contentid (to inform of a new comment)
	 * 	 and the moderators
	 */
		if (JArrayHelper::getValue( $_REQUEST, 'confirm_notify', 0 )) {
		    $notification = new JOSC_notification($config);
		    $sentemail = $notification->notifyComments($cids, $publish ? 'publish':'unpublish');
		    global $mainframe; $mainframe->redirect("index.php?option=$option", $sentemail ? (_JOOMLACOMMENT_ADMIN_NOTIFY_SENT_TO.$sentemail):_JOOMLACOMMENT_ADMIN_NOTIFY_NOT_SENT);
		}

		global $mainframe; $mainframe->redirect("index.php?option=$option", _JOOMLACOMMENT_ADMIN_NOTIFY_NOT_SENT);
	    }

	    function editComment($option, $uid, $component='')
	    {
		$user = JFactory::getUser();

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comment'.DS.'tables');
		$row =& JTable::getInstance('comment', 'Table');

		$row->load($uid);
		$lists = array();

		$null=null;
		$componentObj 	= JOSC_utils::ComPluginObject($row->component,$null);
		$temp	= $componentObj->getObjectIdOption(intval($row->contentid), false);
		unset($componentObj);

		if (count($temp) < 1) {
		    echo "<script> alert('You must add component objects first.'); window.history.go(-1); </script>\n";
		    exit();
		}
		$contentitem	= $temp[0];

		// build list of content item
		$lists['content'] = $contentitem->title . JOSC_library::hidden('contentid', intval($row->contentid));
		// JHTML::_('select.genericlist',$contentitem, 'contentid', 'class="inputbox" size="1"', 'id', 'title', intval($row->contentid));
		// build list of users
//		$lists['userid']	= mosAdminMenus::UserSelect( 'userid', $row->userid, 1, NULL, 'name', 0 );

		$lists['userid'] = JHTML::_('list.users', 'userid', $row->userid, 1, NULL, 'name', 0);
		// build publish
		if ($uid) {
		    $row->checkout($user->id);
		} else {
		    $row->published = 0;
		}
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $row->published);

		// component choice is made before the call (in view). Else this becomes hard to update contentitem list...
		// 	if ($uid) {
		$name = JOSC_utils::getComponentName($row->component);
		$lists['component']	=  $name . JOSC_library::hidden('component', $row->component);
		HTML_comments::editComment($option, $row, $lists);
	    }

	    function saveComment($option, $component)
	    {
                global $mainframe;
		$database =& JFactory::getDBO();

		$config = new JOSC_defaultconfig($component);
		if (!$config->load()) {
		    echo "<script> alert('default config not found for $component'); window.history.go(-1); </script>\n";
		    exit();
		}

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comment'.DS.'tables');
		$row =& JTable::getInstance('comment', 'Table');
		if (!$row->bind($_POST)) {
		    echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		    exit();
		}
		$row->date = date("Y-m-d H:i:s");
		$row->ip = getenv('REMOTE_ADDR');
		if (!$row->store()) {
		    echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		    exit();
		}
		$row->reorder("contentid='$row->contentid'");
		$config->_comObject->cleanComponentCache();
		$mainframe->redirect("index.php?option=$option&task=comments");
	    }

	    function removeComments($cid, $option, $component)
	    {
		$database =& JFactory::getDBO();


		$config = new JOSC_defaultconfig($component);
		if (!$config->load()) {
		    echo "<script> alert('default config not found for $component'); window.history.go(-1); </script>\n";
		    exit();
		}

		if (!is_array($cid) || count($cid) < 1) {
		    $action = 'delete';
		    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
		    exit;
		}
		$cids = implode(',', $cid);
		$database->setQuery("DELETE FROM #__comment WHERE id IN ($cids)");
		if (!$database->query()) {
		    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
		    exit();
		}

		$config->_comObject->cleanComponentCache();

	/*
	 * mail to notify :
	 *   the writer (AT LEAST)
	 * 	 AND moderators
	 */
		if (JArrayHelper::getValue( $_REQUEST, 'confirm_notify', 0 )) {
		    $notification = new JOSC_notification($config);
		    $sentemail = $notification->notifyComments($cids, 'delete');
		    global $mainframe; $mainframe->redirect("index.php?option=$option", $sentemail ? (_JOOMLACOMMENT_ADMIN_NOTIFY_SENT_TO.$sentemail):_JOOMLACOMMENT_ADMIN_NOTIFY_NOT_SENT);
		}

		global $mainframe; $mainframe->redirect("index.php?option=$option", _JOOMLACOMMENT_ADMIN_NOTIFY_NOT_SENT);
	    }

	    function viewSettings($option, $task)
	    {
		global $mainframe, $mosConfig_list_limit ;
		$database =& JFactory::getDBO();

		$lists = array();

		$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewset{$option}limitstart", 'limitstart', 0 ) );

		$search = $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
		$search = $database->getEscaped(trim(strtolower($search)));
		$where = array();
		if ($search) {
		    $where[] = "LOWER(s.set_name) LIKE '%$search%'";
		    $where[] = "LOWER(s.set_component) LIKE '%$search%'";
		}

		if (strpos($task, 'expert')===false) {
		    $database->setQuery("SELECT count(*) FROM #__comment_setting WHERE set_sectionid>0");
		    $totalexpert = $database->loadResult();
		    if ($totalexpert)
                    {
			$mainframe->redirect("index.php?option=$option&task=settingsexpert");
                    }
		    else
                    {
                        $lists['expert'] = false;
                    }
		} else {
		    $lists['expert'] = true;
		}

		$database->setQuery("SELECT count(*) FROM #__comment_setting AS s"
		    . (count($where) ? "\nWHERE " . implode(' OR ', $where) : ""));
		$total = $database->loadResult();
		echo $database->getErrorMsg();

		require_once( JPATH_SITE . '/administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav($total, $limitstart, $limit);

		$query = "SELECT s.* FROM #__comment_setting AS s"
		// , cs.title AS cstitle        . "\n LEFT JOIN #__sections AS cs ON cs.id = s.set_sectionid "
		. (count($where) ? "\n WHERE " . implode(' OR ', $where) : "")
		. "\n ORDER BY s.set_component, s.set_sectionid, s.id "
		;
		$database->setQuery( $query , $pageNav->limitstart, $pageNav->limit );
		$rows = $database->loadObjectList();

//                var_dump($rows);
		if ($database->getErrorNum()) {
		    echo $database->stderr();
		    return false;
		}

		if (!$search && !$rows)
		{
                    $mainframe->redirect("index.php?option=$option&task=settingsedit");
                }


		$lists['title'] = "List of joomlacomment settings BY COMPONENT";

		HTML_comments::viewSettings($option, $rows, $lists, $search, $pageNav, $task);
	    }

            function removeSettings($cid, $option, &$config)
            {
                global $mainframe;
                $database =& JFactory::getDBO();


                if (!is_array($cid) || count($cid) < 1) {
                    $action = 'delete';
                    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
                    exit;
                }
                $cids = implode(',', $cid);
                $database->setQuery("DELETE FROM #__comment_setting WHERE id IN ($cids) AND id <> 1 ");
                if (!$database->query()) {
                    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
                    exit();
                }

                //	$config->_comObject->cleanComponentCache();

                $mainframe->redirect("index.php?option=$option&task=settings");
            }

	    function setImport_ComponentList() {

		$result = array();
	/*
	 * important: the fromcomponent value will be used as function name
	 */
		$fromcomponent = 'mXcomment';
		if (function_exists("getImport_".$fromcomponent) && call_user_func( "getImport_".$fromcomponent, true))
		$result[] = JHTML::_('select.option',  $fromcomponent, $fromcomponent, 'fromcomponent', 'desc' );
		$fromcomponent = 'AkoComment';
		if (function_exists("getImport_".$fromcomponent) && call_user_func( "getImport_".$fromcomponent, true))
		$result[] = JHTML::_('select.option',  $fromcomponent, $fromcomponent, 'fromcomponent', 'desc' );
		$fromcomponent = 'JReaction';
		if (function_exists("getImport_".$fromcomponent) && call_user_func( "getImport_".$fromcomponent, true))
		$result[] = JHTML::_('select.option',  $fromcomponent, $fromcomponent, 'fromcomponent', 'desc' );
		$fromcomponent = 'JomComment';
		if (function_exists("getImport_".$fromcomponent) && call_user_func( "getImport_".$fromcomponent, true))
		$result[] = JHTML::_('select.option',  $fromcomponent, $fromcomponent, 'fromcomponent', 'desc' );
		return($result);
	    }

            function save_importQuery( $query, $component='', $option='') {
                global $mainframe;
                $File = "media/joscomment_importquery_$component.sql";

                if ($fp = fopen( JPATH_SITE."/".$File , "w")) {
                    fputs($fp, $query, strlen($query));
                    fclose ($fp);
                } elseif ($option) {
                    $mainframe->redirect("index.php?option=$option", 'File $File creation error!');
                    break;
                } else {
                    return false;
                }
                    
                if ($option) {
                    $mainframe->redirect("index.php?option=$option", 'Query saved');
                } else {
                    return $File;
                }
            }

/* ---------------------------------------------
 * --- P R E - D E F I N E D   I M P O R T S ---
 */

    function getImport_mXcomment($checktable=false) {
	$database =& JFactory::getDBO();


	if (JOSC_TableUtils::existsTable( '#__mxc_comments' )) {
	    if (!JOSC_TableUtils::TableColumnsGet( '#__mxc_comments' )) {
		return;
	    }
	} else {
	    return;
	}
	

	$result['fromtable'] = $database->getPrefix().'mxc_comments';
	 /* ['sel_columns'][joscolumn] = component_column */
	$result['sel_columns']['componentfield']	= 'component';
	$result['sel_columns']['id'] 		= 'id';
	$result['sel_columns']['contentid'] = 'contentid';
	$result['sel_columns']['date'] 		= 'date';
	$result['sel_columns']['name'] 		= 'name';
	$result['sel_columns']['userid'] 	= 'iduser';
	$result['sel_columns']['ip'] 		= 'ip';
	$result['sel_columns']['email']		= 'email';
	$result['sel_columns']['notify']	= 'subscribe';
	$result['sel_columns']['website']	= 'web';
	$result['sel_columns']['title']		= 'title';
	$result['sel_columns']['comment']	= 'comment';
	$result['sel_columns']['published']	= 'published';
	$result['sel_columns']['voting_yes'] = 'rating'; /* better than nothing */
	$result['sel_columns']['voting_no']  = '';
	$result['sel_columns']['parentid']	= 'parentid';

	return( $result );
    }

    function getImport_AkoComment($checktable=false) {
	$database =& JFactory::getDBO();


	if (JOSC_TableUtils::existsTable('#__akocomment') == true) {
	    if (!JOSC_TableUtils::TableColumnsGet( '#__akocomment' )) {
		return;
	    }
	} else {
	    return;
	}
	$result['fromtable'] = $database->getPrefix().'akocomment';
	 /* ['sel_columns'][joscolumn] = component_column */
	$result['sel_columns']['componentfield']	= '';
	$result['sel_columns']['id'] 		= 'id';
	$result['sel_columns']['contentid'] = 'contentid';
	$result['sel_columns']['date'] 		= 'date';
	$result['sel_columns']['name'] 		= 'name';
	$result['sel_columns']['userid'] 	= 'iduser';
	$result['sel_columns']['ip'] 		= 'ip';
	$result['sel_columns']['email']		= 'email';
	$result['sel_columns']['notify']	= 'subscribe';
	$result['sel_columns']['website']	= 'web';
	$result['sel_columns']['title']		= 'title';
	$result['sel_columns']['comment']	= 'comment';
	$result['sel_columns']['published']	= 'published';
	$result['sel_columns']['voting_yes'] = '';
	$result['sel_columns']['voting_no']  = '';
	$result['sel_columns']['parentid']	= 'parentid';

	return( $result );
    }

    function getImport_JReaction($checktable=false) {
	$database =& JFactory::getDBO();


	if (JOSC_TableUtils::existsTable( '#__jreactions' )) {
	    if (!JOSC_TableUtils::TableColumnsGet( '#__jreactions' )) {
		return;
	    }
	} else {
	    return;
	}
	

	$result['fromtable'] = $database->getPrefix().'jreactions';
	 /* ['sel_columns'][joscolumn] = component_column */
	$result['sel_columns']['componentfield']	= '';
	$result['sel_columns']['id'] 		= 'id';
	$result['sel_columns']['contentid'] = 'contentid';
	$result['sel_columns']['date'] 		= 'date';
	$result['sel_columns']['name'] 		= 'name';
	$result['sel_columns']['userid'] 	= 'userid';
	$result['sel_columns']['ip'] 		= 'ip';
	$result['sel_columns']['email']		= 'email';
	$result['sel_columns']['notify']	= ''; //'subscribe';
	$result['sel_columns']['website']	= 'website';
	$result['sel_columns']['title']		= 'title';
	$result['sel_columns']['comment']	= 'comments';
	$result['sel_columns']['published']	= 'published';
	$result['sel_columns']['voting_yes'] = 'rank'; /* better than nothing */
	$result['sel_columns']['voting_no']  = '';
	$result['sel_columns']['parentid']	= '';

	return( $result );
    }

    function getImport_JomComment($checktable=false) {
	$database =& JFactory::getDBO();


	if (JOSC_TableUtils::existsTable( '#__jomcomment' )) {
	    if (!JOSC_TableUtils::TableColumnsGet( '#__jomcomment' )){
		return;
	    }
	} else {
	    return;
	}

	$result['fromtable'] = $database->getPrefix().'jomcomment';
	 /* ['sel_columns'][joscolumn] = component_column */
	$result['sel_columns']['componentfield']	= 'option';
	$result['sel_columns']['id'] 		= 'id';
	$result['sel_columns']['contentid'] = 'contentid';
	$result['sel_columns']['date'] 		= 'date';
	$result['sel_columns']['name'] 		= 'name';
	$result['sel_columns']['userid'] 	= 'user_id';
	$result['sel_columns']['ip'] 		= 'ip';
	$result['sel_columns']['email']		= 'email';
	$result['sel_columns']['notify']	= ''; //'subscribe';
	$result['sel_columns']['website']	= 'website';
	$result['sel_columns']['title']		= 'title';
	$result['sel_columns']['comment']	= 'comment';
	$result['sel_columns']['published']	= 'published';
	$result['sel_columns']['voting_yes'] = 'star'; /* better than nothing */
	$result['sel_columns']['voting_no']  = '';
	$result['sel_columns']['parentid']	= 'parentid';

	return( $result );
    }

    ?>