<!--
/*
 *
 */
-->

<div id="content">

  <div id="register">

    <form id="Register" name="Register" action="{FORMACTION}" method="post" accept-charset="ISO-8859-1">
    {fh:"module","register"}

    <table>

    <tr>
      <td style="padding:10px 0;text-align:center" colspan="3">
        <h3>[[Register.AccountPasswords]]</h3>
        <!-- IF REGISTERMSG -->
        <p class="msgerror">{nvl:REGISTERMSG}<br></p>
        <!-- ENDIF -->
      </td>
    </tr>

    <tr class="tr1">
      <td><label for="user">[[Register.Account]]</label></td>
      <td>&nbsp;:&nbsp;</td>
      <td colspan="2">
        {ft:"user",,"input","id=\"user\""}
      </td>
    </tr>

    <tr class="tr1">
      <td colspan="3">[[Register.AccountComment]]</td>
    </tr>

    <tr class="tr2">
      <td><label for="pass1">[[Register.EbayPassword]]</label></td>
      <td>&nbsp;:&nbsp;</td>
      <td style="white-space:nowrap">{fp:"pass[ebay][]",,"input"} &nbsp; &amp; &nbsp; {fp:"pass[ebay][]",,"input"}</td>
    </tr>

    <tr class="tr2">
      <td colspan="3">[[Register.EbayPasswordComment]]</td>
    </tr>

    <tr class="tr1">
      <td>[[Register.EsfPassword]]</td>
      <td>&nbsp;:&nbsp;</td>
      <td style="white-space:nowrap">{fp:"pass[esf][]",,"input"} &nbsp; &amp; &nbsp; {fp:"pass[esf][]",,"input"}</td>
    </tr>

    <tr class="tr1">
      <td colspan="3">[[Register.EsfPasswordComment]]</td>
    </tr>

    <tr class="tr2">
      <td>[[Register.MsgForAdmin]]</td>
      <td>&nbsp;:&nbsp;</td>
      <td>{fta:"cmt",,"input","style=\"width:97%;height:3.7em\""}</td>
    </tr>

    <tr>
      <td colspan="3" style="text-align:center;padding:30px">
        <input class="button" type="submit" name="register"
               value="[[Register.Register]]">
      </td>
    </tr>

    </table>

    </form>

  </div>

</div>
