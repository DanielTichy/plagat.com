<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?>
<?php
	if(!empty($this->msg))
	{
		echo $this->msg;
		return;
	}
?>

<form action="index.php" method="post" name="mmForm" id="mmForm">
<div>
	<table class="admintable">
    	<tr>
        	<td>
            	<label for="subject">
                	<?php echo JText::_( 'Subject' ); ?>:
                </label>
			</td>
		</tr>
        <tr>
        	<td>
            	<input class="text_area" type="text" name="subject" id="greeting" size="25" maxlength="75" value="<?php echo $this->greeting;?>" />
			</td>
		</tr>
		<tr>
			<td>
            	<?php
                	$editor =& JFactory::getEditor();
                    echo $editor->display('message', $this->content, '600', '300', '60', '20', false);
				?>
			</td>
		</tr>
	</table>
	<input type="button" name="Submit" class="button" value="Send" onclick="mmForm.task.value='skicka';mmForm.submit();"/>
</div>
<input type="hidden" name="option" value="com_qvarnis" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="qvarnis" />
</form>
<?php echo JHTML::_( 'form.token' ); ?>