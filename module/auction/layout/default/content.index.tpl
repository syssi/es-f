<!-- COMMENT
/*
 *
 */
-->

<script type="text/javascript">
  // <![CDATA[
  esf_CountDownExtra[esf_CountDownExtra.length] = 'category_';
  esf_CountDownEndedStr = '{ENDED}';
  var tip_start = '[[Auction.Start]]';
  var tip_stop = '[[Auction.Stop]]';
  var tip_startstop = new Array;
  // ]]>
</script>

<table id="auctions" style="width:100%">

<!-- IF AUCTIONS|count > "0" -->
<!-- INCLUDE row.th -->
<!-- ENDIF -->

<!-- INCLUDE row.add -->

<form id="auctionstable" name="auctionstable" action="{FORMACTION}" method="post">
{fh:"module","auction"}

<tbody>

<!-- Some default values -->
{nvl:__THUMBSIZE,"50" > __THUMBSIZE}
{nvl:__IMGBORDERCOLOR,"#DDD" > __IMGBORDERCOLOR}

<!-- BEGIN AUCTIONS -->

  <!-- IF CATEGORY.NEW -->
    <!-- COMMENT reset row classes -->
    {cycle:"ACLASS"}{cycle:"GCLASS"}
    <!-- INCLUDE row.category -->
  <!-- ENDIF -->

  <tr id="tr_{ITEM}" class="{cycle:"ACLASS","tr1","tr2"} {ACLASS}"
      style="border:dashed black 1px">

    <!-- IF !ENDED -->
      <!-- INCLUDE auction.show -->
    <!-- ELSE -->
      <!-- IF !CONST.MODULE.LAYOUTENDED -->
        <!-- INCLUDE auction.show -->
      <!-- ELSE -->
        <!-- INCLUDE auction.show.ended -->
      <!-- ENDIF -->
    <!-- ENDIF -->

    <!-- IF GROUP.NEW -->
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

<!-- IF AUCTIONS|count > "0" -->
<!-- INCLUDE row.multi -->
<!-- ENDIF -->

</form>

<!-- IF CONST.MODULE.POPUPEDIT -->
  <!-- INCLUDE inc.popups -->
<!-- ENDIF -->

<!-- INCLUDE inc.cookies -->
