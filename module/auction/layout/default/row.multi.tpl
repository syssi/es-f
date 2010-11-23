<!--
/**
 *
 */
-->

<div id="multijs" class="noprint"
     style="display:none;padding:10px 5px;border-top:dashed gray 1px">
  <div class="multijsitem">
    <img style="vertical-align:top;margin:0 10px"
         src="{$IMGDIR}/arrow.gif" alt="[[Auction.MarkedAuctions]]:">
    <select id="multiaction" name="action" onchange="SwitchMulti(this)">
      <option value="">- [[Auction.Actions]] -</option>
      <optgroup label=". [[Auction.Auctions]]">
        <option style="background:url(layout/default/images/edit.gif)    left no-repeat" value="mcategory">[[Auction.MoveToCategory]]</option>
        <option style="background:url(layout/default/images/edit.gif)    left no-repeat" value="mgroup">[[Auction.MoveToGroup]]</option>
        <option style="background:url(layout/default/images/edit.gif)    left no-repeat" value="mimage">[[Auction.SetImage]]</option>
        <option style="background:url(layout/default/images/edit.gif)    left no-repeat" value="mcomment">[[Auction.SetComment]]</option>
        <option style="background:url(layout/default/images/edit.gif)    left no-repeat" value="mbid">[[Auction.SetBid]]</option>
        <option style="background:url(layout/default/images/edit.gif)    left no-repeat" value="mcurrency">[[Auction.SetCurrency]]</option>
        <option style="background:url(layout/default/images/refresh.gif) left no-repeat" value="mrefresh">[[Auction.RefreshAuctions]]</option>
        <option style="background:url(layout/default/images/delete.gif)  left no-repeat" value="mdel">[[Auction.DeleteAuction]]</option>
        <option style="background:url(layout/default/images/delete.gif)  left no-repeat" value="mdelg">[[Auction.DeleteGroup]]</option>
      </optgroup>
      <optgroup label=". [[Auction.Groups]] =&gt; esniper">
        <option style="background:url(layout/default/images/start.gif)   left no-repeat" value="mstart">[[Auction.Start]]</option>
        <option style="background:url(layout/default/images/stop.gif)    left no-repeat" value="mstop">[[Auction.Stop]]</option>
      </optgroup>
    </select>
  </div>
  <div id="dmcategory" class="multijsitem" style="display:none;">
    : {fdd:"category",CONST.CATEGORIES} [[Auction.Or]] <input class="input" name="categorynew" type="text">
  </div>
  <div id="dmgroup" style="display:none;">
    <div class="multijsitem">
      : {fdd:"group",CONST.GROUPS} [[Auction.Or]] <input class="input" name="groupnew" type="text">
      &nbsp; / &nbsp;
      <input class="input num" type="text" name="q" size="2">
      [[Auction.Piece]] &nbsp; à
      <input class="input num" type="text" name="b" size="6">
    </div>
    <div class="multijsitem">
      <input type="image" class="icon" name="start" src="layout/default/images/start.gif"
             alt="[[[Auction.Start]]]" title="[[Auction.Start]]">
    </div>
  </div>
  <div id="dmimage" class="multijsitem" style="display:none;">
    : <input class="input" name="image" type="text" size="60">
  </div>
  <div id="dmcomment" class="multijsitem" style="display:none;">
    : <input class="input" name="comment" type="text" size="40">
  </div>
  <div id="dmbid" class="multijsitem" style="display:none;">
    : <input class="input num" name="mybid" type="text">
  </div>
  <div id="dmcurrency" class="multijsitem" style="display:none;">
    : <input class="input" name="currency" type="text" size="5">
  </div>

  <div id="dmdel" class="multijsitem" style="display:none;">
    <strong class="confirm">{fcb:"mdel_confirm"} [[Auction.ConfirmDelete]]</strong>
  </div>

  <div id="dmdelg" class="multijsitem" style="display:none;">
    <strong class="confirm">{fcb:"mdelg_confirm"} [[Auction.ConfirmDelete]]</strong>
  </div>

  <div id="multibutton" class="multijsitem" style="display:none;">
    <input type="image" class="icon" name="go" src="layout/default/images/save.gif"
           alt="[[[Auction.Save]]]" title="[[Auction.Save]]">
  </div>
</div>

<script type="text/javascript">
  // <![CDATA[
  addLoadEvent(function() {
    $('multijs').show();         // show select
    $('multiaction').value = ''; // set drop down to "- Actions -"
  });
  // ]]>
</script>

<noscript>
<div class="noprint">
<table style="margin-top:5px">
<tr>
  <td style="vertical-align:top" rowspan="12">
    <img src="{$IMGDIR}/arrow.gif"
         style="margin:0 10px" alt="[[Auction.MarkedAuctions]]:">
  </td>
  <td colspan="3">
    <strong>[[Auction.Auctions]]</strong>
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mcategory">
    [[Auction.MoveToCategory]]
  </td>
  <td>
    <select name="category">{options:CONST.CATEGORIES}</select>
  </td>
  <td>
    [[Auction.Or]] <input class="input" name="categorynew" type="text">
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mgroup">
    [[Auction.MoveToGroup]]
  </td>
  <td>
    <select name="group">{options:CONST.GROUPS}</select>
  </td>
  <td>
    [[Auction.Or]] <input class="input" name="groupnew" type="text">
    &nbsp; / &nbsp;
    <input class="input num" type="text" name="q" value="1" size="2">
    [[Auction.Piece]] &nbsp; à
    <input class="input num" type="text" name="b" size="6">
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mimage">
    [[Auction.SetImage]] &nbsp;
  </td>
  <td colspan="2">
    <input class="input" style="width:99%" name="image" type="text">
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mcomment">
    [[Auction.SetComment]] &nbsp;
  </td>
  <td colspan="2">
    <input class="input" style="width:99%" name="comment" type="text">
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mbid">
    [[Auction.SetBid]] &nbsp;
  </td>
  <td colspan="2">
    <input class="input num" name="mybid" type="text">
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mcurrency">
    [[Auction.SetCurrency]] &nbsp;
  </td>
  <td colspan="2">
    <input class="input" name="currency" type="text" size="5">
  </td>
</tr>

<tr>
  <td colspan="3">
    <input type="radio" name="action" value="mrefresh">
    [[Auction.RefreshAuctions]]
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mdel">
    [[Auction.DeleteAuction]]
  </td>
  <td colspan="2">
    <input type="checkbox" name="mdel_confirm"> [[Auction.ConfirmDelete]]
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mdelg">
    [[Auction.DeleteGroup]]
  </td>
  <td>
    <input type="checkbox" name="mdelg_confirm"> [[Auction.ConfirmDelete]]
  </td>
  <td>
    <input type="image" class="icon" style="float:right" name="go"
           src="layout/default/images/save.gif"
           title="[[Auction.Save]]" alt="[[[Auction.Save]]]">
  </td>
</tr>

<tr>
  <td colspan="3">
    <strong>[[Auction.Groups]] =&gt; esniper</strong>
  </td>
</tr>

<tr>
  <td>
    <input type="radio" name="action" value="mstart">
    [[Auction.Start]]
  </td>
  <td>
    <input type="radio" name="action" value="mstop">
    [[Auction.Stop]]
  </td>
  <td style="text-align:right">
    <input type="image" class="icon" name="go" src="layout/default/images/save.gif"
           title="[[Auction.Save]]" alt="[[[Auction.Save]]]">
  </td>
</tr>
</table>
</div>
</noscript>
