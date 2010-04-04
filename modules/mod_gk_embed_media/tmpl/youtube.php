<?php

/**
	Template for YouTube player
**/

$rel = ($this->yt_related) ? '&rel=1' : '';
$border = ($this->yt_border) ? '&border=1' : '&border=0';

$style = '';

switch($this->yt_style){
	case 0: $style = '';break;
	case 1: $style = '&color1=0x3a3a3a&color2=0x999999';break;
	case 2: $style = '&color1=0x2b405b&color2=0x6b8ab6';break;
	case 3: $style = '&color1=0x006699&color2=0x54abd6';break;
	case 4: $style = '&color1=0x234900&color2=0x4e9e00';break;
	case 5: $style = '&color1=0xe1600f&color2=0xfebd01';break;
	case 6: $style = '&color1=0xcc2550&color2=0xe87a9f';break;
	case 7: $style = '&color1=0x402061&color2=0x9461ca';break;
	case 8: $style = '&color1=0x5d1719&color2=0xcd311b';break;
	case 9: $style = '&color1='.$this->yt_color1.'&color2='.$this->yt_color2;break;
}     

$this->url = str_replace('watch?v=', 'v/', $this->url);

?>

<object width="<?php echo $this->width; ?>" height="<?php echo $this->height; ?>">
<param name="movie" value="<?php echo $this->url.$rel.$border.$style; ?>"></param>
<param value="transparent" name="wmode"/>
<embed src="<?php echo $this->url.$rel.$border.$style; ?>" type="application/x-shockwave-flash" width="<?php echo $this->width; ?>" height="<?php echo $this->height; ?>" wmode="transparent"></embed>
</object>