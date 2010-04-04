<?php
/*
// JoomlaWorks "Tabs & Slides" Module for Joomla! 1.5.x - Version 1.0
// License: http://www.gnu.org/copyleft/gpl.html
// Copyright (c) 2006 - 2008 JoomlaWorks, a Komrade LLC company.
// More info at http://www.joomlaworks.gr
// Developers: Fotis Evangelou - George Chouliaras
// ***Last update: May 8th, 2008***
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

global $mainframe;

// Module Parameters
$moduleclass_sfx		= $params->get('moduleclass_sfx','');
$jwts_id 				= trim($params->get('jwts_id','tabsnslides'));
$jwts_position			= trim($params->get('jwts_position','left'));
$jwts_displaytype		= $params->get('jwts_displaytype','tabs');
$jwts_showmodtitle		= intval($params->get('jwts_showmodtitle',0));

// JS loader selection
$use_optimized_loader	= 0; // Use optimized JS code loader? 0=no and 1=yes. Default is 0.

// Assign live site path
$mosConfig_live_site = JURI::base();

// Load the position
$jwts_document	= &JFactory::getDocument();
$jwts_renderer	= $jwts_document->loadRenderer('module');
$jwts_params	= array('style'=>'none');

// Load CSS and JS files in the head
global $loadJWTSscripts;
if(!$loadJWTSscripts){
	$loadJWTSscripts = 1;
	$jwts = '
	<!-- JoomlaWorks "Tabs & Slides" Module (v1.0) starts here -->
	<style type="text/css" media="screen">
		@import "'.$mosConfig_live_site.'modules/mod_jw_ts/mod_jw_ts/tabs_slides.css";
	</style>
	<style type="text/css" media="print">
		.jwts_tabbernav{display:none;}
	</style>
	<script type="text/javascript" src="'.$mosConfig_live_site.'modules/mod_jw_ts/mod_jw_ts/tabs_slides_comp.js"></script>
';
if($use_optimized_loader) {
	$jwts .= '
	<script type="text/javascript" src="'.$mosConfig_live_site.'modules/mod_jw_ts/mod_jw_ts/tabs_slides_opt_loader.js"></script>
	';
} else {	
	$jwts .= '
	<script type="text/javascript" src="'.$mosConfig_live_site.'modules/mod_jw_ts/mod_jw_ts/tabs_slides_def_loader.js"></script>
	';
}	
$jwts .= '
	<!-- JoomlaWorks "Tabs & Slides" Module (v1.0) ends here -->
	';
	$mainframe->addCustomHeadTag($jwts);
}
?>

<!-- JoomlaWorks "Tabs & Slides" Module (v1.0) starts here -->
<?php if ($jwts_displaytype=="tabs") { ?>
<div class="jwts_tabber" id="<?php echo $jwts_id; ?>">
<?php } elseif ($jwts_displaytype=="slides") { ?>
<div class="jwts_slider" id="<?php echo $jwts_id; ?>">
<?php }

foreach (JModuleHelper::getModules($jwts_position) as $jwts_module)  {
	if ($jwts_displaytype=="tabs") {
		echo '<div class="jwts_tabbertab" title="'.JText::_( $jwts_module->title ).'"><h2><a href="javascript:void(null);" name="advtab">'.JText::_( $jwts_module->title ).'</a></h2>';
	} elseif ($jwts_displaytype=="slides") {
		echo '<div class="jwts_title"><div class="jwts_title_left"><a href="javascript:void(null);" title="Click to open!" class="jwts_title_text">'.JText::_( $jwts_module->title ).'</a></div></div><div class="jwts_slidewrapper"><div>';
	}
	
	// Show/hide module title
	if ($jwts_showmodtitle) {echo '<div class="jwts_modtitle">'.JText::_( $jwts_module->title ).'</div>';}
	
	// Output
	echo $jwts_renderer->render($jwts_module, $jwts_params);
		
	if ($jwts_displaytype=="tabs") {
		echo '</div>';
	} elseif ($jwts_displaytype=="slides") {
		echo '</div></div>';
	}
}
?>
</div>
<div class="jwts_clr"></div>
<!-- JoomlaWorks "Tabs & Slides" Module (v1.0) ends here -->
