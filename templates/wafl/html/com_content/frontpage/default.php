<?php // @version $Id: default.php 9722 2007-12-21 16:55:15Z mtk $
defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->params->get('show_page_title')) : ?>
<h1 class="componentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>

<div class="blog<?php echo $this->params->get('pageclass_sfx'); ?>">

	<?php $i = $this->pagination->limitstart;
	$rowcount = $this->params->def('num_leading_articles', 1);
	for ($y = 0; $y < $rowcount && $i < $this->total; $y++, $i++) : ?>
		<div class="leading<?php echo $this->params->get('pageclass_sfx'); ?>">
			<?php $this->item =& $this->getItem($i, $this->params);
			echo $this->loadTemplate('item'); ?>
		</div>
		<span class="leading_separator<?php echo $this->params->get('pageclass_sfx'); ?>">&nbsp;</span>
	<?php endfor; ?>

	<?php $introcount = $this->params->def('num_intro_articles', 4);
	if ($introcount) :
	    /**
	     * @var integer The number of columns with articles. We only want 1 for the mobile version.
	     */
		$colcount = 1;
		$rowcount = (int) $introcount / $colcount;
		$ii = 0;
		for ($y = 0; $y < $rowcount && $i < $this->total; $y++, $i++) : ?>
			<div class="article_row<?php echo $this->params->get('pageclass_sfx'); ?>">
				<div class="article" >
                    <?php $this->item =& $this->getItem($i, $this->params);
                    echo $this->loadTemplate('item'); ?>
                </div>
				<span class="row_separator<?php echo $this->params->get('pageclass_sfx'); ?>">&nbsp;</span>
			</div>
		<?php endfor;
	endif; ?>

	<?php $numlinks = $this->params->def('num_links', 4);
	if ($numlinks && $i < $this->total) : ?>
	<div class="blog_more<?php echo $this->params->get('pageclass_sfx'); ?>">
		<?php $this->links = array_slice($this->items, $i - $this->pagination->limitstart, $i - $this->pagination->limitstart + $numlinks);
		echo $this->loadTemplate('links'); ?>
	</div>
	<?php endif; ?>

	<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
		<?php if( $this->pagination->get('pages.total') > 1 ) : ?>
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php endif; ?>
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
