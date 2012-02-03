<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<tr class="th th2">
  <th>
    <a href="?module=auction&amp;action=add"
       onclick="Modalbox.show('?module=auction&amp;action=ajaxadd',\{title:'[[Auction.AddAuctions]]'\}); return false">
      <img class="clickable" src="module/auction/layout/default/images/plus.gif" alt="+"
           title="[[Auction.AddAuctions|striptags|quote]]"
           data-tip="[[Auction.AddAuctions|striptags|quote]]"
           onmouseover="Tip(this)">
    </a>
  </th>

  <th colspan="2">
    [[Auction.Auction]] &nbsp;
  </th>

  <th style="text-align:right">
    [[Auction.Price]]<br>[[Auction.Shipping]]
  </th>

  <th>
    [[Auction.NoOfBids]]
  </th>

  <th>
    [[Auction.AuctionBid|nl2br]]
  </th>

  <th>
    <img style="width:20px;height:20px" width="20" height="20"
         src="layout/default/images/tool.gif" alt="[[Auction.Actions]]"
         title="[[Auction.Actions|striptags|quote]] - [[Auction.Auction|striptags|quote]]"
         data-tip="[[Auction.Actions|striptags|quote]] - [[Auction.Auction|striptags|quote]]"
         onmouseover="Tip(this)">
  </th>

  <th style="border-left:dashed gray 1px">
    <img style="width:24px;height:24px" width="24" height="24"
         src="{$IMGDIR}/mybid.gif" alt="[[Auction.MyBid]]"
         title="[[Auction.Quantity|striptags|quote]] / [[Auction.MyBid|striptags|quote]]"
         data-tip="[[Auction.Quantity|striptags|quote]] / [[Auction.MyBid|striptags|quote]]"
         onmouseover="Tip(this)">
  </th>

  <th>
    <img style="width:20px;height:20px" width="20" height="20"
         src="layout/default/images/tool.gif" alt="[[Auction.Actions]]"
         title="[[Auction.Actions|striptags|quote]] - [[Auction.Group|striptags|quote]]"
         data-tip="[[Auction.Actions|striptags|quote]] - [[Auction.Group|striptags|quote]]"
         onmouseover="Tip(this)">
  </th>
</tr>
