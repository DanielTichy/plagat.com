<?php
/**
* @version		$Id: veevaamodule.php 1 2009-05-31 19:34:56Z solo $
* @package		veevaamodule
* @copyright	Copyright (C) 2005 - 2008 www.veevaa.com. All rights reserved.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onPrepareContent', 'plgContentVeevaaModule' );

function plgContentVeevaaModule( &$row, &$params, $page=0 )
{
	$db =& JFactory::getDBO();
	// simple performance check to determine whether bot should process further
	if ( JString::strpos( $row->text, 'vvmodule' ) === false ) {
		return true;
	}

	// Get plugin info
	$plugin =& JPluginHelper::getPlugin('content', 'veevaamodule');
	
 	// expression to search for
 	$regex = '/{vvmodule\s*.*?}/i';

 	$pluginParams = new JParameter( $plugin->params );

	// check whether plugin has been unpublished
	if ( !$pluginParams->get( 'enabled', 1 ) ) {
		$row->text = preg_replace( $regex, '', $row->text );
		return true;
	}

 	// find all instances of plugin and put in $matches
	preg_match_all( $regex, $row->text, $matches );

	// Number of plugins
 	$count = count( $matches[0] );

 	// plugin only processes if there are any instances of the plugin in the text
 	if ( $count ) {
		// Get plugin parameters
	 	$style	= $pluginParams->def( 'style', 'raw' );
 		plgContentProcessVeevaaModule( $row, $matches, $count, $regex, $style );
	}
}

function plgContentProcessVeevaaModule ( &$row, &$matches, $count, $regex, $style='raw' )
{
	for ( $i=0; $i < $count; $i++ )
	{
		$regexid	= '/[^\d]/i';
		$regexstyle	=	'/[\d]|vvmodule|{|}/i';
		
 		$id	= preg_replace( $regexid, '', $matches[0][$i] );
 		
 		$realstyle	= preg_replace( $regexstyle, '', $matches[0][$i] );
 		trim($realstyle);
 		
 		if($realstyle==' '){
 			$realstyle	= $style;
 		}
 		
		$module		= plgContentFetchModule( $id, $realstyle );
		
		$row->text 	= str_replace($matches[0][$i], $module, $row->text );
 	}

  	// removes tags without matching module positions
	$row->text = preg_replace( $regex, '', $row->text );
}

function plgContentFetchModule( $mid, $style='raw' )
{
	global $mainframe, $Itemid;

	$mid	= (int) $mid;
	$user	=& JFactory::getUser();
	$db		=& JFactory::getDBO();
	$aid	= $user->get('aid', 0);
	$query = 'SELECT id, title, module, position, content, showtitle, control, params'
		. ' FROM #__modules AS m'
		. ' WHERE'
		. ' id = '.$mid
		. ' ORDER BY position, ordering';

	$db->setQuery( $query );

	if (null === ($module = $db->loadObject())) {
		JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'Error Loading Modules' ) . $db->getErrorMsg());
		return false;
	}

	//determine if this is a custom module
	$file					= $module->module;
	$custom 				= substr( $file, 0, 4 ) == 'mod_' ?  0 : 1;
	$module->user  	= $custom;
	// CHECK: custom module name is given by the title field, otherwise it's just 'om' ??
	$module->name		= $custom ? $module->title : substr( $file, 4 );
	$module->style		= null;
	$module->position	= strtolower($module->position);
	
	$document = &JFactory::getDocument();

	$renderer = $document->loadRenderer( 'module' );
	$options = array( 'style' => $style );
	$finalmodule = false;
	$finalmodule = $renderer->render($module, $options );
	return $finalmodule;
}