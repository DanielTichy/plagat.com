<?php
/**
* Module My Social Bookmark For Joomla 1.5
* Versi	: 1.0
* Created by	: Rony Sandra Yofa Zebua And camp26.biz Team
* Email	: ronysyz@gmail.com
* Created on	: 19 March 2008
* URL		: www.camp26.biz
* Released under GNU/GPL License - http://www.gnu.org/copyleft/gpl.htm
* This Module based from Social Bookmark Script - Joomla Module For Joomla 1.0.x (http://www.social-bookmark-script.de/)
* Help us to keep this project alive and let the links and copyright information untouched!
*/

$align 			= $params->get('align', 'center');
$padding 		= $params->get('padding', '0');
$ani 			= $params->get('ani', '_ani');
?>
<div style="padding:<?echo $padding;?>px;" align="<?echo $align;?>">
<?
defined('_JEXEC') or die('Restricted access');
global $mosConfig_MetaKeys, $mosConfig_MetaDesc;
$alt 			= $params->get('alt', "Add to:");
$whatlink		= $params->get('whatlink', "http://en.wikipedia.org/wiki/Social_Bookmarking");
$tags 			= str_replace("\n","", $mosConfig_MetaKeys);
$tags 			= trim($tags);
$tags_space		= str_replace(',', ' ', $tags);
$tags_semi 		= str_replace(',', ';', $tags);
$tags_space 	= str_replace('  ', ' ', $tags_space);
$description 	= $mosConfig_MetaDesc;
$style 			= $params->get('style', "");
$wong 			= $params->get('wong', 1);
$webnews 		= $params->get('webnews', 1);
$iciode 		= $params->get('iciode', 1);
$oneview 		= $params->get('oneview', 1);
$yigg 			= $params->get('yigg', 1);
$linkarena 		= $params->get('linkarena', 1);
$digg 			= $params->get('digg', 1);
$icio 			= $params->get('icio', 1);
$reddit 		= $params->get('reddit', 1);
$simpy 			= $params->get('simpy', 1);
$stumbleupon 	= $params->get('stumbleupon', 1);
$slashdot 		= $params->get('slashdot', 1);
$netscape 		= $params->get('netscape', 1);
$furl 			= $params->get('furl', 1);
$yahoo 			= $params->get('yahoo', 1);
$blogmarks 		= $params->get('blogmarks', 1);
$diigo 			= $params->get('diigo', 1);
$technorati 	= $params->get('technorati', 1);
$newsvine 		= $params->get('newsvine', 1);
$blinkbits		= $params->get('blinkbits', 1);
$magnolia 		= $params->get('magnolia', 1);
$smarking 		= $params->get('smarking', 1);
$netvouz 		= $params->get('netvouz', 1);
$folkd 			= $params->get('folkd', 1);
$spurl 			= $params->get('spurl', 1);
$google 		= $params->get('google', 1);
$blinklist 		= $params->get('blinklist', 1);
$linktype 		= $params->get('linktype', 1);
$what 			= $params->get('what', 1);
$baseurl 		= JURI::base();
if ($ani == "_ani")
		{	
		if ($wong == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/bookmarks/wong".$style."_ani.gif',";
		}
		
		if ($webnews == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/webnews".$style."_ani.gif',";
		}
			
		if ($icio == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/icio".$style."_ani.gif',";
		}
		
		if ($oneview == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/oneview".$style."_ani.gif',";
		}
		
		if ($folkd == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/folkd".$style."_ani.gif',";
		}
		
		if ($yigg == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/yigg".$style."_ani.gif',";
		}
		
		if ($linkarena == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/linkarena".$style."_ani.gif',";
		}
	
		if ($digg == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/digg".$style."_ani.gif',";
		}
		
		if ($del == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/del".$style."_ani.gif',";
		}
		
		if ($reddit == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/reddit".$style."_ani.gif',";
		}
		
		if ($simpy == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/simpy".$style."_ani.gif',";
		}
		
		if ($stumbleupon == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/stumbleupon".$style."_ani.gif',";
		}
		
		if ($slashdot == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/slashdot".$style."_ani.gif',";
		}
		
		if ($netscape == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/netscape".$style."_ani.gif',";
		}
		
		if ($furl == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/furl".$style."_ani.gif',";
		}
		
		if ($yahoo == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/yahoo".$style."_ani.gif',";
		}
		
		if ($spurl == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/spurl".$style."_ani.gif',";
		}
		
		if ($google == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/google".$style."_ani.gif',";
		}
		
		if ($blinklist == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/blinklist".$style."_ani.gif',";
		}
			
		if ($blogmarks == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/blogmarks".$style."_ani.gif',";
		}
		
		if ($diigo == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/diigo".$style."_ani.gif',";
		}
		
		if ($technorati == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/technorati".$style."_ani.gif',";
		}
		
		if ($newsvine == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/newsvine".$style."_ani.gif',";
		}
		
		if ($blinkbits == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/blinkbits".$style."_ani.gif',";
		}
		
		if ($magnolia == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/ma.gnolia".$style."_ani.gif',";
		}
		
		if ($smarking == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/smarking".$style."_ani.gif',";
		}
		
		if ($netvouz == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/netvouz".$style."_ani.gif',";
		}
		
		if ($what == "1")
		{
		$Social_Load .= "'".$baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/what".$style."_ani.gif',";
		}
	$Social_Load .= "'http://www.social-bookmark-script.de/load.gif'";
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function Social_Load() { 
var d=document; if(d.images){ if(!d.Social) d.Social=new Array();
var i,j=d.Social.length,a=Social_Load.arguments; for(i=0; i<a.length; i++)
if (a[i].indexOf("#")!=0){ d.Social[j]=new Image; d.Social[j++].src=a[i];}}
}
Social_Load(<? echo $Social_Load;?>)
function schnipp() { 
var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function schnupp(n, d) { 
  var p,i,x; if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
  d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=schnupp(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
  }
function schnapp() { 
  var i,j=0,x,a=schnapp.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
  if ((x=schnupp(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
  }
  //-->
</script>
	<?	}




switch ( $wong ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.mister-wong.de/"  onclick="window.open('http://www.mister-wong.de/index.php?action=addurl&amp;bm_url='+encodeURIComponent(location.href)+'&amp;bm_notice=<?echo $description;?>&amp;bm_description='+encodeURIComponent(document.title)+'&amp;bm_tags=<?echo $tags_space;?>');return false;" title="<?echo $alt;?> Mr. Wong"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('wong','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/wong<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/wong".$style.".gif";?>" alt="<?echo $alt;?> Mr. Wong" name="wong" border="0" id="wong"/>
		</a>
		<?
		break;
	case 2:
		break;
}

switch ( $webnews) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.webnews.de/" onclick="window.open('http://www.webnews.de/einstellen?url='+encodeURIComponent(document.location)+'&amp;title='+encodeURIComponent(document.title)+'&amp;desc=<?echo $description;?>');return false;" title="<?echo $alt;?> Webnews"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Webnews','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/webnews<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/webnews".$style.".gif";?>" alt="<?echo $alt;?> Webnews" name="Webnews" border="0" id="Webnews"/>
		</a>
		<?
		break;
	case 2:
		break;
}

switch ( $iciode) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.icio.de/" onclick="window.open('http://www.icio.de/add.php?url='+encodeURIComponent(location.href));return false;" title="<?echo $alt;?> Icio"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Icio','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/icio<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/icio".$style.".gif";?>" alt="<?echo $alt;?> Icio" name="Icio" border="0" id="Icio"/>
		</a>
		<?
		break;
	case 2:
		break;
}

switch ( $oneview) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://beta.oneview.de/"  onClick="window.open('http://beta.oneview.de:80/quickadd/neu/addBookmark.jsf?URL='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Oneview"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Oneview','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/oneview<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/oneview".$style.".gif";?>" alt="<?echo $alt;?> Oneview" name="Oneview" border="0" id="Oneview">
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $yigg ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://yigg.de/" onclick="window.open('http://yigg.de/neu?exturl='+encodeURIComponent(location.href));return false" title="<?echo $alt;?> Yigg"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Yigg','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/yigg<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/yigg".$style.".gif";?>" alt="<?echo $alt;?> Yigg" name="Yigg" border="0" id="Yigg"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $linkarena ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.linkarena.com/" onclick="window.open('http://linkarena.com/bookmarks/addlink/?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;desc=<?echo $description;?>&amp;tags=<?echo $tags_space;?>');return false;" title="<?echo $alt;?> Linkarena"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Linkarena','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/linkarena<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/linkarena".$style.".gif";?>" alt="<?echo $alt;?> Linkarena"  name="Linkarena" border="0" id="Linkarena"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $digg ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://digg.com/" onclick="window.open('http://digg.com/submit?phase=2&amp;url='+encodeURIComponent(location.href)+'&amp;bodytext=<?echo $description;?>&amp;tags=<?echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Digg"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Digg','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/digg<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/digg".$style.".gif";?>" alt="<?echo $alt;?> Digg" name="Digg" border="0" id="Digg"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $icio ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://del.icio.us/" onclick="window.open('http://del.icio.us/post?v=2&amp;url='+encodeURIComponent(location.href)+'&amp;notes=<?echo $description;?>&amp;tags=<?echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Del.icio.us"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Delicious','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/del<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/del".$style.".gif";?>" alt="<?echo $alt;?> Del.icoi.us" name="Delicious" border="0" id="Delicious"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $reddit ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://reddit.com/" onclick="window.open('http://reddit.com/submit?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Reddit"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Reddit','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/reddit<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/reddit".$style.".gif";?>" alt="<?echo $alt;?> Reddit" name="Reddit" border="0" id="Reddit"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $simpy ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.simpy.com/" onclick="window.open('http://www.simpy.com/simpy/LinkAdd.do?title='+encodeURIComponent(document.title)+'&amp;tags=<?echo $tags;?>&amp;note=<?echo $description;?>&amp;href='+encodeURIComponent(location.href));return false;" title="<?echo $alt;?> Simpy"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Simpy','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/simpy<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/simpy".$style.".gif";?>" alt="<?echo $alt;?> Simpy" name="Simpy" border="0" id="Simpy"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $stumbleupon ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.stumbleupon.com/" onclick="window.open('http://www.stumbleupon.com/submit?url='+encodeURIComponent(location.href)+'&amp;newcomment=<?echo $description;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> StumbleUpon"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('StumbleUpon','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/stumbleupon<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/stumbleupon".$style.".gif";?>" alt="<?echo $alt;?> StumbleUpon" name="StumbleUpon" border="0" id="StumbleUpon"/>
		</a>
		<?
		break;
	case 2:
		break;
}

switch ( $slashdot ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://slashdot.org/" onclick="window.open('http://slashdot.org/bookmark.pl?url='+encodeURIComponent(location.href)+'&amp;tags=<?echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Slashdot"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Slashdot','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/slashdot<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/slashdot".$style.".gif";?>" alt="<?echo $alt;?> Slashdot" name="Slashdot" border="0" id="Slashdot"/>
		</a>
		<?
		break;
	case 2:
		break;
}

switch ( $netscape ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.netscape.com/" onclick="window.open('http://www.netscape.com/submit/?U='+encodeURIComponent(location.href)+'&amp;storyText=<?echo $description;?>&amp;storyTags=<?echo $tags;?>&amp;T='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Netscape"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Netscape','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/netscape<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/netscape".$style.".gif";?>" alt="<?echo $alt;?> Netscape" name="Netscape" border="0" id="Netscape"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $furl ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.furl.net/" onclick="window.open('http://www.furl.net/storeIt.jsp?u='+encodeURIComponent(location.href)+'&amp;keywords=<?echo $tags;?>&amp;t='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Furl"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Furl','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/furl<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/furl".$style.".gif";?>" alt="<?echo $alt;?> Furl" name="Furl" border="0" id="Furl"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $yahoo ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.yahoo.com/" onclick="window.open('http://myweb2.search.yahoo.com/myresults/bookmarklet?t='+encodeURIComponent(document.title)+'&amp;d=<?echo $description;?>&amp;tag=<?echo $tags?>&amp;u='+encodeURIComponent(location.href));return false;" title="<?echo $alt;?> Yahoo"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Yahoo','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/yahoo<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/yahoo".$style.".gif";?>" alt="<?echo $alt;?> Yahoo" name="Yahoo" border="0" id="Yahoo"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $blogmarks ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://blogmarks.net/" onclick="window.open('http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url='+encodeURIComponent(location.href)+'&amp;content=<?echo $description;?>&amp;public-tags=<?echo $tags?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Blogmarks"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Blogmarks','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/blogmarks<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/blogmarks".$style.".gif";?>" alt="<?echo $alt;?> Blogmarks" name="Blogmarks" border="0" id="Blogmarks"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $diigo ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.diigo.com/" onclick="window.open('http://www.diigo.com/post?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;tag=<?echo $tags?>&amp;comments=<?echo $description;?>'); return false;" title="<?echo $alt;?> Diigo"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Diigo','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/diigo<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/diigo".$style.".gif";?>" alt="<?echo $alt;?> Diigo" name="Diigo" border="0" id="Diigo"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $technorati ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.technorati.com/" onclick="window.open('http://technorati.com/faves?add='+encodeURIComponent(location.href)+'&amp;tag=<?echo $tags_space?>');return false;" title="<?echo $alt;?> Technorati"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Technorati','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/technorati<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/technorati".$style.".gif";?>" alt="<?echo $alt;?> Technorati" name="Technorati" border="0" id="Technorati"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $newsvine ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.newsvine.com/" onclick="window.open('http://www.newsvine.com/_wine/save?popoff=1&amp;u='+encodeURIComponent(location.href)+'&amp;tags=<?echo $tags?>&amp;blurb='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Newsvine"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Newsvine','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/newsvine<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/newsvine".$style.".gif";?>" alt="<?echo $alt;?> Newsvine" name="Newsvine" border="0" id="Newsvine"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $blinkbits ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.blinkbits.com/" onclick="window.open('http://www.blinkbits.com/bookmarklets/save.php?v=1&amp;title='+encodeURIComponent(document.title)+'&amp;source_url='+encodeURIComponent(location.href)+'&amp;source_image_url=&amp;rss_feed_url=&amp;rss_feed_url=&amp;rss2member=&amp;body=<?echo $description;?>');return false;" title="<?echo $alt;?> Blinkbits"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Blinkbits','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/blinkbits<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/blinkbits".$style.".gif";?>" alt="<?echo $alt;?> Blinkbits" name="Blinkbits" border="0" id="Blinkbits"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $magnolia ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://ma.gnolia.com/" onclick="window.open('http://ma.gnolia.com/bookmarklet/add?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;description=<?echo $description;?>&amp;tags=<?echo $tags;?>');return false;" title="<?echo $alt;?> Ma.Gnolia"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('MaGnolia','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/ma.gnolia<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/ma.gnolia".$style.".gif";?>" alt="<?echo $alt;?> Ma.Gnolia" name="MaGnolia" border="0" id="MaGnolia"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $smarking ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://smarking.com/" onclick="window.open('http://smarking.com/editbookmark/?url='+ location.href +'&amp;description=<?echo $description;?>&amp;tags=<?echo $tags;?>');return false;" title="<?echo $alt;?> Smarking"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Smarking','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/smarking<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/smarking".$style.".gif";?>" alt="<?echo $alt;?> Smarking" name="Smarking" border="0" id="Smarking"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $netvouz ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.netvouz.com/" onclick="window.open('http://www.netvouz.com/action/submitBookmark?url='+encodeURIComponent(location.href)+'&amp;description=<?echo $description;?>&amp;tags=<?echo $tags;?>&amp;title='+encodeURIComponent(document.title)+'&amp;popup=yes');return false;" title="<?echo $alt;?> Netvouz"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Netvouz','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/netvouz<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/netvouz".$style.".gif";?>" alt="<?echo $alt;?> Netvouz" name="Netvouz" border="0" id="Netvouz"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $folkd ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.folkd.com/" onclick="window.open('http://www.folkd.com/page/submit.html?step2_sent=1&amp;url='+encodeURIComponent(location.href)+'&amp;check=page&amp;add_title='+encodeURIComponent(document.title)+'&amp;add_description=<?echo $description;?>&amp;add_tags_show=&amp;add_tags=<?echo $tags_semi;?>&amp;add_state=public');return false;" title="<?echo $alt;?> Folkd"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Folkd','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/folkd<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/folkd".$style.".gif";?>" alt="<?echo $alt;?> Folkd" name="Folkd" border="0" id="Folkd"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $spurl ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.spurl.net/" onclick="window.open('http://www.spurl.net/spurl.php?v=3&amp;tags=<?echo $tags;?>&amp;title='+encodeURIComponent(document.title)+'&amp;url='+encodeURIComponent(document.location.href));return false;" title="<?echo $alt;?> Spurl"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Spurl','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/spurl<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/spurl".$style.".gif";?>" alt="<?echo $alt;?> Spurl" name="Spurl" border="0" id="Spurl"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $google ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.google.com/" onclick="window.open('http://www.google.com/bookmarks/mark?op=add&amp;hl=de&amp;bkmk='+encodeURIComponent(location.href)+'&amp;annotation=<?echo $description;?>&amp;labels=<?echo $tags;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Google"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Google','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/google<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/google".$style.".gif";?>" alt="<?echo $alt;?> Google" name="Google" border="0" id="Google"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $blinklist ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.blinklist.com/" onclick="window.open('http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Description=<?echo $description;?>&amp;Tag=<?echo $tags;?>&amp;Url='+encodeURIComponent(location.href)+'&amp;Title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Blinklist"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Blinklist','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/blinklist<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/blinklist".$style.".gif";?>" alt="<?echo $alt;?> Blinklist" name="Blinklist" border="0" id="Blinklist"/>
		</a>
		<?
		break;
	case 2:
		break;
}


switch ( $linktype ) {
	case 1:
		$target= "_Blank";
		break;
	case 2:
		$target= "_Self";
		break;
}


switch ( $what ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="<? echo $whatlink; ?>" target="<? echo $target; ?>" title="Information"<?  if ($ani == "_ani") { ?> onMouseOver="schnapp('Information','','<? echo $baseurl ;?>/modules/mod_mysocialbookmark/mysocialbookmark/what<?echo $style;?>_ani.gif',1)" onMouseOut="schnipp()" <? }?>>
		<img style="padding-bottom:1px;" src="<?echo $baseurl ."/modules/mod_mysocialbookmark/mysocialbookmark/what".$style.".gif";?>" alt="Information" name="Information" border="0" id="Information"/>
		</a>
		<?
		break;
	case 2:
		break;
}


?><br/>
<align="center" style="text-decoration:none;font-size:6px;font-family:verdana;color: Gray;">by: <a style="text-decoration:none;font-size:6px;font-family:verdana;color: Gray;" href="http://www.camp26.biz/" target="_blank" alt="MySocialBookmark for Joomla 1.5 by camp26.biz">camp26.biz</a></align>
</div>