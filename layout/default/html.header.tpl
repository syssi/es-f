<!--
/**
 * Page header
 *
 * @version   0.2.0
 * @author    Knut Kohl <knutkohl@users.sourceforge.net>
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<a name="pagetop"></a>

<noscript><div id="nojs">
  For best usability activate JavaScript for at least
  <tt>&nbsp;{server:"HTTP_HOST"}&nbsp;</tt>
  <br>
  <a href="http://www.enable-javascript.com/" target="_blank">Instructions</a>
  how to enable JavaScript in your web browser.
</div></noscript>

<header>
<div id="header">

  <div id="header_title" style="text-align:center">

    <div id="esf_title" style="float:left;text-align:left;margin-bottom:5px">
      <strong style="font-size:120%"><tt>{CONST.ESF.TITLE}</tt> {CONST.ESF.SLOGAN}</strong><br>
      {CONST.ESF.FULL_VERSION}
      <!-- IF CONST.DEVELOP -->
      <br>
      <span style="font-size:smaller;font-style:italic">
        <script type="text/javascript">
          // <![CDATA[
          document.write("Server time difference: " + ServerTimeOffset.toFixed(1) + "s");
          // ]]>
        </script>
      </span>
      <!-- ENDIF -->
    </div>

    <div style="float:right;text-align:right;margin-bottom:5px">
      <div style="float:right;margin-left:2em">
        <!-- BEGIN LANGUAGE -->
        <a class="language" href="{URL}" onmouseover="Tip('{DESC}')"><img
           src="application/language/{NAME}.png" alt="[{NAME}]"></a>
        <!-- END LANGUAGE -->
      </div>

      <div id="efa_allLinks" style="float:right;text-align:right;padding-bottom:5px">
        <script type="text/javascript">document.write(efa_fontSize.allLinks)</script>
      </div>

      <div class="clear">
        [[Core.Welcome]],
        <!-- IF USER -->
          <strong>{USER}</strong>
        <!-- ELSE -->
          <a href="?module=login">[[Core.Login]]</a>
        <!-- ENDIF -->
      </div>

      <!-- IF CONST.DEVELOP -->
      <span style="font-size:smaller;font-style:italic">
      {server:"REMOTE_HOST"} ({server:"REMOTE_ADDR"})
      </span>
      <!-- ENDIF -->
    </div>

    <div style="width:35em;margin:0 auto">
      <h1 style="margin:0 0 0.2em 0">
        {SUBTITLE1}
        <!-- IF {regex:CONST.MODULE.VERSION,"~[^\d.]~"} -->
        &nbsp;
        <img src="layout/default/beta.png" alt="[BETA]"
             title="[[Core.Version]]: {CONST.MODULE.VERSION}"
             onmouseover="Tip('{CONST.MODULE.VERSION} &nbsp; &nbsp;',TITLE,'[[Core.Version]]')">
        <!-- ENDIF -->
      </h1>
      <h3 style="margin-bottom:0.3em">{SUBTITLE2}</h3>
    </div>

  </div>

</div>
</header>

<!-- INCLUDE html.messages -->

<nav>
<div id="menu">

  <div id="menu_main" style="float:left;height:20px">
    {Menu.Main > MENUDATA}
    <!-- INCLUDE menu -->
  </div>

  <div style="float:right">
    <a href="http://{EBAY_HOMEPAGE}"><img src="layout/images/logoEbay.gif" alt="eBay"
       title="ebay Homepage" onmouseover="Tip('ebay Homepage')"></a>
  </div>

  <!-- IF LASTUPDATE -->
  <div style="float:right;padding-right:5px" onmouseover="Tip('[[Core.LastUpdate|js]]')">
    <span id="lastupdate">{LASTUPDATE}</span>
  </div>
  <!-- ENDIF LASTUPDATE -->

  <hr class="clear" style="margin-top:25px">

  <div id="menu_module" style="float:left;padding-left:5px;height:20px">
    <!-- IF Menu.Module -->
      {Menu.Module > MENUDATA}
      <!-- INCLUDE menu -->
    <!-- ENDIF -->
  </div>

  <div id="menu_system" style="text-align:right;height:20px">
    <!-- IF Menu.System -->
      {Menu.System > MENUDATA}
      <!-- INCLUDE menu -->
    <!-- ENDIF -->
  </div>

</div>
</nav>

<div style="clear:both"></div>

<div id="header_left" style="float:left">{nvl:HEADER_LEFT}</div>
<div id="header_right" style="float:right">{nvl:HEADER_RIGHT}</div>
<div style="clear:both"></div>
<div id="header_center">{nvl:HEADER_CENTER}</div>

<!-- _INCLUDE html.messages -->
