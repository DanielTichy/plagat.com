<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Example system plugin
 */
class plgContentperchadownloadsattachement extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatibility we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgContentperchadownloadsattachement( &$subject, $params )
	{
		parent::__construct( $subject, $params );
                $this->plugin = &JPluginHelper::getPlugin('content', 'perchadownloadsattachement');
		$this->params = new JParameter($this->plugin->params); 

		// Do some extra initialisation in this constructor if required
	}

	/**
	 * Do something onAfterInitialise 
	 */
	function onPrepareContent  ( &$article, &$params, $page=0)
	{
                 global $mainframe;

                 $document = & JFactory::getDocument();
                $document->addStyleSheet( JURI::base() . 'plugins/content/perchadownloadsattachement.css', 'text/css', null, array() );

                 //Parametros  ********************************************************************************************
		//$params =$this->params;
                 $str = "";

                $db	= & JFactory::getDBO();
		// Perform some action 
                if($article->id>0)
                    {
                        $query = 'SELECT * FROM #__perchadownloadsattach WHERE articleid='. $article->id .' AND published=1 ORDER BY ordering';
                        $db->setQuery( $query );
                        $images = $db->loadObjectList();

                    }
                 $myabsoluteurl=JURI::base()  ;
                 $class = $this->params->get("float") ;
                 $listclass = $this->params->get("listclass") ;
                 //if(!empty($class) ){ $class = 'class="'.$class.'"';}
                 //if(!empty($listclass) ){ $listclass = 'class="'.$listclass.'"';}
                 if(count($images)>0){
                 $str.='<div class="perchadownloadsattachementbox '.$class.'" ><div class="perchadownloadsattachementdiv" ><ul class="perchadownloadsattachement '.$listclass.'" >';
                 foreach($images as $image)
                     {
                        $file = 'images/downloads/'.$image->file ;
                         $file_ext = substr($image->file, strrpos($image->file, ".") + 1);
                        if(file_exists(JPATH_BASE.DS.$file)){ 
                            $file = $myabsoluteurl.DS.$file;
                            $str.= '<li><a href="'.$file.'" class="'. $file_ext.'">'.$image->title.'</a></li>';
                        }
                        

                        //$str .= '<img src="'.$myabsoluteurl.'unidad_" alt ="'.$image->title.'" /><br>';

                     }
                 $str.='</ul></div></div>';
                 }
                 if($this->params->get("position") == "above")  $article->text  =$str. $article->text ;
                 else $article->text  .=  $str ;
	}
        
}
