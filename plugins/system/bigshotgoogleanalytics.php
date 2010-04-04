<?php
######################################################################
# BIGSHOT Google Analytics          	          	          	       #
# Copyright (C) 2008 by BIGSHOT  	   	   	   	   	   	   	   	   	   #
# Homepage   : www.thinkBIGSHOT.com		   	   	   	   	   	   		   	 #
# Author     : Kenneth Crowder    		   	   	   	   	   	   	   	   #
# Email      : KenC@thinkBIGSHOT.com 	   	   	   	   	   	   	       #
# Version    : 1.5.2                        	   	    	   	   	   	 #
# License    : http://www.gnu.org/copyleft/gpl.html GNU/GPL          #
######################################################################

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemBigshotgoogleanalytics extends JPlugin
{
	function plgSystemBigshotgoogleanalytics(&$subject, $config)
	{
		parent::__construct($subject, $config);
		
    $this->_plugin = JPluginHelper::getPlugin( 'system', 'bigshotgoogleanalytics' );
    $this->_params = new JParameter( $this->_plugin->params );
	}
	
	function onAfterRender()
	{
		global $mainframe;
		
		$web_property_id = $this->params->get('web_property_id', '');
		
		if($web_property_id == '' || $mainframe->isAdmin() || strpos($_SERVER["PHP_SELF"], "index.php") === false)
		{
			return;
		}

    $buffer = JResponse::getBody();

		$google_analytics_javascript = '
			<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("'.$web_property_id.'");
			pageTracker._trackPageview();
			} catch(err) {}</script>
			';

		$pos = strrpos($buffer, "</body>");
		
		if($pos > 0)
		{
			$buffer = substr($buffer, 0, $pos).$google_analytics_javascript.substr($buffer, $pos);

			JResponse::setBody($buffer);
		}
		
		return true;
	}
}
?>