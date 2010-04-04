<?php

/*
 * Roman Gelembjuk <roman@gelembjuk.com> 
 * 
*/

// Do the usual dance
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class plgContentSimilarArticles extends JPlugin {

 function plgContentSimilarArticles(&$subject, $params) { parent::__construct($subject, $params); }

 function onPrepareContent( &$article, &$params ) {
  
  if(JRequest :: getCmd('option') != 'com_content') return;
  
  $view=JRequest :: getCmd('view'); $layout=JRequest :: getCmd('layout');
  if(! ($view=='article')) return;
  
  // Be sure that this section/category/article is not one that the user wanted to exclude.
  if ($this->param('Enabled_Front_Page') == 0 and $view=='frontpage') return;
  if (in_array($article->sectionid, explode(',', $this->param('Exclude_Section_Ids')))) return;
  if (in_array($article->catid, explode(',', $this->param('Exclude_Category_Ids')))) return;
  if (in_array($article->id, explode(',', $this->param('Exclude_Article_Ids')))) return;
  
  $maxcount=$this->param('Similar_Articles_max_count');
  if(intval($maxcount)<1) $maxcount=5;
  
  $similararticles=array();
  $allarticles=array();
  $snums=array();
  //$item->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid));
  
  //get list of all articles
  $list=$this->getAllArticles($article->id);
  
  //find similar articles
  foreach($list as $art){
	  $s=similar_text($article->title,$art->title);
	  $link=JRoute::_(ContentHelperRoute::getArticleRoute($art->slug, $art->catslug, $art->sectionid));
	  $item=array('subject'=>$art->title,'href'=>$link);
	  if(in_array($s,$snums)){
		  $allarticles[$s][]=$item;
	  }else{
		  $snums[]=$s;
		  $allarticles[$s]=array($item);
	  }
  }
  
  $c=0;
  rsort($snums);
  //print_r($snums);
  foreach($snums as $level){
	  foreach($allarticles[$level] as $link){
		  $similararticles[]=$link;
		  $c++;
		  if($c>=$maxcount) break;
	  }
	  if($c>=$maxcount) break;
  }
  
  if(count($similararticles)>0){
   $article->text .= '<br><br><div class="'.$this->param('Similar_Articles_class').'"><h3>'.$this->param('The_list_title').'</h3><br><ul>';
   
   foreach($similararticles as $link){
	   $article->text .= '<li><a href="'.$link['href'].'">'.$link['subject'].'</a></li>';
   }
   
   $article->text .= '</ul></div>';
  }
 
  }

 function param($name) {
  static $plugin, $pluginParams;
  if (!isset($plugin)) {
   $plugin =& JPluginHelper::getPlugin('content', 'SimilarArticles');
   $pluginParams = new JParameter( $plugin->params );
   }
  return $pluginParams->get($name);
  }
  
  function getAllArticles($thisid){
	  $db			=& JFactory::getDBO();
	  
	  $nullDate	= $db->getNullDate();

	  $date =& JFactory::getDate();
	  $now = $date->toMySQL();

	  $where		= 'a.state = 1'
	  . ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
	  . ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
	  . ' AND ( a.id<> '.$thisid.' )'
	  ;

	  // Content Items only
	  $query = 'SELECT a.*, ' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
			' FROM #__content AS a' .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
			' WHERE '. $where .' AND s.id > 0' .
			' AND s.published = 1' .
			' AND cc.published = 1';
			
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	
	return $rows;
  }

 }
