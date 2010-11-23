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
      img.alt = '⇓';
      img.onmouseover = function() { Tip(AnalyseShowAuctions) };
    } else {
      // show auction table
      img.src = path + 'hide.gif';
      img.alt = '⇑';
      img.onmouseover = function() { Tip(AnalyseHideAuctions) };
    }
  }
  return false;
}
