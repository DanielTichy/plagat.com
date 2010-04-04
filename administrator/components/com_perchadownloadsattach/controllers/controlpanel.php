<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Percha Component
 * @copyright Copyright (C) Cristian Grañó Reder www.percha.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * perchadownloadsattachControllerControlpanel
 *
 * @package    perchadownloadsattach
 * @subpackage Components
 */
class perchadownloadsattachControllerControlpanel extends  perchadownloadsattachController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
                
		  
	}  
	function display()
	{

		JRequest::setVar( 'view', 'controlpanel' );
		parent::display();
	}
}
