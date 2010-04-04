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

jimport('joomla.application.component.model');

/**
 * Hello Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class perchadownloadsattachModelUnidad extends JModel
{
        
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);

               // echo "DESDE MODEL: ".$array[0];
	}

	/**
	 * Method to set the hello identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}
        /**
	 * Method to get a last Id
	 * @return object with data
	 */
	function &getId()
	{
            $query = ' SELECT id FROM #__perchadownloadsattach ORDER BY  id DESC ';
	    echo $query;
            $this->_db->setQuery( $query );
	    $this->_data = $this->_db->loadObjectList();
            
            return $this->_data[0]->id;
        }
	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function &getData()
	{
		
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__perchadownloadsattach '.
					'  WHERE id = '.$this->_id;
			//echo $query;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
			//echo "<br>EE:: ".$this->_data->nombre;
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->title = null;  
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{	 
		
		$row =& $this->getTable();

               
                

		$data = JRequest::get( 'post', JREQUEST_ALLOWHTML );

                   //FECHAS
                if(empty( $data['createdate'])){
                                $data['createdate']= date("y-m-d");
                                //echo "<br>createdate:: ".$POST['createdate'];

                              }
                if(empty( $data['publishdate'])){
                                $data['publishdate']= date("y-m-d");

                              }
 

               
                /*if(empty($data->createdate)){
                                $data->createdate = date("y-m-d");
                                echo "FECHA creada : ".$row->createdate;
                              }
                
                if(empty($data->publishdate)){
                                $data->publishdate=date("y-m-d");
                              }
                 * *//*
                  * else 
                              {
                                  echo "FECHA:: ".$row->publishdate;
                                  $fecha = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));

                                  //$row->publishdate= date("y-m-d", $row->publishdate);
                              }

		  echo "FECHA:: ".$row->publishdate;*/

		//Parametros 
		$config =& JComponentHelper::getParams('com_perchadownloadsattach');  


		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

              


		

		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

        function move($direction,$articleid) {

              $db = JFactory::getDBO();
              global $mainframe;

              $row =& $this->getTable('unidad');

             
               $cid = JRequest::setVar( 'cid' ); 
                $this->id =  $cid[0];
              //  echo 'El id:'.$this->id;
            //JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_recipes'.DS.'tables');

              if (!$row->load($this->id)) {
                 //  echo '<br>ERROR El id:'.$this->id;
                 $this->setError($db->getErrorMsg());
                 return false;
              }

              if (!$row->move( $direction, '    articleid= '.$articleid )) {
                 $this->setError($db->getErrorMsg());
                 return false;
              }

              $this->reorderAll($articleid);

              return true;
           }
         
        function reorderAll($articleid)
        {
             $query = ' SELECT #__perchadownloadsattach.id  FROM #__perchadownloadsattach  '.
                        ' WHERE   #__perchadownloadsattach.articleid='.$articleid.
                        ' ORDER BY #__perchadownloadsattach.ordering ' ;
             
	    $this->_db->setQuery( $query );

            $unidades =   $this->_db->loadObjectList();

            for($contador = 0; count($unidades) > $contador; $contador++)
                {
                     $unidad = $unidades[$contador];
                     $query = 'UPDATE #__perchadownloadsattach SET  ordering   '.
					'   =  '.($contador+1).' '.
                                        ' WHERE id= '.$unidad->id;

                            $this->_db->setQuery( $query );
                            if(!$this->_db->query()) { echo $query." --> ERROR INSERT3 SQL<br>"; };
                }
            return true;
        }

	/**
	 * Method to move a weblink
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function saveorder($cid = array(), $order)
	{
		/*$row =& $this->getTable(); 
		$groupings = array();
		 
		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			 
			// track categories
			$groupings[] = $row->catid;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					
					return false;
				}
			}
		}
		 
		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('catid = '.(int) $group);
		}
*/
		return true;
	}


         

         

}
