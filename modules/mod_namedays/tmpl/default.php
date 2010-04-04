<?php
/**
 * Mod_Namedays 
 * @date 2008-11-11
 * @author Vytautas Krivickas webmaster@vytux.com
 * @based on Roman Náhlovský web@renat.sk module
 * @Copyright (C) 2008
 * @ All rights reserved
 * @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version joomla 1.5.X
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<div align="<?php echo $align; ?>"><div style="height:<?php echo $height; ?>px; width:<?php echo $width; ?>px;">
<?php
	echo "\t".$label_pre;
    
	if ( $show_pre_yesterday == 1 )  
		echo str_replace("{@date@}", date($date_format, $day1), $label_pre_yesterday)."\t<strong>".$names[date('z',$day1)]."</strong>\t".$seperator.$br_pre_yesterday;
    
	if ( $show_yesterday == 1 )			
		echo str_replace("{@date@}", date($date_format, $day2), $label_yesterday)."\t<strong>".$names[date('z',$day2)]."</strong>\t".$seperator.$br_yesterday;
    
	if ( $show_today == 1  )          
		echo str_replace("{@date@}", date($date_format, $day3), $label_today)."\t<strong>".$names[date('z',$day3)]."</strong>\t".$seperator.$br_today;
    
	if ( $show_tomorrow == 1  )
		echo str_replace("{@date@}", date($date_format, $day4), $label_tomorrow)."\t<strong>".$names[date('z',$day4)]."</strong>\t".$seperator.$br_tomorrow;
    
	if ( $show_post_tomorrow == 1 )    
		echo str_replace("{@date@}", date($date_format, $day5), $label_post_tomorrow)."\t<strong>".$names[date('z',$day5)]."</strong>\t".$br_post_tomorrow;
	
	echo "\t".$label_post; 
	echo $createdby;
?>
</div></div>

