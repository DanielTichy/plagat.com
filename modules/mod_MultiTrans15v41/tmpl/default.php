<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**

*
* @package    mod_MultiTrans15v41
* @subpackage Modules
* @link www.blackdale.com
* @Copyright Bob Galway</copyright>
* @license>GPL3   http://www.gnu.org/licenses/

* Consult Yahoo/Google/altavista for terms of service and permission for alterations to scripts accessed..
*/

//Collect Parameters
$server1=$params->get('server1');
$server2=$params->get('server2');
$frame=$params->get('frame');
$langg1=$params->get('langg1');
$langg2=$params->get('langg2');
$langy=$params->get('langy');
$langa=$params->get('langa');

$paddingleft=$params->get('paddingleft');
$paddingright=$params->get('paddingright');
$paddingtop=$params->get('paddingtop');
$paddingbottom=$params->get('paddingbottom');
$margintop=$params->get('margin-top');
$marginbottom=$params->get('margin-bottom');
$marginleftmodule=$params->get('margin-leftmodule');
$colour2=$params->get('colour2');

$asia=$params->get('asia');
$other=$params->get('other');
$west=$params->get('west');
$east=$params->get('east');

//translation language groups
//Language Groups for Google - new widget

$WE="ca,da,nl,en,fi,fr,gl,de,is,ga,it,no,pt,es,sv,cy,yi,";
$EE="sq,be,bg,hr,cs,et,el,hu,lv,lt,mk,pl,ro,ru,sr,sk,sl,uk,";
$AF="af,mt,sw";
$AS="ar,zh-CN,zh-TW,tl,iw,hi,id,ja,ko,ms,fa,th,tr,vi,";

//Making the Selections for translation for Google - new widget

if ($west==1){$selection.=$WE;}
if ($east==1){$selection.=$EE;}
if ($asia==1){$selection.=$AS;}
if ($other==1){$selection.=$AF;}


//Code to select particular Yahoo/Google/AltaVista with originating language

echo '<div  style = " margin-top:'.$margintop.' margin-bottom:'.$marginbottom.' position:relative;left:'.$marginleftmodule.' ">';

//Yahoo

if ($server2=='y' and $server1=='O'){echo '<br><div style ="padding-left:'.$paddingleft.'px; padding-right:'.$paddingright.'px; padding-top:'.$paddingtop.'px; padding-bottom:'.$paddingbottom.'px;  background-color:'.$colour2.';width:131px; "><script type="text/javascript" charset="UTF-8" language="JavaScript1.2" src="http://uk.babelfish.yahoo.com/free_trans_service/babelfish2.js?from_lang='.$langy.'&region=us"></script></div>';}

//Alta Vista

if ($server2=='a' and $server1=='O'){echo '<br><div style ="padding-left:'.$paddingleft.'px; padding-right:'.$paddingright.'px; padding-top:'.$paddingtop.'px; padding-bottom:'.$paddingbottom.'px;  background-color:'.$colour2.';width:131px; "><script language="JavaScript1.2" src="http://www.altavista.com/static/scripts/translate_'.$langa.'.js"></script></div>';}

//Google new style widget

if ($server1=='g1'){echo '<br><div style ="padding-left:'.$paddingleft.'px; padding-right:'.$paddingright.'px; padding-top:'.$paddingtop.'px; padding-bottom:'.$paddingbottom.'px;  background-color:'.$colour2.';"><div id="google_translate_element"></div><script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: "'.$langg1.'",
     includedLanguages: "'.$selection.'"
  }, "google_translate_element");
}
</script><script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script></div>';}

//Google old style widget

if ($server2=='g2' and $server1=='O'){echo '<br><div style ="padding-left:'.$paddingleft.'px; padding-right:'.$paddingright.'px; padding-top:'.$paddingtop.'px; padding-bottom:'.$paddingbottom.'px;  background-color:'.$colour2.';"><script src="http://www.gmodules.com/ig/ifr?url=http://www.google.com/ig/modules/translatemypage.xml&up_source_language='.$langg2.'&w=160&h=60&title=&border=&output=js"></script></div>';}

// removing frames ( not with google new style widget ) 

if (($frame=='1') and !($server1==('g1'))){echo '<script type="text/javascript">if (top.location != self.location)
{
	top.location.replace(self.location)
}</script>' ;  }

echo '<br/></div>';




?>

