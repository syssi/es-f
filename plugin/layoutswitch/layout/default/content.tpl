<div class="noprint" style="text-align:center;padding:5px;font-size:90%">
  [[Core.Layout]]:
  {form:}
  {fh:"module",CONST.ESF.MODULE}
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