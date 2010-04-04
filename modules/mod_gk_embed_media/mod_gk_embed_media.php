<?php

/**
* Gavick Embed Media v.1.0
* @package Joomla!
* @Copyright (C) 2008 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.5 $
**/

/**
	access restriction
**/

defined('_JEXEC') or die('Restricted access');

/**
	Loading helper class
**/

require_once (dirname(__FILE__).DS.'helper.php');

$gk = & new gkEmbedMediaHelper();

$gk->validateVariables($params);
$gk->renderLayout();

?>