<!--
/**
 *
 */
-->

<div id="content">

<!-- IF INSTALL -->

  <h2>Welcome to installation of {CONST.ESF.LONG_TITLE}</h2>

  <p>This setup will guide you through the following steps:</p>

  <div class="li">Main configuration</div>
  <div class="li">Directory and file permissions, system checks</div>
  <div class="li">User definition</div>

  <h3>Required PHP settings</h3>

  <div class="li">
    <tt style="font-weight:bold">allow_url_fopen On &nbsp; </tt><br>
    Must be enabled in your PHP configuration (php.ini).<br>
    See also <a class="php" href="http://php.net/manual/filesystem.configuration.php#ini.allow-url-fopen">php.net</a>
  </div>

  <br>

  <div class="li">
    <tt style="font-weight:bold">GD library</tt><br>
    Must be installed for auction image display.
  </div>

  <!-- IF CONST.PHP_VERSION < "6" -->
  <h3>Recommended PHP settings</h3>

  <div class="li">
    <tt style="font-weight:bold">register_globals Off &nbsp; </tt><br>
    Should be disabled in your PHP configuration (php.ini) If not, it will simulated.<br>
    This feature has been DEPRECATED and REMOVED as of PHP 6.0.0, see
    <a class="php" href="http://php.net/manual/security.globals.php">php.net</a>
  </div>
  <!-- ENDIF -->

<!-- ELSE -->

  <h2>Welcome to reconfiguration of {CONST.ESF.LONG_TITLE}</h2>

  <p>This setup will guide you through the following steps:</p>

  <div class="li">Main configuration</div>
  <div class="li">User maintenance (optional)</div>

<!-- ENDIF -->

  <h3>Change log</h3>
  {loadfile:"setup/CHANGELOG" > CHANGELOG}
  {CHANGELOG|nl2br}

</div>
