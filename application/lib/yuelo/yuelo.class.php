<?php
/** @defgroup Core Core Classes

Core classes

*/

// --------------------------------------------------------------------------

/**
 * Base class with constants and configuration settings
 *
 * @ingroup  Core
 * @version  3.1.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
abstract class Yuelo {

  /**
   * Class version, Version & date
   */
  const VERSION = '3.2.0 / 2010-07-21';

  /**
   * @name Verbose level flags
   * @{
   */

  /**
   * @defgroup verbose Verbose level flags (bit mask)
   * To enable all options, you can also set all bits by using
   * @code
   * Yuelo::set('Verbose', -1);
   * @endcode
   * To combine two or more of them, @c OR them
   * @code
   * Yuelo::VERBOSE_READABLE | Yuelo::VERBOSE_COMMENTS
   * @endcode
   * @{
   */

  /**
   * No messages, default
   */
  const VERBOSE_NO = 0;

  /**
   * Create human readable file names
   *
   * This mostly for debugging
   */
  const VERBOSE_READABLE = 1;

  /**
   * Hold and don't remove HTML comments
   *
   * Template comments are removed always!
   */
  const VERBOSE_COMMENTS = 2;

  /**
   * Mark missing variables
   */
  const VERBOSE_MARKMISSING = 4;

  /**
   * Add debugging infos to HTML code
   *
   * - Show search trace for template in HTML output
   * - Mark begin/end of template in HTML output
   */
  const VERBOSE_TRACE = 8;

  /**
   * All infos about template, up to 16 bits ;-)
   */
  const VERBOSE_DEBUG = -1;
  /** @} */
  /** @} */

  /**
   * The compiler marks cachable content with this tag for Yuelo_Cache
   */
  const CACHETAG = '<!-- .:° YUELO CACHABLE °:. -->';

  /**
   * @name Manipulate and retrieve settings
   * @{
   */

  /**
   * Set a configuration value
   *
   * If $var is an array and $value is not set, assume $var will be an array of settings
   *
   * @param string|array $var Single variable or array of variable => value
   * @param mixed $value
   * @return void
   */
  public static function set( $var, $value=NULL ) {
    if (is_array($var))
      foreach ($var as $k=>$v) self::set($k, $v);
    else
      self::$Settings[strtolower($var)] = $value;
  }

  /**
   * Get a configuration value
   *
   * @param string $var
   * @return mixed
   */
  public static function get( $var ) {
    $var = strtolower($var);
    return (isset(self::$Settings[$var])) ? self::$Settings[$var] : NULL;
  }
  /** @} */

  /**
   * Remove compiled PHP files
   *
   * @return void
   */
  public static function ClearCache() {
    foreach (glob(self::$Settings['compiledir'].'/*.php') as $file) unlink($file);
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   * Library settings
   */
  private static $Settings = array(

    /** ******************************************************************* **/
    /**                   Predefines always in lower case!                  **/
    /** ******************************************************************* **/

    //-------------------------------------------------------------------------
    // Template engine
    //-------------------------------------------------------------------------
    'autoload'   => FALSE,
    'compiledir' => '/tmp',
    'reusecode'  => TRUE,
    'verbose'    => 0,

    //-------------------------------------------------------------------------
    // Layout settings
    //-------------------------------------------------------------------------
    'language'        => '',
    'defaultlanguage' => 'en',
    'layout'          => '',
    'defaultlayout'   => 'default',
    'customlayout'    => '',

    //-------------------------------------------------------------------------
    // Extensions and filters
    //-------------------------------------------------------------------------
    'dateformat'     => 'Y-m-d',
    'datetimeformat' => 'Y-m-d H:i:s',
    'timeformat'     => 'H:i:s',

    //-------------------------------------------------------------------------
    // Template compiler
    //-------------------------------------------------------------------------
    'varnamesuppercase' => FALSE,
    'varregexexternal'  => '[a-zA-Z0-9_.]+',
    'missingvarstyle'   => 'font-weight:bold;color:red;padding:2px',

    // Save available constants and variables in templates during development
    '_saveconstantsandvariables' => FALSE,
  );

}
