
<td id="td_group1_{GROUP.NAME|hash}" rowspan="{GROUP.COUNT}"
    class="{GCLASS} group1 {iif:GROUP.ACTIVE,"group_active"}">
  <a name="{GROUP.NAME|hash}"></a>
  <small>
  <!-- IF GROUP.COMMENT -->
    <abbr title="{GROUP.COMMENT|striptags|quote}"
          onmouseover="Tip('{js:GROUP.COMMENT}',TITLE,'[[Auction.Comment]]')">{GROUP.NAME}</abbr>
    <br><br>
  <!-- ELSEIF GROUP.NAME <> ITEM -->
    {GROUP.NAME}
    <br><br>
  <!-- ENDIF -->
  </small>
  <span
    <!-- IF GROUP.ACTIVE -->
    onmouseover="Tip('{js:[[Auction.EsniperIsRunning]]}')"
    <!-- ENDIF -->
  >
  <!-- IF GROUP.TOTAL -->
    <img class="grouptotal" src="{$IMGDIR}/total.gif" alt="(T)"
         title="[[Auction.GroupTotal|striptags|quote]]"
         onmouseover="Tip('{js:[[Auction.GroupTotal]]}')">
  <!-- ENDIF -->
  <tt><!-- IF GROUP.QUANTITY > "1" -->{GROUP.QUANTITY} / <!-- ENDIF -->{currency:GROUP.BID}</tt>
  </span>
</td>

<td id="td_group2_{GROUP.NAME|hash}" rowspan="{GROUP.COUNT}"
    class="{GCLASS} group2 {iif:GROUP.ACTIVE,"group_active"}">
  <!-- INCLUDE inc.group.edit -->
  <br>
  <!-- INCLUDE inc.group.startstop -->
</td>
