<!--
/**
 *
 */
-->

<div class="tabber">

<!-- BEGIN SCOPE -->

  <div class="tabbertab">

  <h2>{$ROWID|ucwords}</h2>

  <table class="list" cellspacing="1">

  <!-- BEGIN CATEGORY -->

    <!-- IF $ROWID -->
    <tr class="tr3">
      <th>&nbsp;</th>
      <th colspan="7">
        <h2 style="margin:0.25em 0">[[Backend.Category]]: {$ROWID}</h2>
      </th>
    </tr>
    <tr class="tr2">
      <th>&nbsp;</th>
      <th colspan="2">[[Backend.Extension]]</th>
      <th>[[Backend.Version]]</th>
      <th colspan="2">[[Backend.Description]]</th>
      <th>[[Backend.Actions]]</th>
    </tr>
    <!-- ENDIF -->

    {cycle:"class"}

    <!-- BEGIN EXTENSIONS -->

    <tr class="{cycle:"class","tr1","tr2"}">
      <td class="image">
        <!-- IF ENABLED -->
          <img alt="!" title="[[Backend.Enabled]]"
               onmouseover="Tip('[[Backend.Enabled]]')"
               src="{__$IMGDIR}/active.gif">
        <!-- ELSEIF INSTALLED -->
          <img alt="+" title="[[Backend.Installed]]"
               onmouseover="Tip('[[Backend.Installed]]')"
               src="{__$IMGDIR}/inactive.gif">
        <!-- ELSE -->
          <img alt="-" title="[[Backend.NotInstalled]]"
               onmouseover="Tip('[[Backend.NotInstalled]]')"
               src="{__$IMGDIR}/delete.gif">
        <!-- ENDIF -->
      </td>
      <td class="name">
        {NAME}
        <!-- IF COREFUNCTION -->
          &nbsp; {anno:}
        <!-- ENDIF -->
      </td>
      <td class="help">
        <a href="{INFOURL}">
          <img src="{__$IMGDIR}/info.gif"
               alt="?" title="Information" onmouseover="Tip('Information')">
        </a>
        <!-- IF CONST.MODULES.CONFIGURATION.ENABLED -->
        <!-- IF INSTALLED --><!-- IF CONFIGURL -->
        <a style="margin-left:5px" href="{CONFIGURL}">
          <img src="module/backend/layout/default/images/configuration.gif"
               alt="C" title="[[Backend.EditConfiguration]]"
               onmouseover="Tip('[[Backend.EditConfiguration]]')">
        </a>
        <!-- ENDIF --><!-- ENDIF --><!-- ENDIF -->
      </td>
      <td class="version">
        {VERSION}
      </td>
      <td class="desc">
        {nvl:DESCRIPTION,"&nbsp;"}
      </td>
      <td class="thumbnail">
        <!-- IF REQUIREJS -->
        <img style="float:right" title="Require JavaScript!" onmouseover="Tip('Require JavaScript!')"
             src="module/backend/layout/default/images/requirejs.gif">
        <!-- ENDIF -->
        <!-- IF THUMBURL -->
        <img style="width:60px" width="60" src="{THUMBURL}"
             onmouseover="Tip('<img style=\'width:400px\' width=\'400\' src=\'{THUMBURL}\'>',ABOVE,true)">
        <!-- ENDIF THUMBURL -->
      </td>
      <td class="actions">
        <!-- BEGIN ACTIONS -->
          <a href="{URL}">{TITLE}</a>
          <br>
        <!-- END ACTIONS -->
      </td>
    </tr>
    <!-- END EXTENSIONS -->

    <!-- IF !$ROWLAST -->
    <tr><td colspan="7">&nbsp;</td></tr>
    <!-- ENDIF -->

  <!-- END CATEGORY -->

  <tr>
    <td colspan="7">
      {anno:[[Backend.CoreInfo]]}
    </td>
  </tr>

  </table>

  </div>

<!-- END SCOPE -->

</div>
