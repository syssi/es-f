<!--
/**
 * @version 0.3.0
 * @author  Knut Kohl / knutkohl@users.sourceforge.net
 *
 * CHANGELOG
 * =========
 */
-->
<html>
<head>
  <script type="text/javascript">
    // <![CDATA[
    var ServerTimeOffset = {server:"REQUEST_TIME"} - (new Date).getTime()/1000;
    var ApplicationId = '{CONST.ESF.APPID}';
    var GB_ROOT_DIR = '{CONST.BASEHTML}js/greybox/greybox/';
    var GetCategoryFromGroup = '{GETCATEGORYFROMGROUP}';
    // ]]>
  </script>

  <title>
    :: {CONST.ESF.TITLE} {TITLE}
    <!-- IF SUBTITLE2 --> / {SUBTITLE2}<!-- ENDIF -->
    ::
  </title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta http-equiv="imagetoolbar" content="no">

  <link rel="shortcut icon" href="favicon.ico">
  <link rel="icon" type="image/x-icon" href="favicon.ico">

  <link rel="stylesheet" type="text/css" href="layout/text.css">

  <!-- BEGIN HTMLHEADER.CSS -->
  <link rel="stylesheet" type="text/css" href="{CSS}">
  <!-- END HTMLHEADER.CSS -->

  <!-- dynamic font size -->
  <script type="text/javascript" src="js/efa/cookies.js"></script>
  <script type="text/javascript" src="js/efa/efa_fontsize.js"></script>

  <script type="text/javascript" src="js/_load.js"></script>

  <script type="text/javascript" src="layout/script.js"></script>

  <script type="text/javascript">
    // <![CDATA[
    var esf_cookieManager = new esf_Cookiemanager('esf_cookieManager');
    esf_cookieManager.setCookie('esf_jstest',true);
    <!-- BEGIN HTMLHEADER.SCRIPT -->
    {SCRIPT}
    <!-- END HTMLHEADER.SCRIPT -->
    <!-- addLoadEvent(setupZoom); -->
    // ]]>
  </script>

  <!-- BEGIN HTMLHEADER.JS -->
  <script type="text/javascript" src="{JS}"></script>
  <!-- END HTMLHEADER.JS -->

{HTMLHEADER.RAW}

</head>
