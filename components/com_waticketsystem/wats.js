/**
 * @version $Id: wats.js 53 2009-03-26 19:11:55Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL
 * @package wats
 */

/**
 * Shows / Hides layers.
 */
function watsToggleLayer(whichLayer)
{
	if (document.getElementById)
	{
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
	}
	else if (document.all)
	{
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
	}
	else if (document.layers)
	{
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
	}
	
	// toggle display type
	if ( style2.display == "block" )
	{
		style2.display = "none";
	}
	else
	{
		style2.display = "block";
	}
}

/**
 * Associtaions: post12007 (IE compatability)
 * implement 'jump' feature of category selection box
 */
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

/**
 * implement 'jump' feature of category selection box
 
function watsCategorySetSelect( catid, itemid )
{
	if ( catid == -1 )
	{
		// jump to location
		document.location.href = "index.php?option=com_waticketsystem&Itemid=" + itemid;
    }
	else
	{
		// jump to location
		document.location.href = "index.php?option=com_waticketsystem&Itemid=" + itemid + "&act=category&catid=" + catid + "&page=1&lifecycle=a";
	}
}*/

/**
 * Validates new user form
 */
function watsValidateNewUser( form, user, errorMessage )
{
	returnValue = true;
	// check fields
	if(user.selectedIndex < 0)
	{
		alert( errorMessage );
		user.focus();
		returnValue = false;
	}
	else if ( trim( form.grpId.value ) == "" )
	{
		alert( errorMessage );
		form.grpId.focus();
		returnValue = false;
	}
	else if ( trim( form.organisation.value ) == "" )
	{
		alert( errorMessage );
		form.organisation.focus();
		returnValue = false;
	} // end check fields
	return returnValue;
}