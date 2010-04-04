/**
 * @version $Id: admin.wats.js 66 2009-03-31 14:18:46Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL
 * @package wats
 */

/**
 * Updates all form elements names in array from control
 */
function updateControls( array, control )
{
	// loop through array and change values to control
	for (key in array)
			getElement( array[ key ] ).value = control.value;
}

/**
 * Gets element by id
 */
function getElement( id )
{
   if( document.all )
   {
      return document.all[ id ];
   }
   else
   {
      return document.getElementById( id );
   }
} 