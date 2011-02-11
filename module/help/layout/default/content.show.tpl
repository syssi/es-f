<!--
/**
 *
 */
-->

<div id="content">

<!-- IF EDITURL -->
<a style="float:right" href="{EDITURL}#editor">[ Edit ]</a>
<!-- ENDIF -->

<h1>{SCOPE}: {EXTENSION}</h1>

<tt>Version: {VERSION}, Category: {nvl:CATEGORY,"Core"}</tt>

<p>{nvl:DESCRIPTION}</p>

<h1>[[Help.Description]]</h1>

{HELPTEXT}

<h1 style="clear:both">Author</h1>

<address>{html:AUTHOR}</address>

<!-- IF CHANGELOG -->
<h3>Changelog</h3>

<div class="tabber">
  <!-- BEGIN CHANGELOG -->
  <div class="tabbertab">
    <h4 class="info">{VERSION}</h4>
    {CHANGES}
  </div>
  <!-- END CHANGELOG -->
</div>
<!-- ENDIF -->

</div>