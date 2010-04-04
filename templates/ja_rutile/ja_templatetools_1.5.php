<?php
/*------------------------------------------------------------------------
# JA Rutile for Joomla 1.5 - Version 1.0 - Licence Owner JA122250
# ------------------------------------------------------------------------
# Copyright (C) 2004-2008 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
-------------------------------------------------------------------------*/
define ('JA_TOOL_COLOR', 'ja_color');
define ('JA_TOOL_SCREEN', 'ja_screen');
define ('JA_TOOL_FONT', 'ja_font');
define ('JA_TOOL_LAYOUT', 'ja_layout');
define ('JA_TOOL_BODY', 'ja_body');
define ('JA_TOOL_MENU', 'ja_menu');
define ('JA_TOOL_USER', 'usertool');
class JA_Tools {
	var $_params_cookie = array(); //Params will store in cookie for user select. Default: store all params
	var $_tpl = null;
	var $template = '';
	//This default value could override by setting with function setScreenSizes() and setColorThemes()
	var $_ja_layouts = null;
	var $_ja_body_themes = null;
	var $_ja_screen_sizes = null;
	var $_ja_color_themes = null;

	function JA_Tools ($template, $_params_cookie=null) {
		$this->_tpl = $template;
		$this->template = $template->template;
		if(!$_params_cookie) {
			$_params_cookie = array();
		}
		
		if ($this->getParam('usertool_screen') && !in_array(JA_TOOL_SCREEN, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_SCREEN;
		}
		if ($this->getParam('usertool_font') && !in_array(JA_TOOL_FONT, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_FONT;
		}
		if ($this->getParam('usertool_color') && !in_array(JA_TOOL_COLOR, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_COLOR;
		}
		if ($this->getParam('usertool_layout') && !in_array(JA_TOOL_LAYOUT, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_LAYOUT;
		}
		if ($this->getParam('usertool_body') && !in_array(JA_TOOL_BODY, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_BODY;
		}
		foreach ($_params_cookie as $k) {
			$this->_params_cookie[$k] = $this->_tpl->params->get($k);
		}

		$this->getUserSetting();
	}

	function getUserSetting(){
		$exp = time() + 60*60*24*355;
		if (isset($_COOKIE[$this->template.'_tpl']) && $_COOKIE[$this->template.'_tpl'] == $this->template){
			foreach($this->_params_cookie as $k=>$v) {
				$kc = $this->template."_".$k;
				if (isset($_GET[$k])){
					$v = $_GET[$k];
					setcookie ($kc, $v, $exp, '/');
				}else{
					if (isset($_COOKIE[$kc])){
						$v = $_COOKIE[$kc];
					}
				}
				$this->setParam($k, $v);
			}

		}else{
			@setcookie ($this->template.'_tpl', $this->template, $exp, '/');
		}
		return $this;
	}

	function getParam ($param, $default='') {
		if (isset($this->_params_cookie[$param])) {
			return $this->_params_cookie[$param];
		}
		return $this->_tpl->params->get($param, $default);
	}

	function setParam ($param, $value) {
		$this->_params_cookie[$param] = $value;
	}

	function getCurrentURL(){
		$cururl = JRequest::getURI();
		if(($pos = strpos($cururl, "index.php"))!== false){
			$cururl = substr($cururl,$pos);
		}
		$cururl =  JRoute::_($cururl, true, 0);
		return $cururl;
	}

	function genToolMenu($ja_tools, $imgext = 'gif'){
		if ($ja_tools & 1){//show screen tools
			?>
			<ul class="ja-usertools-screen">
			<?php
			foreach ($this->_ja_screen_sizes as $ja_screen_size) {
				echo "
				<li><img style=\"cursor: pointer;\" src=\"".$this->templateurl()."/images/".strtolower($ja_screen_size).( ($this->getParam(JA_TOOL_SCREEN)==$ja_screen_size) ? "-hilite" : "" ).".".$imgext."\" title=\"".$ja_screen_size."\" alt=\"".$ja_screen_size."\" id=\"ja-tool-".$ja_screen_size."\" onclick=\"switchTool('".$this->template."_".JA_TOOL_SCREEN."','$ja_screen_size');return false;\" /></li>
				";
			} ?>
			</ul>
		<?php
		}
  	if ($ja_tools & 2){//show font tools
  		?>
  		<ul class="ja-usertools-font">
  		<li><img style="cursor: pointer;" title="<?php echo JText::_('Increase font size');?>" src="<?php echo $this->templateurl();?>/images/user-increase.<?php echo $imgext;?>" alt="<?php echo JText::_('Increase font size');?>" id="ja-tool-increase" onclick="switchFontSize('<?php echo $this->template."_".JA_TOOL_FONT;?>','inc'); return false;" /></li>
  		<li><img style="cursor: pointer;" title="<?php echo JText::_('Default font size');?>" src="<?php echo $this->templateurl();?>/images/user-reset.<?php echo $imgext;?>" alt="<?php echo JText::_('Default font size');?>" id="ja-tool-reset" onclick="switchFontSize('<?php echo $this->template."_".JA_TOOL_FONT;?>',<?php echo $this->_tpl->params->get(JA_TOOL_FONT);?>); return false;" /></li>
  		<li><img style="cursor: pointer;" title="<?php echo JText::_('Decrease font size');?>" src="<?php echo $this->templateurl();?>/images/user-decrease.<?php echo $imgext;?>" alt="<?php echo JText::_('Decrease font size');?>" id="ja-tool-decrease" onclick="switchFontSize('<?php echo $this->template."_".JA_TOOL_FONT;?>','dec'); return false;" /></li>
  		</ul>
  		<script type="text/javascript">var CurrentFontSize=parseInt('<?php echo $this->getParam(JA_TOOL_FONT);?>');</script>
  		
  		<?php
    }

		if ($ja_tools & 4){//show color tools
			?>
			<ul class="ja-usertools-color">
			<?php
			foreach ($this->_ja_color_themes as $ja_color_theme) {
				echo "
				<li><img style=\"cursor: pointer;\" src=\"".$this->templateurl()."/images/".strtolower($ja_color_theme).( ($this->getParam(JA_TOOL_COLOR)==$ja_color_theme) ? "-hilite" : "" ).".".$imgext."\" title=\"".$ja_color_theme." color\" alt=\"".$ja_color_theme." color\" id=\"ja-tool-".$ja_color_theme."color\" onclick=\"switchTool('".$this->template."_".JA_TOOL_COLOR."','$ja_color_theme');return false;\" /></li>
				";
			} ?>
			</ul>
		<?php
		}

		if ($ja_tools & 8){//show LAYOUT tools
			?>
			<ul class="ja-usertools-layout">
			<?php
			foreach ($this->_ja_layouts as $ja_layout) {
				echo "
				<li><img style=\"cursor: pointer;\" src=\"".$this->templateurl()."/images/".strtolower($ja_layout).( ($this->getParam(JA_TOOL_LAYOUT)==$ja_layout) ? "-hilite" : "" ).".".$imgext."\" title=\"".JText::_($ja_layout)."\" alt=\"".JText::_($ja_layout)."\" id=\"ja-tool-".$ja_layout."\" onclick=\"switchTool('".$this->template."_".JA_TOOL_LAYOUT."','$ja_layout');return false;\" /></li>
				";
			} ?>
			</ul>
		<?php
		}

		if ($ja_tools & 16){//show LAYOUT tools
			?>
			<ul class="ja-usertools-body">
			<?php
			foreach ($this->_ja_body_themes as $ja_body_theme) {
				echo "
				<li><img style=\"cursor: pointer;\" src=\"".$this->templateurl()."/images/".strtolower($ja_body_theme).( ($this->getParam(JA_TOOL_BODY)==$ja_body_theme) ? "-hilite" : "" ).".".$imgext."\" title=\"".JText::_($ja_body_theme)."\" alt=\"".JText::_($ja_body_theme)."\" id=\"ja-tool-".$ja_body_theme."\" onclick=\"switchTool('".$this->template."_".JA_TOOL_BODY."','$ja_body_theme');return false;\" /></li>
				";
			} ?>
			</ul>
		<?php
		}
  	
		if ($ja_tools & 32){//show font tools
  		?>
  		<ul class="ja-usertools-expand">
  		<li id="ja-mainbody-resize"></li>
  		</ul>
  		<?php
    }
    
		if ($ja_tools & 64){//show font tools
  		?>
  		<div class="ja-usertools-modfunc">
  		<a href="" title="<?php echo JText::_('Reset Module Status');?>" onclick="switchTool('ja-ordercolumn','-'); return false;" ><?php echo JText::_('Reset Module Status');?></a>
  		</div>
  		<?php
    }
	}

	function setLayouts ($_array_layouts) {
		$this->_ja_layouts = $_array_layouts;
	}

	function setBodyThemes ($_array_body_themes) {
		$this->_ja_body_themes = $_array_body_themes ;
	}

	function setScreenSizes ($_array_screen_sizes) {
		$this->_ja_screen_sizes = $_array_screen_sizes;
	}

	function setColorThemes ($_array_color_themes) {
		$this->_ja_color_themes = $_array_color_themes;
	}

	function getCurrentMenuIndex(){
		$Itemid = JRequest::getInt( 'Itemid');
		$database		=& JFactory::getDBO();
		$id = $Itemid;
		$menutype = 'mainmenu';
		$ordering = '0';
		while (1){
			$sql = "select parent, menutype, ordering from #__menu where id = $id limit 1";
			$database->setQuery($sql);
			$row = null;
			$row = $database->loadObject();
			if ($row) {
				$menutype = $row->menutype;
				$ordering = $row->ordering;
				if ($row->parent > 0)
				{
					$id = $row->parent;
				}else break;
			}else break;
		}

		$user	=& JFactory::getUser();
		if (isset($user))
		{
			$aid = $user->get('aid', 0);
			$sql = "SELECT count(*) FROM #__menu AS m"
			. "\nWHERE menutype='". $menutype ."' AND published='1' AND access <= '$aid' AND parent=0 and ordering < $ordering";
		} else {
			$sql = "SELECT count(*) FROM #__menu AS m"
			. "\nWHERE menutype='". $menutype ."' AND published='1' AND parent=0 and ordering < $ordering";
		}
		$database->setQuery($sql);

		return $database->loadResult();
	}

	function calSpotlight ($spotlight, $totalwidth=100, $specialwidth=0, $special='first') {

		/********************************************
		$spotlight = array ('position1', 'position2',...)
		*********************************************/
		$modules = array();
		$modules_s = array();
		foreach ($spotlight as $position) {
			if( $this->_tpl->countModules ($position) ){
				$modules_s[] = $position;
			}
			$modules[$position] = array('class'=>'-full','width'=>$totalwidth.'%');
		}

		if (!count($modules_s)) return null;
		if ($specialwidth) {
			if (count($modules_s)>1) {
				$width = round(($totalwidth-$specialwidth)/(count($modules_s)-1),1) . "%";
				$specialwidth = $specialwidth . "%";
			}else{
				$specialwidth = $totalwidth . "%";
			}
		}else{
			$width = (round($totalwidth/(count($modules_s)),2)) . "%";
			$specialwidth = $width;
		}

		if (count ($modules_s) > 1){
			$modules[$modules_s[0]]['class'] = "-left";
			$modules[$modules_s[0]]['width'] = ($special=='left')?$specialwidth:$width;
			$modules[$modules_s[count ($modules_s) - 1]]['class'] = "-right";
			$modules[$modules_s[count ($modules_s) - 1]]['width'] = ($special=='right')?$specialwidth:$width;
			for ($i=1; $i<count ($modules_s) - 1; $i++){
				$modules[$modules_s[$i]]['class'] = "-center";
				$modules[$modules_s[$i]]['width'] = $width;
			}
		}
		return $modules;
	}

	function isIE6 () {
		$msie='/msie\s(5\.[5-9]|[6]\.[0-9]*).*(win)/i';
		return isset($_SERVER['HTTP_USER_AGENT']) &&
			preg_match($msie,$_SERVER['HTTP_USER_AGENT']) &&
			!preg_match('/opera/i',$_SERVER['HTTP_USER_AGENT']);
	}

	function isOP () {
		return isset($_SERVER['HTTP_USER_AGENT']) &&
			preg_match('/opera/i',$_SERVER['HTTP_USER_AGENT']);
	}

    function noBG4IE6() {
	if ($this->isIE6())
	echo ' style="background: none;"';
	}

	function baseurl(){
		return JURI::base();
	}

	function templateurl(){
		return JURI::base()."templates/".$this->template;
	}

	function getRandomImage ($img_folder) {
		$imglist=array();

		mt_srand((double)microtime()*1000);

		//use the directory class
		$imgs = dir($img_folder);

		//read all files from the  directory, checks if are images and ads them to a list (see below how to display flash banners)
		while ($file = $imgs->read()) {
			if (eregi("gif", $file) || eregi("jpg", $file) || eregi("png", $file))
				$imglist[] = $file;
		}
		closedir($imgs->handle);

		if(!count($imglist)) return '';

		//generate a random number between 0 and the number of images
		$random = mt_rand(0, count($imglist)-1);
		$image = $imglist[$random];

		return $image;
	}

	function isFrontPage(){
		return (JRequest::getCmd( 'option' )=='com_content' && JRequest::getCmd( 'view' ) == 'frontpage') ;
	}

	function sitename() {
		$config = new JConfig();
		return $config->sitename;
	}

	function genMenuHead(){
		$html = "";
		if ($this->getParam(JA_TOOL_MENU)== '2') {
				$html = '<link href="'.$this->templateurl().'/ja_menus/ja_cssmenu/ja-sosdmenu.css" rel="stylesheet" type="text/css" />
					<script language="javascript" type="text/javascript" src="'. $this->templateurl().'/ja_menus/ja_cssmenu/ja.cssmenu.js"></script>';
		} else if ($this->getParam(JA_TOOL_MENU) == 3) {
			$html = '<link href="'.$this->templateurl().'/ja_menus/ja_scriptdlmenu/ja-scriptdlmenu.css" rel="stylesheet" type="text/css" />
					<script language="javascript" type="text/javascript" src="'.$this->templateurl().'/ja_menus/ja_scriptdlmenu/ja-scriptdlmenu.js"></script>';
		} else if ($this->getParam(JA_TOOL_MENU) == 4) {
			$html = '<link href="'.$this->templateurl().'/ja_menus/ja_moomenu/ja-moomenu.css" rel="stylesheet" type="text/css" />
						<script language="javascript" type="text/javascript" src="'.$this->templateurl().'/ja_menus/ja_moomenu/ja.moomenu.js"></script>';
		} else { //Default
				$html = '<link href="'.$this->templateurl().'/ja_menus/ja_splitmenu/ja-splitmenu.css" rel="stylesheet" type="text/css" />';
		}

		if ($this->getParam('usertool_font')){
		?>
			<script type="text/javascript">
			var currentFontSize = <?php echo $this->getParam(JA_TOOL_FONT); ?>;
			</script>
		<?php
		}
		echo $html;
	}

}
?>
