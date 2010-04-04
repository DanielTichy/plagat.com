<?php
function get_AdminParameters(&$GMFrontEndBlurb,&$GMEmptyMailingListBlurb)
{


  $db =& JFactory::getDBO();
      $query = "SELECT params FROM jos_modules WHERE module = 'mod_GraphicMail' ";
          $db->setQuery( $query );    
          
          if (null === ($raw_params = $db->loadResult())) 
          {
                          return " ";
          }

	  $params = new JParameter( $raw_params );
    $GMSelectedMailingList =   $params->get('GMSelectedMailingList');
    $GMFrontEndBlurb = $params->get('GMFrontEndBlurb');
    $GMEmptyMailingListBlurb = $params->get('GMEmptyMailingListBlurb');
    
    return $GMSelectedMailingList;

  
  
}


function SubscribeTo_MailingList($UserName,$UserPassword,$MailingListID,$SubscriberEmail,&$APIResponse)
{

  $server= "www.graphicmail.com";
  $url = "/api.aspx";
  $content = "?Username=". $UserName . "&Password=" . $UserPassword ."&Function=post_subscribe&Email=" . $SubscriberEmail . "&MailinglistID=" . $MailingListID. "&SID=0";
  $content_length = strlen($content); 


  $headers = "POST " . $url .  $content . " HTTP/1.0\r\n";
        $headers .= "Host: " . $server . "\r\n";
        $headers .= "Content-type: application/x-www-form-urlencoded\r\n";
        $headers .= "Content-length: " . strlen($content) . "\r\n";
        $headers .= "Connection: close \r\n\r\n";
        
  


  $fp = fsockopen ("ssl://" . $server,"443",$err_num, $err_str, 30);
  

  if (!$fp) 
  {
    print "Unable";
    return false;
  }
  fputs($fp, $headers);
  fputs($fp, $content);
  $ret = "";
  while (!feof($fp)) {
    $ret.= fgets($fp, 1024);
    }
  fclose($fp);
  
  
  $response=explode("\r\n\r\n",$ret);

  $ArrayCount = count($response); 
  if ($ArrayCount > 1)
  {
	  $MailingListText = $response[$ArrayCount - 1];
    
    $PositionAt = strrpos($MailingListText,"|") + 1;
    if ($PositionAt >= 0)
    {
	    $APIResponse = substr($MailingListText,$PositionAt) ;
    }
  }
 }
function showMessage($MessageText)
{
  $MessageHtml = '<table border="0px" cellpadding="1" cellspacing="1" style="border: solid 1px #E5E5E5;background-color: #ffffff;">';
  $MessageHtml .= '<TR><TD>' . $MessageText . '</TD></TR></TABLE>';
  return $MessageHtml;
}

?>
