<!--
/**
 *
 */
-->

<form action="{FORMACTION}" style="display:inline" method="post">
{fh:"module","auction"}
{fh:"action","add"}

<tbody id="rowadd" class="noprint">

<tr class="tr1" style="border-bottom:dashed gray 1px;height:3em">
<td colspan="5">

  <input name="auctions" class="input" style="width:100%">
  <br>

  <label for="q">[[Auction.Quantity]]</label>:
  <input style="width:3em" class="input num" type="text" id="q" name="q" value="1">
  &nbsp;
  <label for="b">[[Auction.Bid]]</label>:
  <input style="width:3em" class="input num" type="text" id="b" name="b">

  <div style="float:right">
    <input class="icon" type="image" name="save" src="layout/default/images/save.gif"
           title="[[Auction.Save]]" alt="[[[Auction.Save]]]">
    &nbsp;
    <input class="icon" type="image" name="start" src="layout/default/images/start.gif"
           title="[[Auction.Save]] &amp; [[Auction.Start]]"
           alt="[[[Auction.Start]]]">
  </div>

</td>
</tr>

</tbody>

</form>
