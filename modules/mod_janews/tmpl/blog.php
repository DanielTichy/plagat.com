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

$catorsec 				= 	trim( $params->get( 'catorsec' ));

$showimage 				= 	$params->get( 'showimage', 0 );
$autoresize 			= 	intval (trim( $params->get( 'autoresize', 0) ));
$img_w 					= 	intval (trim( $params->get( 'width', 100 ) ));
$img_h 				= 	intval (trim( $params->get( 'height', 100 ) ));
$img_align 					= $params->get( 'align' , 'left');

$maxchars 				= 	intval (trim( $params->get( 'maxchars', 200 ) ));
$introitems 				= 	intval (trim( $params->get( 'introitems', 1 ) ));
$linkitems 				= 	intval (trim( $params->get( 'linkitems', 0 ) ));

$showreadmore 				= 	intval (trim( $params->get( 'showreadmore', 1 ) ));
$showcattitle 			= 	trim( $params->get( 'showcattitle' ));
$hiddenClasses 				= 	trim( $params->get( 'hiddenClasses', '' ) );

$cols = intval (trim( $params->get( 'cols', 2 ) ));
$news = count($contents);
if($news < $cols) $cols = $news;
if (!$cols) return;
$width = 99.9/$cols;

?>


<div id="jazin-wrap">
<div id="jazin" class="clearfix">

<?php 
  $k = 0;
  for ($z = 0; $z < $cols; $z ++) :
	  $cls = $cols==1?'full':($z==0?'left':($z==$cols-1?'right':'center'));
?>	  
		<div class="jazin-<?php echo $cls;?>" style="width:<?php echo $width;?>%">
		<?php for ($y = 0; $y < ($news / $cols) && $k<$news; $y ++) :
		  $params->set('blog_theme', $themes[$k]);
		  $rows = $contents[$k];
		  if($catid) {
  			include(dirname(__FILE__).'/blog_item.php');
      }
		  $k++;
		endfor; ?>
		</div>
	<?php endfor; ?>

</div>
</div>
