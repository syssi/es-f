/*
 *
 */

// ---------------------------------------------------------------------------
function LoadJS( _file ) {
  document.writeln('<script type="text/javascript" src="'+_file+'"><\/script>');
}

// ---------------------------------------------------------------------------
function LoadStyle( _file ) {
  document.writeln('<link rel="stylesheet" type="text/css" href="'+_file+'" \/>');
}

// ---------------------------------------------------------------------------
var LoadedJSLibs = new Array();

// ---------------------------------------------------------------------------
function LoadJSLib( _lib, _ver ) {
  if (typeof _ver !== 'undefined') _lib += '/' + _ver + '/' + _lib;
  var Lib = 'js/' + _lib + '.js';
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

var ScriptaculousJsVersion = '1.8.0';
LoadJSLib('scriptaculous', ScriptaculousJsVersion);

var DialogJsPath = 'js/';
LoadJSLib('dialog');

var TabberRootDir = 'js/';
LoadJSLib('tabber');
document.write('<style type="text/css">.tabber{display:none;}<\/style>');

LoadJSLib('cookies');
LoadJSLib('sprintf');

// ---------------------------------------------------------------------------
// load single scripts
// ---------------------------------------------------------------------------