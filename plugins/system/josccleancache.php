<?php 
/*
 * Copyright Copyright (C) 2008 Alain Georgette. All rights reserved.
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * !JoomlaComment is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * !JoomlaComment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA  02110-1301, USA.
 */
/*
 * This mambot will cache the group if joscclean parameter is active
 *
 */

defined( '_JEXEC') or die( 'Restricted access' );
 /*
 * This needs legacy bot  ordered before !
 * (until I take more time to a complete 1.5 code...)
 * check legacy bot has been passed.
 */
jimport( 'joomla.plugin.plugin' );

//class plgJoscCleanCache extends JPlugin {
//    function plgJoscCleanCache()
//{
//		global $option, $database, $_MAMBOTS, $mosConfig_caching;
//die();
//		if (!$mosConfig_caching)
//			/* cache not activated -> return */
//			return true;
//
//		if ($option!='com_content' &&  $option!='com_docman') {
//		    return true;
//		}
//
//
//		if (intval( mosGetParam( $_REQUEST, 'joscclean', 0 ) ))
//			mosCache::cleanCache( $option );
//
//		return true;
//}
//}
//if (defined('_VALID_MOS'))
//	$_MAMBOTS->registerFunction( 'onAfterStart', 'pluginJoscCleanCache');



?>
