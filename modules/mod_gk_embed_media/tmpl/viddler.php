<?php

/**
	Template for Viddler player
**/ 

?>

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?php echo $this->width; ?>" height="<?php echo $this->height; ?>">
	<?php echo ($this->vid_autoplay) ? '<param name="flashvars" value="autoplay=t" />' : ''; ?>
	<param name="movie" value="http://www.viddler.com/<?php echo ($this->vid_player) ? 'player':'simple'; ?>/<?php $this->url; ?>/" />
	<param name="allowScriptAccess" value="always" />
	<param value="transparent" name="wmode"/>
	<param name="allowFullScreen" value="<?php echo ($this->vid_fs) ? 'true': 'false'; ?>" />
	<embed src="http://www.viddler.com/<?php echo ($this->vid_player) ? 'player':'simple'; ?>/<?php $this->url; ?>/" width="<?php echo $this->width; ?>" wmode="transparent" height="<?php echo $this->height; ?>" type="application/x-shockwave-flash" allowScriptAccess="always" <?php echo ($this->vid_autoplay) ? 'flashvars="autoplay=t"' : ''; ?> allowFullScreen="<?php echo ($this->vid_fs) ? 'true': 'false'; ?>" ></embed>
</object>