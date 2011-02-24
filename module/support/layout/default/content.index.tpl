<!-- COMMENT
/*
 *
 */
-->

<div id="content">

<a class="save" href="{DOWNLOADURL}" title="Download support infos"
   onmouseover="Tip('Download support infos')">Download</a>

[[Support.Support]]

<div class="tabber">

<ul id="nojslinks">
  <li><a href="#software">Software versions</a></li>
  <li><a href="#cfg">System configuration</a></li>
  <li><a href="#esniper">esniper configuration</a></li>
  <li><a href="#modules">Modules</a></li>
  <li><a href="#plugins">Plugins</a></li>
  <li><a href="#ebay">ebay</a></li>
  <li><a href="#esf">esf</a></li>
  <li><a href="#USERDIR">USERDIR</a></li>
  <li><a href="#Session">Session</a></li>
  <li><a href="#Auctions">Auctions</a></li>
  <li><a href="#Groups">Groups</a></li>
  <li><a href="#phpinfo">PHP-Info</a></li>
</ul>

<!-- download >> -->

<div class="tabbertab" title="System">
  <h3 class="support"><a name="software">Software versions</a></h3>
  <div id="software">
    <table class="support">
    <tr><td>{CONST.ESF.TITLE}</td><td>:</td><td>{CONST.ESF.FULL_VERSION}</td></tr>
    <tr><td>esniper</td><td>:</td><td>{CONST.ESNIPER.VERSION}</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td>Operating&nbsp;system</td><td>:</td><td>{SYSTEMVERSION}</td></tr>
    <tr><td>Web server</td><td>:</td><td>{CONST.SERVER.VERSION}</td></tr>
    <tr><td>Running as</td><td>:</td><td>{html:ESFUSER}</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td>PHP module</td><td>:</td><td>{CONST.PHP.VERSION}</td></tr>
    <tr><td>PHP cli</td><td>:</td>  <td>{PHPCLIVERSION}</td></tr>
    </table>
  </div>

</div>
<div class="tabbertab" title="Configuration">

<div class="tabber inner">
  <div class="tabbertab">
    <h3 class="support"><a name="cfg">System</a></h3>
    <div id="cfg">
      {dump:SUPPORT.CFG}
    </div>
  </div>
  <div class="tabbertab">
    <h3 class="support"><a name="esniper">esniper</a></h3>
    <div id="esniper">{dump:SUPPORT.ESNIPER}</div>
  </div>
  <div class="tabbertab">
    <h3 class="support"><a name="modules">Modules</a></h3>
    <div id="modules">
      <table class="support">
        <tr><th>Module</th><th>State</th><th>Version</th><th>Author</th></tr>
        <!-- BEGIN SUPPORT.MODULE -->
        <tr class="{cycle:"mc","tr1","tr2"}">
          <td>{NAME}</td>
          <td>{STATE}</td>
          <td><tt>{VERSION}</tt></td>
          <td><tt>{html:AUTHOR}</tt></td>
        </tr>
        <!-- END SUPPORT.MODULE -->
      </table>
    </div>
  </div>
  <div class="tabbertab">
    <h3 class="support"><a name="plugins">Plugins</a></h3>
    <div id="plugins">
      <table class="support">
        <tr><th>Plugin</th><th>State</th><th>Version</th><th>Author</th></tr>
        <!-- BEGIN SUPPORT.PLUGIN -->
        <tr class="{cycle:"pc","tr1","tr2"}">
          <td>{NAME}</td>
          <td>{STATE}</td>
          <td><tt>{VERSION}</tt></td>
          <td><tt>{html:AUTHOR}</tt></td>
        </tr>
        <!-- END SUPPORT.PLUGIN -->
      </table>
    </div>
  </div>
  <div class="tabbertab">
    <h3 class="support"><a name="ebay">eBay</a></h3>
    <div id="ebay">{dump:SUPPORT.EBAY}</div>
    <!-- BEGIN SUPPORT.EBAYPARSER -->
    <hr>
    <h4>{$ROWID}</h4>
    <p>Version: {dump:VERSION}</p>
    <p>URL: <div style="margin-left:20px">{dump:URL}</div></p>
    <p>EXPRESSIONS: <div style="margin-left:20px">{dump:EXPRESSIONS}</div></p>
    <!-- END SUPPORT.EBAYPARSER -->
  </div>
  <div class="tabbertab">
    <h3 class="support"><a name="esf">esf</a></h3>
    <div id="esf">{dump:SUPPORT.ESF}</div>
  </div>
</div>

</div>
<div class="tabbertab" title="Information">

<div class="tabber inner">
  <div class="tabbertab">
    <h3 class="support"><a name="USERDIR">USERDIR</a></h3>
    <pre id="USERDIR">{USERDIR}</pre>
  </div>
  <div class="tabbertab">
    <h3 class="support"><a name="Session">Session</a></h3>
    <div id="Session">{dump:SESSION}</div>
  </div>
  <div class="tabbertab">
    <h3 class="support"><a name="Auctions">Auctions</a></h3>
    <div id="Auctions">{dump:SUPPORT.AUCTIONS}</div>
  </div>
  <div class="tabbertab">
    <h3 class="support"><a name="Groups">Groups</a></h3>
    <div id="Groups">{dump:SUPPORT.GROUPS}</div>
  </div>
  <div class="tabbertab" title="PHP-Info">
    <h3 class="support"><a name="phpinfo">PHP-Info</a></h3>
    <div id="phpinfo">{PHPINFO}</div>
  </div>
</div>

</div>

<!-- << download -->

</div>

</div>
