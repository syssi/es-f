<!--
/**
 * @author    Knut Kohl / knutkohl@users.sourceforge.net
 *
 * CHANGELOG
 * =========
 * [2011-10-22]
 * - reintegrate all JS and CSS loading
 *
 * @version   $Id$
 * @revision  $Rev$
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
    var TabberRootDir = 'js/';
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

  <script type="text/javascript" src="js/fastinit.js"></script>

  <!-- dynamic font size -->
  <script type="text/javascript" src="js/cookies.js"></script>
  <script type="text/javascript" src="js/efa/efa_fontsize.js"></script>

  <!-- Libraries -->
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/prototype/1/prototype.js"></script>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/scriptaculous/1/scriptaculous.js"></script>
  <script type="text/javascript" src="/js/prototypePlus.js"></script>

  <script type="text/javascript" src="js/ModalBox/modalbox.js"></script>
  <link rel="stylesheet" type="text/css" href="js/ModalBox/modalbox.css" media="screen">

  <script type="text/javascript" src="js/tabber.js"></script>
  <script type="text/javascript" src="js/string.js"></script>
  <script type="text/javascript" src="js/dialog.js"></script>

  <script type="text/javascript" src="layout/script.js"></script>
  <!-- BEGIN HTMLHEADER.JS -->
  <script type="text/javascript" src="{JS}"></script>
  <!-- END HTMLHEADER.JS -->

  <link rel="stylesheet" type="text/css" href="layout/text.css">
  <!-- BEGIN HTMLHEADER.CSS -->
  <link rel="stylesheet" type="text/css" href="{CSS}">
  <!-- END HTMLHEADER.CSS -->

  <script type="text/javascript">
    document.write('<style type="text/css">.tabber{ display:none; }<\/style>');

    // <![CDATA[
    <!-- BEGIN HTMLHEADER.SCRIPT -->
    {SCRIPT}
    <!-- END HTMLHEADER.SCRIPT -->

    efa_bigger[1]  = '<img src="layout/default/images/text+1.gif">';
    efa_bigger[2]  = '[[Core.Efabigger]]';
    efa_bigger[7]  = 'Tip(\'[[Core.EfaBigger]]\')';
    efa_reset[1]   = '<img src="layout/default/images/text0.gif">';
    efa_reset[2]   = '[[Core.Efareset]]';
    efa_reset[7]   = 'Tip(\'[[Core.EfaReset]]\')';
    efa_smaller[1] = '<img src="layout/default/images/text-1.gif">';
    efa_smaller[2] = '[[Core.Efasmaller]]';
    efa_smaller[7] = 'Tip(\'[[Core.EfaSmaller]]\')';

    var cookieManager =
      new Cookiemanager('cookieManager', '', 1, 'years', document.domain);
    var efa_fontSize =
      new Efa_Fontsize(efa_increment,
                       efa_bigger, efa_reset, efa_smaller,
                       efa_default);
    if (efa_fontSize) efa_fontSize.efaInit();
    // ]]>
  </script>

{HTMLHEADER.RAW}

</head>
