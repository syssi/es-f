<!-- COMMENT

-->

<div class="tabber">

  <!-- Only visible without JavaScript -->
  <div class="hideInTabber">
    <h2>[[Protocol.Groups]]</h2>
    <!-- BEGIN PROTOCOLS -->
      <div class="li"><a href="#{GROUP|hash}">{GROUP}</a></div>
    <!-- END PROTOCOLS -->
  </div>

  <!-- BEGIN PROTOCOLS -->

  <div class="tabbertab" title="{truncate:GROUP,"15",TRUE," ..."}" onmouseover="Tip('{js:GROUP}')">

    <a name="{GROUP|hash}"></a>

    <h2 class="tr1 grouptitle">
      {GROUP}
      <div class="hideInTabber r" style="font-size:70%;margin:-1em 0.5em 0 0">
        [ <a href="#pagetop" title="Go to top of page">/\</a> ]
      </div>
    </h2>

    <div class="tabber">

      <!-- IF LOG -->
      <div class="tabbertab">

        <h3>[[Protocol.Protocol]]</h3>

        <div class="actions">
          <a href="{SHOWURL}#bottom" onclick="openWin('{SHOWURL}#bottom',650,400,'scrollbars=yes'); return false;"><img
             src="module/protocol/layout/default/images/show.gif" title="[[Protocol.Show]]"
             alt="[[Protocol.Show]]" onmouseover="Tip('[[Protocol.Show]]')"></a>
          &nbsp;
          <a href="{DELETEURL}"><img src="layout/default/images/delete.gif"  title="[[Protocol.Delete]]"
             alt="[[Protocol.Delete]]" onmouseover="Tip('[[Protocol.Delete]]')"/></a>
        </div>

        <!-- IF __REVERSED --><small>([[Protocol.Reversed]])</small><!-- ENDIF -->

        <pre class="log">{LOG}</pre>

      </div>
      <!-- ENDIF LOG -->

      <!-- BEGIN LOG1 -->
      <div class="tabbertab">
        <h3>[[Protocol.ProtocolBidnow]]</h3>
        <pre class="log">{LOG1}</pre>
      </div>
      <!-- END LOG1 -->

      <!-- IF AUCTIONFILE -->
      <div class="tabbertab">
        <h3>[[Protocol.Auctionfile]]</h3>
        <pre class="log">{AUCTIONFILE}</pre>
      </div>
      <!-- ENDIF AUCTIONFILE -->

      <div class="tabbertab">
        <h3>[[Protocol.Auctions]]</h3>
        <!-- BEGIN AUCTIONS -->
          <a class="ebay" href="{ITEMURL}" title="{NAME}" onmouseover="Tip('{NAME}')">{NAME}</a>
          <br>
        <!-- END AUCTIONS -->
      </div>

    </div>

  </div>

  <!-- END PROTOCOLS -->

</div>
