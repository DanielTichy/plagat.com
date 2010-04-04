<?php
/**
 * default.php 
 * The adminForm
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

global $mainframe;

jimport('joomla.html.editor');

$filename2 = 'codemirror.js';
$path2 = 'administrator'.DS.'components'.DS.'com_wafl'.DS.'views'.DS.'advanced'.DS.'tmpl'.DS.'codemirror'.DS.'js'.DS;
JHTML::script($filename2, $path2);
/*$filename2 = 'codepress.js';
$path2 = 'administrator/components/com_wafl/views/advanced/tmpl/codepress/'; // add the path parameter if the path is different than : 'media/system/js/'
JHTML::script($filename2, $path2);*/
JHTML::_(
    'stylesheet', 'linenumbers.css', 'administrator'.DS.'components'.DS.'com_wafl'.DS.'views'.DS.'advanced'.DS.'tmpl'.DS
); 

?>
<script language="javascript" type="text/javascript">
<!--
var editor;
function submitbutton(pressbutton)
{
     var form = document.adminForm;
     if (pressbutton == 'save')
     {
         if (editor != null) {
             var edit = editor.getCode();
             document.getElementById('edit').value = escapeBrackets(edit);
             submitform(pressbutton);
         }
     } else if (pressbutton == 'restore')
     	submitform(pressbutton);
}
function escapeBrackets(text) {
	result = "";
	for (var i = 0; i < text.length; i++) {
		c = text.charAt(i);
		if (c == '<')
			result = result + '&lt;';
		else if (c == '>')
			result = result + '&gt;';
		else
			result = result + c;
	}
	return result;
}

//-->
</script>


<form  method="post" name="adminForm" id="adminForm">
    <div class="col100">
        <fieldset class="adminform">
            <legend><?php echo JText::_('Siruna Advanced'); ?></legend>
    
            <table class="admintable" align="center">
                <tr>
                    <td>
                            
                   <?php if ($this->data !== null) {?>
                        
                                <div class="textborder">
                                	<textarea id="codeeditor" name="codeeditor" class="codepress html" cols="120" rows="30" lang="en" xml:lang="en"><?php echo $this->data; ?></textarea>
                                </div>	
                                <input type="hidden" name="edit" id="edit" value="" />
                                <script type="text/javascript">
                                    editor = CodeMirror.fromTextArea('codeeditor', {    
                                          height: "350px",
                                          width: "700px",
                                    	  parserfile: ["tokenizexml.js", "parsexml.js"],
                                          path: "components/com_wafl/views/advanced/tmpl/codemirror/js/",
                                    	  stylesheet: "components/com_wafl/views/advanced/tmpl/codemirror/css/xmlcolors.css",
                                    	  continuousScanning: 500,
                                    	  lineNumbers: true,
                                    	  textWrapping: true
                                    	});
                                </script>
                   <?php } else {
                               global $mainframe;
                               $mainframe->enqueueMessage(
                                   'SirunaParser.xml doesn\'t exist or is empty. '.
                                   'Please press the save button in the settings panel', 'notice'
                               ); 
                           }
                           ?>
                    	</td>
                </tr>
    		 </table>
        </fieldset>
    </div>

    <input type="hidden" name="option" value="com_wafl" /> 
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="check" value="post"/>
    <input type="hidden" name="boxchecked" value="0"/> 
	<input type="hidden" name="c" value="advanced" />
	<input type="hidden" name="view" value="advanced" />
</form>
