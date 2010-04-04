<?php

/**
	Template for Veoh player
**/

$this->veoh_fs = ($this->veoh_fs) ? 'true' : 'false';  

$this->url = str_replace('videos/', 'veohplayer.swf?permalinkId=', $this->url);

?>

<embed src="<?php echo $this->url;?>&id=anonymous&player=videodetailsembedded&videoAutoPlay=<?php echo $this->veoh_autoplay; ?>" wmode="transparent" allowFullScreen="<?php echo $this->veoh_fs; ?>" width="<?php echo $this->width; ?>" height="<?php echo $this->height; ?>" bgcolor="<?php echo $this->veoh_bg; ?>" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed><br/><a href="http://www.veoh.com/">Online Videos by Veoh.com</a>