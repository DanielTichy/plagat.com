<?php


// Set flag that this is a parent file since this is an ajax file and not directly tied to index.pph
// yes, I know it's an awful lot of overhead. 

define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode( DS, dirname(__FILE__) );	

// pop up two levels to the real root
array_pop($parts);
array_pop($parts);
define( 'JPATH_BASE', implode( DS, $parts ) );

require_once ( JPATH_BASE . '/includes'.DS.'defines.php' );
require_once ( JPATH_BASE . '/includes'.DS.'framework.php' );



$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();


function GraphicMailAPIfunc()
{
  $SubscriberEmail = $_POST['SubscriberEmail'];

	require_once('mod_GraphicMail_helper.php');

  $db =& JFactory::getDBO();
      $query = "SELECT params FROM jos_modules WHERE module = 'mod_GraphicMail' ";
          $db->setQuery( $query );    
          
          if (null === ($raw_params = $db->loadResult())) 
          {
                          return " ";
          }

	  $params = new JParameter( $raw_params );
    
    
    $MailingListTitle = "";
    $MailingListID =  "";
    
    $MailingListTitle =   $params->get('GMSelectedMailingList');
    $APIUsername =   $params->get('GMAPIusername');
    $APIPassword=   $params->get('GMAPIpassword');
    
    if (strrpos($MailingListTitle,"-") > 0)
    {
        $response=explode("-",$MailingListTitle);
        $ArrayCount = count($response);
        if ($ArrayCount == 2)
		{
			$MailingListTitle = $response[0];
			$MailingListID = $response[1];
		}
    }
     SubscribeTo_MailingList($APIUsername,$APIPassword,$MailingListID,$SubscriberEmail,$APIResponse);
     return  $APIResponse;
}

// If being called via ajax, autorun the function with email address and module id
if($_POST['ajax'])
{ 
  echo GraphicMailAPIfunc(); 
}
?>
