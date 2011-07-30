<!--
/**
 * Page header
 *
 * @version 0.2.0
 * @author  Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * CHANGELOG
 * =========
 * 0.2.0
 * - removed breadcrumb
 */
-->

<a name="pagetop"></a>

<header>
<div id="header">

  <div id="header_title" style="text-align:center">

    <div style="float:left">
      <!-- IF USER -->{USER}<!-- ELSE --><a href="?module=login">[[Core.Login]]</a><!-- ENDIF -->
    </div>

    <div style="float:right">
      <!-- BEGIN LANGUAGE -->
      &nbsp;<a class="language" href="{URL}" onmouseover="Tip('{DESC}')">{NAME}</a>
      <!-- END LANGUAGE -->
    </div>

    <div class="clear" style="font-weight:bold">
      {SUBTITLE1}
      <!-- IF {regex:CONST.MODULE.VERSION,"~[^\d.]~"} -->
      beta&nbsp;{CONST.MODULE.VERSION}
      <!-- ENDIF -->
      <br>
      <small>{SUBTITLE2}</small>
    </div>

  </div>

  <!-- IF LASTUPDATE -->
  <div id="lastupdate" style="text-align:center;font-size:80%">{LASTUPDATE}</div>
  <!-- ENDIF LASTUPDATE -->

</div>
</header>

<nav>
<div id="menu">

  <div id="menu_main" style="float:left;height:20px">
    {Menu.Main > MENUDATA}
    <!-- INCLUDE menu -->
  </div>

  <div id="menu_module" style="float:right;height:20px">
    <!-- IF Menu.Module -->
      {Menu.Module > MENUDATA}
      <!-- INCLUDE menu -->
    <!-- ENDIF -->
  </div>

  <div id="menu_system" style="text-align:center;height:20px">
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
