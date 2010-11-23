<!--
/**
 *
 */
-->

{"25" > THUMBSIZE}

<td colspan="2">
  <a name="{ITEM}" href="{ITEMURL}">{NAME}</a>
  {iif:INVALID,"&nbsp;(Invalid Item)"}
  <br>
  <tt>{currency:BID,"--",CURRENCY} <!-- IF BIDS -->&nbsp;({BIDS})<!-- ENDIF --></tt>
  <br>
  <small>{BIDDER}</small>
</td>

<td style="text-align:center">
  <!-- INCLUDE inc.auction.delete -->
</td>
