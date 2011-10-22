<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
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

<!-- IF AUCTIONS|count > "0" -->

  <form id="auctionstable" name="auctionstable" action="{FORMACTION}" method="post">
  {fh:"module","auction"}

  <table id="auctions" style="width:100%">

  <!-- INCLUDE row.th -->

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

  <!-- INCLUDE row.multi -->

  </form>

<!-- ELSE -->

  <div class="c">
  <button class="button" style="margin:100px;padding:1em"
          onclick="Modalbox.show($('addauctions'),\{title:'[[Auction.AddAuctions]]',with:500\}); return false;">
    [[Auction.StartAddAuctions]]
  </button>
  </div>

<!-- ENDIF -->

<!-- INCLUDE inc.auction.add -->

<!-- IF CONST.MODULE.POPUPEDIT -->
  <!-- INCLUDE inc.popups -->
<!-- ENDIF -->

<!-- INCLUDE inc.cookies -->
