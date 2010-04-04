<?php

/**
	Template for Daily motion player
**/

switch($this->dm_size){
	case 0: $width = 220;$height = 170;break;
	case 1: $width = 420;$height = 336;break;
	case 2: $width = 520;$height = 411;break;
}

$fs = ($this->dm_fs) ? 'true' : 'false';
$params = '&colors=background:'.$this->dm_bg.';glow:'.$this->dm_glow.';foreground:'.$this->dm_fg.';special:'.$this->dm_special.';&autoPlay='.$this->dm_autoplay.'&related='.$this->dm_related; 
	
$this->url = str_replace('video/', 'swf/', $this->url);
	
?>

<object width="<?php echo $width; ?>" height="<?php echo $height; ?>">
<param name="movie" value="<?php echo $this->url.$params; ?>"></param>
<param name="allowFullScreen" value="<?php echo $fs; ?>"></param>
<param name="allowScriptAccess" value="always"></param>
<param value="transparent" name="wmode"/>
<embed src="<?php echo $this->url.$params; ?>" wmode="transparent" type="application/x-shockwave-flash" width="<?php echo $width; ?>" height="<?php echo $height; ?>" allowFullScreen="<?php echo $fs; ?>" allowScriptAccess="always"></embed>
</object>