<!--
/**
 * @package Module-Login
 * @author  Knut Kohl <knutkohl@users.sourceforge.net>
 * @version 0.2.0
 *
 * CHANGELOG
 *
 * Version 0.3.0
 * - back to input submit
 *
 * Version 0.2.0
 * - changed layout 2 two columns
 */
-->

<div id="content">
<div id="inner">

  <!-- IF LOGINMSG -->
  <p class="msgerror b">{nvl:LOGINMSG}</p>
  <!-- ENDIF -->

  <form id="loginform" name="Login" action="{FORMACTION}" method="post" accept-charset="ISO-8859-1">
  {fh:"module","login"}

  <h2>[[Login.YourAccountAndPassword]]</h2>

  <br>

  <div>

    <div style="float:left">

      <div style="margin-bottom:0.5em">
      <label for="user">[[Login.Account]]</label>
      </div>
      {ft:"user",USER,"input","id=\"user\" tabindex=\"1\""}

      <p>
      <div style="margin-bottom:0.5em">
      <label for="pass">[[Login.Password]]</label>
      </div>
      {fp:"pass",,"input","id=\"pass\" tabindex=\"2\""}
      </p>

      <p>
      <input class="button" type="submit" name="login" alt="[ [[Login.Login]] ]"
             value="[[Login.Login|quote]]" tabindex="4">
      </p>

    </div>

    <div style="margin-left:16em">

      <!-- IF CONST.MODULE.COOKIE > "0" -->

      <input type="checkbox" id="cookie" name="cookie" tabindex="3">
      <label for="cookie">[[Login.Cookie]]</label> {help:"LoginHelp.Cookie"}

      <p class="alert">[[Login.CookieHint]]</p>

      <!-- ENDIF -->

    </div>

    <br class="clear">

  </div>

  </form>

</div>
</div>

<script type="text/javascript">
  // <![CDATA[
  addLoadEvent(function(){ $('loginform').focusFirstElement() });
  // ]]>
</script>
