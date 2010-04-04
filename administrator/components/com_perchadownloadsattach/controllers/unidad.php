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
class perchadownloadsattachControllerUnidad extends  perchadownloadsattachController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply',    'save');

		
		/*$this->registerTask( 'saveorder'  , 'saveorder' );
		$this->registerTask( 'orderdown'  , 'orderdown' );
		$this->registerTask( 'orderup'  , 'orderup' );*/
 
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		
		JRequest::setVar( 'view', 'unidad' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
 

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$task = JRequest::setVar( 'task', 'save' );
		//$id = JRequest::setVar( 'id', '0' );

		$model = $this->getModel('unidad');
		$post   = JRequest::get('post');

                $id  = $post["id"];
                $articleid  = $post["articleid"];
                $file2 = $post["file"];

                 /*Control de fame*/ 
                 $file = $this->getfileframe();
                
		/*echo  "<br>IDSS:: ".$post["id"];
		echo "<br>IDSS2:: ".$_POST["id"];
		echo "<br>controller:: ".$_POST["controller"]; */
		if ($model->store($post)) {
		switch ($this->_task) {
			case 'apply':
			$msg = JText::_( 'Changes to Data Updated! (by Apply)' );
                         if($id == 0) $id = $model->getId();
			$link = $file.'?option=com_perchadownloadsattach&controller=unidad&task=edit&cid[]='.$id.'&articleid='.$articleid ;
                        

			break;
		
			case 'save':
			default:
			$msg = JText::_( 'Data Saved!' );
			$link = $file.'?option=com_perchadownloadsattach&section=unidades&articleid='.$articleid;
			break;
		}
		} else {
		$msg = JText::_( 'Error Saving Data' );
		$link = $file.'?option=com_perchadownloadsattach';
		}

                //UPLOAD IMAGE
               if($id>0) {
                        $this->uploadunit($id);
                        //------------------------------------------------------------
                        //
                        $delete  = $post["delete"];
                        if($delete == 1){$this->delete($file2);}
                }

                $this->setRedirect($link, $msg);


		

		
		/*$model = $this->getModel('unidad');

		echo $model->title;

		if ($model->store($post)) {
			$msg = JText::_( 'Greeting Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Greeting' );
		}
		
		// Check the table in so it can be edited.... we are done with it anyway
		//$link = 'index.php?option=com_perchadownloadsattach';
		if($task == "save"){
				$link = 'index.php?option=com_perchadownloadsattach&section=unidades';
			}else{ 
				$link = 'index.php?option=com_perchadownloadsattach&controller=unidad&task=edit&cid[]='.$id;
			}
		
		//echo $link;
		$this->setRedirect($link, $msg);*/
	}

         

        function orderup()
	{
                $cid = JRequest::getVar( 'cid' );
                $articleid= JRequest::getVar( 'articleid' );
                //$articleid = $_POST["articleid"];
                //echo "ID: ".$cid[0]."<br>";
               // $this->id =  $cid[0];
                // Check for request forgeries
		//echo "orderup";
		$model = $this->getModel('unidad');
                $model->id =  $cid[0];
		$model->move(-1, $articleid);

                 /*Control de fame*/
                $file = $this->getfileframe(); 

		 $this->setRedirect( $file.'?option=com_perchadownloadsattach&section=unidades&articleid='.$articleid);
	}

	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
                $articleid= JRequest::getVar( 'articleid' );

		$model = $this->getModel('unidad');
		$model->move(1, $articleid);

                /*Control de fame*/
                $file = $this->getfileframe();

		$this->setRedirect( $file.'?option=com_perchadownloadsattach&section=unidades&articleid='.$articleid);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('unidad');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Greeting(s) Deleted' );
		}

                 /*Control de fame*/
                $file = $this->getfileframe();

		$this->setRedirect( $file.'?option=com_perchadownloadsattach&section=unidades', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
                 /*Control de fame*/
                $file = $this->getfileframe();
                
		$this->setRedirect( $file.'?option=com_perchadownloadsattach&section=unidades', $msg );
	}



        function unitimage()
        {
		JRequest::setVar( 'view', 'unitimage' );



		parent::display();
        }

        function uploadunit($unidad_id)
        {
            global $mainframe;

            jimport('joomla.client.helper');
	    JClientHelper::setCredentialsFromRequest('ftp');

            //$unidad_id = JRequest::setVar( 'unidad_id', -1 );
            $newwidth = JRequest::setVar( 'width'  );

           $mosConfig_absolute_path = JPATH_BASE."/../";
             // $mosConfig_absolute_path ='/var/www/vhosts/grancanariamodacalida.es/httpdocs/';
            $url_upload = 'images/downloads';

            echo $mosConfig_absolute_path;
            echo "<br>URL:: ".$url_upload;

            //Get the file information
              $userfile_name = $_FILES["image"]["name"];
              $userfile_tmp = $_FILES["image"]["tmp_name"];
              $userfile_size = $_FILES["image"]["size"];
              $filename = basename($_FILES["image"]["name"]);
              $file_ext = substr($filename, strrpos($filename, ".") + 1);

            //  echo "<br>filename:: ".$filename;
             // echo "<br>file_ext:: ".$file_ext;
              //echo "uploadunitimage";
              //Only process if the file is a JPG and below the allowed limit
               if((!empty($_FILES["image"])) && ($_FILES["image"]["error"] == 0)) {
                  /*if (($file_ext!="jpg" && $file_ext!="png" && $file_ext!="gif") && ($userfile_size > $max_file)) {
                        $error= "ONLY jpeg images under 1MB are accepted for upload";
                    }*/
                }else{
                        $error= "Select a file for upload";

                }
               // echo "<br>Upload OK1 " ;
               //Everything is ok, so we can upload the image.
                    if (strlen($error)==0){
                        $this->delete($unidad_id);
                        echo "<br>Upload OK2 " ;
                         if (isset($_FILES["image"]["name"])){
                            $file 		= JRequest::getVar( 'image', '', 'files', 'array' );


                            // Make the filename safe
                            jimport('joomla.filesystem.file');
                            $file['name']	= JFile::makeSafe($file['name']);

                            $filepath = $mosConfig_absolute_path . $url_upload."/".$file['name'];
                            //$filepath = '/var/www/vhosts/grancanariamodacalida.es/httpdocs/ll.jpg';
                            
                            if (!JFile::upload($file['tmp_name'], $filepath)) {
                                 jimport('joomla.error.log');
                                 JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
                             }else{
                                 $error = "Upload OK2 ";

                             }
                            
                            //---------------------------------------------------------------------------------------
                         }
                     } 

        }

        function delete($file)
        {
            global $mainframe;

            //$unidad_id = JRequest::setVar( 'unidad_id', -1 );
            $newwidth = JRequest::setVar( 'width'  );

            $mosConfig_absolute_path = JPATH_BASE."/../";
            $url_upload = 'images/downloads';

            $filepath = $mosConfig_absolute_path . $url_upload."/".$file;unlink  ($filepath);
            /*$filepath = $mosConfig_absolute_path . $url_upload."/unidad_".$unidad_id.'.gif';unlink  ($filepath);
            $filepath = $mosConfig_absolute_path . $url_upload."/unidad_".$unidad_id.'.png';unlink  ($filepath);*/

            unlink  ($filepath);

            $msg = JText::_( 'Delete OK' );

            //$mainframe->redirect('index3.php?option=com_perchadownloadsattach&controller=unidad&task=unitimage&unidad_id='.$unidad_id.'&msg='.$msg);

        }

        function getfileframe(){
                /*Control de fame*/
                $asunto =   $_SERVER['PHP_SELF'];
                $patron = '/index3.php/';
                preg_match($patron, $asunto, $coincidencias, PREG_OFFSET_CAPTURE, 3);
                //echo "coincidencias:: ". count($coincidencias) ;
                $file = 'index.php';
                if(count($coincidencias)>0) {$file = 'index3.php';}
                return $file;
        }


	
}
