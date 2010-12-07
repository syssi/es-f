<!-- COMMENT
/**
 *
 */
-->

<script type="text/javascript">
  // <![CDATA[
  var id = '';
  if ((!'{ENDED}' || ('{ENDED}' == '0')) &&
      (({CONST.MODULE.COUNTDOWN} == 2) ||
       (({CONST.MODULE.COUNTDOWN} == 1) && '{NEXTAUCTION}'))) {
    // remember end timestamp and item no.
    esf_CountDown[esf_CountDown.length] = new Array('{ENDTS}','{ITEM}');
    // container element for countdown, prefilled with formated remaining time
    id = ' id="' + esf_CountDownPrefix + '{ITEM}"';
  }
  document.write("<tt" + id + ">{REMAIN}<\/tt>");
  // ]]>
</script>

<noscript><tt>{REMAIN}</tt></noscript>
