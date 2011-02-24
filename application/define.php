<?php
/**
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-79-g85bf9fc 2011-02-15 18:24:07 +0100 $
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * prior PHP 5.2
 */
if (!isset($_SERVER['REQUEST_TIME'])) $_SERVER['REQUEST_TIME'] = time();

$version = file(dirname(__FILE__).'/.version', FILE_IGNORE_NEW_LINES);

/**
 * Frontend version
 */
define('ESF_VERSION', $version[0]);

/**
 * Frontend release date
 */
define('ESF_RELEASE', $version[1]);

/**
 * Frontend title
 */
define('ESF_TITLE', '|es|f|');

/**
 * Frontend slogan
 */
define('ESF_SLOGAN', 'esniper frontend');

/**
 * Project homepage
 */
define('ESF_HOMEPAGE', 'http://es-f.com/');

/**
 * Author
 */
define('ESF_AUTHOR', 'Knut Kohl');

/**
 * Authors email
 */
define('ESF_EMAIL', 'es-f@users.sourceforge.net');

/**
 * Long title
 *
 * @uses ESF_TITLE
 * @uses ESF_SLOGAN
 */
define('ESF_LONG_TITLE', ESF_TITLE . ' ' . ESF_SLOGAN);

/**
 * Full version
 *
 * @uses ESF_VERSION
 * @uses ESF_RELEASE
 */
define('ESF_FULL_VERSION', 'es-f/' . ESF_VERSION.' (' . ESF_RELEASE . ')');

/**
 * Full title
 *
 * @uses ESF_LONG_TITLE
 * @uses ESF_FULL_VERSION
 */
define('ESF_FULL_TITLE', ESF_LONG_TITLE . ', ' . ESF_FULL_VERSION);

/**
 * Actual version of configuration file
 *
 * If the actual configuration version is lower than the required, the frontend
 * redirects automatic to the setup routines.
 *
 * Version 3:
 * - added EBAYTLD
 * Version 4:
 * - added CACHE
 * - added PARSEORDER for ebay parser
 * Version 5:
 * - changed all settings direct to Registry::set(...);
 * - changed module to startmodule
 * - run dir: local/data (fix)
 * Version 6:
 * - force extensions check
 * Version 7:
 * - search more binaries
 * Version 8:
 * - Auto probe caches
 * Version 9:
 * - Remove cache probing
 * - Make currency global
 */
define('ESF_CONFIG_VERSION', 9);

/**
 * Auction data structure version, start using above ESF_VERSION > 2.5.0
 */
define('ESF_AUCTION_VERSION', '2.5');

/**
 * Required PHP version
 */
define('PHP_VERSION_REQUIRED', '5.1.0');

/**
 * Operating system
 */
define('ESF_OS', 'unix');

/**
 * Unique application id
 */
define('APPID', md5(__FILE__));

/**
 * File system base directory
 * DOCUMENT_ROOT is only set if called from web server
 */
defined('BASEDIR') ||
define('BASEDIR', !empty($_SERVER['DOCUMENT_ROOT'])
                ? dirname($_SERVER['SCRIPT_FILENAME'])
                : realpath(dirname(__FILE__).'/..') );

define('APPDIR',   BASEDIR.'/application');
define('LIBDIR',   APPDIR.'/lib');
define('LOCALDIR', BASEDIR.'/local');
define('TEMPDIR',  realpath(dirname(__FILE__).'/..').'/local/tmp');

/**
 * Start always with Auction module
 */
define('STARTMODULE', 'auction');

/**
 * Placeholder flag for select option value of "- from group -"
 *
 * If user selects FROMGROUP as category for a new auction or during editing
 * an auction, the system will get the category from the selected (new) group
 */
define('FROMGROUP', '#$#$#');

/**
 * No delopment features by default
 */
defined('DEVELOP') || define('DEVELOP', FALSE);
