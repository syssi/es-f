<?php 

Header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>|es|f| esniper frontend | Check translation</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel="shortcut icon" href="../layout/favicon.ico">
  <link rel="icon" type="image/x-icon" href="../layout/favicon.ico">
  <link rel="stylesheet" type="text/css" href="../layout/default/style.css" />
  <style type="text/css">
    body   { background: white; }
    .file  { padding: 2px 5px; border: dotted gray 1px; background-color: #EEEEEE; }
    .msgs  { margin-bottom: 10px; padding: 2px 5px; border: dotted gray 1px; border-top: 0; }
    .ok    { color: green; }
    .err   { color: red; }
    div.ok, div.err { margin: 5px 0; }
    a { text-decoration: none; color: maroon; }
  </style>
</head>
<body>
<div id="content">

<script type="text/javascript" src="../js/wz/wz_tooltip.js"></script>
<script type="text/javascript">tt_Init();</script>

<p><a href="index.php">Utility index</a></p>

<?php

/**
 * @ignore
 */
define( '_ESF_OK', TRUE );

## error_reporting(E_ALL);

$lang = isset($_GET['lang']) ? $_GET['lang'] : FALSE;

if (!$lang) {
  exec('find ../language -name "*.php"',$res);
  $s = array();
  foreach ($res as $l) {
    $l = preg_replace('~.*/(\w+)\..*~i','$1',$l);
    if ($l != 'en') $s[] = sprintf('<option>%s</option>',$l);
  }
  $s = '<select name="lang">'.implode($s).'</select>';
  echo '<form><p>Check translation to '.$s.' <input type="submit" value="Start" /></p></form>';

} else {

  /**
   * @ignore
   */
  include '../application/define.php';
  /**
   * @ignore
   */
  include APPDIR.'/functions.esf.php';
  /**
   * @ignore
   */
  include APPDIR.'/classes/__autoload.php';

  $cmd = 'find ../ -name "en.*php"';
  exec($cmd,$res);
  sort($res);

  $res = array_map('realpath', $res);

  echo '<p><a href="'.$_SERVER['PHP_SELF'].'">Language selection</a></p>';

  printf('<h1>Check translation to "<tt style="font-size:125%%">%s</tt>"</h1>'."\n",$lang);

  $mm = '(move mouse over text id to see text)';

  foreach ($res as $en_file) {
  
    $chk_file = str_replace('/en.', '/'.$lang.'.', $en_file);
    $rel_file = str_replace(realpath(dirname(__FILE__).'/..').'/', '', $chk_file);

    if (strpos($rel_file, 'local') === 0) {
      // skip files in loacl directory
      continue;
    }

    $esf_translation = array();
    /**
     * @ignore
     */
    include $en_file;

    $en_translation = $esf_translation;

    $esf_translation = array();
    /**
     * @ignore
     */
    @include $chk_file;

    foreach (array_keys($esf_translation) as $translation) {
      if (isset($en_translation[$translation])) {
        unset($en_translation[$translation], $esf_translation[$translation]);
      }
    }

    $ok = (empty($en_translation) AND empty($esf_translation));

    printf('<div class="file"><tt>%s</tt></div><div class="msgs">'."\n", $rel_file);
    if ($ok) { 
      echo '<div class="ok">OK</div>', "\n";
    }

    if (!file_exists($chk_file)) {
      echo '<div class="err">Missing file</div>'."\n";
    } elseif (!empty($en_translation)) {
      echo '<div class="err">Missing translations '.$mm.'</div>'."\n";
      print_a($en_translation);
    }

    if (!empty($esf_translation)) {
      echo '<div class="err">To much translations '.$mm.'</div>'."\n";
      print_a($esf_translation);
    }
    echo '</div>', "\n\n", str_pad('',4096);
  }
}

/**
 * @ignore 
 */
function print_a ( $array ) {
  echo '<ul>';
  foreach ($array as $key => $val) {
    $id = md5($key);
    printf('<li><div id="%s" style="display:none">%s</div>'."\n",$id,$val);
    printf('<span style="cursor:help" onmouseover="TagToTip(\'%s\',FONTCOLOR,\'black\',FONTSIZE,\'100%%\')">%s</span></li>'."\n",$id,$key);
  }
  echo '</ul>';
}
?>

</div>
</body>
</HTML>
