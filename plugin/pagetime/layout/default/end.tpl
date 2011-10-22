<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<div id="plugin_pagetime" class="pagetime monospace noprint">
  {translate:"PageTime.PageMessage", PAGETIME} / <span id="plugin_htmltime"></span>
</div>

<script type="text/javascript">
  // <![CDATA[
  _pt_time = ((new Date()).getTime() - _pt_time) / 1000;
  $('plugin_htmltime').update('{js:[[PageTime.HtmlMessage]]}'.sprintf(_pt_time).replace(/\./gi,','));
  addLoadEvent(function(){ $('plugin_pagetime').move('footer_after') });
  // ]]>
</script>
