<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<div class="c">

<form method="post">
  {fh:"module","auction"}
  {fh:"action","delete"}
  {fh:"item",ITEM}

  <!-- IF !AJAX -->
  <h3>[[Auction.DeleteAuction]]: {ITEM}</h3>
  <!-- ENDIF -->

  <div style="margin:1em">
    {translate:"Auction.ConfirmDeleteAuction", RAW.NAME}
  </div>

  <div class="MB_buttons">
    <input class="button" type="submit" name="confirm" value="[[Auction.Yes]]"
           <!-- IF AJAX -->
           onclick="ajaxDeleteAuction('{ITEM}'); Modalbox.hide(); return false"
           <!-- ENDIF -->
           >
    <input class="button" type="submit" name="cancel" value="[[Auction.No]]"
           <!-- IF AJAX -->onclick="Modalbox.hide(); return false"<!-- ENDIF -->>
  </div>

</form>

</div>