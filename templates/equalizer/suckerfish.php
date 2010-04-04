<?php
defined( '_JEXEC' ) or die( 'Restricted moo access' );
function mosRecurseListMenu( $id, $level, &$children, $open, &$indents, $class_sfx, $highlight ) {
	global $Itemid;
	global $HTTP_SERVER_VARS;
	if (@$children[$id]) {
		$n = min( $level, count( $indents )-1 );
			if ($level==0) echo '<ul class="nav" id="sonja">';
			else
        echo $indents[$n][0];
		foreach ($children[$id] as $row) {
		        switch ($row->type) {
          		case 'separator':
          		$row->link = "seperator";
          		break;
          		case 'url':
          		if ( eregi( 'index.php\?', $row->link ) ) {
        				if ( !eregi( 'Itemid=', $row->link ) ) {
        					$row->link .= '&Itemid='. $row->id;
        				}
        			}
          		break;
          		default:
          			$row->link .= "&Itemid=$row->id";
          		break;
          	}
            $li =  "\n".$indents[$n][1] ;
            $current_itemid = trim( JRequest::getVar( 'Itemid', 0 ) );
            if ($row->link != "seperator" &&
								$current_itemid == $row->id || 
            		$row->id == $highlight || 
                (JRoute::_( substr($_SERVER['PHP_SELF'],0,-9) . $row->link)) == $_SERVER['REQUEST_URI'] ||
                (JRoute::_( substr($_SERVER['PHP_SELF'],0,-9) . $row->link)) == $HTTP_SERVER_VARS['REQUEST_URI']) {
							$li = "<li class=\"active\">";
						}
	          echo $li;
								
            echo mosGetLink( $row, $level, $class_sfx );
						mosRecurseListMenu( $row->id, $level+1, $children, $open, $indents, $class_sfx, "" );
            echo $indents[$n][2];

        }
		echo "\n".$indents[$n][3];

	}
}

function getTheParentRow($rows, $id) {
		if (isset($rows[$id]) && $rows[$id]) {
			if($rows[$id]->parent > 0) {
				return $rows[$id]->parent;
			}	
		}
		return -1;
	}
function mosGetLink( $mitem, $level, $class_sfx='' ) {
	global $Itemid;
	$txt = '';
	$menuclass = '';
	$topdaddy = 'top';
	JRoute::_('$mitem->link');
	if (strcasecmp(substr($mitem->link,0,4),"http")) {
		$mitem->link = JRoute::_($mitem->link);
	}
    switch ($mitem->browserNav) {
		case 1:
    if ($mitem->cnt > 0) {
		   if ($level == 0) {
                $txt = "<a class=\"topdaddy\" target=\"_window\"  href=\"$mitem->link\">$mitem->name</a>";
								$topdaddy = "topdaddy";
		   } else {
                $txt = "<a class=\"daddy\" target=\"_window\"  href=\"$mitem->link\">$mitem->name</a>";
		   }
		} else {
		   	$txt = "<a href=\"$mitem->link\" target=\"_window\" >$mitem->name</a>\n";
		}
		break;

		case 2:
    if ($mitem->cnt > 0) {
				if ($level == 0) {
                $txt = "<a href=\"#\" class=\"topdaddy\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
		   					$topdaddy = "topdaddy";
		} else {
                $txt = "<a href=\"#\" class=\"daddy\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
		   }
		} else {
		    $txt = "<a href=\"#\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
		}

		break;

		case 3:
    if ($mitem->cnt > 0) {
		   if ($level == 0) {
                $txt = "<a class=\"topdaddy\">$mitem->name</a>";
								$topdaddy = "topdaddy";
		   } else {
                $txt = "<a class=\"daddy\">$mitem->name</a>";
		   }
		} else {
		   	$txt = "<a>$mitem->name</a>\n";
		}
		break;
		default:
		if (isset($mitem->cnt) && $mitem->cnt > 0) {
		    if ($level == 0) {
                $txt = "<a class=\"topdaddy\" href=\"$mitem->link\">$mitem->name</a>";
								$topdaddy = "topdaddy";
		   } else {
                $txt = "<a class=\"daddy\" href=\"$mitem->link\"> $mitem->name</a>";
		   }
		} else {
		   $txt = "<a href=\"$mitem->link\">$mitem->name</a>";
		}
        break;
	}
    return "<span class=\"" . $topdaddy . "\">" . $txt . "</span>";
}
ini_set('arg_separator.output','&amp;');
function mosShowListMenu($menutype) {
	global $mainframe, $Itemid;
	$database = JFactory::getDBO();    
    $user = JFactory::getUser();
	$class_sfx = null;
	error_reporting(E_ALL);
	$hilightid = null;
    if ($mainframe->getCfg('shownoauth')) {
     $sql = ("SELECT m.*, count(p.parent) as cnt" .
	"\nFROM #__menu AS m" .
	"\nLEFT JOIN #__menu AS p ON p.parent = m.id" .
    "\nWHERE m.menutype='$menutype' AND m.published='1'" .
	"\nGROUP BY m.id ORDER BY m.parent, m.ordering ");
      } else {
     $sql = ("SELECT m.*, sum(case when p.published=1 then 1 else 0 end) as cnt" .
	"\nFROM #__menu AS m" .
	"\nLEFT JOIN #__menu AS p ON p.parent = m.id" .
    "\nWHERE m.menutype='$menutype' AND m.published='1' AND m.access <= " . $user->get('gid') .      // Picks up the access-id
	"\nGROUP BY m.id ORDER BY m.parent, m.ordering ");
   }
     $database->setQuery($sql);
	$rows = $database->loadObjectList( 'id' );
	echo $database->getErrorMsg();
		$sql = "SELECT m.* FROM #__menu AS m"
		. "\nWHERE menutype='". $menutype ."' AND m.published='1'"; 
		$database->setQuery( $sql );
		$subrows = $database->loadObjectList( 'id' );
		$maxrecurse = 5;
		$parentid = $Itemid;
		while ($maxrecurse-- > 0) {
			$parentid = getTheParentRow($subrows, $parentid);
			if (isset($parentid) && $parentid >= 0 && $subrows[$parentid]) {
				$hilightid = $parentid;
			} else {
				break;	
			}
		}	
	$indents = array(
	array( "<ul>", "<li>" , "</li>", "</ul>" ),
	);
	$children = array();
    foreach ($rows as $v ) {
		$pt = $v->parent;
		$list = @$children[$pt] ? $children[$pt] : array();
		array_push( $list, $v );

        $children[$pt] = $list;
    }
	$open = array( $Itemid );
	$count = 20; // maximum levels - to prevent runaway loop
	$id = $Itemid;
	while (--$count) {
		if (isset($rows[$id]) && $rows[$id]->parent > 0) {
			$id = $rows[$id]->parent;
			$open[] = $id;
		} else {
			break;
		}
	}

	$class_sfx = null;

    mosRecurseListMenu( 0, 0, $children, $open, $indents, $class_sfx, $hilightid );
}
?>



