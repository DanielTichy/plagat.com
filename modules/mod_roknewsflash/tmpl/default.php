<?php
/**
 * RokNewsFlash Module
 *
 * @package		Joomla
 * @subpackage	RokNewsFlash Module
 * @copyright Copyright (C) 2009 RocketTheme. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
 * @author RocketTheme, LLC
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
?>
<div id="newsflash" class="roknewsflash<?php echo $params->get('moduleclass_sfx'); ?>">
    <span class="flashing"><?php echo $params->get('pretext'); ?></span>
    <ul style="margin-left:<?php echo $params->get('news_indent',70); ?>px;">
<?php foreach ($list as $item) :  ?>
		<li>
		    <a href="<?php echo $item->link; ?>">
		    <?php
		    if ($params->get('usetitle')==1) {
		        echo ($item->title);
		    } else {
		        echo ($item->introtext . '...');
		    }
		    ?>
  		    </a>
		</li>
<?php endforeach; ?>
    </ul>
</div>