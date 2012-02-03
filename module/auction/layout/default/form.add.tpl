<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<form method="post">
  {fh:"module","auction"}
  {fh:"action","add"}

  <label class="MB_label" for="CategorySelect">[[Auction.Auctions]]{help:"AuctionHelp.AddMultipleAuctions"}</label>
  <div class="MB_inputs">
    <input name="auctions" class="input" style="width:97%"
           required="required" placeholder="[[Auction.AddAuctionsHint]]">
  </div>

  <label class="MB_label" for="CategorySelect">[[Auction.Category]]{help:"AuctionHelp.Category"}</label>
  <div class="MB_inputs">
    <select id="CategorySelect" name="category">{options:CONST.CATEGORIES}</select>
    &nbsp; <label for="categorynew">[[Auction.Or]]</label> &nbsp;
    <input id="categorynew" name="categorynew" class="input"
           placeholder="[[Auction.NewCategoryHint]]" size="10">
  </div>

  <label class="MB_label" for="GroupSelect">[[Auction.Group]]{help:"AuctionHelp.Group"}</label>
  <div class="MB_inputs">
    <select id="GroupSelect" name="group" onchange="SetGroupCategory(this.value)">{options:CONST.GROUPS}</select>
    &nbsp; <label for="groupnew">[[Auction.Or]]</label> &nbsp;
    <input id="groupnew" name="groupnew" class="input"
           placeholder="[[Auction.NewGroupHint]]" size="10">
  </div>

  <label class="MB_label" for="q">[[Auction.Quantity]]</label>
  <div class="MB_inputs">
    <input type="number" id="q" name="q" style="width:3em" class="input num"
           value="1" placeholder="[[Auction.Quantity]]" required="required">
  </div>

  <label class="MB_label" for="b">[[Auction.Bid]]</label>
  <div class="MB_inputs">
    <input type="float" id="b" name="b" style="width:3em" class="input num"
           placeholder="[[Auction.Bid]]">
  </div>

  <div class="MB_buttons">
    <input class="button" type="submit" name="save" value="[[Auction.Save]]">
    <input class="button" type="submit" name="start"
           value="[[Auction.Save]] &amp; [[Auction.Start]]">
  </div>

</form>
