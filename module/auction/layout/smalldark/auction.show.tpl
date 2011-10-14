<!-- COMMENT
/*
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

  <div style="clear:both" class="{iif:ENDTS,"running","ended"}">
    <span onmouseover="Tip('<img noimagesize src=\'html/image.php?i={IMGURL}&amp;m={IMGSIZE}\'>',
                           OPACITY,100,WIDTH,{IMGWIDTH},OFFSETY,-{IMGHEIGHT}/2-7,PADDING,0,
                           BORDERCOLOR,'#C0C0C0',BORDERWIDTH,10)">
      <tt style="float:left">{END}</tt>
    </span>
    <!-- IF ENDTS -->
    <span style="float:right"><!-- INCLUDE inc.remain --></span>
    <!-- ENDIF -->
  </div>
  <div class="comment" style="clear:both">{COMMENT}</div>
</td>

<td style="text-align:right">
  <!-- IF CURRENCY <> CONST.MODULE.CURRENCY --><tt>{CURRENCY}&nbsp;</tt><!-- ENDIF -->
  <tt>{currency:BID}</tt><br>
  <!-- IF SHIPPING = "FREE" -->
    <tt>{replace:[[Auction.ShippingFree]]," ","&nbsp;"}</tt>
  <!-- ELSEIF SHIPPING -->
    <tt>{currency:SHIPPING,"--"}</tt>
  <!-- ENDIF -->
</td>

<td style="text-align:center">
  <!-- IF BIN -->
  <img alt="Buy now" title="Buy now" onmouseover="Tip('Buy now')"
       style="margin-right:5px"
       src="module/auction/layout/default/images/buynow.png">
  <!-- ENDIF -->
  <!-- IF BIDS > "0" -->
    <tt>{BIDS}</tt><!-- IF BIDDER --> / <small>{BIDDER}</small><!-- ENDIF -->
  <!-- ELSE -->
    <tt>[[Auction.NoBids]]</tt>
  <!-- ENDIF -->
  <!-- IF DUTCH > "1" -->
    <br>
    <small>{DUTCH} [[Auction.Available]]</small>
  <!-- ENDIF -->
</td>

<td style="text-align:right">
  <tt>{currency:MYBID,FALSE}</tt>
</td>

<td style="text-align:center;white-space:nowrap">
  <!-- INCLUDE inc.auction.edit -->
  <br>
  <!-- INCLUDE inc.auction.delete -->
</td>
