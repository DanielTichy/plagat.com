<?php
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Hello View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class perchadownloadsattachViewUnidad extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//global $mosConfig_absolute_path;
                $mosConfig_absolute_path = JPATH_BASE."/../";
		$user =& JFactory::getUser(); 

		$db		=& JFactory::getDBO();
		//get the Unidad
		$Unidad		=& $this->get('Data');
		$isNew		= ($Unidad->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'News' ).': <small><small>[ ' . $text.' ]</small></small>','wrms_toolbar_title' );


                 $bar =& new JToolBar( 'My Toolbar' );
               

		if(!$isNew) JToolBarHelper::save();
                JToolBarHelper::apply();

                if(!$isNew)  $bar->appendButton( 'Standard', 'save', 'Save', 'save', false );
                if(!$isNew)  $bar->appendButton( 'Separator' );
                $bar->appendButton( 'Standard', 'apply', 'Apply', 'apply', false );
                
                
                $bar->appendButton( 'Standard', 'cancel', 'Cancel', 'cancel', false );
                
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

                $this->assignRef('bar',	$bar);

                 /*Control de fame*/
                $asunto =   $_SERVER['PHP_SELF'];
                $patron = '/index3.php/';
                preg_match($patron, $asunto, $coincidencias, PREG_OFFSET_CAPTURE, 3);
                //echo "coincidencias:: ". count($coincidencias) ;
                $isframe = false;
                if( count($coincidencias)>0) $isframe = true;
                $this->assignRef('isframe',	$isframe);
                
                
 

               // $upload =  JHTML::_('behavior.uploader');
               // $upload =  JHTML::_('behavior.uploader', 'file-upload', array('onAllComplete' =>'function(){ ImageManager.refreshFrame(); }'));
                // JHTML::_('behavior.mootools');
                //JHTML::_('behavior.uploader', 'file-upload', array());

             //   echo "UPLOAD;:;;: ".$upload;
 
 //TABS

                $articleid=  JRequest::getVar( 'articleid' , -1);
                if($articleid > 0)
                    {
                        $Unidad->articleid = $articleid;
                        $Unidad->articleid_name = $this->getarticlename($articleid);
                    }

		//Image button 
		$link = 'index3.php?option=com_perchadownloadsattach&amp;controller=upload&amp;&amp;task=form';
		JHTML::_('behavior.modal', 'a.modal-button');
		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_( 'Image' ));
		$button->set('name', 'image');
		$button->set('modalname', 'modal-button');
		$button->set('options', "{handler: 'iframe', size: {x: 760, y: 520}}");
                $this->assignRef('folder',		$folder);

		$editor =& JFactory::getEditor(); 

		$this->assignRef('unidad',		$Unidad);
		$this->assignRef('button',		$button);		
		$this->assignRef('lists',		$lists); 	
		$this->assignRef('editor', $editor);		
		$this->assignRef('url_upload',		$url_upload);
		$this->assignRef('mosConfig_absolute_path',		$mosConfig_absolute_path);
                
                $this->assignRef('upload',		$upload);

                $this->assignRef('articleid',		$articleid);
                



		parent::display($tpl);
	}
        function getarticlename($articleid)
        {
            $db		=& JFactory::getDBO();            
            $query = ' SELECT * FROM #__content WHERE id='.$articleid;
            $db->setQuery( $query );
	    return  $db->loadObject()->title;

        }
}
