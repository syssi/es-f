/*
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

addLoadEvent(function() {
  Modalbox.setOptions({
    width:        600,
/*
    inactiveFade: false,
    transitions:  false
*/
  });
  // redefine alert message
  Modalbox.alert = function(message){
    var html = '<div class="MB_alert"><p>' + message +
               '</p><input type="button" class="button" ' +
               'onclick="Modalbox.hide()" value="OK" /></div>';
    Modalbox.show(html, {title:'', width:300});
  };
  window.alert = Modalbox.alert;
});

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