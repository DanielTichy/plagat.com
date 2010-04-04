<?php
/*----------------------------------------------------------------------
#Youjoomla Defaul Index - 
# ----------------------------------------------------------------------
# Copyright (C) 2007 You Joomla. All Rights Reserved.
# Designed by: You Joomla
# License: GNU, GPL Index.php Only!
# Website: http://www.youjoomla.com
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted index access' );
$iso = split( '=', _ISO );
// xml prolog - quirks mode
//echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';

// SUCKERFISH  MENU SWITCH // 
$menu_name = $this->params->get("menuName", "mainmenu");// mainmenu by default, can be any Joomla! menu name


$default_color = $this->params->get("defaultcolor", "green"); // GREEN | ORANGE | BLUE
$default_font  = $this->params->get("fontsize", "medium"); // SMALL | MEDIUM | BIG
$default_width = $this->params->get("sitewidth", "wide"); // WIDE | NARROW 

$showtools  = $this->params->get("templateTools", "1"); // 0 HIDE TOOLS | 1 SHOW ALL  | 2 COLOR AND WIDTH  | 3 COLOR AND FONT | 4 WIDTH AND FONT |5 WIDTH ONLY | 6 FONT ONLY | 7 COLOR ONLY


// SEO SECTION //

$seo                    = $this->params->get ("seo", "Joomla Hosting Template");                      # JUST FOLOW THE TEXT
$tags                   = $this->params->get ("tags", "Joomla Hosting, Joomla Templates, Youjoomla");                        # JUST FOLOW THE TEXT


#DO NOT EDIT BELOW THIS LINE
define( 'TEMPLATEPATH', dirname(__FILE__) );
include( TEMPLATEPATH.DS."settings.php");
include( TEMPLATEPATH.DS."styleswitcher.php");
require( TEMPLATEPATH.DS."suckerfish.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />

<link href="templates/<?php echo $this->template ?>/css/<?php echo $css_file; ?>" rel="stylesheet" title="" type="text/css" media="all"/>
<link href="templates/<?php echo $this->template ?>/css/<?php echo $layoutcss; ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/<?php echo $this->template ?>/src/reflection.js"></script>
	<?php echo "<!--[if lt IE 7]>\n";		
	  include_once( "templates/". $this->template . "/src/ie.js" );
	  echo "<![endif]-->\n";?>
<!--[if lte IE 6]>
<style type="text/css">
#logo,#searchbox,#fotr{
	behavior: url(templates/<?php echo $this->template ?>/css/iepngfix.htc);
}
</style>
<![endif]-->


<?php 
  	
  if (	$layoutcss == "wide.css"){
  echo '<!--[if lte IE 6]> <link href="templates/'.$this->template.'/css/iesucks.css" rel="stylesheet" title="" type="text/css" media="all"/> <![endif]-->';
  } else if (	$layoutcss == "narrow.css")
    echo '<!--[if lte IE 6]> <link href="templates/'.$this->template.'/css/iesucks2.css" rel="stylesheet" title="" type="text/css" media="all"/> <![endif]-->';
 ?>
</head>
<!-- ODVAJANJE ZAREZOM -->
<body>
<div id="<?php echo $img ?>">

<div id="centar2" style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width; ?>;">

<div id="header"> 
<div id="logo"><h1><a href="index.php" title="<?php echo $tags?>"><?php echo $seo ?></a></h1></div>
<div id="searchbox"><jdoc:include type="modules" name="header" style="xhtml" /></div>
</div>
<!-- suckerfish-->
<div id="suckmen">
  <div id="navigacija">
      <?php mosShowListMenu($menu_name); ?>
    </div>

</div>
<!-- end suckerfish-->
<?php if ($this->countModules('user1') || $this->countModules('user2') || $this->countModules('user3') || $this->countModules('user4')) { ?><!-- midsection-->
<div id="midsection">
<div id="midr">
<div id="midl">
<div id="midmods">
<?php if ($this->countModules('user1')) { ?>
<div id="user1" style="width:<?php echo $topwidth; ?>;"><jdoc:include type="modules" name="user1" style="xhtml" /></div><?php } ?>
<?php if ($this->countModules('user2')) { ?>
<div id="user2" style="width:<?php echo $topwidth; ?>;"><jdoc:include type="modules" name="user2" style="xhtml" /></div><?php } ?>
<?php if ($this->countModules('user3')) { ?>
<div id="user3" style="width:<?php echo $topwidth; ?>;"><jdoc:include type="modules" name="user3" style="xhtml" /></div><?php } ?>
<?php if ($this->countModules('user4')) { ?>
<div id="user4" style="width:<?php echo $topwidth; ?>;"><jdoc:include type="modules" name="user4" style="xhtml" /></div><?php } ?>


</div>
</div>
</div>
</div>
<!--end midsection-->
<?php } ?>
<!-- pathway-->
<div id="pathw"><div id="patha"><jdoc:include type="module" name="breadcrumbs" style="none" /></div><div id="styles"><?
// 1 = 	SHOW ALL  | 2 COLOR AND WIDTH  | 3 COLOR AND FONT | 4 WIDTH AND FONT |5 WIDTH ONLY | 6 FONT ONLY | 7 COLOR ONLY

if ( $showtools== 1 || $showtools== 2 || $showtools== 3 || $showtools== 7 ){
// COLOR SWITCHER LINKS
while(list($key, $val) = each($mystyles)){
 echo "<a href='".$_SERVER['PHP_SELF']."?change_css=".$key."' >".$val["label"]."</a>";
 }
 
 }
 ?>
 <?
 if ( $showtools== 1 || $showtools== 3 || $showtools== 4 || $showtools== 6){
 // FONT SWITCHER LINKS
 while(list($key, $val) = each($myfont)){
 echo "<a href='".$_SERVER['PHP_SELF']."?change_font=".$key."' >".$val["label"]."</a>";
}

}
?>

<?
 if ( $showtools== 1 || $showtools== 2 || $showtools== 4 || $showtools== 5 ){
// WIDTH SWITCHER LINKS
while(list($key, $val) = each($mywidth)){
 echo "<a href='".$_SERVER['PHP_SELF']."?change_width=".$key."' >".$val["label"]."</a>";
}

}
?> </div></div>
<!-- end pathway-->

</div><!-- end centar2-->


<div id="centar" style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width; ?>;">
<div id="<?php echo $wrap?>">
<div id="<?php echo $insidewrap ?>">

<div id="<?php echo $mainbody ?>">
<div id="<?php echo $content ?>">
<?php if ($this->countModules('top')) { ?>
<div id="topmod"><div class="inside">
<jdoc:include type="modules" name="top" style="xhtml" />  </div></div><?php } ?>
<div class="inside">

<jdoc:include type="component" />
</div></div>

<?php if ($this->countModules('left')) { ?>
<div id="<?php echo $left ?>"> 

<div class="inside2"><!-- keep mods of edges-->

<jdoc:include type="modules" name="left" style="rounded" />
<!-- end inside--></div><!-- end modsl--></div><!-- end left side-->
<?php } ?>
</div> <!--end of main-body-->
<!-- right side always stand alone-->
<?php if ($this->countModules('right')) { ?>
<div id="<?php echo $right ?>"> 
<div class="inside2"> <!-- keep mods of edges-->
<jdoc:include type="modules" name="right" style="rounded" />
</div><!-- end of inside --></div><!-- end right side-->
<?php } ?>
<div class="clr"></div>
<?php if ($this->countModules('user5') || $this->countModules('user6') || $this->countModules('user7') || $this->countModules('user8')) { ?><!-- botsection-->
<div id="bottomshelf">
<div id="bottomshelfl">
<div id="bottomshelfr">
<div id="botmods">
<?php if ($this->countModules('user5')) { ?>
<div id="user5" style="width:<?php echo $bottomwidth; ?>;"><jdoc:include type="modules" name="user5" style="xhtml" /></div><?php } ?>
<?php if ($this->countModules('user6')) { ?>
<div id="user6" style="width:<?php echo $bottomwidth; ?>;"><jdoc:include type="modules" name="user6" style="xhtml" /></div><?php } ?>
<?php if ($this->countModules('user7')) { ?>
<div id="user7" style="width:<?php echo $bottomwidth; ?>;"><jdoc:include type="modules" name="user7" style="xhtml" /></div><?php } ?>
<?php if ($this->countModules('user8')) { ?>
<div id="user8" style="width:<?php echo $bottomwidth; ?>;"><jdoc:include type="modules" name="user8" style="xhtml" /></div><?php } ?>
</div>
</div>
</div>
</div><?php } ?>
</div><!-- end of insidewrap--></div> <!--end of wrap-->
</div><!-- end centar-->
</div>
<div id="footer"><div id="footerin" style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width; ?>;">
<div id="fotl"><jdoc:include type="modules" name="footer" style="xhtml" />
&nbsp;&nbsp;&nbsp;Design by <a href="http://www.youjoomla.com">Youjoomla.com</a><a href="#centar2">Top</a></div><div id="fotr"></div></div>
</div>
</body>
</html>

