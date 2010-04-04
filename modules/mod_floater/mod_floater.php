<?php
/*----------------------------------------------------------------------
# R3D Floater Module 1.5.0.4 for Joomla 1.5 
# ----------------------------------------------------------------------
# Copyright (C) 2009 R3D Internet Dienstleistungen. All Rights Reserved.
# Coded by: 	r3d
# Copyright: 	GNU/GPL
# Website: 		http://www.r3d.de
------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

/////////////////////////////// PARAMS //////////////////////////////////

$module_name = $params->get( 'module_name', 'newsflash' );
$module_title = $params->get( 'module_title', 'Floater Newsflash' );

$direction = 'left';
$initdir = 'slideDR';

$timeout = $params->get( 'timeout', '10000' );

// Box Parameters
$boxwidth = $params->get( 'boxwidth', '300px' );
$boxheight = $params->get( 'boxheight', '300px' );
$boxleft = $params->get( 'boxleft', '-400px' );
$boxtop = $params->get( 'boxtop', '190px' );
$bgcolor = $params->get( 'bgcolor', '#f5f5f5' );
$border = $params->get( 'border', '7px solid #135CAE' );
$opacity = $params->get( 'opacity', '90' );
$talign = $params->get( 'talign', 'right' );

// inside positions
$iwidth = $params->get( 'iwidth', '270px' );
$iheight = $params->get( 'iheight', '250px' );
$ileft = $params->get( 'ileft', '10px' );
$itop = $params->get( 'itop', '0px' );
$ibgcolor = $params->get( 'ibgcolor', 'transparent' );
$iborder = $params->get( 'iborder', 'none' );
$italign = $params->get( 'italign', 'center' );
$ioverflow = $params->get( 'ioverflow', 'auto' );

// startpositions
$startpos = $params->get ( 'startpos', '-400' );
$rightpos = $params->get ( 'rightpos', '100' );
$rightspeed = $params->get ( 'rightspeed', '20' );
$leftpos = $params->get ( 'leftpos', '-400' );
$leftspeed = $params->get ( 'leftspeed', '20' );

$line1 = JText :: _('CLOSE WINDOW');

////////////////////////////   END PARAMS   //////////////////////////////


jimport('joomla.application.module.helper');
$modname = JModuleHelper::getModule($module_name,$module_title);


?>
<style type="text/css"><!--
#floaterDiv
{
	position: absolute;
	width:<?php echo $boxwidth; ?>;
	height:<?php echo $boxheight; ?>;
	left:<?php echo $boxleft; ?>;
	top:<?php echo $boxtop; ?>;
	background-color:<?php echo $bgcolor; ?>;
	border:<?php echo $border; ?>;
	text-align:<?php echo $talign; ?>;
	z-index: 9999;
}
.translucent {-moz-opacity:0.<?php echo $opacity; ?>; opacity:0.<?php echo $opacity; ?>; filter:alpha(opacity=<?php echo $opacity; ?>);}

#floaterDiv div.box
{
	position:absolute;
	left:5px;
	top:5px;
	text-align: right;
}
#insideDiv
{
	position: relative;
	width:<?php echo $iwidth; ?>;
	height:<?php echo $iheight; ?>;
	left:<?php echo $ileft; ?>;
	top:<?php echo $itop; ?>;
	background-color:<?php echo $ibgcolor; ?>;
	border:<?php echo $iborder; ?>;
	z-index: 10000;
	text-align:<?php echo $italign; ?>;
	overflow: <?php echo $ioverflow; ?>;
}
-->
</style>

<div style="left: <?php echo $startpos;?>px;" id="floaterDiv" class="translucent">
<div class="box"><a onfocus="this.blur()" href="javascript:goaway()" title="Exit"> <strong> <?php echo $line1;?> &nbsp; X</strong></a><br><div><br>
<div  id="insideDiv"><?php echo JModuleHelper::renderModule($modname,array('style'=> 'raw')); ?></div></div>
<script type="text/javascript">
<?php
echo "var direction = \"$direction\"; \n";
echo "var initdir = \"$initdir\"; \n";
echo "var startpos = $startpos; \n";
echo "var timeout = $timeout; \n";
echo "var rightpos = $rightpos; \n";
echo "var rightspeed = $rightspeed; \n";
echo "var leftpos = $leftpos; \n";
echo "var leftspeed = $leftspeed; \n";
?>

function moveTo(obj, x, y) {
        if (document.getElementById) {
        document.getElementById('floaterDiv').style.left = x;
        document.getElementById('floaterDiv').style.top = y;
        }
}
if (direction == 'top'){
var udlr = "top";
} else {
	var udlr = "left";
	}
function init(){
        if(document.getElementById){
        obj = document.getElementById("floaterDiv");
        obj.style[udlr] = parseInt(startpos) + "px";
        }
}
function slideDR(){
        if(document.getElementById){
                if(parseInt(obj.style[udlr]) < rightpos){
                        obj.style[udlr] = parseInt(obj.style[udlr]) + 20 + "px";
                        setTimeout("slideDR()",rightspeed);
                }
        }
}
function slideUL(){
        if(document.getElementById){
                if(parseInt(obj.style[udlr]) > leftpos){
                        obj.style[udlr] = parseInt(obj.style[udlr]) - 30 + "px";
                        setTimeout("slideUL()",leftspeed);
                }
        }
}
function ShowHide(id, visibility) {
    divs = document.getElementsByTagName("div");
    divs[id].style.visibility = visibility;
}
function goaway() 
{
   if (initdir == 'slideDR' ){
   slideUL();
   }
 else{
   slideDR();
   }
}
function selection() 
{
   if (initdir == 'slideDR' ){
   slideDR();
   }
 else{
   slideUL();
   }
}
function start() 
{
   init();
   selection();
}
window.onload = start;

if (initdir == 'slideDR' ){
   setTimeout('slideUL();',timeout);
   }
 else{
   setTimeout('slideDR();',timeout);
   }
</script>