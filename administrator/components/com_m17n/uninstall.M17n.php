<?php

/**
* Joomla :: Multilang_M17n v1.5.8
* Multilang info@groenewegdigitaal, M17n joomla@sdic.ch
* http://www.groenewegdigitaal.nl, http://www.sdic.ch
**/

function com_uninstall () {
	global $mainframe;
	global $dirs,$save,$copy;
	
	$db=JFactory::getDBO();
	$db->setQuery("DELETE FROM `#__modules` WHERE module='mod_M17n' LIMIT 1");
	$db->query();
	$db->setQuery("DELETE FROM `#__plugins` WHERE element='M17n' AND folder='system' LIMIT 1");
	$db->query();
	
	// Files Definition List
	$dirs=Array(
		'modules/mod_M17n/icons',
		'modules/mod_M17n'
	);
	
	// Unhacked files to save
	$save=Array(
		'administrator/components/com_menus/views/item/tmpl/form.php',
		'administrator/components/com_menus/views/list/tmpl/default.php',
		'administrator/components/com_menus/views/list/view.php',
		'administrator/components/com_menus/models/list.php',
		
		'libraries/joomla/application/menu.php',
		
		'modules/mod_mainmenu/helper.php',
		'modules/mod_mainmenu/legacy.php',
		'modules/mod_mainmenu/mod_mainmenu.php',
		'modules/mod_mainmenu/mod_mainmenu.xml',
		'modules/mod_mainmenu/tmpl/default.php',
//  hack multilang
		'components/com_content/models/frontpage.php',
		'components/com_content/models/category.php',
		'components/com_content/models/section.php',
		'components/com_content/controller.php',
		'plugins/search/content.php',
		'modules/mod_mostread/helper.php',
		'modules/mod_latestnews/helper.php', 
		'modules/mod_newsflash/helper.php' ,
// end hack multilang					
	);
	
	// Packed files to copy
	$copy=Array(
		'administrator/components/com_menus/views/item/tmpl/form.php',
		'administrator/components/com_menus/views/list/tmpl/default.php',
		'administrator/components/com_menus/views/list/view.php',
		'administrator/components/com_menus/models/list.php',
		
		'libraries/joomla/application/menu.php',
		
		'modules/mod_mainmenu/helper.php',
		'modules/mod_mainmenu/legacy.php',
		'modules/mod_mainmenu/mod_mainmenu.php',
		'modules/mod_mainmenu/mod_mainmenu.xml',
		'modules/mod_mainmenu/tmpl/default.php',
		
		'modules/mod_M17n/index.html',
		'modules/mod_M17n/mod_M17n.php',
		'modules/mod_M17n/mod_M17n.xml',
		'modules/mod_M17n/icons/af.gif',
		'modules/mod_M17n/icons/index.html',
		'modules/mod_M17n/icons/ar.gif',
		'modules/mod_M17n/icons/az.gif',
		'modules/mod_M17n/icons/bg.gif',
		'modules/mod_M17n/icons/bn.gif',
		'modules/mod_M17n/icons/bs.gif',
		'modules/mod_M17n/icons/ca.gif',
		'modules/mod_M17n/icons/cy.gif',
		'modules/mod_M17n/icons/cz.gif',
		'modules/mod_M17n/icons/da.gif',
		'modules/mod_M17n/icons/de.gif',
		'modules/mod_M17n/icons/el.gif',
		'modules/mod_M17n/icons/en.gif',
		'modules/mod_M17n/icons/eo.gif',
		'modules/mod_M17n/icons/es.gif',
		'modules/mod_M17n/icons/et.gif',
		'modules/mod_M17n/icons/fa.gif',
		'modules/mod_M17n/icons/fi.gif',
		'modules/mod_M17n/icons/fr.gif',
		'modules/mod_M17n/icons/hi.gif',
		'modules/mod_M17n/icons/hr.gif',
		'modules/mod_M17n/icons/hu.gif',
		'modules/mod_M17n/icons/hy.gif',
		'modules/mod_M17n/icons/is.gif',
		'modules/mod_M17n/icons/it.gif',
		'modules/mod_M17n/icons/ja.gif',
		'modules/mod_M17n/icons/ka.gif',
		'modules/mod_M17n/icons/ko.gif',
		'modules/mod_M17n/icons/ku.gif',
		'modules/mod_M17n/icons/lt.gif',
		'modules/mod_M17n/icons/lv.gif',
		'modules/mod_M17n/icons/mn.gif',
		'modules/mod_M17n/icons/nl.gif',
		'modules/mod_M17n/icons/no.gif',
		'modules/mod_M17n/icons/pl.gif',
		'modules/mod_M17n/icons/ps.gif',
		'modules/mod_M17n/icons/ro.gif',
		'modules/mod_M17n/icons/ru.gif',
		'modules/mod_M17n/icons/sk.gif',
		'modules/mod_M17n/icons/sl.gif',
		'modules/mod_M17n/icons/sr.gif',
		'modules/mod_M17n/icons/sv.gif',
		'modules/mod_M17n/icons/ta.gif',
		'modules/mod_M17n/icons/th.gif',
		'modules/mod_M17n/icons/tr.gif',
		'modules/mod_M17n/icons/uk.gif',
		'modules/mod_M17n/icons/ur.gif',
		'modules/mod_M17n/icons/uz.gif',
		'modules/mod_M17n/icons/vi.gif',
		'modules/mod_M17n/icons/zh.gif',
		
		'plugins/system/M17n.php',
		'plugins/system/M17n.xml',
		
//  hack multilang
		'components/com_content/models/frontpage.php',
		'components/com_content/models/category.php',
		'components/com_content/models/section.php',
		'plugins/search/content.php',
		'modules/mod_mostread/helper.php',
		'modules/mod_latestnews/helper.php', 
		'modules/mod_newsflash/helper.php' ,
// end hack multilang			
	);
	
	$rootPath=JPATH_ROOT;
	
	uncopyFiles($rootPath);
	clearFiles($rootPath);
	
	$db=JFactory::getDBO();
	$db->setQuery("DELETE FROM `#__modules` WHERE module='mod_M17n'");
	$db->query();
	$db->setQuery("DELETE FROM `#__plugins` WHERE element='M17n' AND folder='system'");
	$db->query();
	
	$module_msg="Successfully Uninstalled Joomla :: M17n Package";
	
	echo "<p>$module_msg</p>";
	
}

// Function to remove the files (remove files, remove dirs, restore originals)
function uncopyFiles($basePath){
	global $dirs,$save,$copy;
	
	if(subStr($basePath,-1)!='/')$basePath.='/';
	
	$basePath=str_replace("\\","/",$basePath);
	
	$fromPath=$basePath.'administrator/components/com_m17n/';
	
	$fsdirs=new JFolder();
	$fslink=new JFile();
	
	foreach($copy as $src){
		// Removes installed file
		$dest=$basePath.$src;
		$fslink->delete($dest);
		// Removes package file
		$dest=$fromPath.$src;
		if(file_exists($dest))$fslink->delete($dest);
	}
	
	foreach($dirs as $dir){
		$folder=$basePath.$dir;
		if(is_dir($folder)) $fsdirs->delete($folder);
	}
	
	foreach($save as $bak){
		$new=str_replace(Array('.php','.xml','.html'),Array('.bak.php','.bak.xml','.bak.html'),$bak);
		$oldPath=$basePath.$bak;
		$newPath=$basePath.$new;
		if(file_exists($newPath))$fslink->move($newPath,$oldPath);
	}
}

// Function to clean package (removes dirs from install folder)
function clearFiles($basePath){
	
	$fsdirs=new JFolder();
	$fslink=new JFile();
	
	if(subStr($basePath,-1)!='/')$basePath.='/';
	$fromPath=$basePath.'administrator/components/com_m17n';
	
	if(is_dir($fromPath.'/modules/mod_mainmenu/tmpl'))$fsdirs->delete($fromPath.'/modules/mod_mainmenu/tmpl');
	if(is_dir($fromPath.'/modules/mod_mainmenu'))$fsdirs->delete($fromPath.'/modules/mod_mainmenu');
	if(is_dir($fromPath.'/modules/mod_M17n'))$fsdirs->delete($fromPath.'/modules/mod_M17n');
	if(is_dir($fromPath.'/modules'))$fsdirs->delete($fromPath.'/modules');
	if(is_dir($fromPath.'/administrator/components/com_menus/views/list/tmpl'))$fsdirs->delete($fromPath.'/administrator/components/com_menus/views/list/tmpl');
	if(is_dir($fromPath.'/administrator/components/com_menus/views/list'))$fsdirs->delete($fromPath.'/administrator/components/com_menus/views/list');
	if(is_dir($fromPath.'/administrator/components/com_menus/views/item/tmpl'))$fsdirs->delete($fromPath.'/administrator/components/com_menus/views/item/tmpl');
	if(is_dir($fromPath.'/administrator/components/com_menus/views/item'))$fsdirs->delete($fromPath.'/administrator/components/com_menus/views/item');
	if(is_dir($fromPath.'/administrator/components/com_menus/views'))$fsdirs->delete($fromPath.'/administrator/components/com_menus/views');
	if(is_dir($fromPath.'/administrator/components/com_menus/models'))$fsdirs->delete($fromPath.'/administrator/components/com_menus/models');
	if(is_dir($fromPath.'/administrator/components/com_menus'))$fsdirs->delete($fromPath.'/administrator/components/com_menus');
	if(is_dir($fromPath.'/administrator/components'))$fsdirs->delete($fromPath.'/administrator/components');
	if(is_dir($fromPath.'/libraries/joomla/application'))$fsdirs->delete($fromPath.'/libraries/joomla/application');
	if(is_dir($fromPath.'/libraries/joomla'))$fsdirs->delete($fromPath.'/libraries/joomla');
	if(is_dir($fromPath.'/libraries'))$fsdirs->delete($fromPath.'/libraries');
	if(is_dir($fromPath.'/administrator'))$fsdirs->delete($fromPath.'/administrator');
	if(is_dir($fromPath.'/plugins/system'))$fsdirs->delete($fromPath.'/plugins/system');
	if(is_dir($fromPath.'/plugins'))$fsdirs->delete($fromPath.'/plugins');
//  hack multilang				
//	if(is_dir($fromPath.'/components/com_content/models/frontpage.php'))$fsdirs->delete($fromPath.'/components/com_content/models/frontpage.php');
//	if(is_dir($fromPath.'/components/com_content/models/category.php'))$fsdirs->delete($fromPath.'/components/com_content/models/category.php');
//	if(is_dir($fromPath.'/components/com_content/models/section.php'))$fsdirs->delete($fromPath.'/components/com_content/models/section.php');
//	if(is_dir($fromPath.'/plugins/search/content.php'))$fsdirs->delete($fromPath.'/plugins/search/content.php');
//	if(is_dir($fromPath.'/modules/mod_mostread/helper.php'))$fsdirs->delete($fromPath.'/modules/mod_mostread/helper.php');
//	if(is_dir($fromPath.'/modules/mod_latestnews/helper.php'))$fsdirs->delete($fromPath.'/modules/mod_latestnews/helper.php');
//	if(is_dir($fromPath.'/modules/mod_newsflash/helper.php'))$fsdirs->delete($fromPath.'/modules/mod_newsflash/helper.php');
// end hack multilang			
	if(file_exists($fromPath.'/M17n.xml'))$fslink->delete($fromPath.'/M17n.xml');
	if(file_exists($fromPath.'/install.M17n.php'))$fslink->delete($fromPath.'/install.M17n.php');
	if(file_exists($fromPath.'/uninstall.M17n.php'))$fslink->delete($fromPath.'/uninstall.M17n.php');
	
	
	if(is_dir($fromPath))$fsdirs->delete($fromPath);
}

?>
