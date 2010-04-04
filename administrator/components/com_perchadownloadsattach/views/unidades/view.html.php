<?php 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.toolbar' );


/**
 * Hellos View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class perchadownloadsattachViewUnidades extends JView
{
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		
		global $mainframe, $option;

		$db =& JFactory::getDBO();

		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::Preferences( 'com_perchadownloadsattach' );

                $bar =& new JToolBar( 'My Toolbar' );
                $bar->appendButton( 'Standard', 'new', 'New', 'add', false );
                $bar->appendButton( 'Separator' );
                $bar->appendButton( 'Standard', 'edit', 'Edit', 'edit', false );
                $bar->appendButton( 'Separator' );
                $bar->appendButton( 'Standard', 'delete', 'Delete', 'remove', false );
                $this->assignRef('bar',	$bar);

                
               // $this->bar->render();
                /*Control de fame*/
                $asunto =   $_SERVER['PHP_SELF'];  
                $patron = '/index3.php/';
                preg_match($patron, $asunto, $coincidencias, PREG_OFFSET_CAPTURE, 3);
                //echo "coincidencias:: ". count($coincidencias) ;
                $isframe = false;
                if( count($coincidencias)>0) $isframe = true;
                $this->assignRef('isframe',	$isframe);
                  


		$document = & JFactory::getDocument();
                //$img =   $mainframe->getSiteURL().'administrator'.DS.'components'.DS.'com_perchadownloadsattach'.DS.'images'.DS.'logo.gif' ;
                //echo $img;
		JToolBarHelper::title(   JText::_( 'File attached Manager' ), 'wrms_toolbar_title'  );
                //JToolBarHelper::custom( JText::_( 'Units Manager' ), 'camera.png', 'camera.png', 'Manage Photos', true, true );
 
		//Parametros  ********************************************************************************************
		$config =& JComponentHelper::getParams('com_perchadownloadsattach'); 
		// echo $url_upload;

                // Filter ********************************************************************************************
		$filter_catid= 0; 

		$javascript		= 'onchange=" actualizar_lista();"';
                $articleslist_id=  JRequest::getVar( 'articleid' , -1);
		
                $query = 'SELECT * FROM #__content order by ordering, title ';
		$db->setQuery($query);

		$articleslist[] = JHTML::_('select.option',  '-1', JText::_( '- Choose article -' ), 'id', 'title' );
                $articleslist = array_merge( $articleslist, $db->loadObjectList() );


		$lists_familylist  = JHTML::_('select.genericlist', $articleslist, 'articleid',  $javascript, 'id', 'title' ,  $articleslist_id   );


		$lists['html_select'] = $lists_familylist;
 
		 

		// Get data from the model ****************************************************************************		 
		$items		= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );

                
		
		// Order ********************************************************************************************
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.filter_order',	'filter_order',	'a.ordering', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
 
		 
		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('items',		$items); 
		$this->assignRef('lists',		$lists);
		$this->assignRef('pagination',	$pagination);
                $this->assignRef('articleid', $articleslist_id);
 

		parent::display($tpl);
	}
}
