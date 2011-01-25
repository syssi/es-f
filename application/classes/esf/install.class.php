<?php
/** @defgroup Install Extension installation


*/

/**
 * Abstract class for Extension installation
 *
 * @ingroup    Install
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
abstract class esf_Install {

  /**
   * @var array $Messages
   */
  public $Messages = array();

  /**
   * Force the redirect to Info page after activation
   *
   * @var bool $ForceInfo
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
   * Runs after an extension installation was successful finished
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
   * Runs after an extension deinstallation was successful finished
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
   * Runs after an extension was enabled successful
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
   * Runs after an extension was disabled successful
   */
  public function DisableFinished() {}

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Create a directory
   *
   * @param string $dir Directory name
   * @param int $chmod Directory access mode
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
   * Create a file
   *
   * @param string $file File name with absolute or relative from local working
   *                     directory .../local/...
   * @param string $content File content
   * @param int $chmod File access mode
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
   * Extract an ZIP archive
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
   * Remove a directory on deinstallation
   */
  protected function RemoveDirectory( $dir='' ) {
    $this->DeterminePath($dir);
    $msg = Translation::get('Backend.RemoveDirectory', $dir).' ... ';
    $cmd = sprintf('rm -rf "%s"',$dir);
    return !$this->Exec($cmd, $msg);
  }

  /**
   * Remove a file on deinstallation
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
   * Collect the installation messages
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
