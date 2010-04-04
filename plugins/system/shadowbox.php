<?php
/**
* Shadowbox Joomla! Plugin v3.0rc1
*
* @author Joe Palmer
* @copyright Copyright (C) 2009 SoftForge Ltd. - http://www.softforge.co.uk
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onAfterDispatch', 'plgSystemShadowbox' );

/**
* Plugin that adds the shadowbox effect
*/
function plgSystemShadowbox() {

	$app      =& JFactory::getApplication();
	$document =& JFactory::getDocument();

	// check if site is active
	if (!($app->getName() == 'site' && is_a($document, 'JDocumentHTML'))) {
		return true;
	}
	
	// get plugin info
	$plugin =& JPluginHelper::getPlugin('system', 'shadowbox');
 	$params = new JParameter($plugin->params);
	$plugin_base = JURI::base() . 'plugins/system/shadowbox/';

	// check whether plugin has been unpublished
	if (!$params->get('enabled', 1)) {
		return true;
	}

	// get parameters
	$imgPlayer = $params->get('imgPlayer', '1');
	$swfPlayer = $params->get('swfPlayer', '0');
	$flvPlayer = $params->get('flvPlayer', '0');
	$qtPlayer = $params->get('qtPlayer', '0');
	$wmpPlayer = $params->get('wmpPlayer', '0');
	$iframePlayer = $params->get('iframePlayer', '0');
	$htmlPlayer = $params->get('htmlPlayer', '0');
	$adapter = $params->get('adapter', 'auto');
	$animate = $params->get('animate', '1');
	$animateFade = $params->get('animateFade', '1');
	$animSequence = $params->get('animSequence', 'sync');
	$autoDimensions = $params->get('autoDimensions', '0');
	$autoplayMovies = $params->get('autoplayMovies', '1');
	$continuous = $params->get('continuous', '0');
	$counterLimit = $params->get('counterLimit', '10');
	$counterType = $params->get('counterType', 'default');
	$displayCounter = $params->get('displayCounter', '1');
	$displayNav = $params->get('displayNav', '1');
	$enableKeys = $params->get('enableKeys', '1');
	$fadeDuration = $params->get('fadeDuration', '0.35');
	$handleOversize = $params->get('handleOversize', 'resize');
	$handleUnsupported = $params->get('handleUnsupported', 'link');
	$initialHeight = $params->get('initialHeight', '160');
	$initialWidth = $params->get('initialWidth', '320');
	$language = $params->get('language', 'shadowbox-en');
	$modal = $params->get('modal', '1');
	$overlayColor = $params->get('overlayColor', '#000');
	$overlayOpacity = $params->get('overlayOpacity', '0.8');
	$resizeDuration = $params->get('resizeDuration', '0.35');
	$showMovieControls = $params->get('showMovieControls', '1');
	$showOverlay = $params->get('showOverlay', '1');
	$slideshowDelay = $params->get('slideshowDelay', '0');
	$viewportPadding = $params->get('viewportPadding', '20');
	$skipSetup = $params->get('skipSetup', '1');
	$useSizzle = $params->get('useSizzle', '1');
	
	// build javascript for <head>
	$javascript = '';
	$javascript .= '<script type="text/javascript" src="'.$plugin_base.'shadowbox.js"></script>';
	$javascript .= '<script type="text/javascript">';
	$javascript .= 'Shadowbox.init({ ';
	
	// build players array
	$players = '';
	if ($imgPlayer) $players .= '"img", ';
	if ($swfPlayer) $players .= '"swf", ';
	if ($flvPlayer) $players .= '"flv", ';
	if ($qtPlayer) $players .= '"qt", ';
	if ($wmpPlayer) $players .= '"wmp", ';
	if ($iframePlayer) $players .= '"iframe", ';
	if ($htmlPlayer) $players .= '"html", ';
	$javascript .= 'players: ['.substr($players, 0, -2).'], ';
	
	// build other parameters
	if ($adapter != "auto") $javascript .= 'adapter: "'.$adapter.'", ';
	if (!$animate) $javascript .= 'animate: false, ';
	if (!$animateFade) $javascript .= 'animateFade: false, ';
	if ($animSequence != "sync") $javascript .= 'animSequence: "'.$animSequence.'", ';
	if (!$autoplayMovies) $javascript .= 'autoplayMovies: false, ';
	if ($autoDimensions) $javascript .= 'autoDimensions: true, ';
	if ($continuous) $javascript .= 'continuous: true, ';
	if ($counterLimit != 10) $javascript .= 'counterLimit: '.$counterLimit.', ';
	if ($counterType != "default") $javascript .= 'counterType: "'.$counterType.'", ';
	if (!$displayCounter) $javascript .= 'displayCounter: false, ';
	if (!$displayNav) $javascript .= 'displayNav: false, ';
	if (!$enableKeys) $javascript .= 'enableKeys: false, ';
	if ($fadeDuration != 0.35) $javascript .= 'fadeDuration: '.$fadeDuration.', ';
	if ($handleOversize != "resize") $javascript .= 'handleOversize: "'.$handleOversize.'", ';
	if ($handleUnsupported != "link") $javascript .= 'handleUnsupported: "'.$handleUnsupported.'", ';
	if ($initialHeight != 160) $javascript .= 'initialHeight: '.$initialHeight.', ';
	if ($initialWidth != 320) $javascript .= 'initialWidth: '.$initialWidth.', ';
	if ($language != "shadowbox-en") $javascript .= 'language: "'.str_replace("shadowbox-", "", $language).'", ';
	if (!$modal) $javascript .= 'modal: false, ';
	if ($overlayColor != "#000") $javascript .= 'overlayColor: "'.$overlayColor.'", ';
	if ($overlayOpacity != 0.8) $javascript .= 'overlayOpacity: '.$overlayOpacity.', ';
	if ($resizeDuration != 0.35) $javascript .= 'resizeDuration: '.$resizeDuration.', ';
	if (!$showMovieControls) $javascript .= 'showMovieControls: false, ';
	if (!$showOverlay) $javascript .= 'showOverlay: false, ';
	if ($slideshowDelay != 0) $javascript .= 'slideshowDelay: '.$slideshowDelay.', ';
	if ($viewportPadding != 20) $javascript .= 'viewportPadding: '.$viewportPadding.', ';
	if (!$skipSetup) $javascript .= 'skipSetup: false, ';
	if (!$useSizzle) $javascript .= 'useSizzle: false, ';
	$javascript = substr($javascript, 0, -2);
	$javascript .= ' });</script>';

	// add javascript and css
	$document->addCustomTag($javascript);
	$document->addStyleSheet($plugin_base . 'shadowbox.css');
}