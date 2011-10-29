<!--
/**
 * @ingroup   Module-Login
 * @author    Knut Kohl <knutkohl@users.sourceforge.net>
 * @version   $Id$
 * @revision  $Rev$
 *
 * CHANGELOG
 *
 * - back to input submit
 * - changed layout 2 two columns
 * - Clear table layout
 */
-->

<div id="content">

<table id="content">
<tr><td style="width:450px;padding:0 auto">

  <form id="loginform" name="Login" action="{FORMACTION}" method="post"
        accept-charset="ISO-8859-1" onsubmit="$('button').disable().value='• • •'">
  {fh:"module","login"}

  <table id="logintable">

  <tr><td class="menu">
    Login
  </td></tr>

  <tr><td class="label">
    <a href='http://es-f.com'><img src='module/login/images/login.gif'></a>
  </td></tr>

  <!-- IF LOGINMSG -->
  <tr><td class="label error">{LOGINMSG}</td></tr>
  <!-- ENDIF -->

  <tr><td>
    <label for="user">[[Login.Account]]</label><br>
    <input name="user" value="{USER}" required="required">
  </td></tr>

  <tr><td>
    <label for="pass">[[Login.Password]]</label><br>
    <input type="password" name="pass" required="required" placeholder="[[Login.PasswordHint]]">
  </td></tr>

  <tr><td>
    <label for="layout">[[Login.Layout]]</label><br>
    {cookie:"LastLayout" > LAYOUT}
    <select id="layout" name="layout">{options:LAYOUTS,LAYOUT}</select>
  </td></tr>

  <!-- IF CONST.MODULE.COOKIE > "0" -->
  <tr><td>
    {fcb:"cookie",,,,"style=\"width:0\""}
    <label for="cookie"><strong>[[Login.Cookie]]</strong></label>
    <div>[[Login.CookieHint]] {help:"LoginHelp.Cookie"}</div>
  </td></tr>
  <!-- ENDIF -->

  <tr><td>
    {fs:[[Login.Login|quote]],"button"}
  </td></tr>

  </table>

  <div id="copyright">
    Copyright &copy; 2006-{CONST.YEAR}.
    <a href='http://es-f.com'>|es|f| esniper frontend {CONST.esf.Version}</a>
  </div>

  </form>

</td><td style="min-width:460px">

  <h3 style="float:left"><a name="twitter"></a>Twitter Updates</h3>
  <a style="float:right" href="http://www.twitter.com/es_f"><img
     src="http://twitter-badges.s3.amazonaws.com/follow_us-a.png" alt="Follow es_f on Twitter"/></a>

  <div class="clear"></div>

  <ul id="twitter_update_list"></ul>
  <noscript><em>(For Twitter updates is JavaScript required.)</em></noscript>

</td></tr></table>

</div>

<script type="text/javascript">
  // <![CDATA[
  FastInit.addOnLoad(function(){ $('loginform').focusFirstElement() });
  // ]]>
</script>

<div>
{nvl:CONTENT_AFTER}
</div>