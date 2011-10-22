<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<form action="{server:"PHP_SELF"}#{ITEM}" method="post">
  {fh:"module","auction"}
  {fh:"action","editauction"}
  {fh:"item",ITEM}

  <h3 class="c">{RAW.NAME}</h3>

  <label class="MB_label" for="image">[[Auction.ImageUrl]]</label>
  <div class="MB_inputs">
    {ft:"image",,"input MB_input"}
    <!-- IF CONST.DEVELOP -->
    <br>
    {fcb:"imagere"}reread from ebay (during development only)
    <!-- ENDIF -->
  </div>

  <label class="MB_label" for="currency">[[Auction.Currency]]</label>
  <div class="MB_inputs">
    {ft:"currency",CURRENCY,"input","size=\"5\""}
    <!-- IF CONST.MODULE.CURRENCY -->
      [[Auction.Or]] {fcb:"currencydef"}{CONST.MODULE.CURRENCY}
    <!-- ENDIF -->
  </div>

  <label class="MB_label" for="shippingfree">[[Auction.Shipping]]</label>
  <div class="MB_inputs">
    <input class="input num" type="text" size="8" name="shipping" value="{currency:SHIPPING}">
    [[Auction.Or]]
    {if:SHIPPING,"=","FREE","on","" > SHIPPINGFREE}
    {fcb:"shippingfree",TRUE,SHIPPINGFREE}[[Auction.ShippingFree]]
  </div>

  <label class="MB_label" for="rotate">[[Auction.ImageRotate]]</label>
  <div class="MB_inputs">
    <img id="ImageRotated{ITEM}" alt=""
         src="html/image.php?i={IMGURL}&amp;d&amp;h=25&amp;r=0" noimagesize>
    &nbsp;
    {"onclick='var img=$(\"ImageRotated",ITEM,"\");if(img && this.checked)img.src=img.src.replace(/r=-?\d+/,\"r=\"+this.value)'" > ONCLICK}
    {frb:"rotate","0","0",,ONCLICK}[[Auction.ImageRotateNo]]
    {frb:"rotate","-90",,,ONCLICK}
    <img src="{$IMGDIR}/rotate-left.gif" alt="[[Auction.ImageRotateLeft]]">90°
    {frb:"rotate","90",,,ONCLICK}
    <img src="{$IMGDIR}/rotate-right.gif" alt="[[Auction.ImageRotateRight]]">90°
    {frb:"rotate","180",,,ONCLICK}180°
  </div>

  <label class="MB_label" for="comment">[[Auction.Comment]]</label>
  <div class="MB_inputs">
   {ft:"comment",COMMENT,"input MB_input"}
  </div>

  <label class="MB_label" for="CategorySelect_{ITEM}">[[Auction.Category]]</label>
  <div class="MB_inputs">
    <select id="CategorySelect_{ITEM}" name="category">
      {options:CONST.CATEGORIES,CATEGORY.NAME}
    </select>
    &nbsp; [[Auction.Or]] &nbsp;
    <input name="categorynew" class="input" placeholder="[[Auction.NewCategoryHint]]">
  </div>

  <label class="MB_label" for="GroupSelect_{ITEM}">[[Auction.Group]]</label>
  <div class="MB_inputs">
    <select id="GroupSelect_{ITEM}" name="group" onchange="SetGroupCategory(this.value,'CategorySelect_{ITEM}')">
      {options:CONST.GROUPS,GROUP.NAME}
    </select>
    &nbsp; [[Auction.Or]] &nbsp;
    <input name="groupnew" class="input" placeholder="[[Auction.NewGroupHint]]">
  </div>

  <label class="MB_label" for="mybid">[[Auction.AuctionBid]]</label>
  <div class="MB_inputs">
    {currency:MYBID,FALSE > CUR_MYBID}
    {ft:"mybid",CUR_MYBID,"input num","size=\"8\""}
    &nbsp; [[Auction.DifferentFromGroup]]
  </div>

  <label class="MB_label" for="now">[[Auction.BidNow]]</label>
  <div class="MB_inputs">
    {ft:"now",,"input num","style=\"float:left\" size=\"8\""}
    &nbsp; <tt>esniper -s now ...</tt>
  </div>

  <div class="MB_buttons">
    <input type="submit" class="button" name="confirm" value="[[Auction.EditSaveAuction]]">
  </div>

</form>
