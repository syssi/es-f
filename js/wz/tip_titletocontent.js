/*
tip_correctcontent.js  v. 1.0

The latest version is available at
http://www.walterzorn.com
or http://www.devira.com
or http://www.walterzorn.de

Copyright (c) 2007 Walter Zorn. All rights reserved.
Last modified: 1.6.2007

Extension for the tooltip library wz_tooltip.js.
handles empty tip content but title given by moving title to content
*/

// Here we define new global configuration variable(s) (as members of the
// predefined "config." class).
// From each of these config variables, wz_tooltip.js will automatically derive
// a command which can be passed to Tip() or TagToTip() in order to customize
// tooltips individually. These command names are just the config variable
// name(s) translated to uppercase,
// e.g. from config. TitleToContent a command TITLETOCONTENT will automatically be
// created.

//===================  GLOBAL TOOPTIP CONFIGURATION  =========================//
config. TitleToContent = false	// true / false, not active by default
//=======  END OF TOOLTIP CONFIG, DO NOT CHANGE ANYTHING BELOW  ==============//


// Create a new tt_Extension object (make sure that the name of that object,
// here ctrwnd, is unique amongst the extensions available for
// wz_tooltips.js):
var t2t = new tt_Extension();

// Implement extension eventhandlers on which our extension should react
t2t.OnCreateContentString = function()
{
       if(tt_aV[TITLETOCONTENT])
	{
	       if(!tt_sContent && tt_aV[TITLE])
       	{
			// if title but no text, move title to text
			tt_sContent = tt_aV[TITLE],  tt_aV[TITLE] = '';
       	}
	}
}
