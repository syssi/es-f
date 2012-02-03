<!--
/*
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<div id="content">

<h2>[[BulkAdd.AuctionsHere]]</h2>

<p>[[BulkAdd.AddListOrFile]]</p>

<form name="bulkform" action="index.php" method="post" enctype="multipart/form-data">
{fh:"module","auction"}
{fh:"action","add"}

<div class="left" style="width:48%">
  <textarea id="auctions" class="input" name="auctions"></textarea>
</div>

<div style="margin-left:50%;align:top">
  <input type="file" name="auctions" class="input" size="40" accept="text/*">

  <table style="margin-top:3em">
    <tr>
      <td><label for="CategorySelect">[[Auction.Category]]</label>{help:"AuctionHelp.Category"}:</td>
      <td><select id="CategorySelect" name="category">{options:CATEGORIES}</select></td>
      <td><label for="categorynew">[[Auction.Or]]</label></td>
      <td>{ft:"categorynew",,"input","id=\"categorynew\" size=\"10\""}</td>
    </tr>

    <tr>
      <td><label for="GroupSelect">[[Auction.Group]]</label>{help:"AuctionHelp.Group"}:</td>
      <td><select id="GroupSelect" name="group" onchange="SetGroupCategory(this.value)">{options:GROUPS}</select></td>
      <td><label for="groupnew">[[Auction.Or]]</label></td>
      <td>{ft:"groupnew",,"input","id=\"groupnew\" size=\"10\""}</td>
    </tr>
  </table>

  <p style="margin-top:3em">
    <input class="button" type="submit" name="bulkadd" value="[[BulkAdd.Save]]">
  </p>
</div>

</form>

<br class="clear">

</div>