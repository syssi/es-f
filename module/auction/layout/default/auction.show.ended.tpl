<!--
/**
 *
 */
-->

<td style="text-align:left">
  <!-- INCLUDE inc.auction.check -->
</td>

{"25" > THUMBSIZE}
<td style="text-align:center;width:{THUMBSIZE}px">
  <!-- INCLUDE inc.image -->
</td>

<td>
  <div style="height:1.5em;line-height:1.5em;overflow:hidden">
    <a name="{ITEM}" class="ebay" href="{ITEMURL}" title="{RAW.NAME}"
       onmouseover="Tip('{js:RAW.NAME}')">{NAME}</a>
  </div>
  {iif:INVALID,"(Invalid Item)"}
</td>

<td style="text-align:right">
  <tt>{currency:BID,"--",CURRENCY}</tt>
</td>

<td style="text-align:center">
  <tt>{BIDS}</tt>
</td>

<td style="text-align:right">
  <tt>{currency:MYBID,"&nbsp;"}</tt>
</td>

<td style="text-align:center;white-space:nowrap">
  <!-- INCLUDE inc.auction.delete -->
</td>
