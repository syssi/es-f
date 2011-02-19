<?php
/**
 * Cache_File extends Cache_FileBase
 */
require_once dirname(__FILE__).'/filebase.class.php';

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
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-77-gc4bf735 2011-02-13 21:51:53 +0100 $
 */
class Cache_File extends Cache_FileBase {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public function isAvailable() {
    if (parent::isAvailable()) {
      // Load cached data
      $this->data = $this->ReadFile($this->FileName());
      if (!is_array($this->data)) $this->data = array();
      return TRUE;
    }
  }

  public function set( $id, $data, $ttl=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      $this->data[$this->id($id)] = array($this->ts, $ttl, $data);
      $this->modified = TRUE;
      return TRUE;
    } elseif ($id !== NULL) { // AND $data === NULL
      return $this->delete($id);
    } else { // $id === NULL AND $data === NULL
      return $this->clear();
    }
  } // function set()

  public function get( $id, $expire=0 ) {
    $id = $this->id($id);
    // Id set?
    if (!isset($this->data[$id])) return;
    // split into store time, ttl, data
    list($ts, $ttl, $data) = $this->data[$id];
    // Data valid?
    if ($this->valid($ts, $ttl, $expire)) return $data;
    // else drop data for this key
    $this->delete($id);
  } // function get()

  public function delete( $id ) {
    $id = $this->id($id);
    if (isset($this->data[$id])) unset($this->data[$id]);
    $this->modified = TRUE;
    return TRUE;
  } // function remove()

  public function flush() {
    $this->data = array();
    // Don't just RemoveFile, if this fails, cache will remain,
    // so write empty data if it fails
    return $this->WriteFile($this->FileName(), NULL);
  } // function clear()

  public function __destruct() {
    // Save only if data was modified
    if ($this->modified)
      $this->WriteFile($this->FileName(), $this->data);
  } // function __destruct()
  /** @} */

  public function info() {
    $info = parent::info();
    $info['filename'] = $this->FileName();
    $info['count'] = count($this->data);
    if (function_exists('memory_get_usage')) {
      $size = memory_get_usage();
      $a = array_merge($this->data);
      $info['size'] = memory_get_usage() - $size;
      unset($a);
    }
    return $info;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Data storage
   *
   * @var array $data
   */
  private $data;

  /**
   * Save whole cache file only if at least one id was changed/deleted
   *
   * @var bool $modified
   */
  private $modified = FALSE;

}
