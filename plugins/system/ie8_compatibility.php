<?php

/**
* IE8 Compatibility Mambot
* Author: Jeremy.J <joly.jeremy@gmail.com>
* Copyright (C) 2009 All Rights Reserved
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class  plgSystemIE8_Compatibility extends JPlugin
{

	function plgSystemIE8_Compatibility(& $subject, $config)
	{
	
		parent::__construct($subject, $config);

		//load the translation
		$this->loadLanguage( );
	}

	function onAfterInitialise()
	{
		$document	=& JFactory::getDocument();
		$doctype	= $document->getType();

		// Only handle HTML output
		if ( $doctype !== 'html' ) { return; }

        // Get plugin parameters
        $mode = $this->params->get('compatibility_mode', 'EmulateIE7');
        $method = $this->params->get('method', 'meta');
        
        switch($method){
            case 'meta':
                $document->setMetaData('X-UA-Compatible', 'IE=' . $mode, true);
                break;
                
            case 'header':
                header('X-UA-Compatible: IE=' . $mode);
                break;
        }
        
        
	}
}