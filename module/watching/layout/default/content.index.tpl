<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<form id="watchingform" name="watchingform" action="{FORMACTION}" method="post">
{fh:"module","auction"}
{fh:"action","add"}

<table id="watchlist" style="width:100%">
<tbody>
<tr>
  <th style="text-align:center">
    <input id="ToggleWatched" style="display:none" type="checkbox"
           onclick="ToggleAuctions(this.checked)"
           title="[[WATCHING.TOGGLE]]"
           onmouseover="Tip('[[WATCHING.TOGGLE]]')">
  </th>
  <th>[[WATCHING.AUCTION]]</th>
  <th>[[WATCHING.REMAINING_TIME]]</th>
  <th>[[WATCHING.PRICE]]</th>
  <th>[[WATCHING.SHIPPING]]</th>
  <th>[[WATCHING.SELLER]]</th>
  <th style="text-align:center">[[WATCHING.NO_OF_BIDS]]</th>
</tr>

<!-- BEGIN AUCTIONS -->
<tr id="tr_{ITEM}" class="{cycle:"class","tr1","tr2"}">
  <td>
    <input type="checkbox" name="auctions[]" value="{ITEM}" class="cb"
           {iif:ACTIVE,"disabled=\"disabled\""}
           onclick="ToggleClass('tr_{ITEM}','selected',this.checked)">
  </td>
  <td>
    <a class="ebay" href="{ITEMURL}">{DESCRIPTION}</a>
    <!-- IF BIDS = "--" -->
    <br><small><i>[[WATCHING.BUY_IT_NOW]]</i></small>
    <!-- ENDIF -->
  </td>
  <td>{TIME_LEFT}</td>
  <td>{PRICE}</td>
  <td>{SHIPPING}</td>
  <td>{SELLER}</td>
  <td style="text-align:center">{BIDS}</td>
</tr>
<!-- END AUCTIONS -->

<tr>
  <td style="padding:7px;border-top:dashed black 1px" colspan="5">
    [[WATCHING.ADD_MARKED]]
  </td>
  <td style="padding:7px;border-top:dashed black 1px">
    [[WATCHING.QUANTITY_BID]]
  </td>
  <td style="text-align:center;border-top:dashed black 1px" rowspan="2">
    <input type="image" class="icon" name="save" src="layout/default/images/save.gif"
           alt="[[[WATCHING.SAVE]]]" title="[[WATCHING.SAVE]]"
           onmouseover="Tip('[[WATCHING.SAVE]]')">
    <br>
    <input type="image" class="icon" name="start" src="layout/default/images/start.gif"
           alt="[[[WATCHING.SAVE]] &amp; [[WATCHING.START]]]"
           title="[[WATCHING.SAVE]] &amp; [[WATCHING.START]]"
           onmouseover="Tip('[[WATCHING.SAVE]] &amp; [[WATCHING.START]]')">
  </td>
</tr>

<tr>
  <td colspan="5">
    <div style="padding:2px;float:left">
      [[WATCHING.CATEGORY]]:
      <select id="CategorySelect" name="category">{options:CATEGORIES}</select>
      [[WATCHING.OR]]
      <input class="input" type="text" name="categorynew" size="10">
    </div>
    <div style="padding:2px;text-align:right">
      [[WATCHING.GROUP]]:
      <select name="group" onchange="SetGroupCategory(this.value)">{options:GROUPS}</select>
      [[WATCHING.OR]]
      <input class="input" type="text" name="groupnew" size="10">
    </div>
  </td>

  <td>
    <input style="width:3em" class="input num" type="text" name="q" value="1">
    [[WATCHING.PIECE]] á
    <input style="width:3em" class="input num" type="text" name="b">
  </td>
</tr>
</tbody>
</table>

</form>

<script type="text/javascript">
  // <![CDATA[
  var GetCategoryFromGroup = '{GETCATEGORYFROMGROUP}';
  FastInit.addOnLoad(function() { $('ToggleWatched').show() });
  // ]]>
</script>
