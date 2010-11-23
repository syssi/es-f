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
    <img id="drag_{ITEM}" class="draggable" style="width:20px;height:20px;margin-bottom:15px"
         src="{__$IMGDIR}/dragger.gif" alt="{ITEM}" title="[[Auction.Dragger]]"
         onmouseover="Tip('{js:[[Auction.Dragger]]}',WIDTH,200)">
    <script type="text/javascript">
      // <![CDATA[
      addLoadEvent(function() {
        new Draggable('drag_{ITEM}', \{revert: true\});
      });
      // ]]>
    </script>
    <br>
    <input type="checkbox" name="auctions[]" value="{ITEM}"
           onclick="$('tr_{ITEM}').toggleClassName('selected',this.checked)">
  </td>

  {"50" > THUMBSIZE}
  <td style="text-align:center;width:{THUMBSIZE}px">
    <!-- INCLUDE inc.image -->
  </td>

  <td style="vertical-align:top">
    <div style="float:left;height:1.5em;line-height:1.5em;overflow:hidden">
      <a name="{ITEM}" class="ebay" href="{ITEMURL}"
         title="{RAW.NAME}" onmouseover="Tip('{js:RAW.NAME}')">{NAME}</a>
    </div>

    <div style="clear:both" class="{iif:ENDTS,"running","ended"}">
      <tt style="float:left">{END}</tt>
      <!-- IF ENDTS -->
      <span style="float:right"><!-- INCLUDE inc.remain --></span>
      <!-- ENDIF -->
    </div>

    <div style="clear:both" class="comment">
      {iif:INVALID,"<strong>(Invalid Item)</strong><br>"}
      {COMMENT}
    </div>

    <span style="float:left">
      <!-- INCLUDE inc.seller -->
    </span>
    <span style="float:right;margin:5px 0 0 5px">
      <!-- ## INCLUDE inc.amazon -->
    </span>
  </td>

  <td style="text-align:right">
    <!-- IF BIN -->
    <img alt="Buy now" src="{$IMGDIR}/{CONST.ESF.LANGUAGE}/{BIN}.gif"><br>
    <!-- ENDIF -->
    <tt>{currency:BID,,CURRENCY}</tt><br>
    <!-- IF SHIPPING = "FREE" -->
      <tt>[[Auction.ShippingFree]]</tt>
    <!-- ELSEIF SHIPPING -->
      <tt>{currency:SHIPPING,"--",CURRENCY}</tt>
      <br>
      {add:BID,SHIPPING > PRICETOTAL}
      <strong><tt>{currency:PRICETOTAL,,CURRENCY}</tt></strong>
    <!-- ENDIF -->
  </td>

  <td style="text-align:center">
    <!-- IF BIDS -->
      <tt>{BIDS}</tt>
      <br>
      <small>{BIDDER}</small>
    <!-- ELSE -->
      <tt>[[Auction.NoBids]]</tt>
    <!-- ENDIF -->
    <!-- IF DUTCH > "1" -->
      <br>
      <small>{DUTCH} [[Auction.Available]]</small>
    <!-- ENDIF -->
  </td>

  <td style="text-align:right">
    <tt>{currency:MYBID,"&nbsp;"}</tt>
  </td>

  <td style="text-align:center;white-space:nowrap">
    <!-- INCLUDE inc.auction.edit -->
    <br>
    <!-- INCLUDE inc.auction.delete -->
  </td>
