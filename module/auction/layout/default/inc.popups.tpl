<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<!-- BEGIN AUCTIONS -->

  <!-- IF !ENDED -->
  <div id="AuctionEdit{ITEM}" style="display:none">
    <!-- INCLUDE form.editauction -->
  </div>
  <!-- ENDIF !ENDED -->

  <!-- IF GROUP --><!-- IF !GROUP.ENDED -->
  <div id="GroupEdit{GROUP.NAME|hash}" style="display:none">
    <!-- INCLUDE form.editgroup -->
  </div>
  <!-- ENDIF --><!-- ENDIF -->

<!-- END AUCTIONS -->

<div id="CleanupAuctions" style="display:none">
  <form action="{server:"PHP_SELF"}" method="post">
    {fh:"module","auction"}
    {fh:"action","cleanup"}

    <p class="b MB_confirm">[[Auction.ConfirmCleanupAuctions]]</p>

    <div class="MB_buttons">
      <input class="button" type="submit" name="confirm" value="[[Auction.Yes]]">
      <input class="button" type="submit" name="cancel"  value="[[Auction.No]]"
             onclick="Modalbox.hide(); return false">
    </div>

  </form>
</div>

<script type="text/javascript">
  var AuctionDeleteTitle = "[[Auction.DeleteAuction|striptags|quote]]";
  var AuctionDeleteItem  = 0;
</script>

<div id="DeleteAuction" style="display:none">

  <p id="AuctionDeleteMessage" class="MB_confirm"></p>

  <div class="MB_buttons">
    <input class="button" type="submit" name="confirm" value="[[Auction.Yes]]"
           onclick="ajaxDeleteAuction(AuctionDeleteItem); Modalbox.hide(); return false">
    <input class="button" type="submit" name="cancel" value="[[Auction.No]]"
           onclick="Modalbox.hide(); return false">
  </div>

</div>