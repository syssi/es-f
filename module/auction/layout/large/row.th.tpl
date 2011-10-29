<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<tr class="th">

<!--
  <th>&nbsp;</th>
-->
  <th colspan="5">
    [[Auction.Auction]]
  </th>
  
  <th>
    <input id="cb_autorefresh" type="checkbox" style="display:none"
           title="Auto-Refresh of soon ending auctions"
           onmouseover="Tip('Auto-Refresh of soon ending auctions')"
           onclick="esf_CountDownRefresh=this.checked" checked="checked">
    <script type="text/javascript">
      // <![CDATA[
      FastInit.addOnLoad(function() { $('cb_autorefresh').show() });
      // ]]>
    </script>
  </th>

  <th>
    <img src="layout/default/images/tool.gif" alt="[[Auction.Actions]]">
  </th >
  
  <th style="border-left:dashed gray 1px">
    <img src="module/auction/layout/default/images/mybid.gif"
        alt="[[Auction.Quantity]] / [[Auction.MyBid]]">
  </th>
  
  <th><img src="layout/default/images/tool.gif" alt="[[Auction.Actions]]"></th>

</tr>
