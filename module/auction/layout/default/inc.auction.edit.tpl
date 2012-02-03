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
         data-tip="[[Auction.Refresh|striptags|quote]]"
         onmouseover="Tip(this)">
  </a>
  <br>
  <!-- ENDIF -->

  <a href="{EDITAUCTIONURL}"
     onclick="Modalbox.show('?module=auction&amp;action=ajaxeditauction&amp;item={ITEM}',\{title:'[[Auction.EditAuction|striptags|quote]]: {ITEM}'\}); return false">
    <img class="icon" src="layout/default/images/edit.gif" alt="E"
         title="[[Auction.EditAuction|striptags|quote]]"
         data-tip="[[Auction.EditAuction|striptags|quote]]"
         onmouseover="Tip(this)">
  </a>

<!-- ELSE -->

  <img class="icon" src="layout/default/images/edit-d.gif" alt="">

<!-- ENDIF -->
