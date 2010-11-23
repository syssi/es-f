<!-- COMMENT

-->

<table id="savings">

  <tr>
    <th class="l">[[Savings.Auction]]</th>
    <th class="r">[[Savings.Won]]</th>
    <th class="r">[[Savings.YourBid]]</th>
    <th colspan="2">[[Savings.Saving]]</th>
  </tr>

  <!-- BEGIN AUCTIONS -->

  <tr class="{cycle:"tr","tr1,"tr2"}">
    <td>{Name}</td>
    <td class="num r">{currency:PRICE}</td>
    <td class="num r">{currency:BID}</td>
    <td class="num r">{currency:SAVING}</td>
    <td class="num r">{format:SAVINGPERCENT,"%.2f"}%</td>
  </tr>

  <!-- END AUCTIONS -->

  <tr class="tr3">
    <td class="r b">[[Savings.Total]]:</td>
    <td class="r b total">{currency:TOTAL.PRICE}</td>
    <td class="r b total">{currency:TOTAL.BID}</td>
    <td class="r b total">{currency:TOTAL.SAVING}</td>
    <td class="r total">&empty; {format:TOTAL.SAVINGPERCENT,"%.2f"}%</td>
  </tr>

</table>