<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<tr class="th th1" style="border-top:dashed gray 1px">
  <th colspan="6">[[Auction.Auctions]]</th>
  <th>
    <span id="autorefresh" style="display:none">
      <input type="checkbox"
             title="Auto-Refresh of soon ending auctions"
             onmouseover="Tip('Auto-Refresh of soon ending auctions')"
             onclick="esf_CountDownRefresh=this.checked" checked="checked">
      {help:"CoreHelp.RefreshEnding"}
    </span>
    <script type="text/javascript">
      // <![CDATA[
      FastInit.addOnLoad(function() { $('autorefresh').show() });
      // ]]>
    </script>
  </th>
  <th colspan="2" style="border-left:dashed gray 1px">[[Auction.Groups]]</th>
</tr>

