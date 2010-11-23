<!-- COMMENT
/*
 *
 */
-->

<form action="{server:"PHP_SELF"}" method="post">
{fh:"module","auction"}
{fh:"action","cleanup"}
{fh:"ajax",POPUPFORM}

<div class="editform">

  <!-- IF !POPUPFORM -->
  <h2 class="c">[[Auction.CleanupAuctions]]</h2>
  <!-- ENDIF -->

  <div class="c" style="padding:10px">
  
    <div style="line-height:2em">
    [[Auction.ConfirmCleanupAuctions]]
    </div>

  </div>

  <div class="c">
    <input class="button" type="submit" name="confirm" value="[[Auction.Yes]]"
           <!-- IF POPUPFORM -->
           onclick="return !RemovePopupWindow('PopupCleanupAuctions',false)"
           <!-- ENDIF -->
          >
    &nbsp;&nbsp;&nbsp;
    <input class="button" type="submit" name="cancel" value="[[Auction.No]]"
           <!-- IF POPUPFORM -->
           onclick="return RemovePopupWindow('PopupCleanupAuctions')"
           <!-- ENDIF -->
          >
  </div>

</div>

</form>
