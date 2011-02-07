<!--
/**
 * @package Module-Login
 * @author  Knut Kohl <knutkohl@users.sourceforge.net>
 * @version 0.4.0
 *
 * CHANGELOG
 *
 * Version 0.4.0
 * - Clear table layout
 *
 * Version 0.3.0
 * - back to input submit
 *
 * Version 0.2.0
 * - changed layout 2 two columns
 */
-->

<div id="content">

  <form id="loginform" name="Login" action="{FORMACTION}" method="post"
        accept-charset="ISO-8859-1" onsubmit="$('button').disable().value='...'">
  {fh:"module","login"}

  <table id="login">

  <tr><td class="menu">
    Login
  </td></tr>

  <tr><td class="label">
    <a href='http://es-f.com'><img src='module/login/images/login.gif'></a>
  </td></tr>

  <!-- IF LOGINMSG -->
  <tr><td class="error">{LOGINMSG}</td></tr>
  <!-- ENDIF -->

  <tr><td class="value">
    {cookie:"LastUser" > USER}
    <label for="user">[[Login.Account]]</label><br>{ft:"user",USER}
  </td></tr>

  <tr><td class="value">
    <label for="pass">[[Login.Password]]</label><br>{fp:"pass"}
  </td></tr>

  <tr><td class="value">
    <label for="layout">[[Login.Layout]]</label><br>
    {cookie:"LastLayout" > LAYOUT}
    <select id="layout" name="layout">{options:LAYOUTS,LAYOUT}</select>
  </td></tr>

  <!-- IF CONST.MODULE.COOKIE > "0" -->
  <tr><td class="cookie">
    {fcb:"cookie",,,,"style=\"width:0\""}
    <label for="cookie">[[Login.Cookie]]</label> {help:"LoginHelp.Cookie"}
    <p class="alert">[[Login.CookieHint]]</p>
  </td></tr>
  <!-- ENDIF -->

  <tr><td class="value">
    {fs:[[Login.Login|quote]],"button"}
  </td></tr>

  </table>

  <div id="copyright">
    Copyright &copy; 2009-{CONST.YEAR}. <a href='http://es-f.com'>|es|f| esniper frontend</a>
  </div>

  </form>

</div>

<script type="text/javascript">
  // <![CDATA[
  addLoadEvent(function(){ $('loginform').focusFirstElement() });
  // ]]>
</script>