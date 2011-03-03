<?php
/**
 * Exec for *nix platforms
 *
 * @ingroup    exec
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */

/**
 *
 */
class Exec_Unix extends Exec {

  /**
   * mkdir -p $dir
   *
   * @param string $dir Directory name
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function MkDir( $dir, &$result, $sudo='' ) {
    $this->quote($dir);
    $cmd = 'mkdir -p '.$dir;
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * chmod [-R] $mode $filemask
   *
   * @param string $filemask *nix file mask string
   * @param integer $mode File mode, also octal
   * @param array $rec Change recursive
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function ChMod( $filemask, $mode, $rec, &$result, $sudo='' ) {
    $this->quote($filemask);
    $cmd = sprintf('chmod %s %s %s', ($rec?'-R':''), $mode, $filemask);
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * cp -r $source $dest
   *
   * @param string $source *nix file mask string
   * @param string $dest *nix file mask string
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function Copy( $source, $dest, &$result, $sudo='' ) {
    $this->quote($source);
    $this->quote($dest);
    $cmd = sprintf('cp -r %s %s 2>/dev/null', $source, $dest);
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * mv $source $dest
   *
   * @param string $source *nix file mask string
   * @param string $dest *nix file mask string
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function Move( $source, $dest, &$result, $sudo='' ) {
    $this->quote($source);
    $this->quote($dest);
    $cmd = sprintf('mv %s %s', $source, $dest);
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * rm -f $filemask
   *
   * @param string $filemask *nix file mask string
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function Remove( $filemask, &$result, $sudo='' ) {
    $this->quote($filemask);
    $cmd = sprintf('rm -f %s', $filemask);
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * Execute a system command with some *nix specific settings
   *
   * @param string|array $cmd Command to execute, optional as array with sprintf parameters
   * @param array &$result Return of executed command
   * @param string $sudo Run as defined sudo user
   * @return integer Return code of system command
   */
  public function Execute( $cmd, &$result, $sudo='' ) {
    if (is_array($cmd)) {
      $c = array_shift($cmd);
      $cmd = vsprintf($c, $cmd);
    }
    if (preg_match('~^(.*?)\s*([^2]>+[^|]*)$~', $cmd, $args)) {
      $cmd = escapeshellarg('COLUMNS=500; '.$args[1]).$args[2];
    } else {
      $cmd = escapeshellarg('COLUMNS=500; '.$cmd);
    }
    // quote
    $cmd = str_replace('\\\'','"',$cmd);
    $cmd = $this->Shell.' -c '.$cmd;

    // run as another user?
    if ($sudo) $cmd = sprintf('sudo -u %s %s', $sudo, $cmd);

    // pipe error output to stadard output, if not defined
    if (!strstr($cmd, '2>')) $cmd .= ' 2>&1';

    return $this->_exec($cmd, $result);
  }

  //---------------------------------------------------------------------------
  // PROTECTED
  //---------------------------------------------------------------------------

  /**
   * Assume sh as shell
   *
   * @var string $Shell
   */
  protected $Shell = 'sh';

  //-------------------------------------------------------------------------
  // PRIVATE
  //-------------------------------------------------------------------------

  /**
   * Quote file masks if required
   *
   * @param string &$filemask *nix file mask string
   * @return void
   */
  private function quote( &$filemask ) {
    if (!strstr($filemask, '"')) $filemask = '"' . $filemask . '"';
  }

}