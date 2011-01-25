<?php

defined('_ESF_OK') || die('No direct call allowed.');

// ------------------------------------------------------------------------
$cfg = getSavedPosted('cfg');

$err = FALSE;

// directories that must be writable
$setup_dirs = array(
  realpath('../')          => array('Base directory', 'Make directory writable for creation of an initial .htaccess.'),
  realpath('../').'/local' => array('Working directory', 'Create directory and/or make writable.'),
);

foreach ($setup_dirs as $h => $data) {
  $data[2] = array();
  if (is_dir($h)) {
    $data[2][] = Messages::toStr('Exists.', Messages::SUCCESS);
  } else {
    $data[2][] = Messages::toStr('Create.', Messages::INFO);
    @mkdir($h, 0777);
  }
  if (!is_dir($h)) {
    $data[2][] = Messages::toStr('Can\'t create.', Messages::ERROR);
    $err = TRUE;
  } else {
    if (!is_writable($h)) {
      $data[2][] = Messages::toStr('Make writable.', Messages::INFO);
      @chmod($h, 0777);
    }
    if (is_writable($h)) {
      $data[2][] = Messages::toStr('Is writable.', Messages::SUCCESS);
    } else {
      $data[2][] = Messages::toStr('Can\'t make writable.', Messages::ERROR);
      if ($_SESSION['INSTALL']) $err = TRUE;
    }
  }
  $setup1[$h] = $data;
}

// Extensions
CheckResult('<h3>PHP Extensions</h3>');

CheckResult(
  'pcre',
  ($ok = extension_loaded('pcre')),
  'Regular Expressions (Perl-Compatible)',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.pcre.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  $ok ? Messages::toStr('Loaded', Messages::SUCCESS)
      : Messages::toStr('Not loaded', Messages::ERROR)
);

CheckResult(
  'gd',
  ($gdVersion = @gdVersion()),
  'Image Processing and GD',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.image.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  $gdVersion ? Messages::toStr('Loaded<br>Version&nbsp;'.$gdVersion, Messages::SUCCESS, TRUE)
             : Messages::toStr('Not loaded', Messages::ERROR)
);

CheckResult(
  'xml',
  ($ok = extension_loaded('xml')),
  'XML Parser',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.xml.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  $ok ? Messages::toStr('Loaded', Messages::SUCCESS)
      : Messages::toStr('Not loaded', Messages::ERROR)
);

CheckResult(
  'session',
  ($ok = extension_loaded('session')),
  'Session Handling',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.session.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  $ok ? Messages::toStr('Loaded', Messages::SUCCESS)
      : Messages::toStr('Not loaded', Messages::ERROR)
);

CheckResult(
  'iconv',
  ($ok = extension_loaded('iconv')),
  'iconv',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.iconv.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  $ok ? Messages::toStr('Loaded', Messages::SUCCESS)
      : Messages::toStr('Not loaded', Messages::ERROR)
);

CheckResult(
  'curl',
  ($ok = extension_loaded('curl')),
  'Client URL Library',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.curl.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  $ok ? Messages::toStr('Loaded', Messages::SUCCESS)
      : Messages::toStr('Not loaded', Messages::ERROR)
);

CheckResult(
  'zlib',
  TRUE,
  'Zlib Compression<br>(recommended)',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.zlib.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  extension_loaded('zlib') ? Messages::toStr('Loaded', Messages::SUCCESS)
                           : Messages::toStr('Not loaded', Messages::INFO)
);

CheckResult(
  'mbstring',
  TRUE,
  'Multibyte String<br>(required for addon "mail")',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.mbstring.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  extension_loaded('mbstring') ? Messages::toStr('Loaded', Messages::SUCCESS)
                               : Messages::toStr('Not loaded', Messages::INFO)
);

CheckResult(
  'openssl',
  TRUE,
  'OpenSSL<br>(required for addon "mail")',
  'Please refer to <a class="php" href="http://www.php.net/manual/book.openssl.php">php.net</a> '
 .'and the documentation for your distribution how to install this extension.',
  extension_loaded('openssl') ? Messages::toStr('Loaded', Messages::SUCCESS)
                              : Messages::toStr('Not loaded', Messages::INFO)
);

// ------------------------------------------------------------------------
CheckResult('<h3>PHP Settings</h3>');
/**
 * check save mode
 */
if (version_compare(PHP_VERSION, '6', '<')) {
  CheckResult(
    'save_mode',
    ($ok = !ini_get('save_mode')),
    'The PHP safe mode is an attempt to solve the shared-server security problem. '
   .'Safe Mode was removed in PHP 6.0.0.',
    'Deactivate <tt>save_mode</tt> in your PHP configuration (php.ini), '
   .'see <a class="php" href="http://php.net/manual/features.safe-mode.php">php.net</a> '
   .'for details!',
   $ok ? Messages::toStr('Off', Messages::SUCCESS) : Messages::toStr('On', Messages::ERROR)
  );
}

/**
 * check allow url fopen
 */
CheckResult(
  'allow_url_fopen',
  ($ok = ini_get('allow_url_fopen')),
  'This option enables the URL-aware wrappers that enable accessing URL objects like files.',
  'Activate <tt>allow_url_fopen</tt> in your PHP configuration (php.ini), '
 .'see <a class="php" href="http://php.net/manual/ref.filesystem.php#ini.allow-url-fopen">php.net</a> '
 .'for details!',
  $ok ? Messages::toStr('On', Messages::SUCCESS) : Messages::toStr('Off', Messages::ERROR)
);

/**
 * check register globals
 */
$register_globals = ini_get('register_globals');
CheckResult(
  'register_globals',
  TRUE,
  'Whether or not to register the EGPCS (Environment, GET, POST, Cookie, Server) variables as global variables.',
  'Deactivate <tt>register_globals</tt> in your PHP configuration (php.ini) is recommended, '
 .'see <a class="extern" href="http://php.net/manual/en/ini.core.php#ini.register-globals">php.net</a> for details!<br>'
 .($register_globals ? 'For security reasons <tt> register_globals off </tt> will simulated!':''),
  $register_globals ? Messages::toStr('On') : Messages::toStr('Off', Messages::SUCCESS)
);

// ------------------------------------------------------------------------
CheckResult('<h3>binaries</h3>');

$wwwuser = '<tt>'.exec('whoami').'</tt>';

foreach (array('sh', 'grep', 'kill') as $bin) {

  $file = $cfg['cfg']['bin_'.$bin];
  $cmd = 'test -x '.$file;
  exec($cmd, $exec, $rc);
  $ok = ($rc == 0);
  CheckResult(
    $bin,
    $ok,
    'Required for esniper remote control.',
    'Please check the executable flag for of <tt>'.$file.'</tt> for user '.$wwwuser,
    $ok ? Messages::toStr($file, Messages::SUCCESS)
        : Messages::toStr($file, Messages::ERROR)
  );
}

// ------------------------------------------------------------------------
CheckResult('<h3>esniper</h3>');

/**
 * check esniper installed
 */
$cmd = $cfg['cfg']['bin_esniper'].' -v 2>&1';
exec($cmd, $exec, $rc);

$exec = implode($exec);
CheckResult(
  'esniper version',
  ($ok = preg_match('~version\s*([0-9.]+)~', $exec, $esniperVersion)),
  'Check version of<br><tt>'.$cfg['cfg']['bin_esniper'].'</tt>',
  'Download and install from <a class="sourceforge" '
 .'href="http://sourceforge.net/project/showfiles.php?group_id=45285">esniper homepage</a> '
 .'or if installed, but not found in your system path (<tt>'.exec('echo $PATH').'</tt>), '
 .'please go one step <a href="?step=cfg">back</a> and provide full path to esniper binary.',
  $ok ? Messages::toStr($esniperVersion[1], Messages::SUCCESS) : Messages::toStr($exec, Messages::ERROR)
);

if (!$err) {
  Messages::Success('<h4>No errors!</h4>', TRUE);
} else {
  Messages::Error('<h4>There are errors!</h4>', TRUE);
}

TplData::set('ERROR', $err);

foreach ($setup1 as $name => $msgs) {
  TplData::add('PERMS', array(
    'NAME'        => _RealPath($name),
    'DESCRIPTION' => $msgs[0],
    'TODO'        => $msgs[1],
    'MESSAGE'     => implode($msgs[2]),
  ));
}

foreach ($setup2 as $name => $msgs) {
  TplData::add('TESTS', array(
    'NAME'        => $name,
    'DESCRIPTION' => $msgs[0],
    'TODO'        => $msgs[1],
    'MESSAGE'     => implode((array)$msgs[2]),
  ));
}

if (!$err) $_SESSION['CHECKED']['test'] = TRUE;
