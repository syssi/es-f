/*
 *
 */

// ----------------------------------------------------------------------------
// show / hide auction table
// ----------------------------------------------------------------------------
function ToggleAuctionsTable() {
  var img, tbl;
  if ((img = $('AnalyseImg')) && (tbl = $('AuctionsTable'))) {
    var path = 'module/analyse/layout/default/images/';
    tbl.toggle();
    if (!tbl.visible()) {
      // hide auction table
      img.src = path + 'show.gif';
      img.alt = 'v';
      img.onmouseover = function() { Tip(AnalyseShowAuctions) };
    } else {
      // show auction table
      img.src = path + 'hide.gif';
      img.alt = '^';
      img.onmouseover = function() { Tip(AnalyseHideAuctions) };
    }
  }
  return false;
}
