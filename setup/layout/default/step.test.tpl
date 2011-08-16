<!--
/**
 *
 */
-->

<div id="content">

<h2>Check permissions</h2>

<table class="perm">

  <tr>
    <th>Directory / File</th>
    <th>Messages</th>
    <th>Description</th>
    <th>To do in case of an error</th>
  </tr>

<!-- BEGIN PERMS -->
  <tr class="{cycle:"class1","tr1","tr2"}">
    <td style="white-space:nowrap"><tt>{NAME}</tt></td>
    <td style="white-space:nowrap">{MESSAGE}</td>
    <td>{DESCRIPTION}</td>
    <td>{TODO}</td>
  </tr>
<!-- END PERMS -->

</table>

<h2>Check system and PHP settings</h2>

<table class="perm">

  <tr>
    <th>Option</th>
    <th style="text-align:center">Value</th>
    <th>Description</th>
    <th>To do in case of an error</th>
  </tr>

<!-- BEGIN TESTS -->
  <tr class="{cycle:"class2","tr1","tr2"}">
    <td style="white-space:nowrap"><tt>{NAME}</tt></td>
    <td><tt>{MESSAGE}</tt></td>
    <td>{DESCRIPTION}</td>
    <td>{TODO}</td>
  </tr>
<!-- END TESTS -->

</table>

</div>
