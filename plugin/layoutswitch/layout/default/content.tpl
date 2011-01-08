
<div id="plugin_layoutswitch" class="noprint"
     style="display:none;text-align:center;padding:5px;font-size:90%">
  [[Core.Layout]]:
  {form:}
  <!-- IF CONST.PLUGINS.LAYOUTSWITCH.MODE == "d" -->
    {fdd:"switchlayout",LAYOUTS,LAYOUT,"small","onchange=this.form.submit()"}
  <!-- ELSE -->
    <!-- BEGIN LAYOUTS -->
    {frb:"switchlayout",LAYOUTS,__LAYOUT,"small","onchange=this.form.submit()"}{LAYOUTS}
    <!-- END LAYOUTS -->
  <!-- ENDIF -->
  <noscript>
  &nbsp;&nbsp;{fs:[[LayoutSwitch.set]],,"button"}
  </noscript>
  </form>
</div>

<script type="text/javascript">
  // <![CDATA[
  addLoadEvent(function(){ $("plugin_layoutswitch").move($("{TARGET}")).show() });
  // ]]>
</script>
