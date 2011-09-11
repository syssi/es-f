<!--
/*
 *
 */
-->

<div id="content">

<h2>[[Analyse.Groups]]</h2>

<form action="{FORMACTION}" method="post">
{fh:"module","analyse"}
{fh:"action","showmulti"}

<!-- BEGIN GROUPS -->
<div id="row_{GROUP|hash}" class="{cycle:"class","tr1","tr2"}" style="padding:5px">
  <input id="{GROUP|hash}" type="checkbox" name="group[]" value="{GROUP}"
         onclick="ToggleGroupRow(this.id)">
  &nbsp;
  <a href="{SHOWURL}">{GROUPNAME}</a>
  <!-- IF TOTAL -->
    &nbsp;
    <img src="{$IMGDIR}/total.gif" onmouseover="Tip('[[Analyse.WithShipping]]')"
         alt="([[Analyse.WithShipping]])">
  <!-- ENDIF -->
  &nbsp; [{nvl:CATEGORY,[[Auction.NoCategory]]}] ({COUNT})
</div>
<!-- END GROUPS -->

<p>
  <input class="button" type="submit" value="[[Analyse.Analyse|quote]]">
</p>

</form>

</div>