// ---------------------------------------------------------------------------
// settings for popup edit windows
// ---------------------------------------------------------------------------
// Auction
var AuctionEditWindowWidth    = 640;
var AuctionEditWindowHeight   = 600;

// Group
var GroupEditWindowWidth      = 600;
var GroupEditWindowHeight     = 260;

// ---------------------------------------------------------------------------
// show item image in new window
// ---------------------------------------------------------------------------
function ShowItemImg ( _url, _width, _height, _item ) {
  var left = (window.outerWidth  - _width) / 2;
  var top = (window.outerHeight - _height) / 2;
  var win = window.open("", _item, "left="+left+",top="+top+",innerwidth="+_width+",innerheight="+_height+",status=0,chrome=0");
  var d = win.document;
  d.writeln('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
  d.writeln('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">');
  d.writeln('<head><title>Auction '+_item+'</title><meta http-equiv="imagetoolbar" content="no"></head>');
  d.writeln('<body marginwidth="0" marginheight="0" topmargin="0" leftmargin="0" scroll="no">');
  d.writeln('<img style="border:0;cursor:pointer" src="'+_url+'" onClick="window.close();" title="Close window">');
  d.writeln('</body>');
  d.writeln('</html>');
  d.close();
  win.focus();
  return false;
}

// ---------------------------------------------------------------------------
// switch group select for multiple category or group
// ---------------------------------------------------------------------------
function SwitchMulti ( _select ) {
  if (['mrefresh','mstart','mstop'].indexOf(_select.value) >= 0) {
    _select.form.submit();
    return;
  }

  var button = false;
  $A($('multiaction').options).each( function(El) {
    if (!El.value) return;
    var toggle;
    // is there a div to toggle visibility
    if (toggle = $('d'+El.value)) {
      if (El.value == _select.value) {
        toggle.show();
        button = true;
      } else {
        toggle.hide();
      }
    }
  });
  $('multibutton').toggle(button);
}

// ---------------------------------------------------------------------------
// show popup window for auction edit
// ---------------------------------------------------------------------------
function openWinAuction ( _url ) {
  openWin( _url, AuctionEditWindowWidth, AuctionEditWindowHeight );
  return false;
}

// ---------------------------------------------------------------------------
// show popup window for group edit
// ---------------------------------------------------------------------------
function openWinGroup ( _url ) {
  openWin( _url, GroupEditWindowWidth, GroupEditWindowHeight );
  return false;
}

// ---------------------------------------------------------------------------
// if add to group, change category select to "- from group -"
// ---------------------------------------------------------------------------
function SetGroupCategory ( _group, _select ) {
  if (_select === undefined) _select = 'CategorySelect';
  var El = $(_select);
  if (El) {
    if (_group)
      El.value = GetCategoryFromGroup;
    else if (El.value == GetCategoryFromGroup)
      El.value = '';
  }
}

// ---------------------------------------------------------------------------
function ShowHideCategory( _category, _force ) {
  var El = $('tbody_'+_category);
  if (El) {
    $(El).toggle(_force);
    var categories = cookieManager.getCookie('categories');
    if (categories) {
      eval("categories = categories.replace(/%%"+_category+"/g,'');");
    } else {
      categories = '';
    }
    if (!El.visible()) categories += '%%' + _category;
    cookieManager.setCookie('categories', categories);
  }
}

// ---------------------------------------------------------------------------
function ajaxStartGroup ( _group, _hash ) {
  var wait = CreateStandByWindow();
  new Ajax.Request(
    'index.php',
    { method: 'get',
      parameters: { api:'startstop', group:_group },
      onSuccess: function(transport) {
        var json = transport.responseText.evalJSON(true);
        if (json.rc == 0) {
          var action = (json.result != 0) ? 'stop' : 'start';
          var El = $('img_startstop_'+_hash);
          El.src = El.src.replace(/\w+\.gif/,action+'.gif');
          El = $('a_startstop_'+_hash);
          El.href = El.href.replace(/action=\w+/,'action='+action);
          if (action == 'stop') {
            $('td_group1_'+_hash).addClassName('group_active');
            $('td_group2_'+_hash).addClassName('group_active');
            tip_startstop[_hash] = tip_stop;
          } else {
            $('td_group1_'+_hash).removeClassName('group_active');
            $('td_group2_'+_hash).removeClassName('group_active');
            tip_startstop[_hash] = tip_start;
          }
        }
        if (json.msg) {
          alert(json.msg);
        }
      },
      onFailure: function() {
        alert('Something went wrong!')
      },
      onComplete: function() {
        RemovePopupWindow(wait);
      }
    }
  );
  return false;
}

// ---------------------------------------------------------------------------
function DeleteAuction ( _item, _msg ) {
  var answer = confirm(_msg);
  if (answer) ajaxDeleteAuction(_item);
  return false;
}

// ---------------------------------------------------------------------------
function ajaxDeleteAuction ( _item ) {
  new Ajax.Request(
    'index.php',
    { method: 'post',
      parameters: { api:'delete', item:_item },
      onSuccess: function(transport) {
        var json = transport.responseText.evalJSON(true);
        if (json.rc == 0) {
          var AuctionRow = $('tr_'+_item);
          var cells = $A(AuctionRow.cells), cell;
          // group columns in this row?
          var FirstGroupColumn = false, i = 0;
          // move group columns one row down
          var NextRow = AuctionRow.nextSibling;
          while(NextRow && (!NextRow.tagName || (NextRow.tagName.toLowerCase() != 'tr'))) {
            NextRow = NextRow.nextSibling;
          }
          while (cell = cells[i]) {
            if (cell.rowSpan > 1) {
              cell.move(NextRow);
              cell.rowSpan--;
              FirstGroupColumn || (FirstGroupColumn = i);
            }
            i++;
          }
          // if an auction INSIDE a group or SINGLE auction group
          if (!FirstGroupColumn) {
            FirstGroupColumn = i;
            var PrevRow = AuctionRow.previousSibling;
            while (PrevRow && (cells = $A(PrevRow.cells)) && (cells[FirstGroupColumn] === undefined)) {
              PrevRow = PrevRow.previousSibling;
            }
            if (PrevRow) {
              while (cell = cells[FirstGroupColumn++]) {
                cell.rowSpan--;
              }
            }
          }
          // last auction in category?
          var ABody = AuctionRow.parentNode, rowcnt;
          // find previous category tbody
          var CBody = ABody.previousSibling;
          while (CBody && (!CBody.tagName || (CBody.tagName.toLowerCase() != 'tbody'))) {
            CBody = CBody.previousSibling;
          }
          // is CBody the tbody of the category row
          if (CBody) {
            if (rowcnt = ABody.childElements().length-1) {
              var Cnt = $(CBody.id+'_count');
              if (Cnt) Cnt.update(rowcnt);
            } else {
              // no more auctions in this category, remove it
              CBody.remove();
              ABody.remove();
            }
          }
          // remove whole auction row
          AuctionRow.remove();
        }
        if (json.msg) {
          alert(json.msg);
        }
      },
      onFailure: function() {
        alert('Something went wrong!');
      },
      onComplete: function() {
        $(DialogOverlayId).hide();
      }
    }
  );
  return false;
}

// ---------------------------------------------------------------------------
// CountDonws
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// element id prefix before item number
var esf_CountDownPrefix = "countdown_";

// ---------------------------------------------------------------------------
// esf_CountDown[Id][0] = end timestamp
// esf_CountDown[Id][1] = item no.
var esf_CountDown = new Array();

// holds extra element id prefixes that should also updated
// (take a look at plugin nextauction)
var esf_CountDownExtra = new Array();

// is auto refresh allowed
var esf_CountDownRefresh = true;

// "Ended" text, can be overwritten, e.g. for your language
var esf_CountDownEndedStr = 'Ended';

// ---------------------------------------------------------------------------
function showCountDown() {

  var Now = new Date();
  var cMax = esf_CountDown.length;
  var eMax = esf_CountDownExtra.length;
  var Id, item, El, Remain, d, h, m, s, Txt, eId, eEl;

  for (Id=0; Id<cMax; Id++) {
    item = esf_CountDown[Id][1];
    El = $(esf_CountDownPrefix + item);
    // may be, on not ended auctions the remaining time is not shown,
    // so the required element does NOT exist
    if (El) {
      // remaining seconds
      Remain = ((new Date(esf_CountDown[Id][0]*1000)) - Now) / 1000;
      // ends in about 60, 20, 5 or 1 minute or is ended
      if (esf_CountDownRefresh && (
           ( (Remain >= 60*60-2)            && (Remain <= 60*60)              ) ||
           ( (Remain >= 20*60-2)            && (Remain <= 20*60)              ) ||
           ( (Remain >=  5*60-2)            && (Remain <=  5*60)              ) ||
           ( (Remain >=    60-2)            && (Remain <=    60)              ) ||
           ( (Remain >= ServerTimeOffset-4) && (Remain <= ServerTimeOffset-2) )
        ) ) {
        // reload page and refresh auction
        location.href='index.php?module=auction&action=mrefresh&auctions[]=' + item +
                      // add timestamp to force reload of page...
                      '&_ts=' + Now.getTime() + '#' + item;
        return;
      }

      if (Remain > 0) {
        // split into days, hours, minutes, seconds
        d = Math.floor(Remain / 60 / 60 / 24                               );
        h = Math.floor(Remain / 60 / 60 - 24*d                             );
        m = Math.floor(Remain / 60      - 60 * 24*d      - 60*h            );
        s = Math.floor(Remain           - 60 * 60 * 24*d - 60 * 60*h - 60*m);

        // force leading zero!
        Txt = '' + Math.floor(h/10) + h%10 + ':'
                 + Math.floor(m/10) + m%10 + ':'
                 + Math.floor(s/10) + s%10;

        if (d > 0) {
          // show days only if required and WITHOUT leading zeros
          Txt = Math.floor(d/100) + Math.floor((d/10)%10)
              + Math.floor(d%10)  + ':' + Txt;
        }
      } else {
        Txt = esf_CountDownEndedStr;
      }

      El.update(Txt);
      ColorCountDownElement(Remain, El);

      for (eId=0; eId<eMax; eId++) {
        eEl = $(esf_CountDownExtra[eId] + item);
        if (eEl) {
          eEl.update(Txt);
          ColorCountDownElement(Remain, eEl);
        }
      }
    }
  }

  // execute every second
  window.setTimeout('showCountDown()', 1000);
}

// ---------------------------------------------------------------------------
function ColorCountDownElement( Remain, El ) {
  if (Remain < 60*60) {
    // last hour in red
    El.style.color = '#FF0000';
  }
  if (Remain < 5*60) {
    // last 5 minutes also bold
    El.style.fontWeight = 'bold';
  }
  if (Remain < 60) {
    El.update('&nbsp;'+El.innerHTML+'&nbsp;');
    if (Remain >= 0) {
      El.style.backgroundColor = 'transparent';
      new Effect.Morph(El, {
        style: {
          color: '#FFFFFF',
          backgroundColor: '#FF3333'
        },
        duration: 0.9
      });
    } else {
      El.style.color = '#FFFFFF';
      El.style.backgroundColor = '#FF3333';
    }
  }
}

// ---------------------------------------------------------------------------
// Toggle all auctions of a category
// ---------------------------------------------------------------------------
function ToggleCategoryAuctions( _category, _checked ) {
  // get all checkboxes in auction rows
  var i, El;
  var CheckBoxes = document.getElementsByName('auctions[]');
  for (i=0; i<CheckBoxes.length; i++) {
    El = CheckBoxes[i];
    if (El.id.indexOf(_category) === 0) {
      ToggleAuctionRow(El, _checked);
    }
  }
}

// ---------------------------------------------------------------------------
// Toggle auction row class
// ---------------------------------------------------------------------------
function ToggleAuctionRow( _El, _checked ) {
  // can be a string...
  _El = $(_El);
  if (_checked === undefined) {
    _checked = !_El.checked;
  }
  _El.checked = _checked;
  $('tr_'+_El.value).toggleClassName('selected', _checked);
}

// ---------------------------------------------------------------------------
// Toggle auction row class
// ---------------------------------------------------------------------------
function ToggleAddRow( _img ) {
  var row = $('rowadd');
  if (_img && row) {
    if (row.visible()) {
      _img.src = 'layout/images/arrow-down.gif';
      row.hide();
    } else {
      _img.src = 'layout/images/arrow-up.gif';
      row.show();
    }
  }
}

// ---------------------------------------------------------------------------
// Init
// ---------------------------------------------------------------------------
addLoadEvent(showCountDown);
