<?php
/**
 *
 */

/**
 * Abstract class for Extension installation
 */
abstract class esf_Install {

  /**
   * @var array
   */
  public $Messages = array();

  /**
   * force the redirect to Info page after activation
   * @var bool
   */
  public $ForceInfo = FALSE;

  /**
   * Class constructor
   */
  public function __construct() {}

  /**
   * Setup message
   *
   * @return string
   */
  public function SetupInfo() {
    return '';
  }

  /**
   * Module/Plugin info
   *
   * @return string
   */
  public function Info() {
    return '';
  }

  /**
   * Module/Plugin installation
   *
   * @return boolean Return TRUE in case of error
   */
  public function Install() {
    return FALSE;
  }

  /**
   *
   */
  public function InstallFinished() {}

  /**
   * Module/Plugin deinstallation
   *
   * @return boolean Return TRUE in case of error
   */
  public function Deinstall() {
    return FALSE;
  }

  /**
   *
   */
  public function DeinstallFinished() {}

  /**
   * Module/Plugin installation
   *
   * @return boolean Return TRUE in case of error
   */
  public function Enable() {
    return FALSE;
  }

  /**
   *
   */
  public function EnableFinished() {}

  /**
   * Module/Plugin deinstallation
   *
   * @return boolean Return TRUE in case of error
   */
  public function Disable() {
    return FALSE;
  }

  /**
   *
   */
  public function DisableFinished() {}

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Create directory
   */
  protected function CreateDirectory( $dir, $chmod=0755 ) {
    $this->DeterminePath($dir);
    $msg = Translation::get('Backend.CreateDirectory', $dir).' ... ';
    $cmd = sprintf('mkdir -p "%s"', $dir);
    if ($ok = $this->Exec($cmd, $msg, TRUE)) {
      $cmd = sprintf('chmod %o "%s"', $chmod, $dir);
      $this->Exec($cmd);
    } else {
      $this->Message(Translation::get('Backend.CantMakeDirectory', $dir), Messages::ERROR);
    }
    return !$ok;
  }

  /**
   * InstallCreateFile
   */
  protected function CreateFile( $file, $content='', $chmod=0644 ) {
    $this->DeterminePath($file);
    $path = dirname($file);

    $ok = is_writable($path) ? TRUE : $this->CreateDirectory($path);

    if ($ok) {
      $msg = Translation::get('Backend.CreateFile', $file).' ... ';
      if ($ok = (File::write($file,$content) !== FALSE)) {
        $msg .= Translation::get('Backend.Done').'.';
        $this->Message($msg);
        $cmd = sprintf('chmod %o "%s"', $chmod, $file);
        $this->Exec($cmd);
      } else {
        $msg .= Translation::get('Backend.Failed').'.';
        $this->Exec($cmd, Messages::ERROR);
        $msg = Translation::get('Backend.CantMakeFile', $file);
        $this->Message($msg, Messages::ERROR);
      }
    }
    return !$ok;
  }

  /**
   * InstallExtractArchive
   */
  protected function ExtractArchive( $file, $dest='' ) {
    $wd = $this->getWorkDir();
    $path = $dest;
    $this->DeterminePath($path);

    $rc = is_writable($path) ? TRUE : $this->CreateDirectory($path);

    if (is_writable($path)) {
      $file = $wd.'/'.$file;
      $msg = Translation::get('Backend.ExtractArchive', $file).' ... ';
      if (file_exists($file)) {
        require_once LIBDIR.'/dZip/dUnzip2.inc.php';
        $zip = new dUnzip2($file);
        $zip->unzipAll($path, '', TRUE, 0755);
        $zip->close();
        $msg .= Translation::get('Backend.Done').'.';
        $this->Message($msg);
      } else {
        $rc = FALSE;
        $msg .= Translation::get('Backend.Failed').'.';
        $this->Message($msg, Messages::ERROR);
        $msg = Translation::get('Backend.MissingFile', $file);
        $this->Message($msg, Messages::ERROR);
      }
    } else {
      $rc = FALSE;
      $msg = Translation::get('Backend.CantMakeDirectory', $path);
      $this->Message($msg, Messages::ERROR);
      $msg = Translation::get('Backend.MakeDirectoryWritable', $wd);
      $this->Message($msg);
      $rc = 1;
    }
    return $rc;
  }

  /**
   * InstallRemoveDirectory
   */
  protected function RemoveDirectory( $dir='' ) {
    $this->DeterminePath($dir);
    $msg = Translation::get('Backend.RemoveDirectory', $dir).' ... ';
    $cmd = sprintf('rm -rf "%s"',$dir);
    return !$this->Exec($cmd, $msg);
  }

  /**
   * InstallRemoveFile
   */
  protected function RemoveFile( $file ) {
    $this->DeterminePath($file);
    $msg = Translation::get('Backend.RemoveFile', $file).' ... ';
    $cmd = sprintf('rm -f "%s"',$file);
    return !$this->Exec($cmd, $msg);
  }

  /**
   * DeterminePath
   */
  protected function DeterminePath( &$path ) {
    if (substr($path,0,1) != '/') {
      $path = sprintf('%s/local/%s/%s', dirname($_SERVER['SCRIPT_FILENAME']), $this->getWorkDir(TRUE), $path);
    }
  }

  /**
   *
   */
  protected function Message( $msg, $type=Messages::INFO, $formated=FALSE ) {
    $this->Messages[] = array($msg, $type, $formated);
  }

  /**
   * $this->Exec
   */
  protected function Exec( $cmd, $msg=FALSE, $talk=FALSE ) {
    if (Exec::getInstance()->Execute($cmd, $res)) {
      if ($msg) {
        $this->Message($msg.Translation::get('Backend.Failed').'.', Messages::ERROR);
      }
      if ($talk) {
        $msg = $res;
        $this->Message($msg, Messages::ERROR);
      }
      return FALSE;
    } else {
      if ($msg) {
        $this->Message($msg.Translation::get('Backend.Done').'.');
      }
      return TRUE;
    }
  }

  /**
   * $this->getWorkDir
   */
  function getWorkDir( $shift=0 ) {
    // analyse call stack to automatic determine scope
    $bt = debug_backtrace();
    // remove calling function
    array_shift($bt);
    // remove another calling function
    while ($shift > 0) {
      $shift = $shift - 1;
      array_shift($bt);
    }
    $bt = dirname($bt[0]['file']);
    $bt = explode('/',$bt);
    $bt = array_reverse($bt);
    return $bt[1].'/'.$bt[0];
  }

}
