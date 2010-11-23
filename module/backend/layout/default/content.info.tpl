<!--
/**
 *
 */
-->

<div id="content">

<!-- IF ACTIONS -->
<div id="infolinks">
  <!-- BEGIN ACTIONS -->
    <a href="{URL}">{TITLE}</a>
    <br>
  <!-- END ACTIONS -->
</div>
<!-- ENDIF -->

<h2>{TYPE}: {NAME}</h2>

<p><strong>
<!-- IF ENABLED -->
[[Backend.State]]: [[Backend.Enabled]]
<!-- ELSEIF INSTALLED -->
[[Backend.State]]: [[Backend.Installed]]
<!-- ENDIF -->
</strong></p>

<p><tt>Version: {VERSION}, Category: {nvl:CATEGORY,"Core"}</tt></p>

<p>{nvl:DESCRIPTION}</p>

<h3>Description</h3>

<p>{INFO}</p>

<!-- IF CONST.MODULES.HELP.ENABLED -->
<p>For further informations please take also a look into the <a href="{HELPURL}">help</a>.</p>
<!-- ENDIF -->

<!-- IF CHANGELOG -->
<h3>Changelog</h3>

<div class="tabber">
  <!-- BEGIN CHANGELOG -->
  <div class="tabbertab">
    <h4 class="info">{VERSION}</h4>
    {CHANGES}
  </div>
  <!-- END CHANGELOG -->
</div>
<!-- ENDIF -->

<!-- IF CONFIG -->
<h3>Actual configuration</h3>

<table id="config">
<!-- BEGIN CONFIG -->
  <tr><td>{VARIABLE}</td><td> = </td><td><tt>{VALUE}</tt></td></tr>
<!-- END CONFIG -->
</table>

<!-- ENDIF -->

<h3>Author</h3>

<p>{html:AUTHOR}</p>

</div>