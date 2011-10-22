<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

{translate:"Auction.ConfirmDeleteAuction", RAW.NAME > MSG}

<a href="{DELETEURL}"
   onclick="DeleteAuction('{ITEM}','{MSG|quote}'); return false">
  <img class="icon" src="layout/default/images/delete.gif" alt="X"
       title="[[Auction.DeleteAuction|striptags|quote]]"
       onmouseover="Tip('{js:[[Auction.DeleteAuction]]}')">
</a>
