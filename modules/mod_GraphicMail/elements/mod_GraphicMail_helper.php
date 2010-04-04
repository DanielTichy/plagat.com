<?php
function get_MailingList($UserName,$UserPassword,&$MailingListID,&$MailingListTitle,&$MailingListHFTitle,&$ErrorFound)
{

  $server= "www.graphicmail.com";
  $url = "/api.aspx";
  $content = "?Username=". $UserName . "&Password=" . $UserPassword ."&Function=get_mailinglists&SID=0";
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
   
    if ( strrpos(htmlspecialchars($MailingListText),"0") == 0)
    {
       $ErrorFound = true;
       $MailingListTitle = substr($MailingListText,strrpos($MailingListText,"0|")+strlen("0|"));
       return false;
    }
    $xml = new SimpleXMLElement($MailingListText);

    if ($xml)
    {
      
      $MailingListArrayCount =  count($xml->mailinglist);
      for ($i = 0; $i < $MailingListArrayCount; $i++) 
	    {
          $MailingListTitle .= htmlspecialchars($xml->mailinglist[$i]->description). "-" . htmlspecialchars($xml->mailinglist[$i]->mailinglistid) . ",";
	    }
    }
    return true;

  }
  
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


?>
