<!--
/**
 *
 */
-->

<form method="post" accept-charset="ISO-8859-1">
{fh:"step","cfgchk"}

<div class="tabber">

<div class="tabbertab">

<h2>Binaries</h2>

<br />

{cycle:"CLASS"}

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="shell">shell binary :</label>
  <div class="input">
    {ft:"data[cfg][bin_sh]",cfg.bin_sh,"input","id=\"shell\" size=\"50\""}
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="grep">grep binary :</label>
  <div class="input">
    {ft:"data[cfg][bin_grep]",cfg.bin_grep,"input","id=\"grep\" size=\"50\""}
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="kill">kill binary :</label>
  <div class="input">
    {ft:"data[cfg][bin_kill]",cfg.bin_kill,"input","id=\"ESNIPER\" size=\"50\""}
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="ESNIPER">ESNIPER binary :</label>
  <div class="input">
    {ft:"data[cfg][bin_esniper]",cfg.bin_esniper,"input","id=\"ESNIPER\" size=\"50\""}
  </div>
</div>

</div>

<div class="tabbertab">

<h2>System</h2>

<br />

{cycle:"CLASS"}

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="CACHECLASS">Cache class :</label>
  <div class="input">
    <select id="CACHECLASS" class="input" name="data[cfg][cacheclass]">
      {options:CACHECLASS,cfg.cacheclass}
    </select>
    <br />
    At the moment only APC is extensive tested besides native file storage.
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="SUDO">SUDO user :</label>
  <div class="input">
    {ft:"data[cfg][sudo]",cfg.sudo,"input","id=\"SUDO\""}
    <br />
    Run system calls as separate user than your frontend runs as
    (mostly the web server user) To learn how to setup such a constellation,
    please take a look at this
    <a class="extern" href="http://www.es-f.com/sudo.41.html">HowTo</a>.
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="LOCALE">Your prefered locale :</label>
  <div class="input">
    <select id="LOCALE" class="input" name="data[cfg][locale]">
      {options:LOCALES,cfg.locale}
    </select>
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="EBAYTLD">Your prefered eBay homepage :</label>
  <div class="input">
    <tt>ebay.</tt>{ft:"data[cfg][ebaytld]",cfg.ebaytld,"input","id=\"EBAYTLD\" size=\"5\""}
    <div class="li">Used for auction detail view link and</div>
    <div class="li">find out the correct shipping costs</div>
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="PARSEORDER">Your prefered order of eBay parsers to use :</label>
  <div class="input">
    {ft:"data[cfg][parseorder]",cfg.parseorder,"input","id=\"PARSEORDER\""}
    <small style="margin-left:0.5em">(Comma separated)</small>
    <br>
    Installed parser: <tt>{EBAYPARSERS}</tt>
    <br>
    <strong style="color:red">It is strongly recommended to place your "home top level domain" first!</strong>
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="TIMEZONE">Your <strong>local</strong> time zone :</label>
  <div class="input">
    <select id="TIMEZONE" class="input" name="data[cfg][timezone]">
      {options:TIMEZONES,cfg.timezone}
    </select>
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="STARTMODULE">Default / start module :</label>
  <div class="input">
    {ft:"data[cfg][startmodule]",cfg.startmodule,"input","id=\"STARTMODULE\""}
    <br />Modules <strong>index</strong> and <strong>auction</strong> are good entry points.
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="NETMASK">Netmask to protect session hijacking :</label>
  <div class="input">
    {ft:"data[cfg][netmask]",cfg.netmask,"input","id=\"NETMASK\""}
    <div class="li"><tt style="font-weight:bold">255.255.255.255</tt> :
    Allow connect only from one address during a session, e.g. in an intranet.</div>
    <div class="li"><tt style="font-weight:bold">255.255.255.0</tt> :
    Allow connect from x.x.x.1 ... x.x.x.254, may be required if you connect
    from a client behind a firewall with load balancer with changing
    external addresses.</div>
    <div class="li"><tt style="font-weight:bold">255.255.0.0</tt> :
    Allow connect from x.x.1.1 ... x.x.254.254, may be required if you connect
    via a dial-in connection with changing external addresses.</div>
    <div class="li"><tt style="font-weight:bold">0.0.0.0</tt> :
    Allow connect from every address.</div>
  </div>
</div>

</div>

<div class="tabbertab">

<h2>Layout</h2>

<br />

{cycle:"CLASS"}

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="MENUSTYLE">Menu style layout :</label>
  <div class="input">
    {ft:"data[cfg][menustyle]",cfg.menustyle,"input","id=\"MENUSTYLE\""}
    <br />
    For 3 levels of menus, possible values: <tt>(text|image|full)</tt>, please provide
    a comma separated list
  </div>
</div>

</div>

<div class="tabbertab">

<h2>esniper</h2>

<br />

{cycle:"CLASS"}

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="seconds">Seconds :</label>
  <div class="input">
    {ft:"data[esniper][seconds]",esniper.seconds,"input","id=\"seconds\" size=\"2\""}
    <br />Bid n seconds before the end of an auction.
    <br />If you like to try less than 5 seconds, you have to change in esniper.c:
    <br /><tt>#define MIN_BIDTIME 5</tt>
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="debug">Debug :</label>
  <div class="input">
    {ft:"data[esniper][debug]",esniper.debug,"input","id=\"debug\" size=\"3\""}
    <br />Enable debug mode.
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="logdir">Log dir :</label>
  <div class="input">
    {ft:"data[esniper][logdir]",esniper.logdir,"input","id=\"logdir\" size=\"50\""}
    <br />Directory for auction debug log files.
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="proxy">Proxy :</label>
  <div class="input">
    {ft:"data[esniper][proxy]",esniper.proxy,"input","id=\"proxy\" size=\"50\""}
    <br />should be of the form <tt>http://host[:port]/</tt>
  </div>
</div>

<h4>For help about the following host settings, please refer to <a class="sourceforge" href="http://esniper.sourceforge.net/">esniper project</a>.</h4>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="historyHost">History host :</label>
  <div class="input">
    {ft:"data[esniper][historyHost]",esniper.historyHost,"input","id=\"historyHost\""}
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="prebidHost">Prebid host :</label>
  <div class="input">
    {ft:"data[esniper][prebidHost]",esniper.prebidHost,"input","id=\"prebidHost\""}
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="bidHost">Bid host :</label>
  <div class="input">
    {ft:"data[esniper][bidHost]",esniper.bidHost,"input","id=\"bidHost\""}
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="loginHost">Login host :</label>
  <div class="input">
    {ft:"data[esniper][loginHost]",esniper.loginHost,"input","id=\"loginHost\""}
  </div>
</div>

<div class="cfg {cycle:"CLASS","tr1","tr2"}">
  <label class="td" for="myeBayHost">My eBay host :</label>
  <div class="input">
    {ft:"data[esniper][myeBayHost]",esniper.myeBayHost,"input","id=\"myeBayHost\""}
  </div>
</div>

</div>

</div>
