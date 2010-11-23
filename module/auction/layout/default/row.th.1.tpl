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

<tr class="th th1" style="border-top:dashed gray 1px">
  <th colspan="6">[[Auction.Auctions]]</th>
  <th>
    <span id="autorefresh" style="display:none">
      <input type="checkbox"
             title="Auto-Refresh of soon ending auctions"
             onmouseover="Tip('Auto-Refresh of soon ending auctions')"
             onclick="esf_CountDownRefresh=this.checked" checked="checked">
      {help:"CoreHelp.RefreshEnding"}
    </span>
    <script type="text/javascript">
      // <![CDATA[
      addLoadEvent(function(){ $('autorefresh').show() });
      // ]]>
    </script>
  </th>
  <th colspan="2" style="border-left:dashed gray 1px">[[Auction.Groups]]</th>
</tr>

