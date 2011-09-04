<!--
/**
 *
 *
 */
-->

<div id="content" style="text-align:center">

<h1 class="c">
  [[Analyse.Group]]: {GROUPNAME} &nbsp; &nbsp; &nbsp;
  <a href="#" onclick="return ToggleAuctionsTable()">
    <img id="AnalyseImg" class="icon" style="display:none" src="{$IMGDIR}/show.gif"
         alt="⇓" onmouseover="Tip('[[Analyse.ShowAuctions]]')">
  </a>
</h1>

<div id="AuctionsTable">

  <table style="margin:0 auto 20px">
  <tr>
    <th style="text-align:left">[[Analyse.Auction]]</th>
    <th style="text-align:left"><tt>[[Analyse.End]]</tt></th>
    <th style="text-align:center"><tt>[[Analyse.Ended]]</tt></th>
    <th style="text-align:right"><tt>[[Analyse.Bid]]</tt></th>
    <th style="text-align:right"><tt>[[Analyse.Bids]]</tt></th>
  </tr>

  <!-- BEGIN AUCTIONS -->
  <tr class="{cycle:"class","tr1","tr2"}">
    <td style="text-align:left"><a class="ebay" href="{AUCTIONURL}">{NAME}</a></td>
    <td style="text-align:left"><tt>{END}</tt></td>
    <td style="text-align:center"><tt>{iif:ENDED,"X"}</tt></td>
    <td style="text-align:right"><tt>{currency:BID}</tt></td>
    <td style="text-align:right"><tt>{BIDS}</tt></td>
  </tr>
  <!-- END AUCTIONS -->

  </table>

  <a href="#" onclick="return ToggleAuctionsTable()">
    <img class="icon" src="{$IMGDIR}/hide.gif"
         alt="^" onmouseover="Tip('[[Analyse.HideAuctions]]')">
  </a>

  <p><a href="#pagetop">top</a></p>

</div>

<script type="text/javascript">
  // <![CDATA[
  // show toggle image
  $('AnalyseImg').show();
  // hide auction table
  $('AuctionsTable').hide();
  var AnalyseShowAuctions = '[[Analyse.ShowAuctions]]';
  var AnalyseHideAuctions = '[[Analyse.HideAuctions]]';
  // ]]>
</script>

<a name="diagram"></a>

<img style="width:{WIDTH}px;height:{HEIGHT}px" width="{WIDTH}" height="{HEIGHT}"
     src="module/analyse/analyse.php?{DATA}" alt="Generate graph, just a moment please...">

<p>[[Analyse.Description]]</p>

<!-- IF VARIANTS -->

<div id="chances">

  <h2>[[Analyse.ChanceHeader]]</h2>

  <div class="tabber">

    <!-- BEGIN VARIANTS -->
    <div class="tabbertab">

      <h3>[[Analyse.ChanceHeaderVariant]] {sequence:"variant"}</h3>

      <div>{CHANCE_MESSAGE_DESC}</div>

      <p>{translate:"Analyse.ChanceMessage",BEST.LOWER,BEST.UPPER,BEST.CHANCE}</p>

      <table class="chance">
      <tr>
        <th colspan="3">[[Analyse.PriceRange]]</th>
        <th colspan="2">[[Analyse.Auctions]]</th>
        <th>[[Analyse.Chance]]</th>
      </tr>

      <!-- BEGIN ROWS -->
      <tr class="{if:$ROWID,"=",_parent.BEST.ROW,"bestchance"}">
        <td>{currency:LOWER}</td>
        <td class="hyphen">-</td>
        <td>{currency:UPPER}</td>
        <!-- IF !COUNT -->
        <td colspan="3">&nbsp;</td>
        <!-- ELSE -->
        <td>{format:COUNT_PERCENT,"%.1f%%"}</td>
        <td style="text-align:left;width:99%">
          <div class="bar" style="padding:2px 0;width:{WIDTH}%">
            <small>&nbsp;{COUNT}</small>
          </div>
        </td>
        <td>{format:CHANCE,"%.1f%%"}</td>
        <!-- ENDIF -->
      </tr>
      <!-- END ROWS -->

      </table>

    </div>
    <!-- END VARIANTS -->

  </div><!-- tabber -->

</div><!-- chances -->

<p><a href="#pagetop">top</a></p>

<!-- ENDIF VARIANTS -->

</div>

