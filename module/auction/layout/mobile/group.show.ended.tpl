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

<td class="{GCLASS} group1" rowspan="{GROUP.COUNT}">
  <!-- IF GROUP.NAME <> ITEM -->
    <small>{GROUP.NAME}</small>
    <br>
  <!-- ENDIF -->
  <tt><!-- IF GROUP.QUANTITY > "1" -->{GROUP.QUANTITY} / <!-- ENDIF -->{currency:GROUP.BID}</tt>
</td>

<td class="{GCLASS} group2" rowspan="{GROUP.COUNT}">&nbsp;</td>
