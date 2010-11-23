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
<div class="{cycle:"class","tr1","tr2"}" style="padding:5px">
  <input type="checkbox" name="group[]" value="{GROUP}">
  &nbsp;
  <a href="{SHOWURL}">{GROUPNAME}</a> [{nvl:CATEGORY,[[Auction.NoCategory]]}] ({COUNT})
</div>
<!-- END GROUPS -->

<p>
  <input class="button" type="submit" value="[[Analyse.Analyse|quote]]">
</p>

</form>

</div>