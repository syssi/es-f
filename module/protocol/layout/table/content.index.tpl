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

<div style="padding:7px;text-align:center;
            background:url(layout/default/images/th.png)top;
            background-color:#BBB">
  <!-- BEGIN PROTOCOLS -->
    <a href="#{GROUP}">{GROUP}</a> {if:$ROWLAST,"<>",TRUE,"|"}
  <!-- END PROTOCOLS -->
</div>

<table style="width:100%">
<tr>
  <th style="text-align:left">[[Protocol.Group]] &amp; [[Protocol.Auctions]]</th>
  <th style="width:60%;text-align:left">[[Protocol.Protocols]]
    <!-- IF REVERSED --> &nbsp; <small>([[Protocol.Reversed]])</small><!-- ENDIF -->
  </th>
  <th><img src="layout/default/images/tool.gif" title="[[Protocol.Actions]]"
           alt="[[Protocol.Actions]]" onmouseover="Tip('[[Protocol.Actions]]')"></th>
</tr>

<!-- BEGIN PROTOCOLS -->

<tr class="{cycle:"class","tr1","tr2"}">
  <td style="vertical-align:top">
    <div style="max-height:300px;overflow:auto">
      <a name="{GROUP}"></a>
      <h4>{GROUP}</h4>
      <!-- BEGIN AUCTIONS -->
      <div style="max-height:1.5em;line-height:1.5em;overflow:hidden">
        <a class="ebay" href="{ITEMURL}" title="{NAME}" onmouseover="Tip('{NAME}')">{NAME}</a>
      </div>
      <!-- END AUCTIONS -->
    </div>
  </td>
  <td style="vertical-align:top">
    <div style="max-height:300px;overflow:auto">
      <pre style="font-size:90%">{LOG}</pre>
    </div>
  </td>
  <td style="text-align:center;vertical-align:top">
    <a href="#pagetop"><img src="layout/default/images/arrow-up.gif"
       title="[[Protocol.Up]]" alt="[[Protocol.Up]]" onmouseover="Tip('[[Protocol.Up]]')"/></a>
    <br><br>
    <a href="{SHOWURL}#bottom" onclick="openWin('{SHOWURL}#bottom',650,400,'scrollbars=yes'); return false;"><img
       src="module/protocol/layout/default/images/show.gif" title="[[Protocol.Show]]"
       alt="[[Protocol.Show]]" onmouseover="Tip('[[Protocol.Show]]')"></a>
    <br><br>
    <a href="{DELETEURL}"><img src="layout/default/images/delete.gif"  title="[[Protocol.Delete]]"
       alt="[[Protocol.Delete]]" onmouseover="Tip('[[Protocol.Delete]]')"/></a>
  </td>
</tr>

<!-- END PROTOCOLS -->

</table>
