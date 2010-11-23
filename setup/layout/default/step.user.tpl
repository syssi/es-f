<!--
/**
 *
 */
-->

<form method="post" accept-charset="ISO-8859-1">
{fh:"step","userchk"}

<div class="tabber">

<!-- IF USERS -->

<div class="tabbertab">

<h2>Defined users</h2>

<br />

<p>The following users still defined:</p>

<table>
<!-- BEGIN USERS -->
<tr class="{cycle:"truser","tr1","tr2"}">
  <td>{$ROWID}</td>
  <td style="padding-left:30px;padding-right:0">{fcb:"data[remove][]",$ROWID}</td>
  <td style="padding-left:0"><small>remove</small>&nbsp;</td>
</tr>
<!-- END USERS -->
</table>

<p>To change password of an existsing user, just re-define the user data.</p>

</div>

<!-- ENDIF -->

<div class="tabbertab">

<!-- IF USERS -->

<h2>Define/Redefine eBay and {CONST.ESF.TITLE} users</h2>

<!-- ELSE -->

<h2>Define first eBay and {CONST.ESF.TITLE} user</h2>

<p>
  This (first defined) user will be the <strong>Administrator</strong>
  with some special features.
</p>

<p>
  Only this user can by default change settings in the modules
  <strong>backend</strong> and <strong>configuration</strong>,
  until an other or Co-Administrators are defined.
</p>

<!-- ENDIF -->

<br />

<div class="cfg tr1">
  <label class="td" for="data[user]">eBay account == Frontend user :</label>
  <div class="input">
    {ft:"data[user]",,"input"}
  </div>
</div>

<div class="cfg tr2">
  <label class="td" for="data[pass1]">eBay Password :</label>
  <div class="input">
    {ft:"data[pass1]",,"input"}<br />
    This password is used by esniper only.
  </div>
</div>

<p style="font-weight:bold">
  Your <tt>|es|f|</tt> password SHOULD not be the same as your eBay password!
</p>

<div class="cfg tr1">
  <label class="td" for="data[pass2]"><tt>|es|f|</tt> Password :</label>
  <div class="input">
    {ft:"data[pass2]",,"input"}
    <script type="text/javascript">
      document.write('<input style="margin-left:0.5em;width:10em" id="genpw" '+
                     'type="button" value="Generate" onclick="return pwgen()">');
      function pwgen() {
        $('genpw').disabled = true;
        $('genpw').value = 'Please wait...';
        new Ajax.Request(
          'pwgen.php',
          { method: 'get',
            parameters: { syllables:'2' },
            onSuccess: function(transport) {
              $('data[pass2]').value = transport.responseText;
            },
            onComplete: function() {
              $('genpw').value = 'Generate';
              $('genpw').disabled = false;
            }
          }
        );
        return false;
      }
    </script>
    <br />
    This is the password for your login into {CONST.ESF.TITLE}.
  </div>
</div>

</div>

</div>