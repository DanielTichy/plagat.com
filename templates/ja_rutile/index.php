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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

include_once (dirname(__FILE__).DS.'ja_vars_1.5.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">

<head>
<jdoc:include type="head" />
<?php JHTML::_('behavior.mootools'); ?>
<link rel="stylesheet" href="<?php echo $tmpTools->baseurl(); ?>templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->baseurl(); ?>templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->templateurl(); ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->templateurl(); ?>/css/typo.css" type="text/css" />

<script language="javascript" type="text/javascript">
	var siteurl = '<?php echo $tmpTools->baseurl();?>';
	var tmplurl = '<?php echo $tmpTools->templateurl();?>';
</script>

<script language="javascript" type="text/javascript" src="<?php echo $tmpTools->templateurl(); ?>/js/ja.script.js"></script>
<!-- js for dragdrop -->

<!-- Menu head -->
<?php if ($jamenu) $jamenu->genMenuHead(); ?>
<link href="<?php echo $tmpTools->templateurl(); ?>/css/colors/<?php echo $tmpTools->getParam(JA_TOOL_COLOR); ?>.css" rel="stylesheet" type="text/css" />

<!--[if lte IE 6]>
<style type="text/css">
@import { url(<?php echo $tmpTools->templateurl(); ?>/css/ie-all.css); }
.clearfix {height: 1%;}
img {border: none;}
</style>
<![endif]-->

<!--[if gte IE 7.0]>
<style type="text/css">
@import { url(<?php echo $tmpTools->templateurl(); ?>/css/ie-all.css); }
.clearfix {display: inline-block;}
</style>
<![endif]-->

<?php if ($tmpTools->isIE6()) { ?>
<!--[if lte IE 6]>
<link href="<?php echo $tmpTools->templateurl(); ?>/css/ie6.php" rel="stylesheet" type="text/css" />
<script type="text/javascript">
window.addEvent ('load', makeTransBG);
function makeTransBG() {
makeTransBg($$('img'));
}
</script>
<![endif]-->
<?php } ?>
</head>

<body id="bd" class="<?php echo $tmpTools->getParam(JA_TOOL_LAYOUT);?> <?php echo $tmpTools->getParam(JA_TOOL_SCREEN);?> fs<?php echo $tmpTools->getParam(JA_TOOL_FONT);?> <?= ($tmpTools->isFrontPage() ? 'page-front' : 'page-other'); ?>" >
<a name="Top" id="Top"></a>
<ul class="accessibility">
	<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-content" title="<?php echo JText::_("Skip to content");?>"><?php echo JText::_("Skip to content");?></a></li>
	<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-mainnav" title="<?php echo JText::_("Skip to main navigation");?>"><?php echo JText::_("Skip to main navigation");?></a></li>
	<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-col1" title="<?php echo JText::_("Skip to 1st column");?>"><?php echo JText::_("Skip to 1st column");?></a></li>
	<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-col2" title="<?php echo JText::_("Skip to 2nd column");?>"><?php echo JText::_("Skip to 2nd column");?></a></li>
</ul>


<!-- BEGIN: headerMediaBanner -->
<?php if ($this->countModules('user7')) { ?>
<div id="headerMediaBanner">
	<jdoc:include type="modules" name="user7" style="raw" />
</div>
<?php } ?>
<!-- END headerMediaBanner -->
<div id="ja-wrapper">
<!-- BEGIN: HEADER -->
<div class="ja-box-br" style="overflow: visible;"><div class="ja-box-bl"><div class="ja-box-tr"><div class="ja-box-tl">
<div id="ja-header" class="clearfix">

	<?php 
	$siteName = $tmpTools->sitename(); 
	if ($tmpTools->getParam('logoType')=='image') { ?>
	<h1 class="logo">
		<a href="index.php" title="<?php echo $siteName; ?>"><span><?php echo $siteName; ?></span></a>
	</h1>
	<?php } else { 
	$logoText = (trim($tmpTools->getParam('logoText'))=='') ? $config->sitename : $tmpTools->getParam('logoText');
	$sloganText = (trim($tmpTools->getParam('sloganText'))=='') ? JText::_('SITE SLOGAN') : $tmpTools->getParam('sloganText');	?>
	<div class="logo-text">
		<p class="site-slogan"><?php echo $sloganText;?></p>
		<h1>
			<a href="index.php" title="<?php echo $siteName; ?>"><span><?php echo $logoText; ?></span></a>	
		</h1>
	</div>
	<?php } ?>
	
	<?php if ($this->countModules('user3')) { ?>
	<div id="ja-topnav">
	<jdoc:include type="modules" name="user3" />
	</div>
	<?php } ?>

	<?php if ($this->countModules('user4')) { ?>
	<div id="ja-search">
		<jdoc:include type="modules" name="user4" style="raw" />
	</div>	
	<?php } ?>

	<!-- BEGIN: MAIN NAVIGATION -->
	<?php if ($tmpTools->getParam('ja_menu') != 'none') : ?>
	<div id="ja-mainnav" class="clearfix">
		<?php if ($jamenu) $jamenu->genMenu (0); ?>

	  <div id="ja-usertools">
			<?php $tmpTools->genToolMenu($tmpTools->getParam('usertool_font'), 'png'); ?>
	  </div>

	</div>
	
	<?php if ($hasSubnav) : ?>
	<div id="ja-subnav" class="clearfix">
		<?php if ($jamenu) $jamenu->genMenu (1,1);	?>
	</div>
	<?php endif; ?>
	<?php endif; ?>
	<!-- END: MAIN NAVIGATION -->

</div>
</div></div></div></div>
<!-- END: HEADER -->

<div id="ja-containerwrap<?php echo $divid; ?>">
<div id="ja-container" class="clearfix">

	<!-- BEGIN: Bannner 'user9' -->	
	<?php if ($this->countModules('user9')) { ?>
	<div id="block-advs_banner">
		<jdoc:include type="modules" name="user9" style="jarounded" />
	</div>
	<?php } ?>
	<!-- END: Bannner 'user9' -->
	
	<!-- BEGIN: CONTENT -->
	<div id="ja-mainbody" class="clearfix">
				
		<!-- BEGIN: JA_NEWS - Rychlé zprávy -->	
		<?php if ($this->countModules('user8')) { ?>
		<div id="block-newsflash">
			<jdoc:include type="modules" name="user8" style="jarounded" />
		</div>
		<?php } ?>
		<!-- END: JA_NEWS - Rychlé zprávy -->
		
		
		<!-- BEGIN: CONTENT -->
		<div id="ja-content">
			
		<div id="ja-content-top">
		<div id="ja-content-bot">
		
		<div class="ja-innerpad clearfix">

			<jdoc:include type="message" />

			<div id="ja-current-content" class="clearfix">

    		<?php if(!$tmpTools->isFrontPage()) : ?>
    			<div id="ja-pathway">
					<jdoc:include type="module" name="breadcrumbs" />
					</div>
    			<jdoc:include type="component" />
    		<?php endif; ?>			
				
    		<!-- BEGIN: JAZIN -->
			<div id="jazin-fp">
        		<jdoc:include type="modules" name="ja-news" style="raw" />
			</div>
			<!-- END: JAZIN -->
      
			<?php if($this->countModules('banner')) : ?>
			<!-- BEGIN: BANNER -->
			<div id="ja-banner">
				<jdoc:include type="modules" name="banner" />
			</div>
			<!-- END: BANNER -->
			<?php endif; ?>
		</div>

		</div></div></div></div>
		<!-- END: CONTENT -->

	  <?php if ($ja_left) { ?>
	  <!-- BEGIN: LEFT COLUMN -->
		<div id="ja-col1">
		<div class="ja-innerpad">
			<jdoc:include type="modules" name="left" style="jarounded" />
		</div>
		</div><br />
		<!-- END: LEFT COLUMN -->
		<?php } ?>

	</div>
	<!-- END: CONTENT -->
		
	<?php if ($ja_right) { ?>
	<!-- BEGIN: RIGHT COLUMN -->
	<div id="ja-col2">
	<div class="ja-innerpad">
		<jdoc:include type="modules" name="right" style="jarounded" />
	</div></div><br />
	<!-- END: RIGHT COLUMN -->
	<?php } ?>
	
	<?php
	$spotlight = array ('user1','user2','user5','user6');
	$botsl = $tmpTools->calSpotlight ($spotlight,$tmpTools->isOP()?100:99.9);
	if( $botsl ) {
	?>
	<!-- BEGIN: BOTTOM SPOTLIGHT-->
	<div id="ja-botsl" class="ja-box-br">
	  <div class="ja-box-bl"><div class="ja-box-tr"><div class="ja-box-tl clearfix">
	
	  <?php if( $this->countModules('user1') ) {?>
	  <div class="ja-box<?php echo $botsl['user1']['class']; ?>" style="width: <?php echo $botsl['user1']['width']; ?>;">
			<jdoc:include type="modules" name="user1" style="rounded" />
	  </div>
	  <?php } ?>
	  
	  <?php if( $this->countModules('user2') ) {?>
	  <div class="ja-box<?php echo $botsl['user2']['class']; ?>" style="width: <?php echo $botsl['user2']['width']; ?>;">
			<jdoc:include type="modules" name="user2" style="rounded" />
	  </div>
	  <?php } ?>
	  
	  <?php if( $this->countModules('user5') ) {?>
	  <div class="ja-box<?php echo $botsl['user5']['class']; ?>" style="width: <?php echo $botsl['user5']['width']; ?>;">
			<jdoc:include type="modules" name="user5" style="rounded" />
	  </div>
	  <?php } ?>
	
	  <?php if( $this->countModules('user6') ) {?>
	  <div class="ja-box<?php echo $botsl['user6']['class']; ?>" style="width: <?php echo $botsl['user6']['width']; ?>;">
			<jdoc:include type="modules" name="user6" style="rounded" />
	  </div>
	  <?php } ?>

    </div></div></div>
	</div>
	<!-- END: BOTTOM SPOTLIGHT 2 -->
	<?php } ?>

</div></div>

<!-- BEGIN: FOOTER -->
<div id="ja-footer">
	<jdoc:include type="modules" name="footer" />
</div>
<!-- END: FOOTER -->

</div>

<jdoc:include type="modules" name="debug" />
<script type="text/javascript">
	addSpanToTitle();
	jaAddFirstItemToTopmenu();
	jaRemoveLastContentSeparator();
	//jaRemoveLastTrBg();
</script>

</body>

</html>