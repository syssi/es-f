<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<input type="checkbox" name="auctions[]" value="{ITEM}"
       id="{CATEGORY.NAME|hash}-{GROUP.NAME|hash}-{ITEM}"
       onclick="ToggleAuctionRow(this.id, this.checked)">