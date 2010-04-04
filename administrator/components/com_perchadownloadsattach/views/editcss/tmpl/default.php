<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
$user =& JFactory::getUser();
$user_type = $user->get('usertype');
$disable ="disabled";
if ($user_type == "Super Administrator")
    {
    $disable = "";
    }
?>
<form name="adminForm" action="index.php?option=com_perchadownloadsattach">
<textarea name="editcss" cols="100" rows="40" style="width:100%;" <?php echo $disable;?>><?php echo $this->text;?></textarea>
<input type="hidden" name="task" value="store" />
<input type="hidden" name="section" value="editcss" />
<input type="hidden" name="option" value="com_perchadownloadsattach" />
</form>
