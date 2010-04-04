<?php
/**
* Online Babylon- Babylon Online Search Dictionary
* @ version 1.6.0
* @ package mod_Online_Babylon
* @ Released under GNU/GPL License - http://www.gnu.org/copyleft/gpl.htm
* @ copyright (C) 2007 by masih.ad@gmail.com - All rights reserved
**/
# Don't allow direct acces to the file
	defined( '_VALID_MOS' ) or die( 'Restricted access' );
#--------------------------------------
# Parameters
#--------------------------------------
$srtext         = $params->def('srtext','Translate');
$ldefault  		= $params->def('ldefault','Translate');
?>
<div>
<form name="blform" style="font-family:Tahoma, Arial, Verdana;font-size:10pt;text-align:center;" method="post" action="index.php?option=com_onlinebabylon">
<?php 
echo "<img src=\"".$GLOBALS['mosConfig_live_site']."/modules/mod_onlinebabylon/logo.gif\">";
?>
<select style="background: dddddd" name="lng">
<option selected value="<?php echo $ldefault ?>">To <?php echo $ldefault ?></option>
<option value="English">To English</option>
<option value="Farsi">To Persian(Farsi)</option>
<option value="Chinese (S)">To Chinese S</option>
<option value="Chinese (T)">To Chinese T</option>
<option value="Afrikaans ">To Afrikaans</option>
<option value="Arabic">To Arabic</option>
<option value="Bulgarian">To Bulgarian</option>
<option value="Croatian">To Croatian</option>
<option value="Danish">To Danish</option>
<option value="Dutch">To Dutch</option>
<option value="Estonian">To Estonian</option>
<option value="French">To Finnish</option>
<option value="French">To French</option>
<option value="Greek">To Greek</option>
<option value="German">To German</option>
<option value="Hebrew">To Hebrew</option>
<option value="Italian">To Italian</option>
<option value="Japanese">To Japanese</option>
<option value="Korean">To Korean</option>
<option value="Norwegian">To Norwegian</option>
<option value="Portuguese">To Portuguese</option>
<option value="Romanian">To Romanian</option>
<option value="Russian">To Russian</option>
<option value="Serbian">To Serbian</option>
<option value="Slovenian ">To Slovenian</option>
<option value="Spanish">To Spanish</option>
<option value="Swedish">To Swedish</option>
<option value="Thai">To Thai</option>
<option value="Turkish">To Turkish</option>
<option value="Ukrainian">To Ukrainian</option>
<option value="Urdu">To Urdu</option>
</select><br />
<input style="background: dddddd" name="searchtxt" type="text"><br />
<input type="submit" value="<?php echo $srtext ?>" name="Tsearch">
</form>
</div>