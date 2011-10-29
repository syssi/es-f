<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<!-- /* Block definitions >>> */ -->

<!-- BEGIN BLOCK ACTIONS -->
<select name="{ACTION}" onchange="this.form.submit()">
  <option value="">[[Backup.Actions]]</option>
  <option value="delete">[[Backup.Delete]]</option>
  <option value="restore">[[Backup.Restore]]</option>
  <option value="lock">[[Backup.Lock]]</option>
  <option value="unlock">[[Backup.Unlock]]</option>
</select>
<!-- END BLOCK ACTIONS -->

<!-- BEGIN BLOCK PAGINATION -->
<div class="pagination">{PAGINATION}</div>
<!-- END BLOCK PAGINATION -->

<!-- BEGIN BLOCK DELETEALL -->
<input class="button right" style="color:#FF3333;width:200px" type="submit"
       value="[[Backup.DeleteAll]]" name="clear"
       title="[[Backup.Attention]]: [[Backup.DeleteAll]]"
       onmouseover="Tip('[[Backup.DeleteAllTip]]',TITLE,'[[Backup.Attention]]',WIDTH,300)">
<!-- END BLOCK DELETEALL -->

<!-- /* <<< Block definitions */ -->

<!-- BLOCK PAGINATION -->

<form id="backupform" method="post" style="display:inline">

<table id="list">

<tr>
  <td colspan="{iif:SHOWIMAGES,"6","5"}" class="c"
      style="padding:10px 5px;border-top:dashed gray 1px">
    <div class="left">
      <img style="vertical-align:bottom;margin:0 10px"
           src="{$IMGDIR}/arrow1.gif" alt="[[Backup.MarkedAuctions]]:">
      {"action1" > ACTION}
      <!-- BLOCK ACTIONS -->
      <noscript>
        &nbsp;&nbsp;&nbsp;
        <input type="submit" value="[[Backup.Send]]">
      </noscript>
    </div>
    <!-- BLOCK DELETEALL -->
    {translate:"Backup.AuctionsCount", AUCTIONCOUNT}
  </td>
</tr>

<tr>
  <th style="text-align:left">
    <input id="cb_BackupToggleAuctions" style="display:none" type="checkbox"
           onclick="BackupToggleAuctions(this.checked);"
           title="[[Backup.Toggle|striptags|quote]]"
           onmouseover="Tip('{js:[[Backup.Toggle]]}')">
  </th>
  <th id="auction" colspan="{iif:SHOWIMAGES,"2","1"}">[[Backup.Auction]]</th>
  <th>[[Backup.Category]]</th>
  <th>[[Backup.Group]]</th>
  <th>&nbsp;</th>
</tr>

<!-- BEGIN AUCTIONS -->
<tr id="tr_{ITEM}" class="{cycle:"tr","tr1","tr2"}">
  <td>
    <input type="checkbox" name="auction[]" value="{ITEM}"
           onclick="ToggleClass('tr_{ITEM}','selected',this.checked)">
  </td>
  <!-- IF __SHOWIMAGES -->
  <td class="image">
    <!-- IF IMGURL -->
    <a href="html/image.php?i={IMGURL}&amp;m={IMGSIZE}"><img
       width="{THUMBWIDTH}" height="{THUMBHEIGHT}" noimagesize
       src="html/image.php?d&amp;i={IMGURL}&amp;m={THUMBSIZE}" alt=""
       onmouseover="Tip('<img noimagesize src=\'html/image.php?i={IMGURL}&amp;m={IMGSIZE}\'>',
                        OPACITY,100,WIDTH,{IMGWIDTH},OFFSETX,{THUMBSIZE},OFFSETY,-{IMGHEIGHT}/2-7,
                        PADDING,0,BORDERCOLOR,'#C0C0C0',BORDERWIDTH,10)"></a>
    <!-- ELSE -->
    &nbsp;
    <!-- ENDIF IMGURL -->
  </td>
  <!-- ENDIF __SHOWIMAGES -->
  <td><a class="ebay" href="{AUCTIONURL}">{NAME}</a></td>
  <td style="white-space:nowrap">{nvl:CATEGORY}</td>
  <td style="white-space:nowrap">{nvl:GROUP}</td>
  <td style="white-space:nowrap">
    <a href="{SHOWURL}"><img src="module/backup/layout/default/images/detail.gif"
       class="imgbtn"
       alt="[[Backup.Details]]" title="[[Backup.Details]]"
       onmouseover="Tip('[[Backup.Details]]')"></a>
    <!-- IF !LOCKED -->
    <a href="{LOCKURL}"><img src="module/backup/layout/default/images/unlocked.gif"
       class="imgbtn"
       alt="[[Backup.Lock]]" title="[[Backup.Lock]]"
       onmouseover="Tip('[[Backup.Lock]]')"></a>
    <!-- ELSE -->
    <a href="{UNLOCKURL}"><img src="module/backup/layout/default/images/locked.gif"
       class="imgbtn"
       alt="[[Backup.Unlock]]" title="[[Backup.Unlock]]"
       onmouseover="Tip('[[Backup.Unlock]]')"></a>
    <!-- ENDIF -->

  </td>
</tr>
<!-- END AUCTIONS -->

<tr>
  <td colspan="{iif:SHOWIMAGES,"6","5"}" class="c" style="padding:10px 5px;border-top:dashed gray 1px">
    <div class="left">
      <img style="vertical-align:top;margin:0 10px" src="{$IMGDIR}/arrow2.gif"
           alt="[[Backup.MarkedAuctions]]:">
      {"action" > ACTION}
      <!-- BLOCK ACTIONS -->
      <noscript>
        &nbsp;&nbsp;&nbsp;
        <input type="submit" value="[[Backup.Send]]">
      </noscript>
    </div>
    <!-- BLOCK DELETEALL -->
    <!-- BLOCK PAGINATION -->
  </td>
</tr>

</table>

</form>


<script type="text/javascript">
  //<![CDATA[
  FastInit.addOnLoad(function() { $('cb_BackupToggleAuctions').show() });
  //]]>
</script>
