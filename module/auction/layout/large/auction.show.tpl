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

<td>
  <!-- INCLUDE inc.auction.check -->
</td>

<td colspan="5" style="padding:0">

  <table style="width:100%">
  <tr>
    <td rowspan="3" style="text-align:center">
      {"100" > THUMBSIZE}
      <div style="width:{THUMBSIZE}px">
        <!-- INCLUDE inc.image -->
      </div>
    </td>
    <td colspan="2" style="width:99%">
      <div style="float:left;height:1.5em;line-height:1.5em;overflow:hidden">
        <a style="font-size:1.1em;font-weight:bold"
           name="{ITEM}" class="ebay" href="{ITEMURL}"
           title="{RAW.NAME}" onmouseover="Tip('{js:RAW.NAME}')">{NAME}</a>
      </div>
    </td>
  </tr>
  <tr style="white-space:nowrap">
    <td style="vertical-align:top">
      <div class="{iif:ENDTS,"running","ended"}">
        <!-- IF ENDTS -->
        <span style="font-size:130%;font-weight:bold">
          <!-- INCLUDE inc.remain -->
        </span>
        <!-- ENDIF -->
        <br><br>
        <tt>{END}</tt>
      </div>
    </td>
    <td style="width:50%">
      <table>
      <tr>
        <td style="text-align:right">
          [[Auction.Price]] + [[Auction.Shipping]] :
        </td>
        <td>
          <!-- IF BIN -->
          <img alt="Buy now" src="{$IMGDIR}/{CONST.ESF.LANGUAGE}/{BIN}.gif">
          <!-- ENDIF -->
          <!-- IF CURRENCY <> CONST.MODULE.CURRENCY -->{CURRENCY}&nbsp;<!-- ENDIF -->
          {currency:BID}
          <!-- IF SHIPPING = "FREE" -->
            ([[Auction.ShippingFree]])
          <!-- ELSE -->
            {add:BID,SHIPPING > PRICETOTAL}
            {currency:SHIPPING,"--" > SHIPPING}
            <!-- IF SHIPPING != "--" -->
              + {SHIPPING} = <strong>{currency:PRICETOTAL,,CURRENCY}</strong>
            <!-- ENDIF -->
          <!-- ENDIF -->
        </td>
      </tr>
      <tr>
        <td style="text-align:right">
          [[Auction.NoOfBids]] ([[Auction.HighBidder]]) :
        </td>
        <td>
          <!-- IF BIDS > "0" -->
            {BIDS}<!-- IF BIDDER --> ({BIDDER})<!-- ENDIF -->
          <!-- ELSE -->
            [[Auction.NoBids]]
          <!-- ENDIF -->
        </td>
      </tr>
      <tr>
        <td style="text-align:right"><!-- IF MYBID -->[[Auction.MyBid]]:<!-- ENDIF --></td>
        <td><!-- IF MYBID --><tt>{currency:MYBID,FALSE}</tt><!-- ENDIF --></td>
      </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <div class="comment">{COMMENT}</div>
      <!-- IF DUTCH > "1" -->
      <div style="float:right">{DUTCH} [[Auction.Available]]</div>
      <!-- ENDIF -->
      <!-- INCLUDE inc.seller -->
    </td>
  </tr>
  </table>
</td>

<td style="text-align:center;white-space:nowrap">
  <!-- INCLUDE inc.auction.edit -->
  <br>
  <!-- INCLUDE inc.auction.delete -->
</td>
