<?php
/**
 * Program initialization
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */

defined('_ESF_OK') || die('No direct call allowed.');

// no session auto start
ini_set('session.auto_start', 0);

// clear file cache
clearstatcache();

// force UTF-8
Header('Content-Type: text/html; charset=utf-8');

/**
 * P3P Policy (http://www.w3.org/P3P/)
 *
 * Source: http://www.phpopentracker.de/docs/en/installation.html
 *
 * The MS Internet Explorer 6, and maybe other browsers, too, requires a
 * Platform for Privacy Preferences (P3P) Project policy in order to accept
 * cookies.
 *
 * This includes session cookies that are used by PHP's session management
 * system, on which |es|f| relies. |es|f| cannot handle users using the MS
 * Internet Explorer 6 correctly if the site does not send such a policy to the
 * client.
 *
 * The code below shows a suitable P3P policy.
 */
Header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"');

/**
 * Set new separator for namespaces in registry
 */
Registry::$NameSpaceSeparator = '.';

/**
 * Application configuration
 */
// Version 1:
// Registry::set('esniper',     'esniper'); ->bin_exniper
Registry::set('SuDo',        '');
// Registry::set('RunDir',      '.run'); -> v. 5 - now fix: ./local
// Registry::set('Language',    'en'); -> v. 9 - get during login from $_SERVER['HTTP_ACCEPT_LANGUAGE']
// Registry::set('Layout',      'default'); -> v. 9 - during login
Registry::set('MenuStyle',   'image,text,image');

// Version 2:
Registry::set('Netmask',     '255.255.255.255');

// Version 3:
Registry::set('ebayTLD',     'com');

// Version 4:
Registry::set('Cache',       FALSE);  // future use
Registry::set('SessionName', ini_get('session.name'));
Registry::set('LogFile',     '');
Registry::set('ParseOrder',  'com,co_uk,de');

// Version 5:
// Registry::set('Module',      'index'); -> StartModule
Registry::set('bin_esniper',  'esniper');
Registry::set('StartModule',  'index');
Registry::set('TimeZone',     'GMT');
Registry::set('Locale',       'C');
Registry::set('RunDir',       BASEDIR.'/local/data');
// Registry::set('Develop',     FALSE); removed

// Version 6: extension check during setup

// Version 7:
Registry::set('bin_sh',   'sh');
Registry::set('bin_grep', 'grep');
Registry::set('bin_kill', 'kill');

// Version 8:
Registry::set('CacheClass', 'File');

// Version 9:
// get language during login from $_SERVER['HTTP_ACCEPT_LANGUAGE']
// set Layout during login

/**
 * esniper configuration
 */
// Version 1:
Esniper::set('seconds',     10);
Esniper::set('debug',       'no');
Esniper::set('logdir',      '');
Esniper::set('proxy',       '');

// Version 2:
Esniper::set('historyHost', '');
Esniper::set('prebidHost',  '');
Esniper::set('bidHost',     '');
Esniper::set('loginHost',   '');
Esniper::set('myeBayHost',  '');

/**
 * Global application data
 */
Registry::set('esf.contentonly', FALSE);

// use own session name
Registry::set('SessionName', 'ESFSESSID');
#Session::$NVL = '';

/**
 * Somme common URLs
 */
Registry::set('URL.CreateBugTrackerItem', 'http://sourceforge.net/tracker/?func=add&group_id=185222&atid=912405');
Registry::set('URL.es-f.Homepage', 'http://es-f.com');
Registry::set('URL.es-f.SourceForge', 'http://sourceforge.net/projects/es-f');

/**
 * cURL timeouts
 */
Registry::set('cURL.ConnectionTimeOut', 5);
Registry::set('cURL.TimeOut', 10);
Registry::set('cURL.Verbose', FALSE);

/**
 * Configure TemplateData System
 */
TplData::$NameSpaceSeparator = '.';
TplData::$KeysUppercase = TRUE;
TplData::$NVL = NULL;

/**
 * Configure I18n System
 */
Translation::$NameSpaceSeparator = '.';

/**
 * Icons for powered by footer
 */
$GLOBALS['Servers'] = array(
  array( 'apache',    'http://www.apache.org' ),
  array( 'lighttpd',  'http://www.lighttpd.net' ),
  array( 'nginx',     'http://nginx.org' ),
  array( 'litespeed', 'http://www.litespeedtech.com/overview.html' ),
  array( 'iis',       'http://www.microsoft.com/iis' ),
);

// >> Debug
/** --------------------------------------------------------------------------
 * Helper functions
 */

/**
 * @ignore
 */
function _dbg( $var, $name='' ) {
  ob_start();
  $options = array( 'name' => $name );
  new dBug($var, $options);
  Messages::addInfo(ob_get_clean(), TRUE);
}

/**
 * @ignore
 */
function __dbg( $var, $name='' ) {
  $options = array( 'name' => $name );
  new dBug($var, $options);
}

/**
 * @ignore
 */
function _dump() {
  foreach (func_get_args() as $arg) {
    ob_start();
    var_dump($arg);
    Messages::addInfo('<pre>'.ob_get_clean().'</pre>', TRUE);
  }
}
// << Debug
