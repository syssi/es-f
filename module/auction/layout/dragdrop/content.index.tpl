<!--
/*
 *
 */
-->

<script type="text/javascript">
  // <![CDATA[
  esf_CountDownExtra[esf_CountDownExtra.length] = "category_";
  esf_CountDownEndedStr = '{ENDED}';
  var GetCategoryFromGroup = '{GETCATEGORYFROMGROUP}';
  // ]]>
</script>

<table id="auctions" style="width:100%">

<!-- INCLUDE row.th -->

<!-- INCLUDE row.add -->

<form action="{FORMACTION}" method="post">
{fh:"module","auction"}

<tbody>

<!-- BEGIN AUCTIONS -->

  <!-- IF CATEGORY -->
    <!-- COMMENT reset row classes -->
    {cycle:"ACLASS"}{cycle:"GCLASS"}
    <!-- INCLUDE row.category -->
  <!-- ENDIF -->
  
  <tr id="tr_{ITEM}" class="{cycle:"ACLASS","tr1","tr2"} {ACLASS}" style="border:dashed black 1px">
  
    <!-- IF !ENDED -->
      <!-- INCLUDE auction.show -->
    <!-- ELSE -->
      <!-- IF !CONST.MODULE.LAYOUTENDED -->
        <!-- INCLUDE auction.show -->
      <!-- ELSE -->
        <!-- INCLUDE auction.show.ended -->
      <!-- ENDIF -->
    <!-- ENDIF -->
  
    <!-- IF GROUP -->
      {cycle:"GCLASS","tr1","tr2" > GCLASS}
      <!-- IF !GROUP.ENDED -->
        <!-- INCLUDE group.show -->
      <!-- ELSE -->
        <!-- IF !CONST.MODULE.LAYOUTENDED -->
          <!-- INCLUDE group.show -->
        <!-- ELSE -->
          <!-- INCLUDE group.show.ended -->
        <!-- ENDIF -->
      <!-- ENDIF -->
    <!-- ENDIF -->
  
  </tr>

<!-- END AUCTIONS -->

</tbody>

</table>

<script type="text/javascript">
  // <![CDATA[
  addLoadEvent(function() {
    Droppables.add('auctions', {
      accept: 'draggable',
      onHover: ItemHoverReset,
    });
  });
  // ]]>
</script>

<!-- IF AUCTIONS|count > "0" -->
<!-- INCLUDE row.multi -->
<!-- ENDIF -->

</form>

<!-- INCLUDE inc.popups -->

<!-- INCLUDE inc.cookies -->

<!-- COMMENT : preload hidden target:hover image -->
<img style="display:none" src="module/auction/layout/js/images/target-hover.gif">
