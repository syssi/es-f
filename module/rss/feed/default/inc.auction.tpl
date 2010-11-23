{__LASTUPDATE,ITEM > GUID}
<item>
  <title>
    <![CDATA[
      {NAME}
      <!-- IF ENDTS -->
        <!-- IF REMAIN > "0" -->
          ({REMAIN})
        <!-- ELSE -->
          {CONST.LANG.RSS_ENDED}
        <!-- ENDIF -->
      <!-- ENDIF -->
    ]]>
  </title>
  <link>{ITEMURL}</link>
  <guid>{GUID|hash}</guid>
  <!-- IF CATEGORY -->
  <category>[[Rss.Category]]: {CATEGORY}</category>
  <!-- ENDIF -->
  <!-- IF GROUP -->
  <category>[[Rss.Group]]: {GROUP}</category>
  <!-- ENDIF -->
  <!-- IF ENDED -->
  <category>[[Rss.Ended]]</category>
  <!-- ENDIF -->
  <description>
    <![CDATA[
      <table>
      <tr>
        <td style="vertical-align:top">

        <table>
        <!-- IF ENDTS -->
        <tr>
          <td>[[Rss.End]]</td>
          <td>:</td>
          <td>{END}</td>
        </tr>

        <!-- IF !ENDED -->
        <tr>
          <td><strong>[[Rss.Remain]]</strong></td>
          <td>:</td>
          <td><strong>{iif:ENDTS,REMAIN,[[RSS.NoEnd]]}</strong></td>
        </tr>
		<!-- ELSE -->
        <tr>
          <td colspan="3"><strong>[[Rss.AuctionEnded]]</strong></td>
        </tr>
        <!-- ENDIF !ENDED -->

        <tr>
          <td>[[Rss.Bids]]</td>
          <td>:</td>
          <td><!-- IF BIDS > "0" -->{BIDS}<!-- ELSE -->[[Rss.None]]<!-- ENDIF --></td>
        </tr>
        <!-- ENDIF ENDTS -->

        <tr>
          <td>{if:BIN,"<>",,[[RSS.BinPrice]],[[RSS.Bid]]}</td>
          <td>:</td>
          <td>{currency:BID,,CURRENCY}</td>
        </tr>

        <!-- IF !ENDED -->
        <tr>
          <td>[[Rss.Shipping]]</td>
          <td>:</td>
          <!-- IF SHIPPING = "FREE" -->
          <td>[[Auction_shipping_free.]]</td>
          <!-- ELSE -->
          <td>{currency:SHIPPING,"--",CURRENCY}</td>
          <!-- ENDIF SHIPPING... -->
        </tr>

        <tr>
          <td><strong>[[Rss.Total]]</strong></td>
          <td>:</td>
          {calc:BID,"+",SHIPPING > PRICETOTAL}
          <td><strong>{currency:PRICETOTAL,,CURRENCY}</strong></td>
        </tr>

        <!-- IF COMMENT -->
        <tr>
          <td>[[Rss.Comment]]</td>
          <td>:</td>
          <td>{COMMENT}</td>
        </tr>
        <!-- ENDIF COMMENT -->

        <!-- ENDIF !ENDED -->

        <!-- IF CATEGORY -->
        <tr>
          <td>[[Rss.Category]]</td>
          <td>:</td>
          <td>{CATEGORY}</td>
        </tr>
        <!-- ENDIF -->

        <!-- IF GROUP -->
        <tr>
          <td>[[Rss.Group]]</td>
          <td>:</td>
          <td>{GROUP}</td>
        </tr>
        <!-- ENDIF -->

        </table>

        </td>

        <td>&nbsp;&nbsp;</td>

        <td style="vertical-align:top">
          <a href="{BASEHTML}html/image.php?i={IMGURL}" target="_blank"><img style="border:0" src="{BASEHTML}layout/image.php?i={IMGURL}&amp;m=100"></a>
        </td>

      </tr>
      </table>
    ]]>
  </description>
</item>
