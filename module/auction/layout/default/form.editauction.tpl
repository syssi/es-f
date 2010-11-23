<!--
/*
 *
 * CHANGELOG
 * - output raw auction name, not the modified name during DisplayAuction event
 *
 */
-->

<form action="{server:"PHP_SELF"}#{ITEM}" method="post">
{fh:"module","auction"}
{fh:"action","editauction"}
{fh:"item",ITEM}
{fh:"ajax",POPUPFORM}

<div class="editform">

  <!-- IF !POPUPFORM -->
  <h2 class="c">[[Auction.EditAuction]]: {ITEM}</h2>
  <!-- ENDIF -->

  <table class="popup" style="width:100%">

    <tr>
      <td colspan="5">[[Auction.ShouldReadAutomatic]]</td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td class="b c" colspan="5">{RAW.NAME}</td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td class="nowrap" style="width:35%">
        [[Auction.ImageUrl]]
      </td>
      <td>:</td>
      <td colspan="3">
        {ft:"image",,"input","style=\"width:95%\""}
        <!-- IF CONST.DEVELOP -->
        <br>
        {fcb:"imagere"}reread from ebay (during development only)
        <!-- ENDIF -->
      </td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td>[[Auction.Currency]]</td>
      <td>:</td>
      <td>
        {ft:"currency",CURRENCY,"input","size=\"5\""}
      </td>
      <td colspan="2">
        <!-- IF CONST.MODULE.CURRENCY -->
        [[Auction.Or]] {fcb:"currencydef"}{CONST.MODULE.CURRENCY}
        <!-- ELSE -->
        &nbsp;
        <!-- ENDIF -->
      </td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td>[[Auction.Shipping]]</td>
      <td>:</td>
      <td>
        <input class="input num" type="text" size="8" name="shipping" value="{currency:SHIPPING}">
      </td>
      <td colspan="2">
        [[Auction.Or]]
        {if:SHIPPING,"=","FREE" > SHIPPINGFREE}
        {fcb:"shippingfree",TRUE,SHIPPINGFREE}[[Auction.ShippingFree]]
      </td>
    </tr>

    <tr>
      <td class="b" colspan="5">[[Auction.YourAuctionSettings]]</td>
    </tr>

    <!-- /* reset row class */ -->
    {cycle:"tr"}

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td style="vertical-align:middle">
         <div style="height:25px;vertical-align:middle">
         <span style="float:left">[[Auction.ImageRotate]]</span>
         <img id="ImageRotated{ITEM}" style="float:right" alt=""
              src="html/image.php?i={IMGURL}&amp;d&amp;h=25&amp;r=0" noimagesize>
         </div>
      </td>
      <td>:</td>
      <td style="width:75%;vertical-align:middle" colspan="3">
        {"onclick='var img=$(\"ImageRotated",ITEM,"\");if(img && this.checked)img.src=img.src.replace(/r=-?\d+/,\"r=\"+this.value)'" > ONCLICK}
        {frb:"rotate","0","0",,ONCLICK}[[Auction.ImageRotateNo]]
        {frb:"rotate","-90",,,ONCLICK}
        <img src="{$IMGDIR}/rotate-left.gif" alt="[[Auction.ImageRotateLeft]]">90°
        {frb:"rotate","90",,,ONCLICK}
        <img src="{$IMGDIR}/rotate-right.gif" alt="[[Auction.ImageRotateRight]]">90°
        {frb:"rotate","180",,,ONCLICK}180°
      </td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td>[[Auction.Comment]]</td>
      <td>:</td>
      <td colspan="3">
        {ft:"comment",COMMENT,"input","style=\"width:95%\""}
      </td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td>[[Auction.Category]]</td>
      <td>:</td>
      <td>
        <select id="CategorySelect_{ITEM}" name="category">
          {options:CONST.CATEGORIES,CATEGORY.NAME}
        </select>
      </td>
      <td>
        [[Auction.Or]]
      </td>
      <td>
        {ft:"categorynew",,"input","style=\"width:95%\""}
      </td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td>[[Auction.Group]]</td>
      <td>:</td>
      <td>
        <select name="group" onchange="SetGroupCategory(this.value,'CategorySelect_{ITEM}')">
          {options:CONST.GROUPS,GROUP.NAME}
        </select>
      </td>
      <td>
        [[Auction.Or]]
      </td>
      <td style="width:90%">
        {ft:"groupnew",,"input","style=\"width:95%\""}
      </td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td>[[Auction.AuctionBid]]</td>
      <td>:</td>
      <td>
        {currency:MYBID,FALSE > CUR_MYBID}
        {ft:"mybid",CUR_MYBID,"input num","size=\"8\""}
      </td>
      <td colspan="2">
        [[Auction.DifferentFromGroup]]
      </td>
    </tr>

    <tr class="{cycle:"tr","tr1","tr2"}">
      <td>
        [[Auction.BidNow]]
      </td>
      <td>:</td>
      <td>
        {ft:"now",,"input num","size=\"8\""}
      </td>
      <td colspan="2">
        <tt>esniper -s now ...</tt>
        <br>
        <small>([[Auction.UseToBreakBuyNow]])</small>
      </td>
    </tr>

    <tr>
      <td class="c spacer" colspan="5">
        <input class="button" style="width:150px" type="submit" name="confirm"
               value="[[Auction.EditSaveAuction]]"
               <!-- IF POPUPFORM -->
               onclick="return !RemovePopupWindow('PopupAuctionEdit{ITEM}',false)"
               <!-- ENDIF -->
        >
        &nbsp;&nbsp;&nbsp;
        <input class="button" style="width:150px" type="submit" name="cancel"
               value="[[Auction.EditCancel]]"
               <!-- IF POPUPFORM -->
               onclick="return RemovePopupWindow('PopupAuctionEdit{ITEM}')"
               <!-- ENDIF -->
       >
      </td>
    </tr>

  </table>

</div>

</form>
