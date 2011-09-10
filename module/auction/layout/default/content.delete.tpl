<!-- COMMENT
/*
 *
 */
-->

<div style="text-align:center">
  <div id="content" style="width:500px;margin:auto;padding-bottom:1em">

  <form action="{server:"PHP_SELF"}" method="post">
    {fh:"module","auction"}
    {fh:"action","delete"}
    {fh:"item",ITEM}

    <h3 class="c">[[Auction.DeleteAuction]]: {ITEM}</h3>

    <div style="padding-bottom:1em">
      {translate:"Auction.ConfirmDeleteAuction", ITEM, RAW.NAME}
    </div>

    <input class="button" type="submit" name="confirm" value="[[Auction.Yes]]">
    &nbsp;&nbsp;&nbsp;
    <input class="button" type="submit" name="cancel" value="[[Auction.No]]">

  </form>

  </div>
</div>
