<!-- COMMENT
/*
 * Copyright (c) 2006-2009 Knut Kohl <knutkohl@users.sourceforge.net>
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
-->

  <td style="text-align:left">
    <!-- INCLUDE inc.auction.check -->
  </td>

  <td colspan="2">
    <div style="float:left;height:1.5em;line-height:1.5em;overflow:hidden">
      <a name="{ITEM}" class="ebay" href="{ITEMURL}"
         title="{RAW.NAME}" onmouseover="Tip('{js:RAW.NAME}')">{NAME}</a>
    </div>
    {iif:INVALID,"(Invalid Item)"}
  </td>

  <td style="text-align:right">
    <!-- IF CURRENCY != CONST.MODULE.CURRENCY --><tt>{CURRENCY}</tt><br><!-- ENDIF -->
    <tt>{currency:BID}</tt>
  </td>

  <td style="text-align:center">
    <tt>{BIDS}</tt>
  </td>

  <td style="text-align:right">
    <tt>{currency:MYBID,FALSE}</tt>
  </td>

  <td style="text-align:center;white-space:nowrap">
    <!-- INCLUDE inc.auction.delete -->
  </td>
