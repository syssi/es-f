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

<!-- IF GROUP.EDITURL -->
  <!-- IF CONST.MODULE.REFRESHBUTTONS >= "2" -->
  <a href="?module=auction&amp;action=refreshgroup&amp;group={GROUP.NAME|hash}">
    <img class="icon" src="layout/default/images/refresh.gif" alt="R"
         title="[[Auction.RefreshGroup|striptags|quote]]"
         onmouseover="Tip('{js:[[Auction.RefreshGroup]]}')">
  </a>
  <br>
  <!-- ENDIF -->

  <a href="{GROUP.EDITURL}"
     <!-- IF CONST.MODULE.POPUPEDIT -->
     onclick="return CreatePopupWindow('PopupGroupEdit{GROUP.NAME|hash}',150)"
     <!-- ENDIF -->
    >
    <img class="icon" src="layout/default/images/edit.gif" alt="E"
         title="[[Auction.EditGroup|striptags|quote]]"
         onmouseover="Tip('{js:[[Auction.EditGroup]]}')">
  </a>
<!-- ELSE -->
  <img class="icon" src="layout/default/images/edit-d.gif" alt="">
<!-- ENDIF -->
