<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<tr class="th" style="border-top:dashed gray 1px">
  <th style="text-align:center">
    <img id="NoGroupTarget" src="{__$IMGDIR}/target.gif" alt="[no group]"
         style="width:20px;height:20px"
         title="[[Auction.Droptarget]]: [[Auction.DropRemoveGroup]]"
         onmouseover="Tip('{js:[[Auction.DropRemoveGroup]]}',TITLE,'{js:[[Auction.DropTarget]]}',WIDTH,200)">
    <script type="text/javascript">
      // <![CDATA[
      FastInit.addOnLoad(function() {
        Droppables.add('NoGroupTarget', {
          accept: 'draggable',
          onHover: ItemHover,
          onDrop: ItemDropNoGroup,
        });
      });
      // ]]>
    </script>
  </th>

  <th colspan="5">[[Auction.Auctions]]</th>

  <th>
    <script type="text/javascript">
      // <![CDATA[
      document.write('<input type="checkbox" title="Auto-Refresh of soon ending auctions"' +
                     'onmouseover="Tip(\'Auto-Refresh of soon ending auctions\')"'+
                     'onchange="esf_CountDownRefresh=this.checked" checked>');
      // ]]>
    </script>
  </th>

  <th colspan="2" style="border-left:dashed gray 1px">[[Auction.Groups]]</th>
</tr>
