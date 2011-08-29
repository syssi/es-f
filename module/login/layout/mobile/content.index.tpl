<!--
/**
 * @package Module-Login
 * @author  Knut Kohl <knutkohl@users.sourceforge.net>
 * @version 0.1.0
 *
 * CHANGELOG
 */
-->

<div id="content">

  <!-- IF LOGINMSG -->
  <p class="msgerror b">{LOGINMSG}</p>
  <!-- ENDIF -->

  <form id="loginform" name="Login" action="{FORMACTION}" method="post"
        accept-charset="ISO-8859-1">
  {fh:"module","login"}

  <div>

    <p style="margin-bottom:0.5em">
      <label for="user">[[Login.Account]]</label><br>
      {ft:"user",,"input","id=\"user\" tabindex=\"1\""}
    </p>

    <p style="margin-bottom:0.5em">
      <label for="pass">[[Login.Password]]</label><br>
      {fp:"pass",,"input","id=\"pass\" tabindex=\"2\""}
    </p>

    <p>
      <input class="button" type="submit" name="login" alt="[ [[Login.Login]] ]"
             value="[[Login.Login|quote]]" tabindex="3">
    </p>

  </div>

  </form>

</div>

<script type="text/javascript">
  // <![CDATA[
  addLoadEvent(function(){ $('loginform').focusFirstElement() });
  // ]]>
</script>
