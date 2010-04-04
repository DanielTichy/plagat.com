<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$ccid	= $params->get( 'ccid' );
$module_desc = $params->get( 'mod_desc' );
$email_desc = $params->get( 'email_desc' );
$submit_btn = $params->get( 'submit_btn' );
?>
<p><?php echo $module_desc;?></p>
<form name="ccoptin" action="http://visitor.constantcontact.com/d.jsp" method="post" style="margin-bottom:2px" target="_blank">
  <input type="hidden" name="m" value="<?php echo $ccid; ?>">
  <input type="hidden" name="p" value="oi">
  <input type="text" name="ea" size="20" value="<?php echo $email_desc; ?>" onfocus="javascript:if(this.value=='<?php echo $email_description; ?> '){this.value='';}" class="text">
  <input type="submit" name="<?php echo $submit_btn; ?>" value="<?php echo $submit_btn; ?>" class="button">
</form>
