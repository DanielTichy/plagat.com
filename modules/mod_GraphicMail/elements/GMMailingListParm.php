<?php

defined('JPATH_BASE') or die();


class JElementGMMailingListParm extends JElement
{

	var	$_name = 'GMMailingListParm';
  
	function fetchElement($name, $value, &$node, $control_name)
	{
  
    $db =& JFactory::getDBO();
      $query = "SELECT params FROM jos_modules WHERE module = 'mod_GraphicMail' ";
          $db->setQuery( $query );    
          
          if (null === ($raw_params = $db->loadResult())) 
          {
                          print "No record" ;
                          return;
          }

	  $params = new JParameter( $raw_params );
	  $login_name = $params->get('GMAPIusername');
	  $password = $params->get('GMAPIpassword');
      
      
 		  $filepath = JURI::base();
		  $filepath = str_replace("/administrator","",$filepath) . "/modules/mod_graphicmail";
  	
		  require_once("mod_GraphicMail_helper.php");
		  $class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );
      $options = array ();
    if(!$login_name or !$password)
    {
        print "<TABLE cellspacing=2 cellpading=3><TR><TD>Please provide the GraphicMail API Username and Password, then press the Apply button to select " .
        "the mailing list to subscribe to. After this, press Apply again to save your settings.</TD</TR></TABLE>"; 

		    $options[] = JHTML::_('select.option', "0", JText::_("Select mailing list"));
	  }
    else
    {
      	get_MailingList($login_name,$password,$MailingListID,$MailingListTitle,$MailingListHFTitle,$ErrorFound);
   
        if ($ErrorFound)
        {
            print  $MailingListTitle ; 
            $options[] = JHTML::_('select.option', "0", JText::_("Select mailing list"));
        }
        else
        {
        print "<TABLE cellspacing=2 cellpading=3><TR><TD>Please provide the GraphicMail API Username and Password, then press the Apply button to select " .
        "the mailing list to subscribe to. After this, press Apply again to save your settings.</TD</TR></TABLE>"; 

          $response=explode(",",$MailingListTitle);
          $ArrayCount = count($response);
          
          if ($ArrayCount > 0)
          {
            for ($i = 0; $i < $ArrayCount; $i++)
					  {
              $OptionArray=explode("-",$response[$i]);
              if (count($OptionArray) == 2)
              {
                $options[] = JHTML::_('select.option', JText::_($OptionArray[0]) . "-". $OptionArray[1], JText::_($OptionArray[0]));
              }
            }
          }
          else
          {
            print  $MailingListTitle ; 
            $options[] = JHTML::_('select.option', "0", JText::_("Select mailing list"));
          }
        }
    } 
    return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $class, 'value', 'text', $value, $control_name.$name);	
	}
}
?>
