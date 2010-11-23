<!--
/**
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

<body id="{MODULE}">

<script type="text/javascript">
  // <![CDATA[
  efa_bigger[1]  = '<img src="layout/default/images/text+1.gif">';
  efa_bigger[2]  = '[[Core.Efabigger]]';
  efa_bigger[7]  = 'Tip(\'[[Core.EfaBigger]]\')';
  efa_reset[1]   = '<img src="layout/default/images/text0.gif">';
  efa_reset[2]   = '[[Core.Efareset]]';
  efa_reset[7]   = 'Tip(\'[[Core.EfaReset]]\')';
  efa_smaller[1] = '<img src="layout/default/images/text-1.gif">';
  efa_smaller[2] = '[[Core.Efasmaller]]';
  efa_smaller[7] = 'Tip(\'[[Core.EfaSmaller]]\')';
  var efa_fontSize =
    new Efa_Fontsize(efa_increment,
                     efa_bigger, efa_reset, efa_smaller,
                     efa_default);
  if (efa_fontSize) efa_fontSize.efaInit();
  // ]]>
</script>

<!-- INCLUDE tooltip -->
