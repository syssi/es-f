<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<form action="{server:"PHP_SELF"}#{GROUP.NAME|hash}" method="post">
  {fh:"module","auction"}
  {fh:"action","editgroup"}
  {fh:"group",GROUP.NAME}

  <label class="MB_label" for="groupnew">[[Auction.Rename]]</label>
  <div class="MB_inputs">
    <input id="groupnew" class="input MB_input" name="groupnew" value="{nvl:GROUP.AUCTIONGROUP}">
    <br>
    <small>([[Auction.RemoveGroupWillSplit]])</small>
  </div>

  <label class="MB_label" for="q">[[Auction.Quantity]]</label>
  <div class="MB_inputs">
    <input type="number" id="q" name="q" value="{GROUP.QUANTITY}" class="input num"
           min="1" size="3" required="required" placeholder="1"> [[Auction.Piece]]
  </div>

  <label class="MB_label" for="b">[[Auction.GroupBid]]</label>
  <div class="MB_inputs">
    <input type="number" id="b" name="b" value="{currency:GROUP.BID}"
           style="float:left" class="input num" min="1" size="8"
           onfocus="if(this.value=='0,00')this.value=''">
    <div>
      <input type="radio" name="t" value="0" checked="checked">[[Auction.GroupSingle]]
      <br>
      <input type="radio" name="t" value="1" {iif:GROUP.TOTAL,"checked=\"checked\""}>[[Auction.GroupTotal]]
    </div>
  </div>

  <label class="MB_label" for="c">[[Auction.GroupComment]]</label>
  <div class="MB_inputs">
    {ft:"c",GROUP.COMMENT,"input MB_input"}
  </div>

  <div class="MB_buttons">
    <input type="submit" class="button" name="start" value="[[Auction.EditStartGroup]]">
    <input type="submit" class="button" name="save" value="[[Auction.EditSaveGroup]]">
  </div>

</form>
