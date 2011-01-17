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

<!-- COMMENT : Format mouse over tip -->
{format:CATEGORY.NAME,[[Auction.ShowAuctionsOfCategory]] > SHOW_TIP}

</tbody>

<tbody id="{CATEGORY.NAME|hash}">

  <tr id="row_{CATEGORY.NAME|hash}" class="clickable category"
      onclick="ShowHideCategory('{CATEGORY.NAME|hash}')">

    <td style="text-align:center">
      <a name="{CATEGORY.NAME|hash}"></a>
      <img id="category_{CATEGORY.NAME|hash}" src="{__$IMGDIR}/target.gif"
           alt="{CATEGORY.NAME}" style="width:20px;height:20px"
           title="[[Auction.Droptarget]]: [[Auction.DropCategory]]"
           onmouseover="Tip('{js:[[Auction.DropCategory]]}',TITLE,'{js:[[Auction.DropTarget]]}',WIDTH,200)">
      <script type="text/javascript">
        // <![CDATA[
        addLoadEvent(function() {
          Droppables.add('category_{CATEGORY.NAME|hash}', {
            accept: 'draggable',
            onHover: ItemHover,
            onDrop: ItemDropCategory,
          });
        });
        // ]]>
      </script>
    </td>

    <td colspan="7" style="padding:7px" title="{SHOW_TIP|striptags|quote}" onmouseover="Tip('{js:SHOW_TIP}')">
      <a name="{CATEGORY.NAME|quote}"></a>
      <span style="float:left;font-size:130%;font-weight:bold">{CATEGORY.NAME}</span>

      <span style="float:left;margin-left:10px">(<span id="{CATEGORY.NAME|hash}_count">{CATEGORY.COUNT}</span>)</span>

      <div id="remain_{CATEGORY.NAME|hash}"
           style="margin-right:45%;text-align:right;display:none">
        <!-- IF CATEGORY.ITEM -->
        <tt id="category_{CATEGORY.ITEM}">{CATEGORY.NEXTEND}</tt>
        <!-- ENDIF -->
      </div>

      <script type="text/javascript">
        // <![CDATA[
        // hide tbody section using temp. class assignment, via #id don't work??
        var c = cookieManager.getCookie('categories');
        if (c && c.match(/%%{CATEGORY.NAME|hash}/)) {
          addLoadEvent(function() {
            ShowHideCategory('{CATEGORY.NAME|hash}',false);
          });
        }
        // ]]>
      </script>

    </td>

    <td class="group2">
      <!-- IF CONST.MODULE.REFRESHBUTTONS >= "1" -->
      <a href="?module=auction&amp;action=refreshcategory&amp;category={CATEGORY.NAME|hash}">
        <img class="icon" src="layout/default/images/refresh.gif" alt="refresh"
             title="[[Auction.RefreshCategory|striptags|quote]]"
             onmouseover="Tip('{js:[[Auction.RefreshCategory]]}')">
      </a>
      <!-- ENDIF -->
    </td>
  </tr>

</tbody>

<tbody id="tbody_{CATEGORY.NAME|hash}">
