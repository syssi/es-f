<?php
/**
 *
 */

defined('_ESF_OK') || die('No direct call allowed.');

# -------------------------------------------------------------------------
$Redirect = FALSE;
if (!@$_SESSION['CHECKED']['cfg']) {
  $Redirect = 'intro';
} elseif ($_SESSION['INSTALL'] AND !@$_SESSION['CHECKED']['test']) {
  $Redirect = 'test';
} elseif (!@$_SESSION['CHECKED']['user']) {
  $Redirect = 'user';
}

if ($Redirect) {
  Header('Location: index.php?step='.$Redirect);
  Exit;
}

TplData::set('MSG', array());

$dir = '../local';
if (!is_dir($dir)) {
  $msg = 'Create directory <tt class="name">local</tt> ... '
        .fmtResult(@mkdir($dir));
  TplData::add('MSG', $msg);
  @chmod($dir, 0755);
}

// add. directories
$dir = '../local/config';
@mkdir($dir) && @chmod($dir, 0755);
$dir = '../local/custom';
@mkdir($dir) && @chmod($dir, 0777);
$dir = '../local/data';
@mkdir($dir) && @chmod($dir, 0755);

$file = '../local/.htaccess';
if (!file_exists($file)) {
  $msg = 'Protect local working directory (allow only images) via <tt class="name">.htaccess</tt> ... '
        .fmtResult(File::write($file, file_get_contents('dist/htaccess.local')));
  TplData::add('MSG', $msg);
  @chmod($file, 0644);
}

$cfg = getSavedPosted('user');

$users = $_SESSION['USERS'];

foreach ((array)$cfg['remove'] as $remove) unset($users[$remove]);

if (!empty($cfg['user'])) {
  $pass = MD5Encryptor::encrypt(md5($cfg['pass2'])."\x01".$cfg['pass1'], md5($cfg['pass2']));
  $users[$cfg['user']] = $pass;
}

// --------------------------------------------------------------------------
// config.ini

$cfg = getSavedPosted('cfg');

$config = array();

$config[] = '  <!-- Configuration version -->';
$config[] = sprintf('  <config name="CfgVersion">%d</config>', ESF_CONFIG_VERSION);
$config[] = '';
$config[] = '  <!-- Core application settings -->';
foreach ($cfg['cfg'] as $var => $val)
  $config[] = sprintf('  <config name="%s">%s</config>', $var, $val);

// Probe Caches
Loader::Load(LIBDIR.'/cache/cache.class.php');
$config[] = sprintf('  <config name="cacheclass">%s</config>',
                    Cache::test(array('APC', 'EAccelerator', 'File')));

$config[] = '';
$config[] = '  <section name="users">';
foreach ($users as $user => $pass) {
  $config[] = '    <config type="array">';
  $config[] = sprintf('      <config name="name">%s</config>', $user);
  $config[] = sprintf('      <config name="auth">%s</config>', $pass);
  $config[] = '    </config>';
}
$config[] = '  </section>';
$config[] = '';

$config[] = '  <section name="esniper">';
foreach ($cfg['esniper'] as $var => $val)
  $config[] = sprintf('    <config name="%s"%s>%s</config>',
                      $var,
                      (strpos($var, 'Host')?' casesensitive="TRUE"':''),
                      $val);
$config[] = '  </section>';

$config = str_replace('{CONFIG}',
                    implode("\n",$config),
                    file_get_contents('dist/config/config.xml'));

$file = LOCALDIR.'/config/config.xml';
$msg = 'Write configuration <tt class="name">local/config/config.xml</tt> ... '
     . fmtResult(File::write($file, $config));

TplData::add('MSG', $msg);
@chmod($file, 0644);

// --------------------------------------------------------------------------

$file = BASEDIR.'/local/config/state.xml';
if (!file_exists($file)) {
  $msg = 'Write initial module/plugin states <tt class="name">local/config/state.xml</tt> ... '
        .fmtResult(File::write($file, file_get_contents('dist/config/state.xml')));
  TplData::add('MSG', $msg);
  @chmod($file, 0644);
}

// --------------------------------------------------------------------------

$file = BASEDIR.'/local/custom/init.dist.php';
$file2 = BASEDIR.'/local/custom/init.php';
if (!file_exists($file) AND !file_exists($file2)) {
  $msg = 'Create local initialization code file <tt class="name">local/custom/init.dist.php</tt> ... '
        .fmtResult(File::write($file, file_get_contents('dist/custom/init.dist.php')));
  TplData::add('MSG', $msg);
  @chmod($file, 0666);
}

$file = BASEDIR.'/local/custom/exec.dist.xml';
$file2 = BASEDIR.'/local/custom/exec.xml';
if (!file_exists($file) AND !file_exists($file2)) {
  $msg = 'Create file for your custom system command settings <tt class="name">local/custom/exec.dist.xml</tt> ... '
        .fmtResult(File::write($file, file_get_contents('dist/custom/exec.dist.xml')));
  TplData::add('MSG', $msg);
  @chmod($file, 0666);
}

File::write(BASEDIR.'/local/custom/README', file_get_contents('dist/custom/README'));

// --------------------------------------------------------------------------

$file = BASEDIR.'/.htaccess';
if (!file_exists($file)) {
  $msg = 'Create <tt class="name">.htaccess</tt> ... '
        .fmtResult(File::write($file, file_get_contents('dist/htaccess.root')));
  Tpldata::add('MSG', $msg);
  @chmod($file, 0644);
}

// --------------------------------------------------------------------------

if ($_SESSION['INSTALL'] AND $files = glob('install/*.zip')) {
  require_once LIBDIR.'/dZip/dUnzip2.inc.php';
  foreach ($files as $file) {
    $msg = 'Extract extra files from <tt class="name">'.basename($file).'</tt> ... ';
    $zip = new dUnzip2($file);
    $zip->unzipAll(realpath('../'), '', TRUE, 0755);
    $zip->close();
    $msg .= fmtResult();
    TplData::add('MSG', $msg);
  }
}

// --------------------------------------------------------------------------

if ($_SESSION['INSTALL']) {
  // clear temp dir
  $cmd = sprintf('rm -rf "%s/local/data/tmp/"* >/dev/null &2>1', BASEDIR);
  exec($cmd);

  $files = glob(BASEDIR.'/*/*/install.class.php');
  sort($files);
  foreach ($files as $file) {
    include_once $file;
    $file = str_replace(BASEDIR.'/', '', $file);
    list($type, $extension, ) = explode('/', $file);
    $class = sprintf('esf_Install_%s_%s', $type, $extension);
    $installer = new $class;
    if ($info = $installer->SetupInfo()) {
      TplData::set('Notices.'.$type.'.Name', ucwords($type).'s');
      TplData::add('Notices.'.$type.'.Notes',
                   array('Name' => ucwords($extension), 'Note' => $info));
    }
    unset($installer);
  }
}
