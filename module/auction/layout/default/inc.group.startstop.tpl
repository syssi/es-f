<!--
/*
 *
 */
-->

<!-- IF GROUP.ACTION -->

  <script type="text/javascript">
    // <![CDATA[
    // initial icon hint
    tip_startstop['{GROUP.NAME|hash}'] = '{GROUP.ACTIVE}' ? tip_stop : tip_start;
    // ]]>
  </script>

  <a id="a_startstop_{GROUP.NAME|hash}" href="{GROUP.STARTSTOPURL}"
     onclick="return ajaxStartGroup('{GROUP.NAME}','{GROUP.NAME|hash}')">
    <img id="img_startstop_{GROUP.NAME|hash}" class="icon"
         src="layout/default/images/{GROUP.ACTION}.gif"
         alt="S" title="[[Auction.Startstop]]"
         onmouseover="Tip(tip_startstop['{GROUP.NAME|hash}'])">
  </a>

<!-- ELSE -->

  <img class="icon" src="layout/default/images/start-d.gif" alt=""
       onmouseover="Tip('[[Auction.NoBidDefinedYet]]')">

<!-- ENDIF -->
