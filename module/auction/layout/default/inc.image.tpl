<!-- COMMENT
/*
 * Copyright (c) 2006-2009 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 */
-->

<!-- COMMENT / Ensure that image sizes are set {nvl:...} -->
{nvl:IMGSIZE,"1" > IMGSIZE}
{nvl:IMGWIDTH,"1" > IMGWIDTH}
{nvl:IMGHEIGHT,"1" > IMGHEIGHT}

<div style="margin:0 auto">
<a href="html/image.php?i={IMGURL}&amp;m={IMGSIZE}"
   onclick="ShowItemImg('{js:"html/image.php?i=",IMGURL,"&amp;m=",IMGSIZE}',{IMGWIDTH},{IMGHEIGHT},{ITEM}); return false;">
  <img src="html/image.php?d&amp;i={IMGURL}&amp;m={__THUMBSIZE}" alt=""
       onmouseover="Tip('{js:"<img noimagesize src='html/image.php?i=",IMGURL,"&amp;m=",IMGSIZE,"' alt=' Just a moment please...'>"}',
                        OPACITY,100,WIDTH,{IMGWIDTH},OFFSETX,{__THUMBSIZE},OFFSETY,-{IMGHEIGHT}/2-7,PADDING,5,BORDERCOLOR,'{__IMGBORDERCOLOR}',BORDERWIDTH,10)">
</a>
</div>

<!-- COMMENT
<a href="html/image.php?i={IMGURL}&amp;m={nvl:IMGSIZE,"1"}"
   onclick="ShowItemImg('html/image.php?i={IMGURL}&amp;m={nvl:IMGSIZE,"1"}',{nvl:IMGWIDTH,"1"},{nvl:IMGHEIGHT,"1"},{ITEM}); return false;"><img
   src="html/image.php?i={IMGURL}&amp;m={nvl:THUMBSIZE,"50"}" alt=""
   onmouseover="Tip('<img noimagesize src=\'html/image.php?i={IMGURL}&amp;m={nvl:IMGSIZE,"1"}\'>',
                    OPACITY,100,WIDTH,{nvl:IMGWIDTH,"1"},OFFSETX,{nvl:THUMBSIZE,"50"},OFFSETY,-{nvl:IMGHEIGHT,"1"}/2-7,PADDING,0,BORDERCOLOR,'#C0C0C0',BORDERWIDTH,10);"></a>
-->
