<!--
/**
 * @author  Knut Kohl <KnutKohl@users.sourceforge.net>
 *
 * CHANGELOG
 * =========
 *
 * CHANGES:
 * - Include sub-menu definitions back into this template
 *
 * NEW:
 * - ALT attribute for image style
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<!-- BEGIN BLOCK TIP -->
  <!-- IF HINT --><!-- IF TITLE -->
    onmouseover="Tip('{js:HINT}',TITLE,'{js:TITLE}',SHADOW,true)"
  <!-- ELSE -->
    onmouseover="Tip('{js:HINT}',SHADOW,true)"
  <!-- ENDIF --><!-- ENDIF -->
<!-- END BLOCK TIP -->

<!-- BEGIN MENUDATA -->
  <!-- IF STYLE|upper = "FULL" -->
    <a class="fullmenu menu{ID}" href="{URL}" {nvl:EXTRA}
       <!-- BLOCK TIP -->><img src="{IMAGE}" title="{TITLE}" alt=""> {TITLE} </a>
  <!-- ELSEIF STYLE|upper = "IMAGE" -->
    <a class="imagemenu menu{ID}" href="{URL}" {nvl:EXTRA}
      <!-- BLOCK TIP -->><img src="{IMAGE}" title="{TITLE}" alt="[{TITLE}]"></a>
  <!-- ELSE --><!-- /* TEXT menu */ -->
    <a class="textmenu menu{ID}" href="{URL}" {nvl:EXTRA} <!-- BLOCK TIP -->>{TITLE}</a>
  <!-- ENDIF -->
<!-- END MENUDATA -->
