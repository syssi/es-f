<!--
/**
 *
 */
-->

<table width="100%" style="width:100%">

<!-- IF PROCESSES -->

  <!-- BEGIN PROCESSES -->

  <tr class="{cycle:"class","tr1","tr2"}">

    <td style="padding:5px">
      {form:}
      {fh:"module","process"}
      {fh:"action","kill"}
      {fh:"pid",PID}
      <input type="image" src="module/process/layout/images/kill.gif" alt="[KILL]"
             title="kill group {GROUP}" onmouseover="Tip('kill group {GROUP}');">
      </form>
    </td>

    <td style="width:99%">
      <tt>&nbsp; {PROCESS}</tt>
    </td>

  </tr>

  <!-- END PROCESSES -->

<!-- ELSE -->

<div id="content">
  [[Process.NoProcesses]]
</div>

<!-- ENDIF -->

</table>
