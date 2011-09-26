<!-- COMMENT
/*
 *
 */
-->

<tr class="th th2">
  <th>
    <script type="text/javascript">
    if (!"{CONST.MODULE.AUCTION.ADDSTATIC}") {
      document.write("<img id=\"addimg\" style=\"display:none\" class=\"clickable\" " +
                     "src=\"module/auction/layout/default/images/plus.gif\" " +
                     "onmouseover=\"Tip('Add auctions')\" " +
                     "onclick=\"return CreatePopupWindow('addauctions')\">");
      addLoadEvent(function() { $('rowadd').hide(); $('addimg').show() });
    }
    </script>
  </th>

  <th colspan="2">
    [[Auction.Auction]] &nbsp;
  </th>

  <th style="text-align:right">
    [[Auction.Price]]<br>[[Auction.Shipping]]
  </th>

  <th>
    [[Auction.NoOfBids]]<br>[[Auction.HighBidder]]
  </th>

  <th>
    [[Auction.AuctionBid|nl2br]]
  </th>

  <th>
    <img style="width:20px;height:20px" width="20" height="20"
         src="layout/default/images/tool.gif" alt="[[Auction.Actions]]"
         title="[[Auction.Actions]] - [[Auction.Auction]]"
         onmouseover="Tip('[[Auction.Actions]] - [[Auction.Auction]]')">
  </th>

  <th style="border-left:dashed gray 1px">
    <img style="width:24px;height:24px" width="24" height="24"
         src="{$IMGDIR}/mybid.gif" alt="[[Auction.MyBid]]"
         title="[[Auction.Quantity]] / [[Auction.MyBid]]"
         onmouseover="Tip('[[Auction.Quantity]] / [[Auction.MyBid]]')">
  </th>

  <th>
    <img style="width:20px;height:20px" width="20" height="20"
         src="layout/default/images/tool.gif" alt="[[Auction.Actions]]"
         title="[[Auction.Actions]] - [[Auction.Group]]"
         onmouseover="Tip('[[Auction.Actions]] - [[Auction.Group]]')">
  </th>
</tr>
