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

<td id="td_group1_{GROUP.NAME|hash}" rowspan="{GROUP.COUNT}" class="{GCLASS} group1 {iif:GROUP.ACTIVE,"group_active"}">
  <a name="{GROUP.NAME|hash}"></a>
  <small>
  <!-- IF GROUP.COMMENT -->
    <abbr title="{GROUP.COMMENT|striptags|quote}"
          onmouseover="Tip('{js:GROUP.COMMENT}')">{GROUP.NAME}</abbr>
  <!-- ELSE -->
    {GROUP.NAME}
  <!-- ENDIF -->
  </small>
  <br><br>
  <!-- IF GROUP.TOTAL -->
    <img class="grouptotal" src="{$IMGDIR}/total.gif" alt="(T)"
         title="[[Auction.GroupTotal|striptags|quote]]"
         onmouseover="Tip('{js:[[Auction.GroupTotal]]}')">
  <!-- ENDIF -->
  &nbsp;
  <tt><!-- IF GROUP.QUANTITY > "1" -->{GROUP.QUANTITY} / <!-- ENDIF -->{currency:GROUP.BID}</tt>
</td>

<td id="td_group2_{GROUP.NAME|hash}" rowspan="{GROUP.COUNT}"
    class="{GCLASS} group2 {iif:GROUP.ACTIVE,"group_active"}">
  <img id="group_{GROUP.NAME|hash}" src="{__$IMGDIR}/target.gif"
       alt="{GROUP.NAME}" style="width:20px;height:20px"
       title="[[Auction.Droptarget]]: [[Auction.DropGroup]]"
       onmouseover="Tip('{js:[[Auction.DropGroup]]}',TITLE,'{js:[[Auction.DropTarget]]}',WIDTH,200)">
  <script type="text/javascript">
    // <![CDATA[
    addLoadEvent(function() {
      Droppables.add('group_{GROUP.NAME|hash}', {
        accept: 'draggable',
        onHover: ItemHover,
        onDrop: ItemDropGroup,
      });
    });
    // ]]>
  </script>
  <br>
  <!-- INCLUDE inc.group.edit -->
  <br>
  <!-- INCLUDE inc.group.startstop -->
</td>
