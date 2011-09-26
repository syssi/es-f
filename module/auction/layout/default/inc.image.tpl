<!-- COMMENT
/*
 *
 */
-->

<!-- COMMENT / Ensure that image sizes are set {nvl:...} -->
{nvl:IMGSIZE,"1" > IMGSIZE}
{nvl:IMGWIDTH,"1" > IMGWIDTH}
{nvl:IMGHEIGHT,"1" > IMGHEIGHT}

<div style="margin:0 auto">

  <!-- COMMENT
  <a href="html/image.php?i={IMGURL}&amp;m={IMGSIZE}"
     onclick="ShowItemImg('{js:"html/image.php?i=",IMGURL,"&amp;m=",IMGSIZE}',{IMGWIDTH},{IMGHEIGHT},{ITEM}); return false;">
    <img src="html/image.php?d&amp;i={IMGURL}&amp;m={__THUMBSIZE}" alt=""
         onmouseover="Tip('{js:"<img noimagesize src='html/image.php?i=",IMGURL,"&amp;m=",IMGSIZE,"' alt=' Just a moment please...'>"}',
                          OPACITY,100,WIDTH,{IMGWIDTH},OFFSETX,{__THUMBSIZE},OFFSETY,-{IMGHEIGHT}/2-7,PADDING,5,BORDERCOLOR,'{__IMGBORDERCOLOR}',BORDERWIDTH,10)">
  </a>
  -->

  <img id="img_{ITEM}" style="display:none" src='html/image.php?i="{IMGURL}'
       alt=' Just a moment please...'>

  <a href="html/image.php?i={IMGURL}" target="_blank"
     onclick="return CreatePopupWindow('img_{ITEM}')">
    <img src="html/image.php?d&amp;i={IMGURL}&amp;m={__THUMBSIZE}" alt=""
         onmouseover="Tip('{js:"<img noimagesize src='html/image.php?i=",IMGURL,"&amp;m=",IMGSIZE,"' alt=' Just a moment please...'>"}',
                          OPACITY,100,WIDTH,{IMGWIDTH},OFFSETX,{__THUMBSIZE},OFFSETY,-{IMGHEIGHT}/2-7,PADDING,5,BORDERCOLOR,'{__IMGBORDERCOLOR}',BORDERWIDTH,10)">
  </a>

</div>
