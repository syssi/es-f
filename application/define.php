<?php
/**
 * @package es-f
 * @subpackage Core
 * @desc Common used defines
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * prior PHP 5.2
 */
if (!isset($_SERVER['REQUEST_TIME'])) $_SERVER['REQUEST_TIME'] = time();

/**
 * Frontend version
 */
define('ESF_VERSION', '2.3.1');

/**
 * Frontend release date
 */
define('ESF_RELEASE', '2010-11-22');

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
define('ESF_FULL_VERSION', 'Version ' . ESF_VERSION.' / ' . ESF_RELEASE);

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
 */
define('ESF_CONFIG_VERSION', 6);

/**
 * Required PHP version
 */
define('PHP_VERSION_REQUIRED', 5);

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
define('LOCALDIR', BASEDIR.'/local');
define('TEMPDIR',  realpath(dirname(__FILE__).'/..').'/local/tmp');

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
