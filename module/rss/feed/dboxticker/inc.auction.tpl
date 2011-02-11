{__LASTUPDATE,ITEM > GUID}
<item>
  <title>
    <![CDATA[
    {NAME}: {currency:BID,,CURRENCY}
    <!-- IF ENDTS -->
      ({BIDS}) - {if:REMAIN,">","0",REMAIN,[[RSS.Ended]]}
      <!-- IF BIDS --> - {RAW.BIDDER}<!-- ENDIF -->
    <!-- ENDIF -->
    ]]>
  </title>
  <guid>{GUID|hash}</guid>
  <description>
    <![CDATA[
    {NAME}
    <!-- IF REMAIN <= "0" --> ([[Rss.Ended]])<!-- ENDIF -->
    <!-- IF ENDTS -->
      | [[Rss.End]]: {END}
      <!-- IF !ENDED -->
        | [[Rss.Remain]]: {iif:ENDTS,REMAIN,[[RSS.NoEnd]]}
      <!-- ENDIF !ENDED -->
      | [[Rss.Bids]]:
      <!-- IF BIDS -->
        {BIDS}
      <!-- ELSE -->
        [[Rss.None]]
      <!-- ENDIF -->
    <!-- ENDIF ENDTS -->
    | {iif:BIN,[[RSS.BinPrice]],[[RSS.Bid]]}: {currency:BID,,CURRENCY}
    <!-- IF !ENDED -->
      | [[Rss.Shipping]]:
      <!-- IF SHIPPING = "FREE" -->
        [[Auction_shipping_free.]]
      <!-- ELSE -->
        {currency:SHIPPING,"--",CURRENCY}
        {calc:BID,"+",SHIPPING > PRICETOTAL}
        | [[Rss.Total]]: {currency:PRICETOTAL,,CURRENCY}
      <!-- ENDIF SHIPPING -->
      <!-- IF COMMENT -->
        | [[Rss.Comment]]: {COMMENT}
      <!-- ENDIF COMMENT -->

      <!-- IF GROUP -->
        | [[Rss.Group]]: {GROUP}
      <!-- ENDIF GROUP -->
    <!-- ENDIF !ENDED -->
    ]]>
  </description>
</item>