<!--
/**
 *
 */
-->

<!-- BEGIN LOGS -->

<div class="{cycle:"class","tr1","tr2"}" style="padding:5px">
  <div style="float:right;width:20em;text-align:right">&nbsp;
    <!-- IF LASTMODIFIED -->
    <tt>{date:LASTMODIFIED,"DateTimeFormat"}</tt>
    <!-- ENDIF -->
  </div>
  <div style="float:right">
    <tt>{FILESIZE} Bytes</tt>
  </div>
  <a href="{DELETEURL}"><img src="layout/default/images/delete.gif" title="[[Logfiles.Delete]]" 
                             onmouseover="Tip('[[Logfiles.Delete]]');"></a>
  &nbsp;
  <a href="{SHOWURL}" onmouseover="Tip('[[Logfiles.Show]]');"><tt>{NAME}</tt></a>
</div>

<!-- END LOGS -->
