<?php
defined('_JEXEC')  or die('Restricted access');

class JOSC_com_eventlist extends JOSC_component {
//	var $_component=''; /* JOSC_component */
//	var	$_id=0; 		/* JOSC_component */
	/* specific properties of REPLACEnewplugin if needed */
//	var _specific_data;

	function JOSC_com_eventlist($component,&$row,&$list)
	{
		$id	= isset($row->did) ? $row->did : 0; /* document id */

		$this->setRowDatas($row); /* get specific properties */
					
		$this->JOSC_component($component,0,$id);
	}

	/*
	 * Set specific properties
	 * will be called also in admin.html.php manage comments during row loop
	 */
	function setRowDatas(&$row)
	{
		/* for optimization reason, do not save row. save just needed parameters */

		//$this->_specific_data	= isset($row->specific) ? $row->specific : '';
	}
	    
	/*
	 * This function is executed to check 
	 * if section/category of the row are authorized or not (exclude/include from the setting)
	 * return : true for authorized / false for excluded
	 * obj :
	 * - _include_sc
	 * - _exclude_sections
	 * - _exclude_categories
	 * - _exclude_contentitems 
	 * 
	 */
	function checkSectionCategory(&$row, &$obj)
	{	
		$include	= isset($obj->_include_sc) ? $obj->_include_sc : false;
		$sections	= isset($obj->_exclude_sections) ? $obj->_exclude_sections : array();
		$catids		= isset($obj->_exclude_categories) ? $obj->_exclude_categories : array();
		$contentids = isset($obj->_exclude_contentids) ? $obj->_exclude_contentids : array() ;
		$contentitems = isset($obj->_exclude_contentitems) ? $obj->_exclude_contentitems : array() ;
		
		/* content item excluded ? DO NOT USE ANYMORE */
   		if (in_array((($row->id == 0) ? -1 : $row->id), $contentids))
   			return false; 		

		/* 
		 * Include =  include only : selected Ids OR selected Sections OR Selected Categories)
		 * Exclude =  exclude selected Ids OR selected Sections OR Selected Categories
		 */

		/* content ids */
		if (count($contentitems)>0) {
       		$result = in_array((($row->id == 0) ? -1 : $row->id), $contentitems);
       		if ($include && $result) 	return true; 	/* include and selected */
			if (!$include && $result) 	return false; 	/* exclude and selected */ 
		}

		/* sections (docman categories) */
    	$result = in_array((($row->catsid == 0) ? -1 : $row->catsid), $sections);
   		if ($include && $result) 	return true; 	/* include and selected */
		if (!$include && $result) 	return false; 	/* exclude and selected */ 

		if ($include) 	return false; /* was not included */
		if (!$include)	return true; /* was not excluded */
	}

    /*
     * Condition to active or not the display of the post and input form
     * If the return is false, show readon will be executed.
     */
	function checkVisual($contentId=0)
    {	
    	global $option, $task;
    	
    	$func = mosGetParam( $_REQUEST, 'func' );
    	
    	return  (		$option == 'com_eventlist' 
    				&& 	$func == 'details' 
    			);	
    }
	
	/*
	 * This function will active or deactivate the show readon display 
	 */	
	function setShowReadon( &$row, &$params, &$config )
	{
        $show_readon 	= $config->_show_readon;

        return $show_readon;  
	}
	
    /*
     * construct the link to the content item  
     * (and also direct to the comment if commentId set)
     */
    function linkToContent($contentId, $commentId='', $joscclean=false, $admin=false)
    {
    	global $mainframe;

		$add = ( $joscclean ? "&joscclean=1" : "" ) . ( $commentId ? "&comment_id=$commentId#josc$commentId" : "" ) ;
		$menuid = $this->getItemid();
		
		$url	= ($admin ? "/" : "" )
				. "index.php?option=com_eventlist&func=details&did=$contentId" 
				. ( $menuid ? "&Itemid=$menuid" : "" );
				;
		$url 	.= $add;
		if ($admin) 
			$url = JURI::base().$url;

		$url	= function_exists('sefRelToAbs') ? sefRelToAbs($url) : $url; /* does not exist in admin part */

    	return ($url);
    }

    /*
     * clean the cache of the component when comments are inserted/modified...
     * (if cache is active) 
     */
    function cleanComponentCache() {
            mosCache::cleanCache( 'com_eventlist' );
    }

	/*
	 *  getItemid
	 */
	function getItemid( $component='com_eventlist') {
    	static $ids;
        if( !isset($ids) ) {
        	$ids = array();
        }
        if( !isset($ids[$component]) ) {
        	global $database;
            $query = "SELECT id FROM #__menu"
                    ."\n WHERE link LIKE '%option=$component%'"
                    ."\n AND type = 'component'"
                    ."\n AND published = 1 LIMIT 1";
            $database->setQuery($query);
            $ids[$component] = $database->loadResult();
        }
        return $ids[$component];
    }

	/*----------------------------------------------------------------------------------
	 *  F U N C T I O N S   F O R   A D M I N   P A R T 
	 *----------------------------------------------------------------------------------
	 */
	 
    /*
     * section option list used to display the include/exclude section list in setting 
     * must return an array of objects (id,title)
     */
    function getSectionsIdOption() 
    {	
    	global $database;
    	
    	$sectoptions = array();
		$query 	= "SELECT id, catname as title"
				. "\n FROM #__eventlist_categories"
				. "\n WHERE publishedcat = 1"
//				. "\n AND section = 'com_eventlist' "
//				. "\n AND access >= 0"    
				. "\n ORDER BY ordering"
				;
		$database->setQuery( $query );
		$sectoptions = $database->loadObjectList();
		
		return $sectoptions;
    }

    /*
     * categories option list used to display the include/exclude category list in setting 
     * must return an array of objects (id,title)
     */
    function getCategoriesIdOption() 
    {	
    	global $database;
    	
    	$catoptions = array();
		
		return $catoptions;
    }

	/*
	 * document list (or single) for new and edit comment
	 * must return an array of objects (id,title)
	 */
	function getObjectIdOption($id=0, $select=true)
	{
    	global $database;
    	
    	$content = array();
		$query 	= "SELECT id, titel AS title"
				. "\n FROM #__eventlist_dates "
				. "  WHERE published=1"
				. ($id ? "\n AND id = $id":"")   
				;
		$database->setQuery( $query );
		$content = $database->loadObjectList();
		if (!$id && $select && count($content)>0) { 
			array_unshift( $content, JHTML::_('select.option', '0', '-- Select Document --', 'id', 'title' ) );
		}

		return $content;		
	}
	function getViewTitleField()
	{
        $title = 'titel';
        return($title);
	}
	function getViewJoinQuery($alias, $contentid)
	{
		$leftjoin	= "\n LEFT JOIN #__eventlist_dates  AS $alias ON $alias.id = $contentid ";
		return $leftjoin;
	}

	/*----------------------------------------------------------------------------------
	 *  F U N C T I O N S   F O R   MOD_COMMENTS   M O D U L E 
	 *----------------------------------------------------------------------------------
	 */
	function mod_commentsGetMostCommentedQuery($secids, $catids, $maxlines)
	{
		global $database;

		$component = $this->_component;
		
		$limit = $maxlines>=0 ? " limit $maxlines " : "";

		$where = array();
		if ($secids)
			$where[] = "cat.id IN ($secids)";

	    /* 
	     * Count comment id group by contentid 
   	 	 * TODO: restrict according to user rights, dates and category/secitons published and dates...
	     */
	    $query 	=  	"SELECT COUNT(c.id) AS countid, ct.id, ct.titel AS title "
				.	"\n FROM `#__comment`    AS c "
				.  	"\n INNER JOIN `#__eventlist_dates`  AS ct  ON ct.id = c.contentid "
	       		.  	"\n INNER JOIN `#__eventlist_categories` AS cat ON cat.id = ct.catsid "
				.  	"\n WHERE c.published='1' "
				.	"\n   AND c.component='$component' "
				.  	"\n ". (count($where) ? (" AND ".implode(" AND ", $where)) : "")
	    		.	"\n GROUP BY c.contentid"
	    		.	"\n ORDER BY countid DESC"
				.	"\n $limit"
	    		;
		$database->SetQuery($query);
		$rows = $database->loadAssocList();

		return $rows;		
	}
	
	function mod_commentsGetOthersQuery($secids, $catids, $maxlines, $orderby)
	{
		global $database;

		$component = $this->_component;
		
		$limit = $maxlines>=0 ? " limit $maxlines " : "";

		$where = array();
		if ($secids)
			$where[] = "cat.id IN ($secids)";

		if ($orderby=='mostrated') {
			$mostrated =  ", (c.voting_yes-c.voting_no)/2 AS mostrated";
			$where[]  = "(c.voting_yes > 0 OR c.voting_no > 0)";
		} else {
			$mostrated = "";
			$orderby = "c.$orderby";
		}

    	/*
   	 	 * TODO: restrict according to user rights, dates and category/secitons published and dates...
   	 	 */
		$query 	=  "SELECT c.*, ct.titel AS ctitle $mostrated "
				.  "\n FROM `#__comment`    AS c "
				.  "\n INNER JOIN `#__eventlist_dates`    AS ct  ON ct.id = c.contentid "
        		.  "\n INNER JOIN `#__eventlist_categories` AS cat ON cat.id = ct.catsid "
				.  "\n WHERE c.published='1' "
				.	"\n  AND c.component='$component' "
				.  "\n ". (count($where) ? (" AND ".implode(" AND ", $where)) : "")
				.  "\n ORDER BY $orderby DESC "
				.  "\n $limit"
				;
		$database->SetQuery($query);
		$rows = $database->loadAssocList();
		
		return $rows;
	}
	
/*
 * O P T I O N N A L
 * F O R   E X P E R T   M O D E  O N L Y
 * not yet available.
 * do not report theses functions !  
 */
    /*
     * section option list for the admin setting
     */
    function getExpertSectionIdOption($sections, $include) 
    {	
    	global $database;
    	
    	$where = "";
    	if ($sections) {
    		if ($include)
    			$where = "\n AND id IN ($sections) ";
    		else
	    		$where = "\n AND id NOT IN ($sections) ";
    	}    			
    	$sectoptions = array();
		$query 	= "SELECT id, catname as title"
				. "\n FROM #__eventlist_categories"
				. "\n WHERE publishedcat = 1"
				. $where
//				. "\n AND access >= 0"    
				. "\n ORDER BY ordering"
				;
		$database->setQuery( $query );
		$sectoptions = $database->loadObjectList();
		// add "All sections"
		array_unshift( $sectoptions, JHTML::_('select.option', '0', '-- All --', 'id', 'title' ) );
		
		return $sectoptions;
    }
    
    /*
     * return the id,title section object
     */
    function getExpertSectionTitle($sectionid)
    {
    	global $database;
    	   		
    	if ($sectionid==0) 
    		return '(0) All ';

    	$query 	= "SELECT id, catname as title"
				. "\n FROM #__eventlist_categories"
				. "\n WHERE id=$sectionid"
				;    	
		$database->setQuery( $query );
		$row = $database->loadObjectList();
		return "($sectionid) ".$row[0]->title;
    }
        
}
?>