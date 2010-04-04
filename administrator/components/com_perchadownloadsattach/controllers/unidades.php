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
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class perchadownloadsattachControllerUnidades extends  perchadownloadsattachController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		 
		  
	}
	/**
	 * display the edit form
	 * @return void
	 */
	function display()
	{
		
		JRequest::setVar( 'view', 'unidades' );
 
		parent::display();
	}

        /**
	 * display the edit form
	 * @return void
	 */
	function elements()
	{
  
                
		$model	= &$this->getModel( 'element' );
		$view	= &$this->getView( 'element');
		$view->setModel( $model, true );
		$view->display();

		//parent::display();
                
	}
         /**
	 * display the edit form
	 * @return void
	 */
	function elements_categorias()
	{
 
                //echo "ELEMENTS";

		$model	= &$this->getModel( 'elementcategories ' );
		$view	= &$this->getView( 'elementcategories ');
		$view->setModel( $model, true );
		$view->display(); 

		//parent::display();

	}


	
 

	 
}
