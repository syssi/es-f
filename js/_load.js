/*
 *
 */

// ---------------------------------------------------------------------------
function LoadJS( _file ) {
  document.writeln('<script src="'+_file+'"><\/script>');
}

// ---------------------------------------------------------------------------
function LoadCSS( _file, _media ) {
  var media = (typeof _media !== 'undefined') ? 'media="'+_media+'"' : '';
  document.writeln('<link rel="stylesheet" type="text/css" href="'+_file+'" '+media+'\/>');
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
//LoadJSLib('prototype', '1.7');
//LoadJSLib('prototypePlus', '1.7');
//LoadJSLib('scriptaculous', '1.9.0');

LoadJS('https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js');
LoadJSLib('prototypePlus', '1.7');
LoadJS('https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js');

LoadJS('js/ModalBox/modalbox.js');
LoadCSS('js/ModalBox/modalbox.css', 'screen');

var DialogJsPath = 'js/';
LoadJSLib('dialog');

var TabberRootDir = 'js/';
LoadJSLib('tabber');
document.write('<style type="text/css">.tabber{display:none;}<\/style>');

LoadJSLib('cookies');
LoadJSLib('string');
