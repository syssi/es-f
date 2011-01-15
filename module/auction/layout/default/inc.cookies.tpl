<!-- COMMENT
/*
 *
 */
-->

<script type="text/javascript">
  // <![CDATA[
  var c = cookieManager.getCookie('categories');
/*
  // to delete buggy cookies
  if (!c.match(/%%/)) {
    c = '';
    cookieManager.setCookie('categories', c);
  }
*/
  if (c) {
    c = c.split('%%');
    var len = c.length;
    for (var i=1; i<len; i++) {
      // without first (empty) entry
      ShowHideCategory(c[i], false);
    }
  } else {
    cookieManager.setCookie('categories', '');
  }
  // ]]>
</script>
