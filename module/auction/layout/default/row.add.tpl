<!-- COMMENT
/*
 *
 */
-->

<div id="addauctions" class="dialog" style="width:30em;display:none">

<form action="{FORMACTION}" style="display:inline" method="post">
{fh:"module","auction"}
{fh:"action","add"}

  <p style="width:100%">
    <input name="auctions" class="input" style="width:27em"
           required="required" placeholder="[[Auction.AddAuctionsHint]]">
    {help:"AuctionHelp.AddMultipleAuctions"}
  </p>

  <p>
    <label for="CategorySelect">[[Auction.Category]]</label> {help:"AuctionHelp.Category"}:
    <select id="CategorySelect" name="category">{options:CONST.CATEGORIES}</select>
    &nbsp; <label for="categorynew">[[Auction.Or]]</label> &nbsp;
    <input id="categorynew" name="categorynew" class="input"
           placeholder="[[Auction.NewCategoryHint]]" size="10">
  </p>

  <p>
    <label for="GroupSelect">[[Auction.Group]]</label> {help:"AuctionHelp.Group"}:
    <select id="GroupSelect" name="group" onchange="SetGroupCategory(this.value)">{options:CONST.GROUPS}</select>
    &nbsp; <label for="groupnew">[[Auction.Or]]</label> &nbsp;
    <input id="groupnew" name="groupnew" class="input"
           placeholder="[[Auction.NewGroupHint]]" size="10">
  </p>

  <p>
    <label for="q">[[Auction.Quantity]]</label>:
    <input type="number" id="q" name="q" value="1"
           style="width:3em" class="input num"
           required="required" placeholder="[[Auction.Quantity]]">
  </p>

  <p>
    <label for="b">[[Auction.Bid]]</label>:
    <input style="width:3em" class="input num" type="float" id="b" name="b"
           placeholder="[[Auction.Bid]]">
  </p>

  <p>
    <input class="button" type="submit" name="save" value="[[Auction.Save]]">
    &nbsp;
    <input class="button" type="submit" name="start"
           value="[[Auction.Save]] &amp; [[Auction.Start]]">
  </p>

</form>

</div>

<form action="{FORMACTION}" style="display:inline" method="post">
{fh:"module","auction"}
{fh:"action","add"}

<tbody id="rowadd" class="noprint">

<tr class="tr1" style="border-bottom:dashed gray 1px;height:3em"><td colspan="9">

  <table width="100%" style="width:100%">
  <tr>

    <td style="white-space:nowrap">
      <div style="padding:3px;margin-right:3em">
        <input name="auctions" class="input" style="width:100%"
               required="required" placeholder="[[Auction.AddAuctionsHint]]">
        {help:"AuctionHelp.AddMultipleAuctions"}
      </div>

      <div class="left" style="padding:3px">
        <label for="CategorySelect">[[Auction.Category]]</label> {help:"AuctionHelp.Category"}:
        <select id="CategorySelect" name="category">{options:CONST.CATEGORIES}</select>
        &nbsp; <label for="categorynew">[[Auction.Or]]</label> &nbsp;
        <input id="categorynew" name="categorynew" class="input"
               placeholder="[[Auction.NewCategoryHint]]" size="10">
      </div>

      <div class="right" style="padding:3px">
        <label for="GroupSelect">[[Auction.Group]]</label> {help:"AuctionHelp.Group"}:
        <select id="GroupSelect" name="group" onchange="SetGroupCategory(this.value)">{options:CONST.GROUPS}</select>
        &nbsp; <label for="groupnew">[[Auction.Or]]</label> &nbsp;
        <input id="groupnew" name="groupnew" class="input"
               placeholder="[[Auction.NewGroupHint]]" size="10">
      </div>
    </td>

    <td style="text-align:left;white-space:nowrap">
      <div style="padding:2px">
        <div class="left" style="width:4em"><label for="q">[[Auction.Quantity]]</label>:</div>
        <input type="number" id="q" name="q" value="1"
               style="width:3em" class="input num"
               required="required" placeholder="[[Auction.Quantity]]">
      </div>
      <div style="padding:2px">
        <div class="left" style="width:4em"><label for="b">[[Auction.Bid]]</label>:</div>
        <input style="width:3em" class="input num" type="float" id="b" name="b"
               placeholder="[[Auction.Bid]]">
      </div>
    </td>

    <td style="width:30px">
      <input class="icon" type="image" name="save" src="layout/default/images/save.gif"
             title="[[Auction.Save]]" onmouseover="Tip('{js:[[Auction.Save]]}')"
             alt="[[[Auction.Save]]]">
      <br>
      <input class="icon" type="image" name="start" src="layout/default/images/start.gif"
             title="[[Auction.Save]] &amp; [[Auction.Start]]"
             onmouseover="Tip('{js:[[Auction.Save]]} &amp; {js:[[Auction.Start]]}')"
             alt="[[[Auction.Save]] &amp; [[Auction.Start]]]">
    </td>

  </tr>
  </table>

</td></tr>

</tbody>

</form>
