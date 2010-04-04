<?php // @version $Id: default_items.php 8796 2007-09-09 15:46:34Z jinx $
defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
	function tableOrdering(order, dir, task) {
		var form = document.adminForm;

		form.filter_order.value = order;
		form.filter_order_Dir.value = dir;
		document.adminForm.submit(task);
	}
</script>

<div class="display">
	<form action="<?php echo htmlspecialchars($this->action); ?>" method="post" name="adminForm">
		<?php echo JText :: _('Display Num'); ?>&nbsp;
		<?php echo $this->pagination->getLimitBox(); ?>
		<input type="hidden" name="filter_order" value="<?php echo $this->lists['order'] ?>" />
		<input type="hidden" name="filter_order_Dir" value="" />
	</form>
</div>


<div class="weblinks">

	<?php if ($this->params->def('show_headings', 1)) : ?>
	<div class="categoryTopRow">

		<div class="categoryLeft" id="num">
			<?php echo JText::_('Num'); ?>
		</div>

		<div class="categoryMiddle" id="title">
			<?php echo JHTML::_('grid.sort', 'Web Link', 'title', $this->lists['order_Dir'], $this->lists['order']); ?>
		</div>

		<?php if ($this->params->get('show_link_hits')) : ?>
		<div class="categoryRight" id="hits">
			<?php echo JHTML::_('grid.sort', 'Hits', 'hits', $this->lists['order_Dir'], $this->lists['order']); ?>
		</div>
		<?php endif; ?>

	</div>
	<?php endif; ?>

	<?php foreach ($this->items as $item) : ?>
	<div class="sectiontableentry<?php echo $item->odd + 1; ?>">

        <div class="categoryRow">
            <div class="categoryLeft">
                <?php echo $this->pagination->getRowOffset($item->count); ?>
            </div>

            <div class="categoryMiddle">
                <?php if ($item->image) :
                    echo $item->image;
                    endif;
                    echo $item->link;
                    if ($this->params->get('show_link_description')) : ?>
                        <br />
                <?php echo nl2br($item->description);
                endif; ?>
            </div>

            <?php if ($this->params->get('show_link_hits')) : ?>
                <div class="categoryRight">
                    <?php echo $item->hits; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
	<?php endforeach; ?>

</div>


<p class="counter">
	<?php echo $this->pagination->getPagesCounter(); ?>
</p>
<?php echo $this->pagination->getPagesLinks();
