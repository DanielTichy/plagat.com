<?php
 

jimport('joomla.application.component.controller');

/**
 * Profesor   Component Controller
 *
 * @package		Profesores
 */
class perchadownloadsattachController extends JController
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
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
               /* $elview = JRequest::getVar( 'view'  );

                if( $elview == "edit"){ JRequest::setVar( 'view', 'editunidad' ); }*/

		parent::display();
	}

        
	function Listunidades()
	{
		 
		JRequest::setVar( 'view', 'Listunidades' ); 
		JRequest::setVar( 'id', '0' );
		 
		//echo $this->id;
		parent::display();
	}

	  

}
?>
