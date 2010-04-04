<?php
/**
 * @version		$Id: M17n.php 8152 2008-03-01 08:00:18Z jj $
 * @package		Joomla M17n
 * @copyright	Copyright (C) 2005 - 2007 SDIC SA
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/

/**
 * CHANGELOG:
 * 1.5.1.b
 * March 12, Added submission from Tobias Frash for Legacy Fix
 * March 12, Fixed Itemid retrieval for SEF configuration
 * 1.5.2
 * April 15, Fixed 1.5.2 menu administration bug (Javascript ID)
 * April 15, Added auto-dection of language from url itemid
 * 
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Joomla! System Multilingualization (M17n) Plugin
 *
 * @author		Jason Johnston <joomla@sdic.ch>
 * @package		SDIC :: Joomla! M17n
 * @subpackage	System
 */

class plgSystemM17n extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemM17n(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	function onAfterInitialise()
	{
		global $mainframe;

		global $nowLang,$langList,$homeList;

		$langObj=JFactory::getLanguage();
		$nowLang=$langObj->getTag();
		$defLang=$langObj->getDefault();
		if($nowLang=='')$nowLang=$defLang;
		$langDB=JFactory::getDBO();
		$menuDB=JFactory::getDBO();
		
	### Submitted by Nick Davina
        $itemid = JRequest::getVar('Itemid','-1');
        if( $itemid > 0 ) {
            $tmpDb = JFactory::getDBO();
            $tmpDb->setQuery("SELECT params FROM `#__menu` WHERE id = '$itemid' LIMIT 1");
            $tmpParams = new JRegistry();
            $tmpParams->loadINI( $tmpDb->loadResult() );               
            $urlLang = $tmpParams->getValue('lang','');
            if( $urlLang!='' && $urlLang != $nowLang ) $nowLang = $urlLang;
        }
    ### End of contribution, thank you Nick ;)
		
		$getLinks=$this->params->get('GetLinkage', 'auto');
		$getItems=$this->params->get('GetItemId', 'auto');
		$setItems=$this->params->get('CustItemId', '');
		
		// Retrieve User Defined Itemids
		if($getItems=='manu'){
			$splitItems=Array();
			$customItems=Array();
			$splitItems=split('[;|,]',$setItems);
			foreach($splitItems as $sp){
				$item=split('=',$sp);
				if(count($item)==2)$customItems[$item[0]]=$item[1];
			}
		}
		
		// Define Itemids for Language Selector (builds the homeList array)
		$langs=JLanguage::getKnownLanguages(JPATH_ROOT);
		foreach($langs as $lng){
			$langObj=Array();
			$tag=$lng['tag'];
			$langObj['value']=$tag;
			$langObj['text']=$lng['name'];
			$langList[]=$langObj;
			
			// Joomla can't handle Itemids Requests alone, link is needed
			// HomeList will now contain both the itemid and the link
			
			if($getItems=='manu' && isset($customItems[$tag]) && $customItems[$tag]!=""){
				$langItem=$customItems[$tag];
				// Implements HomeList SubArray for manual items
				$langDB->setQuery("SELECT link FROM `#__menu` WHERE id = '$langItem' LIMIT 1");
				$langLink=$langDB->loadResult();
				$homeList[$lng['tag']]=Array('item'=>$langItem, 'link'=>$langLink);
			}else{
			### Added Link Inclusion in Home List
				$langDB->setQuery("SELECT id AS item,link FROM `#__menu` WHERE parent=0 AND published=1 AND params LIKE '%lang=$lng[tag]%' ORDER by ordering ASC LIMIT 1");
				$homeRecs=$langDB->loadAssocList();
				$homeList[$lng['tag']]=$homeRecs[0];
			}
		}
		
		// M17n Associative Keys Retrieval (to overwrite homeList when matching keys are found)
		if($getLinks!='none' && $getItems!='manu'){
			if($getLinks=='pure'){
				// Strict mode only displays language if matching key is found
				foreach($homeList as $k=>$v){
					// Unset all other languages
					if($k!=$nowLang)$homeList[$k]="";
				}
			}
			$nowId=JRequest::getVar('Itemid','');
			
			// Detect Sef Configuration
			$joomSef=$mainframe->getCfg('sef');
			if($nowId=='' && $joomSef){
				// Retrieve Itemid if SEF enabled
				$uri = clone(JURI::getInstance());
				$router=$mainframe->getRouter();
				$valURL=$router->parse($uri);
				if(isset($valURL['Itemid']))$nowId=$valURL['Itemid'];
			}
			
			if($nowId!=''){
				$menuDB->setQuery("SELECT params FROM `#__menu` WHERE id='$nowId'");
				$nowParams=new JRegistry();
				$nowParams->loadINI($menuDB->loadResult());
			//	$nowKey=$nowParams->get('multikey','');
			###	Submitted by Tobias Frasch
				$nowKey=$nowParams->getValue('multikey','');
				if($nowKey!=''){
				//	$menuDB->setQuery("SELECT id,params FROM `#__menu` WHERE params LIKE '%multikey=$nowKey%' AND id!='$nowId'");
					###	Submitted by Tobias Frasch
					$menuDB->setQuery("SELECT id,link,params FROM `#__menu` WHERE (params LIKE '%multikey=$nowKey\n%' OR params LIKE '%multikey=$nowKey') AND id!='$nowId'");
					$sameRecs=$menuDB->loadAssocList();
					if(is_array($sameRecs) && !empty($sameRecs)){
						// Reverse Array so first item may override last one
						$sameItems=array_reverse($sameRecs);
						foreach($sameItems as $same){
							$sameItemid=$same['id'];
							$sameParams=new JRegistry();
							$sameParams->loadINI($same['params']);
						//	$sameLang=$sameParams->get('lang','');
							###	Submitted by Tobias Frasch
							$sameLang=$sameParams->getValue('lang','');
							// Overwrite homeList Items
							if($sameLang!='' && $sameLang!=$nowLang && $sameItemid!=''){
							//	$homeList[$sameLang]=$sameItemid;
								$homeList[$sameLang]=array("item" => $sameItemid, "link" => $same['link']);
							}
						}
					}
				}
			}
		}
	}
}
	
?>