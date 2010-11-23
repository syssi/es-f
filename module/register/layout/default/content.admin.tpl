<!--
/*
 *
 */
-->

<form id="Register" name="Register" action="{FORMACTION}" method="post" accept-charset="ISO-8859-1">
{fh:"module","register"}
{fh:"action","admin"}

<div id="content">

  <h2>[[Register.PendingRegistrations]]</h2>

  <!-- IF REQUESTS|count = "0" -->

    <div class="c" style="padding:30px">[none]</div>

  <!-- ELSE -->

    <div id="admin">

      <!-- BEGIN REQUESTS -->

      <div class="{cycle:"CLASS","tr1","tr2"}" style="padding:5px">
        <strong style="float:left">{ACCOUNT}</strong>
        <div style="float:right;text-align:right">
          <input type="radio" name="process[{ACCOUNT}]" value="0" checked="checked">[[Register.IgnoreAccount]] &nbsp;
          <input type="radio" name="process[{ACCOUNT}]" value="1">[[Register.AcceptAccount]] &nbsp;
          <input type="radio" name="process[{ACCOUNT}]" value="-1">[[Register.RejectAccount]]
        </div>

        <div style="clear:both;font-style:italic">{COMMENT|nl2br}</div>
      </div>

      <!-- END REQUESTS -->

    </div>

    <input class="button" style="margin:20px 0" type="submit"
           value="[[Register.Process]]">

  <!-- ENDIF -->

</div>

</form>
