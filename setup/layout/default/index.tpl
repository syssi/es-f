<!--
/**
 *
 */
-->

<!-- INCLUDE inc.head -->

<body>

<div id="nonfooter">

<div id="header">

  <div style="text-align:center;padding:1px 5px">
    <div style="float:right;font-size:150%;font-weight:bold">
      <tt>{iif:INSTALL,"Installation","Reconfiguration"}</tt>
    </div>
    <div style="float:left;text-align:left;margin-bottom:5px">
      <tt>{CONST.ESF.LONG_TITLE}</tt><br>
      <small>{CONST.ESF.FULL_VERSION}</small>
    </div>

    <div style="max-width:600px;margin:auto">
      <h1>{SUBTITLE1}</h1>
    </div>
  </div>

  <hr style="clear:both" />

</div>

<!-- IF MESSAGES -->
<div class="msg">{MESSAGES}</div>
<hr />
<!-- ENDIF -->

{CONTENT}

<br /><br /><br />

</div>

<!-- IF CONST.PHP_VERSION >= CONST.PHP_VERSION_REQUIRED -->

<div id="footer">

<div style="height:30px;vertical-align:top;text-align:center">

  <div style="float:left;width:48%;text-align:right">
  <!-- IF PREVSTEP -->
  <a href="index.php?step={PREVSTEP}"><img alt="[ {PREVTEXT} << ]" title="Return to last step"
     src="{CONST.BUTTON}?d={CONST.IMAGES}%2Fbutton.cfg.php&amp;i={CONST.IMAGES}%2Fbutton-gray-l.gif&amp;t={PREVTEXT|urlencode}&amp;x=-3" /></a>
  <!-- ENDIF -->
  </div>

  <div style="float:right;width:48%;text-align:left">
  <!-- IF FORM_IS_OPEN -->
  <input style="display:inline" type="image" alt="[ >> {NEXTTEXT} ]" title="Go to next step"
         src="{CONST.BUTTON}?d={CONST.IMAGES}%2Fbutton.cfg.php&amp;i={CONST.IMAGES}%2Fbutton-gray-r.gif&amp;t={NEXTTEXT|urlencode}&amp;x=3" />
  </form>
  <!-- ELSEIF NEXTSTEP -->
  <a href="index.php?step={NEXTSTEP}"><img alt="[ >> {NEXTTEXT} ]" title="Go to next step"
     src="{CONST.BUTTON}?d={CONST.IMAGES}%2Fbutton.cfg.php&amp;i={CONST.IMAGES}%2Fbutton-gray-r.gif&amp;t={NEXTTEXT|urlencode}&amp;x=3" /></a>
  <!-- ENDIF -->
  </div>

</div>

</div>

<!-- ENDIF -->

</body>
</html>
