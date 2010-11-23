<?php
/**
 * Adapter class which loads the templates from a database
 *
 * This adapter use the follwing extra settings:
 * - @c Host :
 * - @c User :
 * - @c Password :
 * - @c Database :
 * - @c TableName :
 *
 * @ingroup  Core Adapter
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Adapter_PDO extends Yuelo_Adapter {

  /**
   * Class constructor
   */
  public function __construct() {
    // Extension of template files, set ONLY if not defined before
    if (!Yuelo::get('TemplateExt')) Yuelo::set('TemplateExt', '.htm');
    // Images directory under found template directory
    // must not set to '', is default if not found ;-)
    // Yuelo::set('ImageDir', '');
  }

  /**
   * Check for example existence of template, can change name of template
   *
   * If there is an error, return FALSE and set $this->Error
   *
   * @param string $TemplateID Interprete here as a part of a file name
   * @return bool Result of check, TRUE if all ok
   */
  public function CheckTemplate( $TemplateID ) {
    $TplHash = $this->TemplateHash($TemplateID);
  }

  /**
   * Load template content
   *
   * @param string $TemplateID Interprete here as a part of a file name
   * @return string HTML code of template
   */
  public function LoadTemplate( $TemplateID ) {
    $TplHash = $this->TemplateHash($TemplateID);
  }

  /**
   * Format temporary file names
   *
   * Used for
   * - Compiled file
   * - Cached file
   *
   * Transform
   *
   * @c /path/to/the/temlates/template.tpl
   *
   * to
   *
   * @c path_to_the_temlates--template
   *
   * if Yuelo::get('Verbose') contains Yuelo::VERBOSE_READABLE
   *
   * @param string $TemplateID Interprete here as a part of a file name
   * @return string Cleaned file name
   */
  public function BuildFilename( $TemplateID ) {
    $TplHash = $this->TemplateHash($TemplateID);
  }

  /**
   * Get last modified time stamp of template
   *
   * @param string $TemplateID Interprete here as a part of a file name
   * @return int Time stamp
   */
  public function TemplateTimestamp( $TemplateID ) {
    $TplHash = $this->TemplateHash($TemplateID);
    return $this->MTime($this->TemplateFiles[$TplHash]['F']);
  }

  /**
   * Modify template $Data / $Constants before output
   *
   * Here set the following extra data:
   * - @c $TPLDIR Directory where the template resides
   * - @c $IMGDIR Directory where the images resides
   *
   * @param array &$Data
   * @param array &$Constants
   */
  public function PrepareOutputData( &$Data, &$Constants ) {
  }

  /**
   * Get last found/used absolute template file name
   *
   * @return string Last processed template file name
   */
  public function getLastTemplate() {
  }

}