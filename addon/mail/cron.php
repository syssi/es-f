<?php
/**
 * Script to check aution log files via cron job
 *
 * @package    Addon-Mail
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */

Header('Content-type: text/plain');

error_reporting(0);
error_reporting(-1);

define('_ESF_OK', TRUE);

define('LINE', str_repeat('-', 78) . "\n");

define('BASEDIR', dirname(dirname(dirname(__FILE__))));

require_once BASEDIR.'/application/define.php';
require_once LIBDIR.'/Yryie/Yryie.class.php';
require_once APPDIR.'/classes/loader.class.php';

if (!Loader::Register()) {
  // Emulate autoloading for PHP < 5.1.2
  function __autoload( $class ) { Loader::__autoload($class); }
}

Loader::$AutoLoadPath[] = APPDIR.'/classes';

ErrorHandler::register('echo');

require_once APPDIR.'/init.php';
require_once APPDIR.'/functions.php';

ErrorHandler::register('echo');

$include = dirname(__FILE__).'/include';
require_once $include.'/functions.php';
require_once $include.'/class.phpmailer-lite.php';

// remove script file name
array_shift($_SERVER['argv']);
$debug = FALSE;
Registry::set('TempDir', '/tmp');
$users = $skipUsers = array();
$cnt = count($_SERVER['argv']);

for ($i=0; $i<$cnt; $i++) {
  $param = strtolower($_SERVER['argv'][$i]);
  if ($param == '-d') {
    $debug = TRUE;
  } elseif ($param == '--help') {
    echo <<<EOT
Usage: cron.sh [OPTIONS] [SkipUsers]
       -d        debug to console

By default, all defined users are informed.

EOT;
    exit;
  }
}

// if no user forced, refresh all
Registry::set('Develop', FALSE);

$oCache = Cache::factory('Mock');
Registry::set('Cache', $oCache);

$xml = new XML_Array_Configuration($oCache);
$cfg = $xml->ParseXMLFile(BASEDIR.'/local/config/config.xml');
if (!$cfg) die($xml->Error);

foreach ($cfg['users'] as $user) $users[] = $user['name'];
unset($cfg['users']);

foreach ($cfg['esniper'] as $key => $val) Esniper::set($key, $val);
unset($cfg['esniper']);

// Set all other into registry
Registry::set($cfg);

esf_Extensions::Init();

_d('Found users:', implode(', ',$users), "\n");

// include plugins
require_once BASEDIR.'/plugin/plugins.php';

// exclude some plugins from auto load
esf_Extensions::setState('plugin', 'refreshbackground', 0);

Core::ReadConfigs(esf_Extensions::PLUGIN);
Core::ReadConfigs('local/*/*');
Core::IncludeSpecial(esf_Extensions::PLUGIN, 'plugin.class');

$SemDir = BASEDIR.'/local/addon/mail';

is_dir($SemDir) || Exec::getInstance()->MkDir($SemDir);

$SemFiles = glob($SemDir.'/*.log');

exec('hostname', $hostname);
$hostname = implode($hostname);

$cfg = $xml->ParseXMLFile(dirname(__FILE__).'/config.xml');
if (!$cfg) die($xml->Error."\n\n");

_d('Configuration:');
_d(print_r($cfg, TRUE));

$mail = $xml->ParseXMLFile(dirname(__FILE__).'/mail.xml');
if (!$mail) die($xml->Error."\n\n");

_d();
_d('Mail settings:');
_d(print_r($mail, TRUE));

// mailer
$Mailer = new PHPMailerLite();

if (isset($mail['mail']) AND $mail['mail']) {
  $Mailer->isMail();
  _d('Using PHPs mail()', "\n");
} else {
  _d('Using Sendmail', "\n");
}

$Mailer->SetFrom(!empty($mail['from']['email'])
               ? $mail['from']['email']
               : 'es-f@'.$hostname,
                 !empty($mail['from']['name'])
               ? $mail['from']['name']
               : '|es|f| '.ESF_VERSION.' @ '.$hostname);

if ($debug) $Mailer->action_function = 'mail_callback';

// defaults
if (empty($mail['subject'])) $mail['subject'] = 'Auction group state "%1$s" (%2$s)';
if (empty($mail['message'])) $mail['message'] = '%1$s' . "\n" . LINE . "\n" . '%2$s';

// loop all users
foreach ($users as $user) {

  $luser = strtolower($user);

  if (in_array($luser, $skipUsers) OR !isset($mail['users'][$luser])) continue;

  esf_User::InitUser($user);
  $userDir = esf_User::UserDir();
  esf_Auctions::Load();

  _d('Scan user:', $user, '(', $userDir, ')');

  $EndRegex = '~(^.*?'.implode('.*?$|^.*?', $cfg['esniperended']).'.*?$)~mi';
  _d('Reg. expr. for ended auctions:');
  _d($EndRegex);

  // analyse all log files
  foreach (glob($userDir.'/*.'.$luser.'.log') as $LogFile) {

    _d();
    _d(LINE, 'Log file:', $LogFile, "\n");

    $SemFile = $SemDir.'/'.basename($LogFile);
    $SemLock = $SemFile.'.ignore';
    $SemMTime = File::MTime($SemFile);

    $LogFileContent = trim(iconv('UTF-8', 'ISO-8859-1//IGNORE', file_get_contents($LogFile)));

    // was auction file changed since last check and should all changes send
    $AuctionChanged = ($SemMTime AND $SemMTime<File::MTime($LogFile) AND $mail['everychange']);
    // is the auction ended
    $AuctionEnded = preg_match($EndRegex, $LogFileContent);

    if ($AuctionChanged OR ($AuctionEnded AND !file_exists($SemLock))) {

      // log file change found, remove the lock file
      if ($AuctionChanged) @unlink($SemLock);

      $AuctionFile = substr($LogFile, 0, -4);
      $AuctionFileContent = trim(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', file_get_contents($AuctionFile)));

      // get group name OR item id
      $name = basename($AuctionFile, '.'.$luser);
      if (isset(esf_Auctions::$Auctions[$name]))
        $name = preg_replace('~_+~', ' ', @esf_Auctions::$Auctions[$name]['name']);
      
      $Mailer->ClearAllRecipients();
      $Mailer->AddAddress($mail['users'][$luser], $user);

      $Mailer->Subject = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', sprintf($mail['subject'], $name, date('r')));

      // plain text
      $Mailer->AltBody = sprintf($mail['message'], $AuctionFileContent, $LogFileContent);
      // prepare HTML
      $AuctionFileContent = '<pre>' . $AuctionFileContent . '</pre>';
      // format log file content
      $LogFileContent = preg_replace('~^Auction .*?$~mi', '<strong>$0</strong>', $LogFileContent);
      $LogFileContent = preg_replace('~^High bidder.*?$~mi', '<span style="color:red">$0</span>', $LogFileContent);
      // mark end reason
      $LogFileContent = preg_replace($EndRegex, '<span style="color:red"><strong>$0</strong></span>', $LogFileContent);
      // adjust line breaks
      $LogFileContent = preg_replace('~\n\s*\n+~', '</p><p>', $LogFileContent);
      $LogFileContent = str_replace("\n", '<br>', $LogFileContent);
      // set HTML mail body
      $Mailer->Body = sprintf($mail['message'], $AuctionFileContent, '<p>'.$LogFileContent.'</p>');
      $Mailer->Body = str_replace(LINE, '<hr>', $Mailer->Body);

      if (!$Mailer->Send()) echo 'Mailer Error: ', $Mailer->ErrorInfo;

    } else {
      _d('Not changed, ignored.');
    }

    if (!$debug) {
       touch($SemFile);
       if ($AuctionEnded) touch($SemLock);
    }

    // mark semaphore file as done
    unset($SemFiles[array_search($SemFile, $SemFiles)]);
    _d();
  }

  // remove orphan semaphore files
  if (!$debug) {
    foreach ($SemFiles as $file) {
      @unlink($file);
      @unlink($file.'.ignore');
    }
  }
}
