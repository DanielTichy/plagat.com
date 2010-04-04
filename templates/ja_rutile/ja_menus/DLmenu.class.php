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
defined( '_VALID_MOS' ) or defined('_JEXEC') or die('Restricted access');
if (!defined ('_JA_SPLIT_MENU_CLASS')) {
	define ('_JA_SPLIT_MENU_CLASS', 1);
	require_once (dirname(__FILE__).DS."Base.class.php");

	class JA_DLmenu extends JA_Base{
		function JA_DLmenu ($params) {
			parent::JA_Base($params);

			//To show sub menu on a separated place
			$this->showSeparatedSub = true;
		}

	    function genMenu($startlevel=0, $endlevel = 10){
			if ($startlevel == 0) parent::genMenu(0,0);
			else {
				$this->setParam('startlevel', $startlevel);
				$this->setParam('endlevel', $endlevel);
				$this->beginMenu($startlevel, $endlevel);
				//Sub level
				$pid = $this->getParentId($startlevel - 1);
				if (@$this->children[$pid]) {
					foreach ($this->children[$pid] as $row) {
						if (@$this->children[$row->id]) {
							$this->genMenuItems ($row->id, $startlevel);
						}					
					}
				}
				$this->endMenu($startlevel, $endlevel);
			}
		}
		
        function beginMenuItems($pid=0, $level=0){
            if(!$level) echo "<ul>";
			else echo "<ul id=\"jasdl-subnav$pid\">";
        }

        function beginMenuItem($mitem=null, $level = 0, $pos = ''){
            if(!$level) echo "<li id=\"jasdl-mainnav{$mitem->id}\">";
			else echo "<li id=\"jasdl-subnavitem{$mitem->id}\">";
        }

        function beginMenu($startlevel=0, $endlevel = 10){
            if(!$startlevel) echo "<div id=\"jasdl-mainnav\">";
            else echo "<div id=\"jasdl-subnav\">";			
        }

		function endMenu($startlevel=0, $endlevel = 10){
			echo "</div>";
			if(!$startlevel) {
				echo "
				<script type=\"text/javascript\">
					var jasdl_activemenu = new Array(". ( (count($this->open) == 1) ? "\"".$this->open[0]."\"" : implode(",", array_reverse($this->open)) ) .");
				</script>
				";
			}
		}

		function genMenuHead () {
			?>
			<link href="<?php echo $this->getParam('menupath'); ?>/ja_scriptdlmenu/ja.scriptdlmenu.css" rel="stylesheet" type="text/css" />
			<script src="<?php echo $this->getParam('menupath'); ?>/ja_scriptdlmenu/ja.scriptdlmenu.js" language="javascript" type="text/javascript" ></script>
			<?php
		}

		function hasSubMenu($level) {
			return true;
		}
	}
}
?>
