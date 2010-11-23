<!-- COMMENT
/*
 *
 */
-->

{"50" > THUMBSIZE}

<td style="vertical-align:top">
  <a name="{ITEM}" href="{ITEMURL}" title="{RAW.NAME}">{NAME}</a>
  <br>
  <tt style="float:left">{END}</tt>
  <br>
  <tt>{currency:BID,"--",CURRENCY}
  <!-- IF SHIPPING = "FREE" --> / [[Auction.ShippingFree]]<!-- ELSEIF SHIPPING --> + {currency:SHIPPING,"--",CURRENCY}<!-- ENDIF --></tt>
  <br>
  {BIDDER}
</td>

<td style="text-align:center">
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

<td style="text-align:center;white-space:nowrap">
  <!-- INCLUDE inc.auction.edit -->
  <br>
  <!-- INCLUDE inc.auction.delete -->
</td>
