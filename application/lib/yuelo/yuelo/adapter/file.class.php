<?php
/**
 * Adapter class which loads the templates from file system
 *
 * This adapter use the follwing extra settings:
 * - @c RootDir : root directory to search templates from, can also be an array of directories
 * - @c TemplateExt : Default template extension
 *
 * @ingroup  Core Adapter
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Adapter_File extends Yuelo_Adapter {

  /**
   * Class constructor
   *
   * @param array $settings Adapter settings
   */
  protected function __construct( $settings=array() ) {
    $this->RootDir = '.';
    $this->TemplateExt = '.htm';
    parent::__construct($settings);
  }

  /**
   * @name Implemented abstract methods
   * @{
   */

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

    if (!isset($this->TemplateFiles[$TplHash]['F'])) {
      $TemplateDirs = array();

      // Custom layouts
      $c = Yuelo::get('CustomLayout');
      if ($l = Yuelo::get('Layout')) {
        if ($c) $TemplateDirs[] = $l.'/'.$c;
        $TemplateDirs[] = $l;
      }

      // Layouts
      $l = Yuelo::get('DefaultLayout');
      if ($c) $TemplateDirs[] = $l.'/'.$c;
      $TemplateDirs[] = $l;

      // Search for template
      $TemplateFiles = array();

      // Language dependent, put into var. for speed
      if ($l = Yuelo::get('Language')) {
        $TemplateFiles[] = $TemplateID.'.'.$l;
        if ($l != ($dl=Yuelo::get('DefaultLanguage')))
          $TemplateFiles[] = $TemplateID.'.'.$dl;
      }

      // At least the ID itself...
      $TemplateFiles[] = $TemplateID;

      $trace = Yuelo::get('Verbose') & Yuelo::VERBOSE_TRACE;
      $this->TemplateFiles[$TplHash]['T'] = '';

      foreach ((array)$this->RootDir as $RootDir) {
        foreach ($TemplateDirs as $TplDir) {
          foreach ($TemplateFiles as $TplFile) {
            $file = $RootDir.'/'.$TplDir.'/'.$TplFile.$this->TemplateExt;

            if ($trace)
              $this->TemplateFiles[$TplHash]['T'] .=
                '  - '.str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $file);

            if (is_file($file)) {
              $this->TemplateFiles[$TplHash]['F'] = $file;

              if ($trace) $this->TemplateFiles[$TplHash]['T'] .= ' -> Ok'."\n";
              // out of all foreach
              break 3;
            }

            if ($trace) $this->TemplateFiles[$TplHash]['T'] .= "\n";
          } // foreach
        } // foreach
      } // foreach

      if (!isset($this->TemplateFiles[$TplHash]['F'])) {
        $this->Error = __CLASS__.' - Missing template: '.implode(' | ',(array)$RootDir)
                      .'/('.implode(' | ',$TemplateDirs).')/'.$TemplateID.$this->TemplateExt;
        return FALSE;
      }

      if (!empty($this->TemplateFiles[$TplHash]['T'])) {
        $this->TemplateFiles[$TplHash]['T'] =
          '<!--'."\n".$this->TemplateFiles[$TplHash]['T'].'-->';
      }
    }
    $this->LastTemplateFile = $this->TemplateFiles[$TplHash]['F'];
    return TRUE;
  }

  /**
   * Load template content
   *
   * @param string $TemplateID Interprete here as a part of a file name
   * @return string HTML code of template
   */
  public function LoadTemplate( $TemplateID ) {
    $TplHash = $this->TemplateHash($TemplateID);

    if (!isset($this->TemplateFiles[$TplHash]['F']) AND
        !$this->CheckTemplate($TemplateID)) return FALSE;

    $this->LastTemplateFile = $this->TemplateFiles[$TplHash]['F'];
    $content = str_replace("\r", "\n", file_get_contents($this->LastTemplateFile));
    return  $this->TemplateFiles[$TplHash]['T']."\n".$content;
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
   * @c /path/to/the/templates/template.tpl
   *
   * to
   *
   * @c path_to_the_templates--template
   *
   * if Yuelo::get('Verbose') contains Yuelo::VERBOSE_READABLE
   *
   * @param string $TemplateID Interprete here as a part of a file name
   * @return string Cleaned file name
   */
  public function BuildFilename( $TemplateID ) {
    $TplHash = $this->TemplateHash($TemplateID);
    $TemplateID = $this->TemplateFiles[$TplHash]['F'];
    if (Yuelo::get('Verbose') & Yuelo::VERBOSE_READABLE) {
      // try to shorten the name
      $TemplateID = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $TemplateID);
      // remove extension
      $TemplateID = str_replace($this->TemplateExt, '', $TemplateID);
      // path--file
      $TemplateID = dirname($TemplateID).'--'.basename($TemplateID);
      // tramsform mostly "/" to "_"
      return trim(preg_replace('~[^\w.-]+~', '_', $TemplateID), '_');
    } else {
      return md5($TemplateID);
    }
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
    $Data['$TPLDIR'] = str_replace(dirname($_SERVER['SCRIPT_FILENAME']).'/', '', dirname($this->LastTemplateFile));
    $Data['$IMGDIR'] = $Data['$TPLDIR'].'/'.$this->ImageDir;
  }
  /** @} */

  /**
   * Get last found/used relative template file name
   *
   * @return string Last processed template file name
   */
  public function getLastTemplate() {
    return str_replace(dirname($_SERVER['SCRIPT_FILENAME']).'/', '', $this->LastTemplateFile);
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Get template hash
   *
   * @param string $TemplateID Interprete here as file name
   * @return string Unique MD5 hash over RootDir, settings and $TemplateID
   */
  protected function TemplateHash( $TemplateID ) {
    return md5(serialize($this->RootDir).parent::TemplateHash($TemplateID));
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   * Last template file requested/loaded
   */
  private $LastTemplateFile = '';

  /**
   * Array with template hashes and file names
   */
  private $TemplateFiles = array();

  /**
   * Determine last file change date (if file exists)
   *
   * @param string $filename
   * @return mixed
   */
  private function MTime( $filename ) {
    return @is_file($filename) ? filemtime($filename) : 0;
  }

}