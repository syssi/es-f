<!--
/**
 *
 */
-->

<table class="w100"><tr><td class="c" style="width:50%">

<h2>[[Exchangerates.Currencies]]</h2>

<table>

<tr class="{cycle:"tr","tr1","tr2"}">
  <th style="text-align:left;width:20em" colspan="2">[[Exchangerates.Currency]]</th>
  <th style="text-align:right">[[Exchangerates.Rate]]</th>
</tr>

<!-- BEGIN RATES -->
<!-- IF $ROWID <> "EUR" -->
<tr class="{cycle:"TR","tr1","tr2"}">
  <td class="l">{NAME|html}</td>
  <td><tt>1 EUR =</tt></td>
  <td style="text-align:right"><tt>{format:RATE,"%.4f"} {$ROWID}</tt></td>
</tr>
<!-- ENDIF -->
<!-- END RATES -->

</table>

</td>

<td class="c t">

<h2>[[Exchangerates.Calculate]]</h2>

{form:}
{fh:"module","exchangerates"}

<p style="font-family:monospace">
{ft:"amount",AMOUNT,"input c","size=\"10\""}

<br><br>

<!-- BEGIN BLOCK CURR_SELECT -->
<select name="{SELNAME}" style="font:inherit !important">
<!-- BEGIN RATES -->
  <option value="{$ROWID}" {if:$ROWID,"=",__SELCURR,"selected"}>{$ROWID} - {NAME}</option>
<!-- END RATES -->
</select>
<!-- END BLOCK CURR_SELECT -->

{"scurr" > SELNAME}{SCURR > SELCURR}
<!-- BLOCK CURR_SELECT -->

<br><br>

<tt style="font-size:300%"><strong>=</strong></tt>

<br><br>

<strong style="font-size:150%">{format:CALCED,"%.2f"}</strong>

<br><br>

{"dcurr" > SELNAME}{DCURR > SELCURR}
<!-- BLOCK CURR_SELECT -->

<br><br>

<input class="button" type="submit" value="[[ExchangeRates.Calc|quote]]">
</p>

</form>

</td></tr></table>

<p class="c">
[[Exchangerates.Source]]: {SOURCE} ({a:SOURCEURL})
</p>
