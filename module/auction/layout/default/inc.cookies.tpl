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

<script type="text/javascript">
  // <![CDATA[
  var c = esf_cookieManager.getCookie('esf_categories');
/*
  // to delete buggy cookies
  if (!c.match(/%%/)) {
    c = "";
    esf_cookieManager.setCookie('esf_categories',c);
  }
*/
  if (c) {
    c = c.split('%%');
    var len = c.length;
  	for (var i=1; i<len; i++) {
      // without first (empty) entry
  	  ShowHideCategory(c[i],false);
    }
  } else {
    esf_cookieManager.setCookie('esf_categories','');
  }
  // ]]>
</script>
