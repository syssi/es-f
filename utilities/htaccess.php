<?php 
/**
 * Copyright (c) 2006-2008 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 * @package es-f
 * @subpackage Utilities
 * @desc Generate basic auth via .htaccess / .htpasswd
 */

Header('Content-Type: text/html; charset=utf-8');
session_start();
error_reporting(E_ALL);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>|es|f| esniper frontend | Access editor</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel="shortcut icon" href="../layout/favicon.ico">
  <link rel="icon" type="image/x-icon" href="../layout/favicon.ico">
  <link rel="stylesheet" type="text/css" href="../layout/default/style.css" />
  <style type="text/css">
    div#content { width: 40em; margin: 20px auto; }
    fieldset { margin: 20px 0; padding-top: 10px; }
    legend { padding: 2px 10px; border: solid gray 1px; background-color: #F0F0F0; }
    input.button { width: 100%; }
    table { width: 100%; }
    td { padding: 5px; }
    td.text { width: 11em; }
    td.colon { padding: 5px 0; }
    input.input, select.input { width: 100%; padding: 3px; }
    td.button { border: 0; width: 6em; }
    a { text-decoration: none; color: maroon; }
    .info { color: blue; }
    .ok { color: green; }
    .err { color: red; }
/*
    fieldset#msgs { font-weight: bold; }
*/
  </style>
</head>
<body>
<div id="content">

<?php

$WorkDir = isset($_SESSION['WorkDir'])
         ? $_SESSION['WorkDir']
         : realpath(dirname(__FILE__).'/..') ;

$users = array();
if ($htpasswd = @file($WorkDir.'/.htpasswd')) {
  foreach ($htpasswd as $line) {
    $line = trim($line);
    if (empty($line)) continue;
    list($user,$pass) = explode(':',$line);
    $users[$user] = $pass;
  }
}

switch (@$_POST['action']) {

  # ---------------------
  case 'setwd':
    checkPosted('dir');
    if ($p_dir) $WorkDir = $p_dir;
    break;

  # ---------------------
  case 'setrealm':
    checkPosted('realm');
    if ($p_realm) {
      $AuthName = $p_realm;
      saveHtAccess();
    }
    break;

  # ---------------------
  case 'create':
    checkPosted('user','pass1','pass2');
    if (!checkRequired('user','pass1','pass2')) {
      Msg('Please fill all required fields!','err');
    } elseif (strstr($p_user,':')) {
      Msg('No colon ":" in user name allowed!','err');
    } elseif ($p_pass1 != $p_pass2) {
      Msg('Passwords are not the same!','err');
    } else {
      $users[$p_user] = crypt($p_pass1);
      if (WriteHtPasswd()) {
        Msg('User "'.$p_user.'" created.','ok');
      } else {
        Msg('User "'.$p_user.'" NOT created!','err');
      }
    }
    break;

  # ---------------------
  case 'change':
    checkPosted('user','pass1','pass2');
    if (!checkRequired('user','pass1','pass2')) {
      Msg('Please fill all required fields!','err');
    } elseif ($p_pass1 != $p_pass2) {
      Msg('Passwords are not the same!','err');
    } else {
      $users[$p_user] = crypt($p_pass1);
      if (WriteHtPasswd()) {
        Msg('User "'.$p_user.'" changed.','ok');
      } else {
        Msg('User "'.$p_user.'" NOT changed!','err');
      }
    }
    break;

  # ---------------------
  case 'delete':
    checkPosted('user','sure','all');
    if ($p_sure AND $p_all) {
      $users = FALSE;
      if (WriteHtPasswd()) {
        Msg('Users removed.','ok');
      } else {
        Msg('Users NOT removed!','err');
      }
    } elseif (!checkRequired('user')) {
      Msg('Please fill all required fields!','err');
    } else {
      unset($users[$p_user]);
      if (WriteHtPasswd()) {
        Msg('User "'.$p_user.'" deleted.','ok');
      } else {
        Msg('User "'.$p_user.'" NOT deleted!','err');
      }
    }
    break;

  # ---------------------
  case 'remove':
    saveHtAccess(TRUE);
    Msg('Access control removed.','ok');
    break;
}

$_SESSION['WorkDir'] = $WorkDir;

$AuthName = preg_match('~^\s*AuthName\s+([\'"])?(.*?)(\\1|$)~im',@file_get_contents($WorkDir.'/.htaccess'),$args)
          ? $args[2] : '';

if (empty($AuthName)) {
  Msg('No access control implemented yet.');
}

if (empty($users)) {
  Msg('No users defined yet.');
} else {
  $UserOptions = '<option value="">--- Please select ---</option>';
  foreach (array_keys($users) as $user) {
    $UserOptions .= sprintf('<option>%s</option>',$user);
  }
}

# -----------------------------------------------------------------------------
if (isset($_POST['changewd'])) {
  showSetWorkdirForm();
} else {
  showChangeWorkdirForm();
  if (isset($_POST['changerealm'])) {
    showSetAuthForm();
  } else {
    showChangeAuthForm();
    if (!empty($AuthName)) {
      showRemoveAuthForm();
    }
    showMessages();
    showCreateUserForm();
    if (!empty($users)) {
      showChangeUserForm();
      showDeleteUserForm();
    }
  }
}
?>
</div>
</body>
</HTML>
<?php

/**
 * @ignore
 */
function checkPosted () {
  foreach (func_get_args() as $param) {
    $GLOBALS['p_'.$param] = isset($_POST[$param]) ? $_POST[$param] : '';
  }
}

/**
 * @ignore
 */
function checkRequired () {
  foreach (func_get_args() as $param) {
    if (empty($GLOBALS['p_'.$param])) return false;
  }
  return TRUE;
}

/**
 * @ignore
 */
function Msg ( $msg, $class='info' ) {
  $GLOBALS['Msgs'][] = sprintf('<span class="%s">%s</span>',$class,$msg);
}

/**
 * @ignore
 */
function WriteHtPasswd () {
  global $WorkDir, $users;

  $file = $WorkDir.'/.htpasswd';
  if (empty($users)) return unlink($file);
  
  if ($h = @fopen($file,'w')) {
    ksort($users);
    foreach ($users as $user => $pass) {
      fwrite($h, $user.':'.$pass."\n");
    }
    fclose($h);
    return TRUE;
  } else {
    return FALSE;
  }
}

/**
 * @ignore
 */
function showMessages () {
  if (!empty($GLOBALS['Msgs'])) {
    echo '<fieldset id="msgs">', implode('<br />',$GLOBALS['Msgs']), '</fieldset>';
  }
}

/**
 * @ignore
 */
function showChangeWorkdirForm () {
  echo <<<EOF
  <form method="post">
  <input type="hidden" name="changewd" />
  <fieldset>
    <legend>Working directory</legend>
    <table>
    <tr>
      <td><tt>$GLOBALS[WorkDir]</tt></td>
      <td class="button"><input class="button" type="submit" value="Change" /></td>
    </tr>
    </table>
  </fieldset>
  </form>
EOF;
}

/**
 * @ignore
 */
function showSetWorkdirForm () {
  echo <<<EOF
  <form method="post">
  <input type="hidden" name="action" value="setwd" />
  <fieldset>
    <legend>Working directory</legend>
    <table>
    <tr>
      <td class="text">Directory</td>
      <td class="colon">:</td>
      <td class="input"><input class="input" name="dir" value="$GLOBALS[WorkDir]" /></td>
      <td class="button"><input class="button" type="submit" value="Set" /></td>
    </tr>
    </table>
  </fieldset>
  </form>
EOF;
}

/**
 * @ignore
 */
function showChangeAuthForm() {
  echo <<<EOF
  <form method="post">
  <input type="hidden" name="changerealm" />
  <fieldset>
    <legend>Authorization realm</legend>
    <table>
    <tr>
      <td><tt>$GLOBALS[AuthName]</tt></td>
      <td class="button"><input class="button" type="submit" value="Change" /></td>
    </tr>
    </table>
  </fieldset>
  </form>
EOF;
}

/**
 * @ignore
 */
function showSetAuthForm() {
  echo <<<EOF
  <form method="post">
  <input type="hidden" name="action" value="setrealm" />
  <fieldset>
    <legend>Authorization realm</legend>
    <table>
    <tr>
      <td class="text">Realm</td>
      <td class="colon">:</td>
      <td class="input"><input class="input" name="realm" value="$GLOBALS[AuthName]" /></td>
      <td class="button"><input class="button" type="submit" value="Set" /></td>
    </tr>
    </table>
  </fieldset>
  </form>
EOF;
}

/**
 * @ignore
 */
function showRemoveAuthForm () {
  echo <<<EOF
  <form method="post">
  <input type="hidden" name="action" value="remove" />
  <fieldset>
    <legend>Remove access control</legend>
    <table>
    <tr>
      <td>Remove code from .htaccess and remove .htpasswd</td>
      <td class="button"><input class="button" type="submit" value="Remove" /></td>
    </tr>
    </table>
  </fieldset>
  </form>
EOF;
}

/**
 * @ignore
 */
function showCreateUserForm () {
  echo <<<EOF
  <form method="post">
  <input type="hidden" name="action" value="create" />
  <fieldset>
    <legend>Create user</legend>
    <table>
    <tr>
      <td class="text">Name</td>
      <td class="colon">:</td>
      <td class="input"><input class="input" name="user" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="text">Password</td>
      <td class="colon">:</td>
      <td class="input"><input class="input" type="password" name="pass1" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="text">Repeat password</td>
      <td class="colon">:</td>
      <td class="input"><input class="input" type="password" name="pass2" /></td>
      <td class="button"><input class="button" type="submit" value="Create" /></td>
    </tr>
    </table>
  </fieldset>
  </form>
EOF;
}

/**
 * @ignore
 */
function showChangeUserForm () {
  echo <<<EOF
  <form method="post">
  <input type="hidden" name="action" value="change" />
  <fieldset>
    <legend>Change user password</legend>
    <table>
    <tr>
      <td class="text">User</td>
      <td class="colon">:</td>
      <td class="input"><select class="input" name="user">$GLOBALS[UserOptions]</select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="text">Password</td>
      <td class="colon">:</td>
      <td class="input"><input class="input" type="password" name="pass1" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="text">Repeat password</td>
      <td class="colon">:</td>
      <td class="input"><input class="input" type="password" name="pass2" /></td>
      <td class="button"><input class="button" type="submit" value="Save" /></td>
    </tr>
    </table>
  </fieldset>
  </form>
EOF;
}

/**
 * @ignore
 */
function showDeleteUserForm () {
  echo <<<EOF
  <form method="post">
  <input type="hidden" name="action" value="delete" />
  <fieldset>
    <legend>Delete user</legend>
    <table>
    <tr>
      <td class="text">User</td>
      <td class="colon">:</td>
      <td class="input"><select class="input" name="user">$GLOBALS[UserOptions]</select></td>
      <td class="button"><input class="button" type="submit" value="Delete" /></td>
    </tr>
    <tr>
      <td>Remove ALL users</td>
      <td class="colon">:</td>
      <td style="text-align:right"><input type="checkbox" name="sure" />sure</td>
      <td class="button"><input class="button" type="submit" name="all" value="Remove" /></td>
    </tr>
    </table>
  </fieldset>
  </form>
EOF;
}

/**
 * @ignore
 */
function saveHtAccess ( $remove=FALSE ) {
  $lines = @file($GLOBALS[WorkDir].'/.htaccess');
  $inControl = FALSE;
  $htaccess = '';
  foreach ((array)$lines as $line) {
    if (trim($line) == '# BEGIN Access control') {
      $inControl = TRUE;
    } elseif (trim($line) == '# END Access control') {
      $inControl = FALSE;
    } else {
      if (!$inControl) {
        $htaccess .= $line."\n";
      }
    }
  }

  if (!$remove) {
    $htaccess .= <<<EOT

# BEGIN Access control
AuthName "$GLOBALS[AuthName]"
AuthType Basic
AuthUserFile $GLOBALS[WorkDir]/.htpasswd
require valid-user
# END Access control
EOT;
  }

  if ($fh = @fopen($GLOBALS[WorkDir].'/.htaccess','w')) {
    fwrite($fh, trim($htaccess));
    fclose($fh);
  }

}

?>