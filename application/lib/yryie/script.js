/**
 * Replace a token in a string
 * - s : string to be processed
 * - t : token to be found and removed
 * - u : token to be inserted
 * return string
 */
function replace(s, t, u) {
  s = s.replace(t, '').trim();
  return s ? s + ' ' + u : u;
}

/**
 * Switch rows of given type on/off
 */
function YryieSwitch( _type, _checked ) {
  var rows = document.getElementById('Yryie')
            .getElementsByTagName('table')[0]
            .getElementsByTagName('tr');
  if (!rows) {
    alert('No rows found!');
    return;
  }
  var v = 0;
  for (var i=0; i<rows.length; i++) {
    if (rows[i].className.indexOf(_type) != -1) {
      rows[i].style.display = _checked ? '' : 'none';
    }
    // re-color rows
    if (!rows[i].style.display) {
      if (v++ % 2) {
        if (rows[i].className.indexOf('odd') != -1) {
          rows[i].className = replace(rows[i].className, 'odd', 'even');
        }
      } else {
        if (rows[i].className.indexOf('even') != -1) {
          rows[i].className = replace(rows[i].className, 'even', 'odd');
        }
      }
    }
  }
}
