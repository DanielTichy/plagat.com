<?php defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript" type="text/javascript">
<!--
 function actualizar_lista()
 {  
    document.forms["adminForm"].controller.value="unidades";
    document.forms["adminForm"].submit();
 }
 
function tableOrdering( order, dir, task )
{
        var form = document.adminForm;
        form.controller.value = "unidades";
        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
}

-->
</script>
<?php  if($this->isframe) {$file = 'index3.php';}else{$file = 'index.php';} ?>
<form action="<?php echo $file?>" method="post" name="adminForm">

<div id="editcell">
        <table>
		<tr>
			<td align="left" width="100%">

			</td>
			<td nowrap="nowrap">
        <?php if($this->isframe) {
            echo $this->bar->render();
            echo '<input type="hidden" name="articleid" value="'.$this->articleid.'" />';
        }else{
            ?>
				<?php
				echo $this->lists['html_select'];
				?>
			
        <?php } ?></td>
		</tr>
	</table>
	<table class="adminlist">
	<thead>
		<tr>
			<th width="1%" >
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="1%" >
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>			
			<th>
				 
				<?php echo JHTML::_('grid.sort',  'title', 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th> 

                     
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Published', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
                         <th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<!--<?php echo JHTML::_('grid.order',  $this->items ); ?>-->
			</th>
			
			
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id ); 
		$link 		= JRoute::_( $file.'?option=com_perchadownloadsattach&amp;controller=unidad&amp;task=edit&amp;cid[]='. $row->id );
		$published 	= JHTML::_('grid.published', $row, $i );
		 
		$ordering = ($this->lists['order'] == 'a.ordering');
		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->title; ?> </a>
			 
                        </td>
			
			<td>
				<?php 
				//$published = JHTML::_('grid.published', $row->published, $i );
				//echo $published;
				 
				$published ="images/publish_x.png";
				if( $row->published ) $published = "images/tick.png";
				echo '<img src="'.$published.'" alt="" />';
				?>
			</td>

                        <td class="order">
                            <?php if($this->articleid>0){?>
				<span><?php echo $this->pagination->orderUpIcon( $i ,  true ,'orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, true , 'orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="hidden" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
                            <?php } ?>
                        </td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	
	</table>
</div>

<input type="hidden" name="option" value="com_perchadownloadsattach" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="unidad" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
