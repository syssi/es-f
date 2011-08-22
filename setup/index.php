<?php
/**
 * @ingroup    setup
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-84-g4cb6710 2011-02-19 14:07:42 +0100 $
 * @revision   $Rev$
 */

ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
error_reporting(0);

// Clear possible caches
if (function_exists('apc_clear_cache')) apc_clear_cache();
if (function_exists('eaccelerator_clear')) eaccelerator_clear();

if (file_exists('../prepend.php')) require '../prepend.php';

define('_ESF_OK', TRUE);

session_start();

define('BASEDIR', dirname(dirname(__FILE__)));

require_once '../application/define.php';

require_once LIBDIR.'/yryie/yryie.class.php';
require_once APPDIR.'/classes/loader.class.php';

if (!Loader::Register()) {
  /**
   * Emulate autoloading for PHP < 5.1.2
   */
  function __autoload( $class ) { Loader::__autoload($class); }
}

Loader::$AutoLoadPath[] = APPDIR.'/classes';

// include functions
Loader::Load(APPDIR.'/functions.php');
// Configurations
Loader::Load(APPDIR.'/init.php');
require_once 'functions.php';

// Emulate register_globals off
unregister_GLOBALS();

Loader::Load(APPDIR.'/lib/cache/cache.class.php');
$oCache = Cache::create(NULL, 'Mock');

Core::$Crypter = new MD5Crypter;

TplData::$NameSpaceSeparator = '.';

// -----------------------------------------------------------------------------
// step definitions
// -----------------------------------------------------------------------------
$steps = array(
// step               Sub-Title           [, next step, next step on error]
  'intro'   => array('Welcome'                                              ),
  'cfg'     => array('Configuration'                                        ),
  'cfgchk'  => array(NULL,                   'test',    'cfg'               ),
  'test'    => array('Permissions / Tests'                                  ),
  'user'    => array('Users'                                                ),
  'userchk' => array(NULL,                   'save',    'user'              ),
  'save'    => array('Finished'                                             ),
);
// -----------------------------------------------------------------------------

$step = (isset($_REQUEST['step']) AND isset($_SESSION['CHECKED']))
      ? $_REQUEST['step'] : 'intro';
$step = isset($steps[$step]) ? $step : 'intro';
$stepdata = $steps[$step];
$Error = FALSE;

/**
 * pre process
 */
switch ($step) {

  // ------------
  case 'intro':
    // reset session data
    $_SESSION = array();
    // clear temp. directories
    exec('rm -rf ../local/data/tmp/*');
    exec('rm -rf ../local/data/session/*');

    if (!empty($_GET['msg'])) Messages::Error($_GET['msg']);

    LoadConfig();
    $_SESSION['USERS'] = Registry::get('Users');
    $_SESSION['INSTALL'] = (empty($_SESSION['USERS']) OR array_key_exists('DEVELOP', $_GET));
    $_SESSION['CHECKED']['intro'] = TRUE;
    break;

  // ------------
  case 'cfg':
    LoadConfig();
    $_SESSION['USERS'] = Registry::get('Users');
    // test for configuration versions below 5
    $cfg = empty($cfg) ? Registry::getAll() : array_merge(Registry::getAll(), $cfg);

    foreach ($cfg as $key => $val) TplData::set('cfg.'.$key, $val);

    $parsers = array();
    foreach (glob(APPDIR.'/classes/ebayparser/*.class.php') as $file)
      $parsers[] = basename($file, '.class.php');

    TplData::set('EBAYPARSERS', implode(', ', $parsers));

    exec('locale -a', $locales);
    TplData::set('LOCALES', $locales);
    unset($locales);

    if ($h = exec('which sh'))      TplData::set('cfg.bin_sh', $h);
    if ($h = exec('which grep'))    TplData::set('cfg.bin_grep', $h);
    if ($h = exec('which kill'))    TplData::set('cfg.bin_kill', $h);
    if ($h = exec('which esniper')) TplData::set('cfg.bin_esniper', $h);

    foreach (Esniper::getAll() as $key => $val)
      TplData::set('esniper.'.strtoupper($key), $val);

    TplData::set('TIMEZONES', file(dirname(__FILE__).'/date.timezone', FILE_IGNORE_NEW_LINES));
    break;

  // ------------
  case 'cfgchk':
    if ($_POST['data']['cfg']['currency'] == '?')
      $_POST['data']['cfg']['currency'] = $_POST['data']['cfg']['currency1'];
    // remove always
    unset($_POST['data']['cfg']['currency1']);
    savePosted('cfg');
    $Error = empty($_POST['data']['cfg']['currency']);
    if ($Error) Messages::Error('Missing currency definition!');
    $_SESSION['CHECKED']['cfg'] = !$Error;
    break;

  // ------------
  case 'test':
    include 'step.test.inc.php';
    break;

  // ------------
  case 'user':
    break;

  // ------------
  case 'userchk':
    checkPosted('remove', 'user', 'pass1', 'pass2');
    savePosted('user');
    $_SESSION['CHECKED']['user'] = TRUE;
    break;

  // ------------
  case 'save':
    include 'step.save.inc.php';
    break;
}

if (!isset($stepdata[0])) {
  // Redirect
  session_write_close();
  Header('Location: index.php?step='.(!$Error ? $stepdata[1] : $stepdata[2]));
  Exit;
}

// since PHP 5.1.0
if (function_exists('date_default_timezone_set'))
  // http://php.net/manual/en/timezones.php
  date_default_timezone_set(Registry::get('TimeZone'));

// -----------------------------------------------------------------------------
// Output
// -----------------------------------------------------------------------------
TplData::setConstant('ESF.TITLE', ESF_TITLE);
TplData::setConstant('ESF.SLOGAN', ESF_SLOGAN);
TplData::setConstant('ESF.VERSION', ESF_VERSION);
TplData::setConstant('ESF.RELEASE', ESF_RELEASE);
TplData::setConstant('ESF.HOMEPAGE', ESF_HOMEPAGE);
TplData::setConstant('ESF.LONG_TITLE', ESF_LONG_TITLE);
TplData::setConstant('ESF.FULL_VERSION', ESF_FULL_VERSION);
TplData::setConstant('ESF.FULL_TITLE', ESF_FULL_TITLE);
TplData::setConstant('BUTTON', '../button/button.php');
TplData::setConstant('IMAGES', urlencode('../setup/layout/default/images'));

TplData::setConstant('PHP_VERSION', PHP_VERSION);
TplData::setConstant('PHP_VERSION_REQUIRED', PHP_VERSION_REQUIRED);

TplData::set('USERS', $_SESSION['USERS']);
TplData::set('INSTALL', $_SESSION['INSTALL']);

TplData::set('SUBTITLE1', $stepdata[0]);
#TplData::set('SUBTITLE2', '');

// form data
TplData::merge(NULL, getSavedPosted($step));

$keys = array_keys($steps);
foreach ($keys as $id => $name) if ($step == $name) break;

TplData::set('BASEDIR', dirname(__FILE__));

TplData::set('FORM_IS_OPEN', FALSE);

TplData::set('NEXTSTEP', (isset($keys[$id+1]) ? $keys[$id+1] : ''));
TplData::set('NEXTTEXT', 'Next');

// skip redirect steps
while (isset($keys[$id-1]) AND !isset($steps[$keys[$id-1]][0])) $id--;
TplData::set('PREVSTEP', (isset($keys[$id-1]) ? $keys[$id-1] : ''));
TplData::set('PREVTEXT', 'Back');

if (version_compare(PHP_VERSION, PHP_VERSION_REQUIRED, '<')) $step = 'version';

Registry::set('TempDir', FALSE);

require_once LIBDIR.'/yuelo/yuelo.require.php';

$Template = esf_Template::getInstance();

// Don't cache PHP code or static content
Yuelo::set('ReuseCode', FALSE);
Yuelo_Cache::Active(FALSE);

$RootDir = dirname(__FILE__).'/layout';

TplData::set('CONTENT', $Template->Render('step.'.$step, TRUE, $RootDir));

/**
 * post process
 */
switch ($step) {

  // ------------
  case 'cfg':
    TplData::set('FORM_IS_OPEN', TRUE);
    break;

  // ------------
  case 'test':
    if (TplData::get('ERROR')) {
      TplData::set('NEXTSTEP', 'test');
      TplData::set('NEXTTEXT', 'Reload');
    }
    break;

  // ------------
  case 'user':
    TplData::set('FORM_IS_OPEN', TRUE);
    TplData::set('NEXTTEXT', 'Save data');
    break;

  // ------------
  case 'save':
    TplData::set('PREVSTEP');
    break;

}

TplData::set('MESSAGES', (($msgs=Messages::get()) ? implode($msgs) : ''));

$Template->Output('index', TRUE, $RootDir);

// -----------------------------------------------------------------------------
// Finalize
// -----------------------------------------------------------------------------
switch ($step) {

  // ------------
  case 'save':
    // clear session data
    $_SESSION = array();
    break;
}
