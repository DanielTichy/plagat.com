<?php
defined('_JEXEC') or die('Restricted Access');

?>
	<script type="text/javascript" src="<?php echo JURI::base(); ?>modules/mod_GraphicMail/ajax.js"> </script>

	<script type="text/javascript">
	// Global Ajax request
	var AjaxReqObj = new AjaxRequest();
	var CalledOption = "GetMailingList";
	function GetMailingList() 
	{
		
		if(!document.getElementById("APIUsername").value)
		{
			alert("Please provide vaild Graphicmail API Username.");
			document.getElementById("APIUsername").focus();
			return;
		}

		if(!document.getElementById("APIPassword").value)
		{
			alert("Please provide vaild Graphicmail API Password.");
			document.getElementById("APIPassword").focus();
			return;
		}	
		
		document.getElementById("MailingListbtn").disabled = true;
		document.getElementById("MessageSpace").innerHTML = "";
		document.getElementById('ProgressImg').style.display='';

		CalledOption = "GetMailingList";
		
		postvars = "ajax=true"  + "&APIPassword=" + document.getElementById("APIPassword").value + 
					"&APIUsername=" + document.getElementById("APIUsername").value + 
					"&Option=GetMailingList";					

		
		AjaxReqObj.send("POST", "<?php echo JURI::base(); ?>modules/mod_GraphicMail/GraphicMail_Api.php", handleRequest, "application/x-www-form-urlencoded; charset=UTF-8", postvars);

	}	// end of GetMailingList


	function SubscribeEmailAddress() 
	{

		// check email address for validity against big, nasty regular expression
		var regex = /^[\w\.-_\+]+@[\w-]+(\.\w{2,3})+$/;
		if (document.getElementById("SubscriberEmail") == null)
		{
			return;
		}
		
		if(!document.getElementById("SubscriberEmail").value)
		{
			alert("Please provide vaild email address.");
			document.getElementById("SubscriberEmail").focus();
			return;
		}		
		if(!regex.test(document.getElementById("SubscriberEmail").value))
		{
			alert("Please provide a valid email address.");
			document.getElementById("MessageSpace").innerHTML = "Invalid email address";
			return;
		}
		
		document.getElementById("SubscribeEmailbtn").disabled = true;
		document.getElementById("MessageSpace").innerHTML = "";
		document.getElementById('ProgressImg').style.display='';
		
		postvars = "ajax=true"  + "&SubscriberEmail=" + document.getElementById("SubscriberEmail").value ; 
		
	
		AjaxReqObj.send("POST", "<?php echo JURI::base(); ?>modules/mod_GraphicMail/GraphicMail_Api.php", handleRequest, "application/x-www-form-urlencoded; charset=UTF-8", postvars);

	}	// end of GetMailingList

	// Handle the Ajax request
	function handleRequest() 
	{
		
		if (AjaxReqObj.getReadyState() == 4 && AjaxReqObj.getStatus() == 200) 
		{
			
			
			document.getElementById("ProgressImg").style.display='none';
			ResponseMessage = AjaxReqObj.getResponseText();
			document.getElementById("SubscribeEmailbtn").disabled = false;
			if (ResponseMessage.indexOf("Error=") > -1)
			{
				alert(ResponseMessage.replace("Error=",""));		
			}
			else	
			{
				ResponseMsgArray = ResponseMessage.split(",");
				ArrayCount = ResponseMsgArray.length;
				alert(ResponseMessage);
			}
			
		}
	}
	function ClearSelection()
	{
		document.getElementById("MessageSpace").innerHTML = "";
		document.getElementById("SubscriberEmail").value = "";
		
	}
	</script>
<?php 
	require_once('mod_GraphicMail_helper.php');
	
    $MailingListTitle = "";
    $MailingListID =  "";
    $GMFrontEndBlurb = "";
    $GMEmptyMailingListBlurb = "";
    $MailingListTitle = get_AdminParameters($GMFrontEndBlurb,$GMEmptyMailingListBlurb);
    if (! $MailingListTitle || $MailingListTitle == "0")
    {
		if (! $GMEmptyMailingListBlurb)
		{
			$GMEmptyMailingListBlurb = "Please complete the setup of this module in your administration.";
		}
		print showMessage($GMEmptyMailingListBlurb);
    }
    if (strrpos($MailingListTitle,"-") > 0)
    {
        $response=explode("-",$MailingListTitle);
        $ArrayCount = count($response);
        if ($ArrayCount == 2)
		{
			$MailingListTitle = $response[0];
			$MailingListID = $response[1];
		}
    
    
   
	
		
?>
	<div id="MessageSpace"></div>
	<form name="GraphicMail">
	<img id="ProgressImg" src="<?php echo JURI::base(); ?>modules/mod_GraphicMail/ajax-loader.gif" style="display:none" />
	<DIV id="MailingList">
	   <table border="0px" cellpadding="1" cellspacing="1" style="border: solid 1px #E5E5E5;background-color: #ffffff;">
	   		<TR>
				<TD>
					<?php print $GMFrontEndBlurb ?>
				</TD>
			</TR>	

			<TR>
				<TD><img src="<?php echo JURI::base(); ?>modules/mod_GraphicMail/bullet_grey.gif" /><?php print $MailingListTitle; ?></TD>
			</TR>
			<TR>
				<TD>		
					Email Address:
				</TD>
			</TR>
			<TR>
				<TD>
					<input type="text" id="SubscriberEmail" name="SubscriberEmail" value="" />
				</TD>
			</TR>
			<TR>
				<TD>
					<input type="button" id="SubscribeEmailbtn" value="Subscribe" onClick="SubscribeEmailAddress()" />
				</TD>
			</TR>
		</TABLE>					
	</DIV>
<?php
	}
?>	
	</form><br />
