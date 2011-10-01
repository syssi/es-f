<div class="noprint" style="text-align:center;padding:5px;font-size:90%">
  {form:}
  <!-- IF ACTIVE <> "on" -->
    <label for="autorefresh_interval">[[Autorefresh.Refresh]]</label>: &nbsp;
    {ft:"autorefresh_interval",INTERVAL,"small r","size=\"3\""} [[AutoRefresh.Min]] &nbsp;
    {fs:[[AutoRefresh.Activate]],,"button"}
    {fh:"autorefresh_active", "on"}
  <!-- ELSE -->
    [[Autorefresh.Refresh]]: &nbsp;
    <span id="plugin_autorefresh_left">{INTERVAL}</span> &nbsp; [[AutoRefresh.Min]] &nbsp;
    {fs:[[AutoRefresh.Deactivate]],,"button"}
    {fh:"autorefresh_active", "off"}
  <!-- ENDIF -->
  </form>
</div>

<script type="text/javascript">
  // <![CDATA[
  if ("{ACTIVE}" == "on") {
    var plugin_autorefresh_start = (new Date).getTime();
    new PeriodicalExecuter( function(pe) {
      // Calc. diff. from load until refresh
      var diff = ((plugin_autorefresh_start - (new Date).getTime())/60000 + {INTERVAL}).toFixed(0);
      if (diff < 0) diff = 0;
      // update remaining time
      $("plugin_autorefresh_left").update(diff);
      // Is it time to refresh?
      if (diff == 0) {
        pe.stop();
        // reload
        location.replace(location.href);
        return;
      }
    }, 60);
  }
  // ]]>
</script>
