<!--
/*
 *
 */
-->

<form action="{server:"PHP_SELF"}#{GROUP.NAME|hash}" method="post">
{fh:"module","auction"}
{fh:"action","editgroup"}
{fh:"group",GROUP.NAME}
{fh:"ajax",POPUPFORM}

<div class="editform">

  <!-- IF !POPUPFORM -->
  <h2 class="c">[[Auction.EditGroup]]: {GROUP.NAME}</h2>
  <!-- ENDIF -->

  <table class="popup" style="width:100%">

    <tr class="{cycle:"EDITROW","tr1","tr2"}">
      <td class="nowrap" style="width:25%">[[Auction.Rename]]</td>
      <td>:</td>
      <td style="width:75%">
        <input class="input" style="width:95%" name="groupnew" value="{nvl:GROUP.AUCTIONGROUP}">
        <br>
        <small>([[Auction.RemoveGroupWillSplit]])</small>
      </td>
    </tr>

    <tr class="{cycle:"EDITROW","tr1","tr2"}">
      <td class="nowrap">[[Auction.Quantity]]</td>
      <td>:</td>
      <td><input class="input num" size="3" name="q" value="{GROUP.QUANTITY}"> [[Auction.Piece]]</td>
    </tr>

    <tr class="{cycle:"EDITROW","tr1","tr2"}">
      <td class="nowrap">[[Auction.GroupBid]]</td>
      <td>:</td>
      <td>
        <input style="float:left" class="input num" type="text" size="8" name="b" value="{currency:GROUP.BID}"
               onfocus="if(this.value=='0,00')this.value=''">
        <div style="margin-left:8em">
          <input type="radio" name="t" value="0" checked="checked">[[Auction.GroupSingle]]
          <br>
          <input type="radio" name="t" value="1" {iif:GROUP.TOTAL,"checked=\"checked\""}>[[Auction.GroupTotal]]
        </div>
      </td>
    </tr>

    <tr class="{cycle:"EDITROW","tr1","tr2"}">
      <td class="nowrap">[[Auction.GroupComment]]</td>
      <td>:</td>
      <td>{ft:"c",GROUP.COMMENT,"input","style=\"width:95%\""}</td>
    </tr>

    <tr>
      <td class="c spacer" colspan="3">
        <input class="button" style="width:150px" type="submit" name="start"
               value="[[Auction.EditStartGroup]]"
               <!-- IF POPUPFORM -->
               onclick="return !RemovePopupWindow('PopupGroupEdit{GROUP.NAME|hash}',false)"
               <!-- ENDIF -->
        >
        &nbsp;&nbsp;&nbsp;
        <input class="button" style="width:150px" type="submit" name="save"
               value="[[Auction.EditSaveGroup]]"
               <!-- IF POPUPFORM -->
               onclick="return !RemovePopupWindow('PopupGroupEdit{GROUP.NAME|hash}',false)"
               <!-- ENDIF -->
        >
        &nbsp;&nbsp;&nbsp;
        <input class="button" style="width:150px" type="submit" name="cancel"
               value="[[Auction.EditCancel]]"
               <!-- IF POPUPFORM -->
               onclick="return RemovePopupWindow('PopupGroupEdit{GROUP.NAME|hash}')"
               <!-- ENDIF -->
              >
      </td>
    </tr>

  </table>

</div>

</form>
