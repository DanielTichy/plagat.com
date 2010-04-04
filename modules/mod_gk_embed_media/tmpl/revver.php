<?php

/**
	Template for Revver player
**/

$fs = ($this->rev_fs) ? 'true' : 'false';
		
?>

<object width="<?php echo $this->width; ?>" height="<?php echo $this->height; ?>" data="http://flash.revver.com/player/1.0/player.swf?mediaId=<?php echo $this->url;?>" type="application/x-shockwave-flash">
	<param name="Movie" value="http://flash.revver.com/player/1.0/player.swf?mediaId=<?php echo $this->url;?>"></param>
	<param name="FlashVars" value="allowFullScreen=<?php echo $fs;?>"></param>
	<param name="AllowFullScreen" value="<?php echo $fs;?>"></param>
	<param value="transparent" name="wmode"/>
	<param name="AllowScriptAccess" value="always"></param>
	<embed type="application/x-shockwave-flash" wmode="transparent" src="http://flash.revver.com/player/1.0/player.swf?mediaId=<?php echo $this->url;?>" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="always" flashvars="allowFullScreen=<?php echo $fs;?>" allowfullscreen="<?php echo $fs;?>" height="<?php echo $this->height; ?>" width="<?php echo $this->width; ?>"></embed>
</object>