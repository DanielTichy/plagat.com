<?php 

// no direct access
defined('_JEXEC') or die('Restricted access');


if ( ! defined('modMainMenuXMLCallbackDefined') )
{
function modMainMenuXMLCallback(&$node, $args)
{
	$user	= &JFactory::getUser();
	$menu	= &JSite::getMenu();
	$active	= $menu->getActive();
	$path	= isset($active) ? array_reverse($active->tree) : null;
	
	### Hacked by SDIC: M17n
	### Childs activation detection
	$nodeKids=$node->attributes('kids');
	$childList=Array();
	
	
	if($nodeKids!=""){
		$clist=Array();
		// Split Entries if comas found
		if(strPos($nodeKids,',')!==false){
			$clist=split(',',$nodeKids);
		}else{
			if(isMenuType($nodeKids)){
				$clist=getMenuByType($nodeKids);
			}else{
				$clist[]=$nodeKids;
			}
		}
		// Then seeks for ranged ids separated by dashes (X-Z = X,Y,Z)
		foreach($clist as $kid){
			if(strPos($kid,'-')!==false){
				$range=split('-',$kid);
				natsort($range);
				$start=$range[0];
				$end=$range[count($range)-1];
				for($k=$start;$k<=$end;$k++){
					$childList[]=$k;
				}
			}else{
				$childList[]=$kid;
			}
		}
		$node->removeAttribute('kids');
		$node->removeAttribute('users');
		$node->removeAttribute('lang');
	}
	
	if (($args['end']) && ($node->attributes('level') >= $args['end']))
	{
		$children = &$node->children();
		foreach ($node->children() as $child)
		{
			if ($child->name() == 'ul') {
				$node->removeChild($child);
			}
		}
	}

	if ($node->name() == 'ul') {
		foreach ($node->children() as $child)
		{
			if ($child->attributes('access') > $user->get('aid', 0)) {
				$node->removeChild($child);
			}
		}
	}

	if (($node->name() == 'li') && isset($node->ul)) {
		$node->addAttribute('class', 'parent');
	}
	
	if (isset($path) && in_array($node->attributes('id'), $path))
	{
		if ($node->attributes('class')) {
			$node->addAttribute('class', $node->attributes('class').' active');
		} else {
			$node->addAttribute('class', 'active');
		}
	}
	else
	{
		if (isset($args['children']) && !$args['children'])
		{
			$children = $node->children();
			foreach ($node->children() as $child)
			{
				if ($child->name() == 'ul') {
					$node->removeChild($child);
				}
			}
		}
	}

	if (($node->name() == 'li') && ($id = $node->attributes('id'))) {
		if ($node->attributes('class')) {
			$node->addAttribute('class', $node->attributes('class').' item'.$id);
		} else {
			$node->addAttribute('class', 'item'.$id);
		}

		$node->removeAttribute('kids');
		$node->removeAttribute('users');
		$node->removeAttribute('lang');
	}

	if (isset($path) && ($node->attributes('id') == $path[0] || in_array($path[0], $childList))) {
		$node->addAttribute('id', 'current');
	} else {
		$node->removeAttribute('id');
	}
	$node->removeAttribute('level');
	$node->removeAttribute('access');

	$node->removeAttribute('kids');
	$node->removeAttribute('users');
	$node->removeAttribute('lang');
}
	define('modMainMenuXMLCallbackDefined', true);
}

modMainMenuHelper::render($params, 'modMainMenuXMLCallback');

?>