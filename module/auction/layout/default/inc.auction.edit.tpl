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

<!-- IF EDITAUCTIONURL -->

  <!-- IF CONST.MODULE.REFRESHBUTTONS = "3" -->
  <a href="?module=auction&amp;action=mrefresh&amp;auctions={ITEM}">
    <img class="icon" src="layout/default/images/refresh.gif" alt="R"
         title="[[Auction.Refresh|striptags|quote]]"
         onmouseover="Tip('{js:[[Auction.Refresh]]}')">
  </a>
  <br>
  <!-- ENDIF -->

  <a href="{EDITAUCTIONURL}"
     <!-- IF CONST.MODULE.POPUPEDIT -->
     onclick="return CreatePopupWindow('PopupAuctionEdit{ITEM}',20)"
     <!-- ENDIF -->
    >
    <img class="icon" src="layout/default/images/edit.gif" alt="E"
         title="[[Auction.EditAuction]]"
         onmouseover="Tip('[[Auction.EditAuction]]');">
  </a>
  
  <!-- ELSE -->
  <img class="icon" src="layout/default/images/edit-d.gif" alt="">
<!-- ENDIF -->
