<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<!-- IF EDITAUCTIONURL -->

  <!-- IF CONST.MODULE.REFRESHBUTTONS = "3" -->
  <a href="?module=auction&amp;action=mrefresh&amp;auctions={ITEM}">
    <img class="icon" src="layout/default/images/refresh.gif" alt="R"
         title="[[Auction.Refresh|striptags|quote]]"
         onmouseover="Tip('{js:[[Auction.Refresh]]}')">
  </a>
  <br>
  <!-- ENDIF -->

  <a href="{EDITAUCTIONURL}" title="[[Auction.EditAuction|striptags|quote]]"
     onclick="Modalbox.show($('AuctionEdit{ITEM}'),{ title:this.title+': {ITEM}' }); return false">
    <img class="icon" src="layout/default/images/edit.gif" alt="E"
         title="[[Auction.EditAuction]]"
         onmouseover="Tip('[[Auction.EditAuction]]');">
  </a>
  
<!-- ELSE -->

  <img class="icon" src="layout/default/images/edit-d.gif" alt="">

<!-- ENDIF -->
