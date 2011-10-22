<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<!-- /* Ensure that image sizes are set {nvl:...} */ -->
{nvl:IMGSIZE,"1" > IMGSIZE}
{nvl:IMGWIDTH,"1" > IMGWIDTH}
{nvl:IMGHEIGHT,"1" > IMGHEIGHT}

<div style="margin:0 auto">

  <a class="zoomable" href="html/image.php?i={IMGURL}" target="_blank"
     onclick="Modalbox.show($('img{ITEM}'),{ title:'{RAW.NAME|striptags|quote}', width:{IMGWIDTH}+50 }); return false">
    <img class="smallframe" src="html/image.php?d&amp;i={IMGURL}&amp;m={__THUMBSIZE}" alt="">
  </a>

  <!-- Modalbox -->
  <div id="img{ITEM}" style="display:none">
    <img class="largeframe" src='html/image.php?i={IMGURL}' alt='{RAW.NAME|striptags|quote}'>
    <div class="MB_buttons" style="margin-top:1em;">
      <button class="button" onclick="Modalbox.hide(); return false;">[[Core.Close]]</button>
    </div>
  </div>

</div>
