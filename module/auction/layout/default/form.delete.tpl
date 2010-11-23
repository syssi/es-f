<!-- COMMENT
/*
 *
 */
-->

<form action="{server:"PHP_SELF"}" method="post">
{fh:"module","auction"}
{fh:"action","delete"}
{fh:"item",ITEM}
{fh:"ajax",POPUPFORM}

<div class="editform">

  <!-- IF !POPUPFORM -->
  <h2 class="c">[[Auction.DeleteAuction]]: {ITEM}</h2>
  <!-- ENDIF -->

  <div class="c" style="padding:10px">

    {translate:"Auction.ConfirmDeleteAuction", ITEM, RAW.NAME}

    <br><br>

    <input class="button" type="submit" name="confirm" value="[[Auction.Yes]]"
           <!-- IF POPUPFORM -->
           onclick="RemovePopupWindow('PopupAuctionDelete{ITEM}',false); return ajaxDeleteAuction('{ITEM}');"
           <!-- ENDIF -->
          >
    &nbsp;&nbsp;&nbsp;
    <input class="button" type="submit" name="cancel" value="[[Auction.No]]"
           <!-- IF POPUPFORM -->
           onclick="return RemovePopupWindow('PopupAuctionDelete{ITEM}')"
           <!-- ENDIF -->
          >
  </div>

</div>

</form>
