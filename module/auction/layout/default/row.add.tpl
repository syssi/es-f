<!-- COMMENT
/*
 * Copyright (c) 2006-2009 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 */
-->

<form action="{FORMACTION}" style="display:inline" method="post">
{fh:"module","auction"}
{fh:"action","add"}

<tbody id="rowadd" class="noprint">

<tr class="tr1" style="border-bottom:dashed gray 1px;height:3em"><td colspan="9">

  <table width="100%" style="width:100%">
  <tr>

    <td style="white-space:nowrap">
      <div style="padding:3px;margin-right:3em">
        <input name="auctions" class="input" style="width:100%"> {help:"AuctionHelp.AddMultipleAuctions"}
      </div>

      <div class="left" style="padding:3px">
        <label for="CategorySelect">[[Auction.Category]]</label> {help:"AuctionHelp.Category"}:
        <select id="CategorySelect" name="category">{options:CONST.CATEGORIES}</select>
        &nbsp; <label for="categorynew">[[Auction.Or]]</label> &nbsp;
        {ft:"categorynew",,"input","id=\"categorynew\" size=\"10\""}
      </div>

      <div class="right" style="padding:3px">
        <label for="GroupSelect">[[Auction.Group]]</label> {help:"AuctionHelp.Group"}:
        <select id="GroupSelect" name="group" onchange="SetGroupCategory(this.value)">{options:CONST.GROUPS}</select>
        &nbsp; <label for="groupnew">[[Auction.Or]]</label> &nbsp;
        {ft:"groupnew",,"input","id=\"groupnew\" size=\"10\""}
      </div>
    </td>

    <td style="text-align:left;white-space:nowrap">
      <div style="padding:2px">
        <div class="left" style="width:4em"><label for="q">[[Auction.Quantity]]</label>:</div>
        <input style="width:3em" class="input num" type="text" id="q" name="q" value="1">
      </div>
      <div style="padding:2px">
        <div class="left" style="width:4em"><label for="b">[[Auction.Bid]]</label>:</div>
        <input style="width:3em" class="input num" type="text" id="b" name="b">
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
