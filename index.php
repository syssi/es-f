<?php
/** @mainpage

@image html logo.gif

@section title |es|f| esniper frontend

A web based HTML frontend for esniper, the lightweight console application for
sniping eBay auctions

© 2006-2011 by Knut Kohl <es-f@users.sourceforge.net>

@section license License

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.

If not, see http://www.gnu.org/licenses/gpl.txt

*/

/**
 * Main program file
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-45-g3faf669 - Wed Jan 12 21:35:21 2011 +0100 $
 */

ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
error_reporting(0);

if (file_exists('prepend.php')) require 'prepend.php';

define('_ESF_OK', TRUE);

try {

  /**
   * Application definitions
   */
  require_once 'application' . DIRECTORY_SEPARATOR . 'define.php';

  // check if required config files exists and required PHP version
  if (!is_file(LOCALDIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.xml') OR
      !is_file(LOCALDIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'state.xml') OR
      version_compare(PHP_VERSION, PHP_VERSION_REQUIRED, '<')) {
    Header('Location: setup/index.php');
  }

  /**
   * Load Yryie
   */
  require_once APPDIR . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR
             . 'yryie' . DIRECTORY_SEPARATOR. 'yryie.class.php';
  Yryie::Versions();

  /**
   * Class autoloading
   */
  require_once APPDIR . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'loader.class.php';

  if (!Loader::Register()) {
    /**
     * Emulate autoloading for PHP < 5.1.2
     */
    function __autoload( $class ) { Loader::__autoload($class); }
  }

  Loader::$AutoLoadPath[] = APPDIR . DIRECTORY_SEPARATOR . 'classes';
  if (DEVELOP) Loader::setPreload('__develop');

  Loader::Load(BASEDIR.'/index.inc.php');

  // set garbage collection probability to 5%
  $gc = Yuelo_Cache::gc(5);

} catch (Exception $e) {

  if (DEVELOP) echo '<pre>', $e; else echo $e->getMessage();

}

if (file_exists('append.php')) require 'append.php';
