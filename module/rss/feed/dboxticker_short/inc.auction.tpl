{__LASTUPDATE,ITEM > GUID}
<item>
  <title>
    {NAME}: {currency:BID,,CURRENCY}
    <!-- IF ENDTS -->
      ({BIDS}) - {if:REMAIN,">","0",REMAIN,[[RSS.Ended]]}
      <!-- IF BIDS --> - {RAW.BIDDER}<!-- ENDIF -->
    <!-- ENDIF -->
  </title>
  <guid>{GUID|hash}</guid>
  <description>
    <![CDATA[
    {NAME}
    <!-- IF GROUP --> ({GROUP})<!-- ENDIF -->
    <!-- IF REMAIN <= "0" --> [[[Rss.Ended]]]<!-- ENDIF -->
    <!-- IF ENDTS -->
      | {END}
      <!-- IF !ENDED -->
        | {iif:ENDTS,REMAIN,[[RSS.NoEnd]]}
      <!-- ENDIF -->
      <!-- IF BIDS -->
        | {BIDS}
      <!-- ENDIF -->
    <!-- ENDIF ENDTS -->
    | {currency:BID,,CURRENCY}
    <!-- IF !ENDED -->
      <!-- IF SHIPPING = "FREE" -->
        ([[Auction_shipping_free.]])
      <!-- ELSE -->
        + {currency:SHIPPING,"--",CURRENCY}
          {calc:BID,"+",SHIPPING > PRICETOTAL}
        = {currency:PRICETOTAL,,CURRENCY}
      <!-- ENDIF -->
    <!-- ENDIF !ENDED -->
    ]]>
  </description>
</item>