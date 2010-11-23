<?php
/**
@defgroup Adapter Adapter classes

Adapters builds them connection between the template object and the places,
where the templates resists, for example in file system (Yuelo_Adapter_File) or
database (Yuelo_Adapter_PDO, not yet implemented).

The have to
@li Find the template code
@li Load the template code
@li Check for changes (recompile?)
@li Build a file name for compiled PHP code and cached content

*/

// --------------------------------------------------------------------------

/**
 * Adapter base class
 *
 * Defines methods for template handling
 *
 * @ingroup  Core Adapter
 * @version  2.1.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
abstract class Yuelo_Adapter {

  // -------------------------------------------------------------------------
  // ABSTRACT
  // -------------------------------------------------------------------------

  /**
   * @name Abstract methods
   * Must be implemented by concrete adapters.
   * @{
   */

  /**
   * Check for example existence of template
   *
   * If there is an error, return FALSE and set $this->Error
   *
   * @param string $TemplateID
   * @return bool Result of check, TRUE if all ok
   */
  abstract function CheckTemplate( $TemplateID );

  /**
   * Load template content
   *
   * @param string $TemplateID
   * @return string HTML code of template
   */
  abstract function LoadTemplate( $TemplateID );

  /**
   * Build file names for compiled templates and chached files
   *
   * @param string $TemplateID
   * @return string File name
   */
  abstract function BuildFilename( $TemplateID );

  /**
   * Get last modified time stamp of template
   *
   * @param string $TemplateID
   * @return int Time stamp
   */
  abstract function TemplateTimestamp( $TemplateID );

  /**
   * Modify template $Data / $Constants before output
   *
   * @param array &$Data
   * @param array &$Constants
   */
  abstract function PrepareOutputData( &$Data, &$Constants );
  /** @} */

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Last error message
   */
  public $Error = '';

  /**
   * Singleton handler
   *
   * Can handle multiple singletons, one for each $adapter
   *
   * @param string $adapter Adapter name
   * @param array $settings Adapter settings
   */
  public static function getInstance( $adapter, $settings=array() ) {
    $adapter = strtolower($adapter);
    if (!isset(self::$Instances[$adapter])) {
      $file = YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'adapter' .
              DIRECTORY_SEPARATOR . $adapter . '.class.php';
      if (file_exists($file)) {
        require_once $file;
        $adapter = 'Yuelo_Adapter_' . $adapter;
        self::$Instances[$adapter] = new $adapter($settings);
      } else {
        throw new Yuelo_Exception('Class Yuelo_Adapter_'.$adapter.' not found, missing '.$file);
      }
    }
    return self::$Instances[$adapter];
  }

  /**
   * @name Manipulate and retrieve settings
   * @{
   */

  /**
   * Set a configuration value
   *
   * @param string|array $var Single variable or array of variable => value
   * @param mixed $value
   * @return void
   */
  public function __set( $var, $value ) {
    $this->Settings[strtolower($var)] = $value;
  }

  /**
   * Get a configuration value
   *
   * @param string $var
   * @return mixed
   */
  public function __get( $var ) {
    $var = strtolower($var);
    return (isset($this->Settings[$var])) ? $this->Settings[$var] : NULL;
  }
  /** @} */

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @param array $settings Adapter settings
   */
  protected function __construct( $settings=array() ) {
    $this->ImageDir = 'images';
    foreach ($settings as $var=>$value) $this->$var = $value;
  }

  /**
   * Get template hash
   *
   * @param string $TemplateID
   * @return string Unique MD5 hash over settings and $TemplateID
   */
  protected function TemplateHash( $TemplateID ) {
    return md5(Yuelo::get('DefaultLayout').Yuelo::get('Layout').Yuelo::get('CustomLayout')
              .Yuelo::get('DefaultLanguage').Yuelo::get('Language')
              .$TemplateID);
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Singleton array, one instance for each adapter type
   */
  private static $Instances = array();

  /**
   * Adapter settings
   */
  private $Settings = array();

}