// ----------------------------------------------------------------------------
// http://snippets.dzone.com/posts/show/4296
// ----------------------------------------------------------------------------
// Same as var_dump for PHP, but for JavaScript. Useful if you do not have Firebug.
// A typical useage:
// document.write(var_dump(ANY-JS-VAR,'html'));
function var_dump( data, addwhitespace, safety, level ) {
  var rtrn = '';
  var dt,it,spaces = '';
  if (!level) {level = 1;}
  for(var i=0; i<level; i++) {
    spaces += '  ';
  } //end for i<level
  if (typeof(data) != 'object') {
    dt = data;
    if (typeof(data) == 'string') {
      if (addwhitespace == 'html') {
        dt = dt.replace(/&/g,'&amp;');
        dt = dt.replace(/>/g,'&gt;');
        dt = dt.replace(/</g,'&lt;');
      } //end if addwhitespace == html
      dt = dt.replace(/\"/g,'\"');
      dt = '"' + dt + '"';
    } //end if typeof == string
    if (typeof(data) == 'function' && addwhitespace) {
      dt = new String(dt).replace(/\n/g,"\n"+spaces);
      if (addwhitespace == 'html') {
        dt = dt.replace(/&/g,'&amp;');
        dt = dt.replace(/>/g,'&gt;');
        dt = dt.replace(/</g,'&lt;');
      } //end if addwhitespace == html
    } //end if typeof == function
    if (typeof(data) == 'undefined') {
      dt = 'undefined';
    } //end if typeof == undefined
    if (addwhitespace == 'html') {
      if (typeof(dt) != 'string') {
        dt = new String(dt);
      } //end typeof != string
      dt = dt.replace(/ /g,"&nbsp;").replace(/\n/g,"<br>");
    } //end if addwhitespace == html
    return dt;
  } //end if typeof != object && != array
  for (var x in data) {
    if (safety && (level > safety)) {
      dt = '*RECURSION*';
    } else {
      try {
        dt = var_dump(data[x],addwhitespace,safety,level+1);
      } catch (e) {continue;}
    } //end if-else level > safety
    it = var_dump(x,addwhitespace,safety,level+1);
    rtrn += it + ':' + dt + ',';
    if (addwhitespace) {
      rtrn += '\n'+spaces;
    } //end if addwhitespace
  } //end for...in
  if (addwhitespace) {
    rtrn = '{\n' + spaces + rtrn.substr(0,rtrn.length-(2+(level*3))) + '\n' + spaces.substr(0,spaces.length-3) + '}';
  } else {
    rtrn = '{' + rtrn.substr(0,rtrn.length-1) + '}';
  } //end if-else addwhitespace
  if (addwhitespace == 'html') {
    rtrn = rtrn.replace(/ /g,"&nbsp;").replace(/\n/g,"<br>");
  } //end if addwhitespace == html
  return rtrn;
} //end function var_dump