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

<tr class="th">

<!--
  <th>&nbsp;</th>
-->
  <th colspan="5">
    [[Auction.Auction]]
  </th>
  
  <th>
    <input id="cb_autorefresh" type="checkbox" style="display:none"
           title="Auto-Refresh of soon ending auctions"
           onmouseover="Tip('Auto-Refresh of soon ending auctions')"
           onclick="esf_CountDownRefresh=this.checked" checked="checked">
    <script type="text/javascript">
      // <![CDATA[
      addLoadEvent($('cb_autorefresh').show());
      // ]]>
    </script>
  </th>

  <th>
    <img src="layout/default/images/tool.gif" alt="[[Auction.Actions]]">
  </th >
  
  <th style="border-left:dashed gray 1px">
    <img src="module/auction/layout/default/images/mybid.gif"
        alt="[[Auction.Quantity]] / [[Auction.MyBid]]">
  </th>
  
  <th><img src="layout/default/images/tool.gif" alt="[[Auction.Actions]]"></th>

</tr>
