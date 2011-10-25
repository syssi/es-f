/*
 *
 */

// ---------------------------------------------------------------------------
function ShowHelp( _help, _style ) {
  var El, id;
  if (El = $(_help)) {
    id = El.id;
    El.style.display = 'none';
  } else {
    var div = document.createElement('div');
    Element.extend(div);
    id = div.addClassName('helpcontent').update(_help).hide().identify();
    document.body.appendChild(div);
  }
  document.write('<img class="help" style="' + _style + '" src="layout/images/help.gif" ' +
                 'onmouseover="TagToTip(\'' + id + '\', ' +
                 'COPYCONTENT,false, PADDING,5, WIDTH,400, SHADOW,true, ' +
                 'FADEIN,250, FADEOUT,100, FADEINTERVAL,30, FONTSIZE,\'100%\', ' +
                 'BORDERCOLOR,\'black\', BGCOLOR,\'white\', OPACITY,100 )"/>');
}

// ---------------------------------------------------------------------------
var wz_tooltip_version = 'wz_tooltip v. 4.12';

//////////////////////  GLOBAL TOOPTIP CONFIGURATION  ////////////////////////
//config.Above           = false;     // false or true - tooltip above mousepointer
config.BgColor         = '#FCFCFC';   // Background color
//config.BgImg           = '';      // Path to background image, none if empty string ''
config.BorderColor     = '#800000';
//config.BorderStyle     = 'solid';    // Any permitted CSS value, but I recommend 'solid', 'dotted' or 'dashed'
//config.BorderWidth     = 1;
//config.BorderRadius    = "0 0 7px 7px";
//config.BorderRadiusPlain  = "7px";
//config.CenterMouse     = false;     // false or true - center the tip horizontally below (or above) the mousepointer
config.ClickClose      = true;       // false or true - close tooltip if the user clicks somewhere
//config.CloseBtn      = false;       // false or true - closebutton in titlebar
//config.CloseBtnColors  = ['#990000', '#FFFFFF', '#DD3333', '#FFFFFF'];
                                    // [Background, text, hovered background, hovered text] -
                                    // use empty strings '' to inherit title colors
//config.CloseBtnText    = '&nbsp;X&nbsp;';  // Close button text (may also be an image tag)
//config.CopyContent    = true;      // When converting a HTML element to a tooltip, copy only the element's content,
                      //rather than converting the element by its own
config.Delay        = 250;      // Time span in ms until tooltip shows up
//config.Duration      = 0;       // Time span in ms after which the tooltip disappears; 0 for infinite duration
//config.FadeIn      = 0;       // Fade-in duration in ms, e.g. 400; 0 for no animation
//config.FadeOut       = 0;
//config.FadeInterval    = 30;      // Duration of each fade step in ms (recommended: 30) -
                      // shorter is smoother but causes more CPU-load
//config.Fix         = null;      // Fixated position - x- an y-oordinates in brackets, e.g. [210, 480],
                      // or null for no fixation
//config.FollowMouse      = false;      // false or true - tooltip follows the mouse
config.FontColor      = 'black';
//config.FontFace      = 'Verdana,Geneva,sans-serif'
config.FontSize      = '85%';     // E.g. '9pt' or '12px' - unit is mandatory
//config.FontWeight    = 'normal';    // 'normal' or 'bold';
//config.Left        = false;     // false or true - tooltip on the left of the mouse
//config.OffsetX       = 14;      // Horizontal offset of left-top corner from mousepointer
config.OffsetY       = 22;       // Vertical offset
config.Opacity       = 90;      // Integer between 0 and 100 - opacity of tooltip in percent
config.Padding       = 6;       // Spacing between border and content
//config.Shadow      = false;     // false or true
//config.ShadowColor     = '#C0C0C0';
config.ShadowWidth      = 0;
//config.Sticky      = false;     // Do NOT hide tooltip on mouseout? false or true
//config.TextAlign      = 'left';    // 'left', 'right' or 'justify'
//config.Title        = '';      // Default title text applied to all tips (no default title: empty string '')
//config.TitleAlign    = 'left';    // 'left' or 'right' - text alignment inside the title bar
//config.TitleBgColor    = '';      // If empty string '', BorderColor will be used
//config.TitleFontColor  = '#ffffff';    // Color of title text - if '', BgColor (of tooltip body) will be used
//config.TitleFontFace    = '';      // If '' use FontFace (boldified)
//config.TitleFontSize    = '85%';      // If '' use FontSize
//config.Width        = 0;       // Tooltip width; 0 for automatic adaption to tooltip content

config.TitlePadding    = '2px 0',    // Spacing between border and content, full css content
config.TitleToContent    = true;      // move title to content, if empty
//////////////////////////////////////////////////////////////////////////////

// ---------------------------------------------------------------------------
tt_Init();
