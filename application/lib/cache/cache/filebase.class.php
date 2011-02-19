<?php
/**
 * Class Cache_File
 *
 * Store all data into one file
 * All data will be held in memeory during the script runs
 *
 * The following settings are supported:
 * - @c token    : used to build unique cache ids (optional)
 * - @c cachedir : Where to store the file with the cached data (optional)
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.1.0
 * @version    $Id: v2.4.1-77-gc4bf735 2011-02-13 21:51:53 +0100 $
 *
 * @changelog
 * - v1.1.0
 *   - NEW: Add locking
 */
abstract class Cache_FileBase extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  public function isAvailable() {
    return is_writable($this->cachedir);
  }

  public function info() {
    $info = parent::info();
    $info['cachedir'] = $this->cachedir;
    return $info;
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Caching directory
   *
   * @var string $cachedir
   */
  protected $cachedir;

  /**
   * The following additional settings are supported:
   * - @c cachedir : Store cache file here (optional)
   *
   * If no @c cachedir is provided the follwing directories are checked for
   * avaibility:
   * - upload_tmp_dir
   * - system temp. dir
   *
   * @copydoc Cache::__construct()
   */
  protected function __construct( $settings=array() ) {
    parent::__construct($settings);

    // Determine cache directory
    // 1st from settings
    if (!empty($settings['cachedir']))
      $this->cachedir = $settings['cachedir'];
    // 2nd use upload temp. directory
    if (empty($this->cachedir) OR !is_writable($this->cachedir))
      $this->cachedir = ini_get('upload_tmp_dir');
    // 3rd use system temp. directory
    if (empty($this->cachedir) OR !is_writable($this->cachedir))
      $this->cachedir = sys_get_temp_dir();
  }

  /**
   * Bulid cache file name
   *
   * @param string $id
   * @return string
   */
  protected function FileName( $id='' ) {
    if ($id) $id = '.' . $this->id($id);
    return $this->cachedir . DIRECTORY_SEPARATOR . $this->token . $id . '.cache';
  } // function FileName()

  /**
   * Read data from cache file
   *
   * @param string $file
   * @return string
   */
  protected function ReadFile( $file ) {
    // php.net suggested 'rb' to make it work under Windows
    if (!file_exists($file) OR !$fh = @fopen($file, 'rb')) return;
    // Get a shared lock
    @flock($fh, LOCK_SH);
    $data = '';
    // Be gentle, so read in 4k blocks
    while ($tmp = @fread($fh, 4096)) $data .= $tmp;
    // Release lock
    @flock($fh, LOCK_UN);
    @fclose($fh);
    // Return
    return $this->unserialize($data);
  } // function ReadFile()

  /**
   * Write data to cache file
   *
   * @param string $file
   * @param string $data
   * @return bool
   */
  protected function WriteFile( $file, $data ) {
    if (empty($data) AND $this->RemoveFile($file)) return TRUE;
    $ok = FALSE;
    $data = $this->serialize($data);

    // Lock file, ignore warnings as we might be creating this file
    if (file_exists($file) AND $fh = @fopen($file, 'rb')) @flock($fh, LOCK_EX);

    // php.net suggested 'wb' to make it work under Windows
    if ($fh = @fopen($file, 'wb')) {
      @flock($fh, LOCK_EX);
      @fwrite($fh, $data, strlen($data));
      $ok = TRUE;
      // Release lock
      @flock($fh, LOCK_UN);
      @fclose($fh);
    }
    // Return
    return $ok;
  } // function WriteFile()

  /**
   * Delete cache file
   *
   * @param string $file
   * @return bool
   */
  protected function RemoveFile( $file ) {
    return (file_exists($file) AND unlink($file));
  } // function RemoveFile()

}

// prior PHP 5.2.1
if (!function_exists('sys_get_temp_dir')) {
function sys_get_temp_dir() {
  if ($temp = getenv('TMP'))     return $temp;
  if ($temp = getenv('TEMP'))    return $temp;
  if ($temp = getenv('TMPDIR'))  return $temp;
  $temp = tempnam(__FILE__, '');
  if (file_exists($temp)) {
    unlink($temp);
    return dirname($temp);
  }
  return null;
}
}
