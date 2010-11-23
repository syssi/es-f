/*
tip_tagtotipex.js  v. 1.0

The latest version is available at
http://www.walterzorn.com
or http://www.devira.com
or http://www.walterzorn.de

Copyright (c) 2007 Walter Zorn. All rights reserved.
Last modified: 1.6.2007

Extension for the tooltip library wz_tooltip.js.
If a tooltip is created with TagToTip(), this extension inserts the DOM node
itself into the tooltip, rather than just a copy of the node's inner HTML.
*/

// Here we define new global configuration variable(s) (as members of the
// predefined "config." class).
// From each of these config variables, wz_tooltip.js will automatically derive
// a command which can be passed to Tip() or TagToTip() in order to customize
// tooltips individually. These command names are just the config variable
// name(s) translated to uppercase,
// e.g. from config. TagToTipDirect a command TAGTOTIPDIRECT will automatically
// be created.

//===================	GLOBAL TOOPTIP CONFIGURATION	======================//
config. TagToTipDirect = false		// true or false - default is false, as we don't want this to be the default behaviour
//=======	END OF TOOLTIP CONFIG, DO NOT CHANGE ANYTHING BELOW	==============//


// Create a new tt_Extension object (make sure that the name of that object,
// here ttte, is unique amongst the extensions available for wz_tooltips.js):
var ttte = new tt_Extension();


// Implement extension eventhandlers on which our extension should react
ttte.OnLoadConfig = function()
{
	if(tt_elToTip && tt_aV[TAGTOTIPDIRECT])
		return true;
	tt_aV[TAGTOTIPDIRECT] = false; // Tip() instead of TagToTip() called
	return false;
}
ttte.OnCreateContentString = function()
{
	if(tt_aV[TAGTOTIPDIRECT])
		tt_sContent = "";
	return false;
}
ttte.OnSubDivsCreated = function()
{
	if(tt_aV[TAGTOTIPDIRECT])
	{
		// Store the tag's parent element so we can restore that DOM tree
		// branch when the tooltip is hidden
		ttte.dad = tt_elToTip.offsetParent || null;
		if(ttte.dad)
		{
			Ttte_MovNode(tt_elToTip, ttte.dad, tt_aElt[6]);
			tt_elToTip.style.display = "block";
		}
	}
	return false;
}
ttte.OnKill = function()
{
	if(tt_aV[TAGTOTIPDIRECT] && ttte.dad)
	{
		tt_elToTip.style.display = "none";
		Ttte_MovNode(tt_elToTip, tt_aElt[6], ttte.dad);
	}
	return false;
}
// Helper functions
function Ttte_MovNode(el, dadFrom, dadTo)
{
	dadFrom.removeChild(el);
	dadTo.appendChild(el);
}
