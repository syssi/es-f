/*
tip_titlepadding.js  v. 1.0

The latest version is available at
http://www.walterzorn.com
or http://www.devira.com
or http://www.walterzorn.de

Copyright (c) 2007 Walter Zorn. All rights reserved.
Last modified: 1.6.2007

Extension for the tooltip library wz_tooltip.js.
adds config TitlePadding and handles empty tip content but title given
*/

// Here we define new global configuration variable(s) (as members of the
// predefined "config." class).
// From each of these config variables, wz_tooltip.js will automatically derive
// a command which can be passed to Tip() or TagToTip() in order to customize
// tooltips individually. These command names are just the config variable
// name(s) translated to uppercase,
// e.g. from config. TitlePadding a command TITLEPADDING will automatically be
// created.

//===================  GLOBAL TOOPTIP CONFIGURATION  =========================//
config. TitlePadding = '0px'	// css padding string for tip title
//=======  END OF TOOLTIP CONFIG, DO NOT CHANGE ANYTHING BELOW  ==============//


// Create a new tt_Extension object (make sure that the name of that object,
// here ctrwnd, is unique amongst the extensions available for
// wz_tooltips.js):
var titlepadding = new tt_Extension();

// Implement extension eventhandlers on which our extension should react
titlepadding.OnCreateContentString = function()
{
	if (tt_aV[TITLE])
	{
		tt_aV[TITLE] = '<div style="padding:'+tt_aV[TITLEPADDING]+'">'+tt_aV[TITLE]+'</div>';
	}
}
