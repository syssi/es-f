<!-- COMMENT
/*
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<td style="text-align:left">
  <!-- INCLUDE inc.auction.check -->
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
  <tt>{currency:BID,"--",CURRENCY}</tt><br>
  <!-- IF SHIPPING = "FREE" -->
    <tt>[[Auction.ShippingFree]]</tt>
  <!-- ELSEIF SHIPPING -->
    <tt>{currency:SHIPPING,"--",CURRENCY}</tt>
    <br>
    {add:BID,SHIPPING > PRICETOTAL}
    <!-- &sum; -->
    <tt style="font-weight:bold"> {currency:PRICETOTAL,,CURRENCY}</tt>
  <!-- ENDIF -->
</td>

<td style="text-align:center">
  <!-- IF BIN -->
  <img alt="Buy now" title="Buy now" onmouseover="Tip('Buy now')"
       style="margin-bottom:5px"
       src="module/auction/layout/default/images/buynow.png">
  <br>
  <!-- ENDIF -->
  <!-- IF BIDS > "0" -->
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
