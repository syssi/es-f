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
var LoadedJSLibs = new Array();

// ---------------------------------------------------------------------------
function LoadJS( _file ) {
  document.writeln('<script type="text/javascript" src="'+_file+'"><\/script>');
}

// ---------------------------------------------------------------------------
function LoadStyle( _file ) {
  document.writeln('<link rel="stylesheet" type="text/css" href="'+_file+'" \/>');
}

// ---------------------------------------------------------------------------
function LoadJSLib( _lib, _ver ) {
  _ver = (_ver === undefined) ? '' : _ver + '/';
  var Lib = 'js/' + _lib + '/' + _ver + _lib + '.js';
  if (!LoadedJSLibs[Lib]) {
    LoadJS(Lib);
    LoadedJSLibs[Lib] = true;
  }
}

// ---------------------------------------------------------------------------
// load libraries
// ---------------------------------------------------------------------------
var PrototypeJsVersion = '1.6.1';
LoadJSLib('prototype', PrototypeJsVersion);
LoadJSLib('prototypePlus', PrototypeJsVersion);

LoadJSLib('scriptaculous', '1.8.0');

var DialogJsPath = 'js/dialog';
LoadJSLib('dialog');

var Tabber_RootDir = 'js/tabber/';
LoadJSLib('tabber');
document.write('<style type="text/css">.tabber{display:none;}<\/style>');

LoadJSLib('esf_cookies');

LoadJSLib('sprintf');

// ---------------------------------------------------------------------------
// load single scripts
// ---------------------------------------------------------------------------
