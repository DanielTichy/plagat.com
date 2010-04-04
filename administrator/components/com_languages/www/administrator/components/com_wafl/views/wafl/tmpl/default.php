<?php
/**
 * default.php
 *
 * PHP version 5
 *
 * @category   Administrator
 * @package    Wafl
 * @subpackage Administrator.Views
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://www.wafl.ugent.be
 */

defined('_JEXEC') or die('Restricted access');
/**
 * Adding db_handler.
 */
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'wafl.php';
global $mainframe;
if ($this->getModel()->isUpdated() === true) {
    $mainframe->redirect('index.php?option=' . $option);
}

//needed for form action
$uri =& JFactory::getURI();
$this->requestURL =& $uri->toString();
?>

<form action="<?php echo $this->requestURL; ?>" method="post" name="adminForm">
	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_('ID'); ?></th>
					<th width="20">
						<input type="checkbox" name="toggle" value=""
							onclick="checkAll(<?php echo count($this->items); ?>);" />
					</th>
        			<th width="5" align="center"><?php echo JText::_('Module_id'); ?></th>
        			<th><?php echo JText::_('Title'); ?></th>
        			<th><?php echo JText::_('Module'); ?></th>
        			<th><?php echo JText::_('Position'); ?></th>
 					<th width="8%" nowrap="nowrap">
                        <?php echo JHTML::_('grid.sort', 'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
                        <?php echo JHTML::_('grid.order', $this->items); ?>
               		</th> 
        			<th width="5" align="center"><?php echo JText::_('Enabled for Mobile devices'); ?></th>
        		</tr>
			</thead>
            <?php
            $orders = ($this->lists['order'] == 'a.ordering');  
            $orders = 0;
            $k = 0;
            for ($i=0, $n=count($this->items); $i < $n; $i++) {
                $row        = &$this->items[$i];
                $checked 	= JHTML::_('grid.id', $i, $row->id);
                $published  = JHTML::_('grid.published', $row, $i);
                $row        = &$this->items[$i];
            ?>
        	<tr class="<?php echo "row$k"; ?>">
                <td><?php echo $row->id; ?></td>
                <td><?php echo $checked; ?></td>
                <td align="center"><?php echo $row->module_id; ?></td>
                <td><?php echo $row->title; ?></td>
                <td><?php echo $row->module; ?></td>
                <td><?php echo $row->position; ?></td>
                <td class="order">
                	<span><?php echo $this->pagination->orderUpIcon($i, true, 'orderup', 'Move Up', true); ?></span>
         			<span><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderdown', 'Move Down', true); ?></span>
         			<?php $disabled = $orders ?  '' : 'disabled="disabled"'; ?>
                	<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $orders ?> class="text_area" style="text-align: center" />
         		</td>
                <td align="center"><?php echo $published; ?></td>
        	</tr>
            <?php
            $k = 1 - $k;
            }
            ?>
            <tfoot>
                <tr>
                    <td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>
            
    	</table>
	</div>
    <input type="hidden" name="option" value="com_wafl" /> 
    <input type="hidden" name="task" value="" /> 
    <input type="hidden" name="boxchecked" value="0" /> 
	<input type="hidden" name="c" value="wafl" />
	<input type="hidden" name="view" value="wafl" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>


