<?php

/**
	Template for Google Video player
**/

$fs = ($this->gv_fs) ? '&fs=true' : '&fs=false';
$fs_value = ($this->gv_fs) ? 'true' : 'false';  

$this->url = str_replace('videoplay?', 'googleplayer.swf?', $this->url);

?>

<embed id="VideoPlayback" wmode="transparent" style="width:<?php echo $this->width; ?>px;height:<?php echo $this->height; ?>px" allowFullScreen="<?php echo $fs_value; ?>" src="<?php echo $this->url.$fs;?>" type="application/x-shockwave-flash"></embed>