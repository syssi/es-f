<div class="noprint" style="text-align:center;padding:5px;font-size:90%">
  {form:}
  <!-- IF ACTIVE = "2" -->
    [[Autorefresh.Refresh]]: &nbsp;
    <span id="plugin_autorefresh_left">{INTERVAL}</span> [[AutoRefresh.Min]] &nbsp;
    {fs:[[AutoRefresh.Deactivate]],,"button"}
    {fh:"autorefresh_active", 1}
  <!-- ELSE -->
    <label for="autorefresh_interval">[[Autorefresh.Refresh]]</label>: &nbsp;
    {ft:"autorefresh_interval",INTERVAL,"small r","size=\"3\""} [[AutoRefresh.Min]] &nbsp;
    {fs:[[AutoRefresh.Activate]],,"button"}
    {fh:"autorefresh_active", 2}
  <!-- ENDIF -->
  </form>
</div>

<script type="text/javascript">
  // <![CDATA[
  if ("{ACTIVE}") {
    var plugin_autorefresh_start = (new Date).getTime();

    function plugin_autorefresh_update() {
      // Calc. diff. from load until refresh
      var diff = ((plugin_autorefresh_start - (new Date).getTime())/60000 + {INTERVAL}).toFixed(0);
      if (diff < 0) diff = 0;
      // update remaining time
      $("plugin_autorefresh_left").update(diff);
      // Is it time to refresh?
      if (diff == 0) {
        // reload
        location.replace(location.href);
        return;
      }
      // re-call update...
      setTimeout("plugin_autorefresh_update();", 60000);
    }
    // init update
    setTimeout("plugin_autorefresh_update();", 60000);
  }
  // ]]>
</script>
