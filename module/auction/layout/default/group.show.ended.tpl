<!--
/**
 * @version 0.2.0
 * @author Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * ############################################################################
 *   For auction delete via AJAX, the same column count is required
 *   as in group.show!
 * ############################################################################
 *
 * If no group bid was placed, suppress the uninteresting data ;-)
 *
 * CHANGELOG
 *
 * Version 0.2.0
 * -------------
 * CHANGED
 * - remove group name if equal item id
 */
-->

<td class="{GCLASS}" rowspan="{GROUP.COUNT}"
    style="text-align:right; border-left:dashed gray 1px; white-space:nowrap">
  <!-- IF GROUP.COMMENT -->
    <abbr title="{GROUP.COMMENT|striptags|quote}"
          onmouseover="Tip('{js:GROUP.COMMENT}')"><small>{GROUP.NAME}</small></abbr>
    <br>
  <!-- ELSEIF GROUP.NAME <> ITEM -->
    <small>{GROUP.NAME}</small>
    <br>
  <!-- ENDIF -->
  <!-- IF GROUP.TOTAL -->
    <img class="grouptotal" src="{$IMGDIR}/total.gif" alt="(T)"
         title="[[Auction.GroupTotal|striptags|quote]]"
         onmouseover="Tip('{js:[[Auction.GroupTotal]]}')">
  <!-- ENDIF -->
  <tt><!-- IF GROUP.QUANTITY > "1" -->{GROUP.QUANTITY} / <!-- ENDIF -->{currency:GROUP.BID}</tt>
</td>

<td class="{GCLASS} group2" rowspan="{GROUP.COUNT}">&nbsp;</td>
