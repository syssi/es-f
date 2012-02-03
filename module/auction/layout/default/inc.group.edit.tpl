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
         data-tip="[[Auction.RefreshGroup|striptags|quote]]"
         onmouseover="Tip(this)">
  </a>
  <br>
  <!-- ENDIF -->

  <a href="{GROUP.EDITURL}"
     onclick="Modalbox.show('?module=auction&amp;action=ajaxeditgroup&amp;group={GROUP.NAME|urlencode}',\{title:'[[Auction.EditGroup|striptags|quote]]: {GROUP.NAME}'\}); return false">
    <img class="icon" src="layout/default/images/edit.gif" alt="E"
         title="[[Auction.EditGroup|striptags|quote]]"
         data-tip="[[Auction.EditGroup|striptags|quote]]"
         onmouseover="Tip(this)">
  </a>
<!-- ELSE -->
  <img class="icon" src="layout/default/images/edit-d.gif" alt="">
<!-- ENDIF -->
