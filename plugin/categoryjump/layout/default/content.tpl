<!-- COMMENT
/*
 *
 */
-->

<div id="categoryjump" class="categoryjump noprint">

  <div id="JumpLinks">
    {if:CATEGORY|count,"=","1",[[Auction.Category]],[[Auction.Categories]]}:
    <!-- BEGIN CATEGORY -->
      <a href="#{CATEGORY|hash}">{nvl:CATEGORY,[[Auction.NoCategory]]}</a>
      {if:$ROWLAST,"<>",TRUE,"|"}
    <!-- END CATEGORY -->
  </div>

  <!-- IF DropDown -->

  <select id="JumpSelect" style="display:none"
          onchange="document.location='#'+(this.value?this.value:'pagetop')">
    <option value="">[[Auction.Categories]] ...</option>
    <!-- BEGIN CATEGORY -->
      <option value="{CATEGORY|hash}">{nvl:CATEGORY,[[Auction.NoCategory]]}</option>
    <!-- END CATEGORY -->
  </select>

  <script type="text/javascript">
    // <![CDATA[
    addLoadEvent(function(){ $('JumpLinks').hide(); $('JumpSelect').show() });
    // ]]>
  </script>

  <!-- ENDIF -->

</div>
