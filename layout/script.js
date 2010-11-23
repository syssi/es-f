/*
 * Copyright (c) 2006-2008 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 */

// ---------------------------------------------------------------------------
// Add a function to window.onload
// ---------------------------------------------------------------------------
function addLoadEvent( _func ) {
//  document.observe('dom:loaded', _func);
  Event.observe(window, 'load', _func);
}

/*
addLoadEvent(nameOfSomeFunctionToRunOnPageLoad);
addLoadEvent(function() {
  // more code to run on page load
});
*/

// ---------------------------------------------------------------------------
// show popup window
// ---------------------------------------------------------------------------
function openWin( _url, _w, _h, _params ) {
  var l = (window.outerWidth  - _w) / 2;
  var t = (window.outerHeight - _h) / 2;
  if (_params) {
    _params = ',' + _params;
  }
  var win = window.open(_url, '', 'left=' + l + ',top=' + t + ',width=' + _w +
                                  ',height=' + _h + ',resizable=yes' + _params);
  win.focus();
  return false;
}

// ---------------------------------------------------------------------------
// show a message window
// ---------------------------------------------------------------------------
function ShowMessage( _msg, _ok ) {
  var width  = 300;
  var height = 120;
  var left = (window.outerWidth - width)  / 2;
  var top = (window.outerHeight - height) / 2;
  var MsgWin = window.open("", "MSGWIN", "left="+left+",top="+top+",innerwidth="+width+",innerheight="+height+",status=0,chrome=0");
  var d = MsgWin.document;
  d.writeln('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
  d.writeln('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">');
  d.writeln('<head><title></title><meta http-equiv="imagetoolbar" content="no">');
  d.writeln('<link rel="stylesheet" type="text/css" href="./layout/default/style.css"></head>');
  d.writeln('<body scroll="no" style="text-align:center;padding:10px 10px 0 10px">');
  if (_ok === false) {
    // don't show OK button yet
    d.writeln(_msg);
  } else {
    d.writeln('<div style="height:'+(height-55)+'px">'+_msg+'</div>');
    d.writeln('<input style="margin-top:10px;height:25px" type="button" value="Ok" onclick="window.close();">');
  }
  d.writeln('</body></html>');
  d.close();
  win.focus();
  return false;
}

// ---------------------------------------------------------------------------
// Togggle a class on an element
// ---------------------------------------------------------------------------
function ToggleClass ( _El, _Class, _Set ) {
  if ($(_El)) {
    if (_Set) {
      $(_El).addClassName(_Class);
    } else {
      $(_El).removeClassName(_Class);
    }
  }
}