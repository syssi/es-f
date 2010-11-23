<!--
/*
 *
 */
-->

<div id="content">

<h2>{TITLE}</h2>

<h3>{ABSTRACT}</h3>

<p>{DESCRIPTION}</p>

<form action="index.php" method="post">
{fh:"module","configuration"}
{fh:"action","edit"}
<input type="hidden" name="ext" value="{SCOPE}-{EXTENSION}">

<table id="edit">

<!-- BEGIN FIELDS -->

  <!-- IF HEADER -->
  <tr class="tr3">
    {cycle:"class"}
    <td class="header" colspan="3">{DESCRIPTION}</td>
  </tr>
  <!-- ELSE -->
  <tr class="{cycle:"class","tr1","tr2"}">
    <td class="desc">{DESCRIPTION}</td>
    <td class="colon">:</td>
    <td class="input">
      <input type="hidden" name="vars[{VARIABLE}][t]" value="{VARTYPE}">
      <!-- BEGIN INPUT -->{INPUT}<!-- END INPUT --> {MEASUREMENT}
    </td>
  </tr>
  <!-- ENDIF -->

<!-- END FIELDS -->

</table>

<p class="buttons">
  <input class="button configbutton" type="submit" name="confirm"
         value="[[Configuration.Save]]">
  <input class="button configbutton" type="submit"
         value="[[Configuration.Cancel]]">
  <!-- IF CHANGED -->
  <input class="button configbutton" type="submit" name="reset"
         value="[[Configuration.Reset]]">
  <!-- ENDIF -->
</p>

</form>

</div>

<br>
