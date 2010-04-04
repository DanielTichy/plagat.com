<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?

$item = $list[0];

$catid 		= trim( $params->get( 'catid' ) );
$secid 		= trim( $params->get( 'secid' ) );

$show_introtext	= $params->get( 'show_introtext', 0 );

$show_date	= $params->get( 'show_date', 0 );
$show_date_type	= $params->get( 'show_date_type', 0 );

$thumb_embed 		= intval( $params->get( 'thumb_embed', 0 ) );
$thumb_width 		= intval( $params->get( 'thumb_width', 32 ) );
$thumb_height 		= intval( $params->get( 'thumb_height', 32 ) );
$limit 		= intval( $params->get( 'limit', 200 ) );
$loadorder 			= intval( $params->get( 'loadorder', 1 ) );

$show_more_in 			= intval( $params->get( 'show_more_in', 0 ) );
$show_more_type 			= intval( $params->get( 'show_more_type', 0 ) );
$show_date_in_introtext 			= intval( $params->get( 'show_date_in_introtext', 0 ) );
$allowed_tags 		=  "<i><b>"; 

$image_path = $params->get( 'image_path', 'images/stories' );

$document=& JFactory::getDocument();
if($document->getType() == 'html') {
	global $mainframe;
	$document->addCustomTag('<link href="'.$mainframe->getBasePath( 0, true ).'modules/mod_latestnewsplusdate/latestnewsplusdate/style.css" rel="stylesheet" type="text/css" />');
}

if($show_introtext) {
	if ($thumb_embed) {	
		/* Regex tool for finding image path on img tag - thx to Jerson Figueiredo */	
		preg_match_all("#<img(.*)>#", modLatestNewsHelperPlusDate::unhtmlentities(html_entity_decode(htmlentities($item->introtext))), $txtimg);
		if (!empty($txtimg[0])) 
		{
			foreach ($txtimg[0] as $txtimgel) 
			{
				$item->introtext = str_replace($txtimgel,"",$item->introtext);
				if ( strstr($txtimgel, $image_path) ) {
					if (strstr($txtimgel, 'src="/')) {
						preg_match_all("#src=\"\/" . addslashes($image_path) . "\/([\:\-\/\_A-Za-z0-9\.]+)\"#",$txtimgel,$txtimgelsr);
					}
					else {
						preg_match_all("#src=\"" . addslashes($image_path) . "\/([\:\-\/\_A-Za-z0-9\.]+)\"#",$txtimgel,$txtimgelsr);
					}
					
					if (!empty($item->images)) {
						$item->images = $txtimgelsr[1][0] . "\n" . $item->images;
					}
					else {
						$item->images = $txtimgelsr[1][0];
					}
				}elseif (preg_match_all("#http#",$txtimgel,$txtimelsr,PREG_PATTERN_ORDER) > 0) {
					preg_match_all("#src=\"([\-\/\_A-Za-z0-9\.\:]+)\"#",$txtimgel,$txtimgelsr);
					if (!empty($item->images)) {
						$item->images = $txtimgelsr[1][0] . "\n" . $item->images;
					}
					else {
						$item->images = $txtimgelsr[1][0];
					}
				}
			}
		}
	} // end of thumbnail processing
	$item->introtext= preg_replace("/{[^}]*}/","",$item->introtext);
	// stripped html by default
	$item->introtext = strip_tags(modLatestNewsHelperPlusDate::unhtmlentities($item->introtext),$allowed_tags);
  if($limit > 0) {
  	$item->introtext = modLatestNewsHelperPlusDate::lnd_limittext($item->introtext,$limit);
	}

	echo '<div class="div_lnd_intro">';
	if ($loadorder == 1) 
	{
		echo  modLatestNewsHelperPlusDate::lnd_showThumb($item->images,$item->image,$params,$item->itemid,$item->id);				
	}

	echo '<a class="lndtitle" href="'.$item->link.'" >'.$item->text.'</a>';
	echo '<br/>';
	if($show_date_in_introtext) {
		if($show_date==1) {
			switch($show_date_type) {
				case 1:
					echo "<span class=\"lnd_introdate\">";
					echo date("d F Y", strtotime($item->created));
					echo "<br/></span>";
					break;
				case 2:
					echo "<span class=\"lnd_introdate\">";
					echo date("H:i", strtotime($item->created));
					echo "<br/></span>";
					break;
				default:
					echo "<span class=\"lnd_introdate\">";
					echo date("d F Y H:i", strtotime($item->created));
					echo "<br/></span>";
					break;
			}
		}
	}
	if ($loadorder == 0) 
	{
		modLatestNewsHelperPlusDate::lnd_showThumb($item->images,$item->image,$params,$item->itemid,$item->id);				
	}
	echo html_entity_decode(htmlentities($item->introtext));
	echo '</div>';
	echo '<div style="clear:both"></div>';
}
	echo '<div class="div_lnd_list">';

	$show_date	= $params->get( 'show_date', 0 );
	$show_date_type	= $params->get( 'show_date_type', 0 );
?>

<ul class="lnd_latestnews">
<?php for ($n=$show_introtext; $n<count($list); $n++) {  ?>
	<?
	$item = $list[$n];
	?>
	<li class="lnd_latestnews">
		
		<?
		
		if($show_date==1) {
			switch($show_date_type) {
				case 1:
					echo date("d F Y", strtotime($item->created));
					break;
				case 2:
					echo date("H:i", strtotime($item->created));
					break;
				default:
					echo date("d F Y H:i", strtotime($item->created));
					break;
			}
		}
		
		?>
		
		<a href="<?php echo $item->link; ?>" class="latestnews">
			<?php echo $item->text; ?></a>
	</li>
<?php } ?>
</ul>
</div>
<div style="clear:both"></div>
<?
if($show_more_in) {
	$database			=& JFactory::getDBO();
	if($catid) {
		$catids = explode( ',', $catid );
		JArrayHelper::toInteger( $catids );
		$where = "AND ( id=" . implode( " OR id=", $catids ) . " )";
		if($where) {
			$query = "SELECT * FROM #__categories WHERE published=1 AND count>=1 AND (section>=1 OR section='com_content') AND access <= " . (int) $my->gid ." $where";
			$database->setQuery($query);
		}
		$rows = $database->loadObjectList();
			if(count($rows)) {
				echo '<div class="lnd_more_ind">';
				echo "<span class=\"morein\">More in: </span>";

				foreach($rows as $row) {
					$_Itemid = "";
					if ($_Itemid == "") {
						$database->setQuery( "SELECT sectionid, catid "
							."FROM #__content WHERE id='$row->id'" );
						$iid = null;
						$database->loadObject( $iid );
				
						if($show_more_type) {
							$database->setQuery("SELECT id "
								."FROM #__menu "
								."WHERE type='content_blog_category' AND published='1' AND link='index.php?option=com_content&task=blogcategory&id=$row->id'");
							$_Itemid = $database->loadResult();
						} else {
							$database->setQuery("SELECT id "
								."FROM #__menu "
								."WHERE type='content_category' AND published='1' AND link='index.php?option=com_content&task=category&sectionid=$row->section&id=$row->id'");
							$_Itemid = $database->loadResult();
						}

						$row->itemid = ($_Itemid)?"&amp;Itemid=$_Itemid":"Itemid=0";
						if($show_more_type) {
							$link = JRoute::_('index.php?view=category&layout=blog&id='.$row->id.':'.$row->title.$row->itemid);
							$more[] = '<a href="'.$link.'">'.$row->title.'</a>';
						} else {
							$link = JRoute::_('index.php?view=category&id='.$row->id.':'.$row->title.$row->itemid);
							$more[] = '<a href="'.$link.'">'.$row->title.'</a>';
						}
					}
				}

				echo implode( ', ', $more );
				echo '</div>';
			}
	} else {
		if($secid) {
			$secids = explode( ',', $secid );
			JArrayHelper::toInteger( $secids );
			$where = "AND ( section=" . implode( " OR section=", $secids ) . " )";
			if($where) {
				$query = "SELECT id, title, section FROM #__categories WHERE published=1 AND count>=1 AND section>=1 AND access <= " . (int) $my->gid ." $where";
				$database->setQuery($query);
			}
			$rows = $database->loadObjectList();

			if(count($rows)) {
				echo '<div class="lnd_more_ind">';
				echo "<span class=\"morein\">More in: </span> ";
				foreach($rows as $row) {
					$_Itemid = "";
					if ($_Itemid == "") {
						$database->setQuery( "SELECT sectionid, catid "
							."FROM #__content WHERE id='$row->id'" );
						$iid = null;
						$database->loadObject( $iid );
				
						if($show_more_type) {
							$database->setQuery("SELECT id "
								."FROM #__menu "
								."WHERE type='content_blog_category' AND published='1' AND link='index.php?option=com_content&task=blogcategory&id=$row->id'");
							$_Itemid = $database->loadResult();
						} else {
							$database->setQuery("SELECT id "
								."FROM #__menu "
								."WHERE type='content_category' AND published='1' AND link='index.php?option=com_content&task=category&sectionid=$row->section&id=$row->id'");
							$_Itemid = $database->loadResult();
						}
						global $Itemid;
						$row->itemid = ($_Itemid)?"&amp;Itemid=$_Itemid":"Itemid=0";
						if($show_more_type) {
							$link = JRoute::_('index.php?view=category&layout=blog&id='.$row->id.':'.$row->title.$row->itemid);
							$more[] = '<a href="'.$link.'">'.$row->title.'</a>';
						} else {
							$link = JRoute::_('index.php?view=category&id='.$row->id.':'.$row->title.$row->itemid);
							$more[] = '<a href="'.$link.'">'.$row->title.'</a>';
						}
					}
				}
				echo implode( ', ', $more );
				echo '</div>';
			}
		}
	}
}
?>