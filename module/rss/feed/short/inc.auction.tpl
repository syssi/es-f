{__LASTUPDATE,ITEM > GUID}
<item>
  <title>
    <![CDATA[
      {NAME}: {currency:BID,,CURRENCY}<!-- IF ENDTS --> ({BIDS}) - {if:ENDED,"<>",TRUE,REMAIN,[[RSS.Ended]]}<!-- ENDIF -->
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
</item>
