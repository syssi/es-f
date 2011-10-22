<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
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

  <a href="{GROUP.EDITURL}" title="[[Auction.EditGroup|striptags|quote]]"
     onclick="Modalbox.show($('GroupEdit{GROUP.NAME|hash}'),{ title:this.title+': {GROUP.NAME}' }); return false">
    <img class="icon" src="layout/default/images/edit.gif" alt="E"
         title="[[Auction.EditGroup|striptags|quote]]"
         onmouseover="Tip('{js:[[Auction.EditGroup]]}')">
  </a>
<!-- ELSE -->
  <img class="icon" src="layout/default/images/edit-d.gif" alt="">
<!-- ENDIF -->
