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

{TRUE > POPUPFORM}

<div id="PopupCleanupAuctions" class="popupwindow" style="width:300px;display:none">
  [[Auction.CleanupAuctions > Titletext]]
  {"PopupCleanupAuctions" > POPUPNAME}
  <!-- INCLUDE popup.title -->
  <div class="content">
    <!-- INCLUDE form.cleanup -->
  </div>
</div>

<!-- BEGIN AUCTIONS -->

  {TRUE > POPUPFORM}

  <!-- IF !ENDED -->
  <div id="PopupAuctionEdit{ITEM}" class="popupwindow" style="width:640px;display:none">
    <!-- provide concat the concat ":" because of the ": "!! -->
    {:[[Auction.EditAuction]],": ",ITEM > TITLETEXT}
    {"PopupAuctionEdit",ITEM > POPUPNAME}
    <!-- INCLUDE popup.title -->
    <div class="content">
      <!-- INCLUDE form.editauction -->
    </div>
  </div>
  <!-- ENDIF !ENDED -->

  <!-- IF GROUP --><!-- IF !GROUP.ENDED -->
  <div id="PopupGroupEdit{GROUP.NAME|hash}" class="popupwindow" style="width:600px;display:none">
    {:[[Auction.EditGroup]],": ",GROUP.NAME > TITLETEXT}
    {"PopupGroupEdit",GROUP.NAME|hash > POPUPNAME}
    <!-- INCLUDE popup.title -->
    <div class="content">
      <!-- INCLUDE form.editgroup -->
    </div>
  </div>
  <!-- ENDIF --><!-- ENDIF -->

<!-- END AUCTIONS -->
