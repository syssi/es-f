<!--
/**
 *
 */
-->

<h3>{NAME}</h3>

<div id="form">

<form id="snipeform" action="{FORMACTION}" method="post"
      onsubmit="return SnipeAuction()">
{fh:"module","snipe"}
{fh:"action","save"}
{fh:"item",ITEM}

<table id="snipe" style="width:100%">

<tr class="{cycle:"class","tr1","tr2"}">
  <td>[[Snipe.Category]]</td>
  <td>:</td>
  <td>
    <select id="CategorySelect" name="category">
      {options:CATEGORIES,CATEGORY}
    </select>
  </td>
  <td>
    [[Snipe.Or]]
  </td>
  <td>
    {ft:"categorynew",,"input","style=\"width:97%\""}
  </td>
</tr>

<tr class="{cycle:"class","tr1","tr2"}">
  <td>[[Snipe.Group]]</td>
  <td>:</td>
  <td>
    <select name="group" onchange="SetGroupCategory(this.value)">
      {options:GROUPS,GROUP}
    </select>
  </td>
  <td>
    [[Snipe.Or]]
  </td>
  <td style="width:90%">
    {ft:"groupnew",,"input","style=\"width:97%\""}
  </td>
</tr>

<tr class="{cycle:"class","tr1","tr2"}">
  <td>
    [[Snipe.Shipping]]
  </td>
  <td>:</td>
  <td colspan="3">
    {ft:"shipping",,"input num","size=\"3\""}
  </td>
</tr>

<tr class="{cycle:"class","tr1","tr2"}">
  <td>
    [[Snipe.GroupCount]] / [[Snipe.GroupBid]]
  </td>
  <td>:</td>
  <td colspan="3">
    {ft:"q",,"input num","size=\"3\""} / {ft:"b",,"input num","size=\"8\""}
  </td>
</tr>

<tr class="{cycle:"class","tr1","tr2"}">
  <td style="width:50%">[[Snipe.Comment]]</td>
  <td>:</td>
  <td colspan="3">
    {ft:"comment",COMMENT,"input","style=\"width:98%\""}
  </td>
</tr>

<tr class="{cycle:"class","tr1","tr2"}">
  <td>
    [[Snipe.AuctionBid]]
    <br>
    <small>[[Snipe.DifferentFromGroup]]</small>
  </td>
  <td>:</td>
  <td colspan="3">
    {ft:"mybid",,"input num","size=\"8\""}
  </td>
</tr>

<tr class="{cycle:"class","tr1","tr2"}">
  <td>
    [[Snipe.BidNow]]
    <br>
    <small>[[Snipe.UseToBreakBuyNow]]</small>
  </td>
  <td>:</td>
  <td colspan="3">
    {ft:"now",,"input num","size=\"8\""}
    &nbsp;&nbsp;&nbsp;
    <small><tt>esniper -s now ...</tt></small>
  </td>
</tr>

<tr>
  <td class="nowrap spacer c" colspan="5">
    <input class="button" style="width:150px" type="submit" name="start"
           value="[[Snipe.Start]]" title="[[Snipe.Save]] &amp; [[Snipe.Start]]"
           onmouseover="Tip('[[Snipe.Save]] & [[Snipe.Start]]')"
           onclick="StartButton=true">
    &nbsp;&nbsp;&nbsp;
    <input class="button" style="width:150px" type="submit"
           value="[[Snipe.Save]]">
  </td>
</tr>

</table>

</form>

</div>

<p id="SnipeMsg" style="display:none">[[Snipe.PleaseWait]]</p>
