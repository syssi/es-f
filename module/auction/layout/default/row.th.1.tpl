<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<tr class="th th1" style="border-top:dashed gray 1px">
  <th>
    <input id="autorefresh" style="display:none"
           type="checkbox" checked="checked"
           title="[[CoreHelp.RefreshEnding|striptags|quote]]"
           data-tip="[[CoreHelp.RefreshEnding|striptags|quote]]"
           onmouseover="Tip(this,WIDTH,250)"
           onclick="esf_CountDownRefresh=this.checked">
    <script type="text/javascript">
      // <![CDATA[
      FastInit.addOnLoad(function() { $('autorefresh').show() });
      // ]]>
    </script>
  </th>
  <th colspan="6">[[Auction.Auctions]]</th>
  <th colspan="2" style="border-left:dashed gray 1px">[[Auction.Groups]]</th>
</tr>