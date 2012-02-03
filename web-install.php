<?php
/**
 * @version   $Id: v2.4.1-60-g77e648b 2011-01-23 13:18:25 +0100 $
 * @revision  $Rev$
 */
define( 'VERSIONFILE',
        'http://es-f.git.sourceforge.net/git/gitweb.cgi?'
       .'p=es-f/es-f;a=blob_plain;f=application/.version;hb=HEAD' );
define( 'DOWNLOADFILE',
        'http://sourceforge.net/projects/es-f/files/1%%20-%%20Latest/es-f_%s.%s/download');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>:: |es|f| Installer ::</title>
<style type="text/css">
  body{font-family:"Trebuchet MS",Verdana,Helvetica,Arial,sans-serif}
  #c1{text-align:left;width:80%;margin:1em auto 0;padding:0 1em}
  .ok{color:green;font-weight:bold}
  .fail{color:red;font-weight:bold}
  #shell{font-family:monospace;font-size:0.9em;height:20em;overflow:scroll}
  input[type=submit]{width:8em;font-size:1.1em}
</style>
</head><body><div id="c1"><div id="c2"><form>
<?php
set_time_limit(0);
session_start();
// ---------------------------------------------------------------------------
if (empty($_SESSION['version']))
  list($_SESSION['version'], $_SESSION['date']) = file(VERSIONFILE, FILE_IGNORE_NEW_LINES);
h1('<tt style="font-size:1.1em">|es|f|</tt> Installer '
  .$_SESSION['version'].' ('.$_SESSION['date'].')');
$last = isset($_SESSION['s']) ? $_SESSION['s'] : 0;
$s = isset($_GET['s']) ? (int)$_GET['s'] : 1;
if ($s > $last+1) $s = $last;
switch ($s) { default: s1(); break; case 2: s2(); break; case 3: s3(); break; }
?>
</form></div></div></body></html>

<?php
// FUNCTIONS
function s1() {
  h2('1. Check required binaries');
  $bins = array();
  foreach ( array('wget', 'tar', 'gunzip', 'unzip') as $bin) {
    unset($ret);
    exec("which $bin", $ret);
    if (!empty($ret)) {
      $bins[$bin] = $ret[0];
      e($bin.' --> <span class="ok">'.$ret[0].'</span>');
    } else {
      e($bin.' --> <span class="fail">Not found</span>');
    }
    br();
  }
  h3('Please choose the archive format to download:');
  if (isset($bins['tar']) AND isset($bins['gunzip'])) {
    e('<input type="radio" name="f" value="tgz" checked="checked">.tgz &nbsp; &nbsp; &nbsp;');
  }
  if (isset($bins['unzip'])) {
    e('<input type="radio" name="f" value="zip">.zip');
  }
  if (isset($bins['wget'])) {
    b(2);
  } else {
    p('Sorry, wget is required to download the archive.');
  }
  $_SESSION['b'] = $bins;
  $_SESSION['s'] = 1;
}
function s2() {
  h2('2. Download archive');
  $f = !empty($_GET['f']) ? $_GET['f'] : 'tgz';
  $d = sprintf(DOWNLOADFILE, $_SESSION['version'], $f);
  $t = tempnam(sys_get_temp_dir(),'~');
  $cmd = sprintf('%s "%s" -O %s', $_SESSION['b']['wget'], $d, $t);
  run($cmd);  b(3);
  $_SESSION['f'] = $f;
  $_SESSION['t'] = $t;
  $_SESSION['s'] = 2;
}
function s3() {
  h2('3. Extract archive');
  if (file_exists('.htaccess')) {
    p('- Backup .htaccess ...');
    copy('.htaccess', '.htaccess~');
  }
  $cmd = ($_SESSION['f'] == 'zip')
       ? $_SESSION['b']['unzip'].' -o '
       : $_SESSION['b']['tar'].' xvzf ';
  p('- Extract files ...',1);
  run($cmd.$_SESSION['t']);
  p('- Remove '.$_SESSION['t'].' ...');
  unlink($_SESSION['t']);
  if (file_exists('.htaccess~')) {
    p('- Restore .htaccess ...');
    copy('.htaccess~', '.htaccess');  unlink('.htaccess~');
  }
  h3('Done, you are now ready to start your <a href="index.php">|es|f| esniper frontend</a>!');
  h2('<em>Happy sniping!</em>');
  $_SESSION = array();
  session_destroy();
}
function h1($h){echo '<h1>'.$h.'</h1>';}
function h2($h){echo '<h2>'.$h.'</h2>';}
function h3($h){echo '<h3>'.$h.'</h3>';}
function b($next) {
  p('<input type="hidden" name="s" value="'.$next.'">'
   .'<input type="submit" value="Next">');
}
function p($t,$f=0){e('<p>'.$t.'</p>',$f);}
function e($t,$f=0){echo $t; if($f){echo str_repeat(' ',4096); flush();}}
function br($c=1){for($i=0; $i<$c; $i++) echo '<br>';}
function run($cmd) {
  e('<div id="shell">',1);
  e('# '.$cmd); br();
  $fp = popen($cmd.' 2>&1', 'r');
  while (!feof($fp)) e(str_replace("\n",br(),str_replace('  ',' &nbsp;',fgets($fp))), 1);
  pclose($fp); e('</div>');
}