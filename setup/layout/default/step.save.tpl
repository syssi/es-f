<!--
/**
 *
 */
-->

<div id="content">

<h2>Finalize configuration</h2>

<!-- BEGIN MSG -->
<div class="li">{MSG}</div>
<!-- END MSG -->

<!-- IF INSTALL -->

<h2>Congratulation, your <tt> <a href="../index.php">{CONST.ESF.LONG_TITLE}</a> </tt> setup was successful.</h2>

<p style="font-weight:bold">Please read carefully the following notices and follow the instructions!</p>

<!-- IF NOTICES -->
<h2>Notices from modules &amp; plugins</h2>

<table class="msg">
<tr>
  <!-- BEGIN NOTICES -->
  <th class="w50">{NAME}</th>
  <!-- END NOTICES -->
</tr>
<tr>
  <!-- BEGIN NOTICES -->
  <td>
    <!-- BEGIN NOTES -->
    <p class="li"><strong>{NAME}</strong><br />{NOTE}</p>
    <!-- END NOTES -->
  </td>
  <!-- END NOTICES -->
</tr>
</table>
<!-- ENDIF NOTICES -->

<h2>Further steps</h2>

<table class="msg">
<tr>
  <th class="w33">Required steps</th>
  <th class="w33">Recommended steps</th>
  <th class="w33">Optional steps</th>
</tr>
<tr>
  <td>
    <div class="li">Login into <a href="../index.php?module=backend">Backend</a></div>
    <div class="li">Check the modules and plugins and configure (install &amp; activate) as you like.</div>
  </td>
  <td>
    <div class="li">Rename and/or protect setup directory, e.g. by .htaccess/.htpasswd</div>
  </td>
  <td>
    <div class="li">Remove write permission from your installation directory.</div>
    <div class="li">To reconfigure your installation or to define more users,
                    just re-run the <a href="index.php">setup</a>.</div>
  </td>
</tr>
</table>

<!-- ELSE -->

<h2>Return to your <a href="../index.php"><tt>{CONST.ESF.LONG_TITLE}</tt></a></h2>

<!--
<h2>Return to</h2>

<p>
  <a href="../index.php"><img
     src="../button/button.php?d={BASEDIR}/tpl/images/button.ini&amp;i={BASEDIR}/tpl/images/button.gif&amp;t={CONST.ESF.LONG_TITLE|urlencode}&amp;f=5&amp;c=000&amp;s=fff,1"
     alt="{CONST.ESF.LONG_TITLE}" title="Start {CONST.ESF.LONG_TITLE}" /></a>
</p>
-->

<!-- ENDIF -->

</div>

{FALSE > PREVSTEP}
