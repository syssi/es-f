<?php
/**
 * Scritp to refresh auctions in background via cron job
 *
 * @package Plugin-Refreshbackground
 */

error_reporting(0);
error_reporting(E_ALL);

Header('Content-type: text/plain');

define( '_ESF_OK', TRUE );

define('BASEDIR', dirname(dirname(dirname(__FILE__))));

require_once BASEDIR.'/application/define.php';
require_once LIBDIR.'/debugstack/debugstack.class.php';
require_once APPDIR.'/classes/loader.class.php';

if (!Loader::Register()) {
  // Emulate autoloading for PHP < 5.1.2
  function __autoload( $class ) { Loader::__autoload($class); }
}

Loader::$AutoLoadPath[] = APPDIR.'/classes';

ErrorHandler::register('echo');

require_once APPDIR.'/init.php';
require_once APPDIR.'/functions.php';

session_start();

Cache::Init('Mock');
HTMLPage::$Debug = FALSE;

$xml = new XML_Array_Configuration(Cache::getInstance());
$cfg = $xml->ParseXMLFile(BASEDIR.'/local/config/config.xml');
if (!$cfg) die($xml->Error);

$allUsers = array();
foreach ($cfg['users'] as $user) $allUsers[] = $user['name'];
unset($cfg['users']);

foreach ($cfg['esniper'] as $key => $val) Esniper::set($key, $val);
unset($cfg['esniper']);

// Set all other into registry
Registry::set($cfg);

esf_Extensions::Init();
esf_Extensions::setState('plugin', 'refreshbackground', 0);

// remove script file name
array_shift($_SERVER['argv']);
$print = $verbose = $debug = FALSE;
$users = $skipUsers = array();
$cnt = count($_SERVER['argv']);

for ($i=0; $i<$cnt; $i++) {
  $param = strtolower($_SERVER['argv'][$i]);
  if ($param == '-p') {
    $print = TRUE;
  } elseif ($param == '-v') {
    $verbose = TRUE;
  } elseif ($param == '-d') {
    $print = $verbose = $debug = TRUE;
  } elseif ($param == '-f') {
    $users[] = strtolower($_SERVER['argv'][++$i]);
  } elseif ($param == '-s') {
    $skipUsers[] = strtolower($_SERVER['argv'][++$i]);
  }
}

// if no user forced, refresh all
if (empty($users)) $users = $allUsers;

Loader::Load(APPDIR.'/functions.php');
Loader::Load(APPDIR.'/ebay.php');

// must not exist
Core::ReadConfigs(BASEDIR.'/local/custom');

// include plugins
Loader::Load(BASEDIR.'/plugin/plugins.php');

// exclude some plugins from auto load
esf_Extensions::setState('plugin', 'refreshbackground', 0);

Core::ReadConfigs(esf_Extensions::PLUGIN);
Core::ReadConfigs(esf_Extensions::MODULE);
Core::ReadConfigs(BASEDIR.'/local/*/*');
Core::IncludeSpecial(esf_Extensions::PLUGIN, 'plugin.class');

/**#@+
 * @ignore
 */
define('LINE1', str_repeat('-',78) . "\n");
define('LINE2', str_repeat('*',78) . "\n");
/**#@-*/

foreach ($users as $user) {

  $ts1 = microtime(TRUE);

  $print || ob_start();

  if ($verbose) echo 'Actual user: ', $user, "\n";

  if (in_array(strtolower($user), $skipUsers)) {
    if ($verbose) echo 'Skip user.', $user, "\n";
    continue;
  }

  Session::set(APPID, MD5Encryptor::encrypt($user));
  esf_User::InitUser($user);

  if (!$logfile = Registry::get('Plugin.RefreshBackground.LogFile'))
    $logfile = 'refresh-bg.log';

  $logfile = esf_User::UserDir().'/'.$logfile;
  if (file_exists($logfile) AND
      (int)filesize($logfile) > Registry::get('Plugin.RefreshBackground.LogFileSize')*1024) {
    if ($verbose) echo 'Delete log file.', "\n";
    File::delete($logfile);
  }

  Event::ProcessInform('setLastUpdate');

  echo "\n", LINE2, date('Y-m-d H:i:s : ', $ts1), ESF_FULL_TITLE, "\n", LINE1;

  esf_Auctions::Load();

  foreach (esf_Auctions::$Auctions as $item => $auction) {
    printf('%s: %s'."\n", $item, $auction['name']);

    if (!$auction['ended']) {
      if ($auctionNew = esf_Auctions::fetchAuction($item, FALSE)) {
        if ($verbose) echo 'Parser: ', $auctionNew['parser'], "\n";

        // no error during reading, save silent
        esf_Auctions::save($auctionNew, FALSE);

        // auction was not ended before
        if (!$auction['ended']) {
          foreach (array('bids', 'bid') as $key) {
            if ($auction[$key] != $auctionNew[$key]) {
              printf('%-6s: %-10s => %s' . "\n",
                     ucwords($key), $auction[$key], $auctionNew[$key]);
            }
          }
        }
      } else {
        echo 'Error fetching auction data!', "\n";
      }
    } else {
      echo 'Auction is ended.', "\n";
    }

    echo LINE1;
  }

  $ts2 = microtime(TRUE);
  $dur = $ts2 - $ts1;
  $cnt = esf_Auctions::count();

  echo date('Y-m-d H:i:s : ', $ts2),
       sprintf('Needed %.3fs for %d auctions - %.3fs per auction',
               $dur, $cnt, $dur/$cnt), "\n",
       LINE2, "\n";

  $content = ob_get_clean();

  if ($print OR $debug) echo $content; else File::append($logfile, $content);
}
