<!-- COMMENT
/*
 *
 */
-->

{translate:"Auction.ConfirmDeleteAuction", "", ITEM > CONFIRM_MSG}

<a href="{DELETEURL}"
   <!-- IF CONST.MODULE.POPUPEDIT -->
   onclick="return DeleteAuction('{ITEM}','{CONFIRM_MSG|striptags}')"
   <!-- ENDIF -->
  >
  <img class="icon" src="layout/default/images/delete.gif" alt="D"
       title="[[Auction.DeleteAuction|striptags|quote]]"
       onmouseover="Tip('{js:[[Auction.DeleteAuction]]}')">
</a>
