<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<form method="post">
  {fh:"module","auction"}
  {fh:"action","cleanup"}

  <p class="b MB_confirm">[[Auction.ConfirmCleanupAuctions]]</p>

  <div class="MB_buttons">
    <input class="button" type="submit" name="confirm" value="[[Auction.Yes]]">
    <input class="button" type="submit" name="cancel"  value="[[Auction.No]]"
           onclick="Modalbox.hide(); return false">
  </div>

</form>
