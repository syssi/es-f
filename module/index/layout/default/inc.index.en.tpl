<h2>Configuration</h2>

<p>
  To change the <tt>|es|f|</tt> configuration, just re-run the <a href="setup/">setup</a>.
</p>

<p>
  To change a module or plugin configuration, install and activate in the
  <a href="index.php?module=backend&action=info&ext=module-configuration">Backend</a>
  the modul <em>configuration</em>.
</p>

<h2>Add auctions</h2>

<p>There are three possibilities to add actions to <tt>|es|f|</tt>:</p>

<ul>
  <li>Manual on page <a href="?module=auction">Auctions</a>.<br />
      Enter one or more action numbers (separated by space or comma).</li>
  <li>Via bookmarklets, see below.</li>
</ul>

<h2>Bookmarklet</h2>

<p>For this function <tt>Javascript</tt> in the browser must be enabled!</p>

<p>To add auctions direct from eBay to <tt>|es|f|</tt>, please follow these steps:<p>

<ul>
  <li>Add the bookmarklet to your bookmarks: <tt><!-- INCLUDE inc.snipe --></tt></li>
  <li>Or this bookmarklet for direkt add: <tt><!-- INCLUDE inc.add2es-f --></tt><br>
      <strong>Attention: An activated plugin "AddFromEbayURL" is required for this!</strong></li>
  <li>On an interesting auction, click on one of your bookmarks to add this auction.</li>
</ul>

<h2>Debugging</h2>

<p>
  <tt>|es|f|</tt> have a debugger plugin that logs all system calls and the output.
</p>

<p>
  The creation of a trace file will be started by URI parameter TRACE like <a href="?TRACE">this</a>.
</p>

<p>
  To activate the debugger use URI parameter DEBUG like <a href="?DEBUG">this</a>.
</p>

<p>
  To stop the debugger use URI parameter STOP like <a href="?STOP">this</a>.
</p>

<p>
  If you find a bug, please follow the steps on the
  <a href="?module=support">support page</a>.
</p>
