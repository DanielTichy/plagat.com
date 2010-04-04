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
JHTML::_('behavior.formvalidation');

JHTML::_(
    'stylesheet', 'settings.css', 'administrator' .DS. 'components' .DS. 'com_wafl' .DS. 'views' .DS. 'settings'. DS. 'tmpl'.DS
); 

global $mainframe;
$k = 0;
$i=0;
$n=1;
$row        = &$this->items[$i];
$published  = JHTML::_('grid.published', $row, $i);

// prepare the options
$options       = array();
$templates     =& $this->templates;
$document =& JFactory::getDocument();
$style = 'fieldset.adminform2{'
        . 'margin: 0 10px 10px 10px;'
        . 'border: 1px solid #007700;'
        . '}'; 
$document->addStyleDeclaration($style);
$selected      = 0;
$selected_base = 0;
$template      =& $row->redirect_to_template;
$base_template =& $row->base_template;
for ($i = 1; $i <= sizeof($templates); $i++) {
    if ($template === $templates[$i-1]->directory) {
        $selected = $templates[$i-1]->directory;
    }
    if ($base_template === $templates[$i-1]->directory) {
        $selected_base = $templates[$i-1]->directory;
    }
    
    $options[] = JHTML::_('select.option', $templates[$i-1]->directory, $templates[$i-1]->name);
    
    if ($templates[$i-1]->name != 'WAFL!') {
        $options2[] = JHTML::_('select.option', $templates[$i-1]->directory, $templates[$i-1]->name);
    }
}
$list          = array('none','template switching','mobile','siruna');
$radioselected = $row->option;
$radioarray    = array();
for ($j = 1; $j <= sizeof($list); $j++) {
    $radioarray[] = JHTML::_('select.option', $j, $list[$j-1]);   
}
?>
<script type="text/javascript">
    /*
    * it gives the k parameter by reference and not by value so that
    * it always is linked with '5' and not 1,2,3,4 ...
    * Hence the loop deployment.
    * 
	var k;
    window.onload = function() {
        for (k=1; k<=4; k++){
        	var ex = document.getElementById('radio'+k);
            ex.onclick = click;
        }
    }

    function click() {
        alert(k);
        var b=document.getElementById(text);
    }
    
    function handler(text) {
        alert(text);
        

    }*/
    var id= 'fieldradio';
    var previous;
    window.onload = function() {
    	var ex1 = document.getElementById('radio1');
    	var ex2 = document.getElementById('radio2');
    	var ex3 = document.getElementById('radio3');
    	var ex4 = document.getElementById('radio4');

    	ex1.onchange = reset;
    	ex2.onchange = function() { handler('fieldradio2'); };
    	ex3.onchange = function() { handler('fieldradio3'); };
    	ex4.onchange = function() { handler('fieldradio4'); };

    	var option = <?php echo $radioselected?>;
    	if(option != 1) {
			handler(id+option);
    	}
    }
    
    function handler(text) {
        if (previous != null) {
			previous.className='adminform';
        }
        var b=document.getElementById(text);
        b.className='adminform2';
        previous = b;
    }

    function reset() {
    	if (previous != null) {
			previous.className='adminform';
        }
    }
</script>

<form action="index.php" method="post" name="adminForm">
	<div class="col width-50">
		<fieldset class="adminform" id="test">
			<legend><?php echo JText::_('Option') ?></legend>
			<div class="comment">
    			Select which WAFL! option you prefer to use.
    			Selecting none disables the wafl plugin.
			</div>
			<table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Choose: '); ?>
                        </label>
                    </td>
                    <td>
                        <?php echo JHTML::_('select.radiolist', $radioarray, 'radio', '', 'value', 'text', $radioselected); ?>
                    </td>
                </tr>
            </table>
		</fieldset>
		<fieldset class="adminform" id="fieldradio2">
			<legend><?php echo JText::_('Template Switching') ?></legend>
			<div class="comment">
    			Template switching enables the administrator to select a different template
    			when the user browses the Joomla! site with a mobile device.
    			In the undermentioned checkbox you can select the template that will be showed to the mobile user.
    		</div>
			<table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Mobile template: '); ?>
                        </label>
                    </td>
                    <td>
                        <?php echo JHTML::_('select.genericlist', $options, 'redirect_template', 'class="inputbox"', 'value', 'text', $selected); ?>
                    </td>
                </tr>
            </table>
		</fieldset>
		<fieldset class="adminform" id="fieldradio3">
			<legend><?php echo JText::_('Mobile Site Redirect') ?></legend>
			<div class="comment">
    			The mobile redirect will, as the name states, redirect users who are browsing
    			with a mobile device to the address inserted below.
			</div>
			<table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Mobile URL: '); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="redirect_mobile_url" id="redirect_mobile_url" size="32" maxlength="250" value="<?php echo $row->redirect_mobile_url;?>" />
                    </td>
                </tr>
            </table>
		</fieldset>
	</div>
	<div class="col width-50">
		<fieldset class="adminform" id="fieldradio4">
			<legend><?php echo JText::_('Siruna') ?></legend>
			<div class="comment">
    			When you have a Siruna account you can insert your Siruna coordinates here.
    			We refer to Siruna and the WAFL! manual for further information about the undermentioned parameters.
    		</div>
			<fieldset class="adminform">
				<legend><?php echo JText::_('General Options') ?></legend>
				<table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Siruna URL: '); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="siruna_url" id="siruna_url" size="32" maxlength="250" value="<?php echo $row->siruna_url;?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Port: '); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="siruna_port" id="siruna_port" size="32" maxlength="250" value="<?php echo $row->siruna_port;?>" />
                    </td>
            	</tr>
            	<tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Mobile URL: '); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="siruna_mobile_url" id="siruna_mobile_url" size="32" maxlength="250" value="<?php echo $row->siruna_mobile_url;?>" />
                    </td>
            	</tr>
            	<tr>
            	    <td width="100" align="right" class="key">
            	        <label>
            	            <?php echo JText::_('Enable device detection: '); ?>
            	        </label>
            	    </td>
            	    <td>
            	        <?php echo JHTML::_('select.booleanlist', 'device_detection', 'class="inputbox"', $row->device_detection);?>
            	    </td>
            	</tr>
            	<tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Base mobile template: '); ?>
                        </label>
                    </td>
                    <td>
                         <?php echo JHTML::_('select.genericlist', $options2, 'base_template', 'class="inputbox"', 'value', 'text', $selected_base); ?>
                    </td>
            	</tr>
            </table>
			</fieldset>
			<fieldset class="adminform">
				<legend><?php echo JText::_('Mapping') ?></legend>
				<table class="admintable">
				<tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Siruna Username: '); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="siruna_user" id="siruna_user" size="32" maxlength="250" value="<?php echo $row->siruna_user;?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Project ID: '); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="siruna_login" id="siruna_login" size="32" maxlength="250" value="<?php echo $row->siruna_login;?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Secret key: '); ?>
                        </label>
                    </td>

                    <td>
                        <input class="text_area" type="text" name="siruna_pass" id="siruna_pass" size="32" maxlength="250" value="<?php echo $row->siruna_pass;?>" />
                    </td>
            	</tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('Project base URL: '); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="base_url" id="base_url" size="32" maxlength="250" value="<?php echo $row->base_url;?>" />
                    </td>
                </tr>
            </table>
			</fieldset>
			
		</fieldset>
	</div>
    <input type="hidden" name="option" value="com_wafl" /> 
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="check" value="post"/>
    <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
    <input type="hidden" name="boxchecked" value="0"/> 
	<input type="hidden" name="c" value="settings" />
	<input type="hidden" name="view" value="settings" />
</form>

