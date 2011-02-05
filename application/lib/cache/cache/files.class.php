<?php
/**
 * Cache_Files extends Cache_FileBase
 */
require_once dirname(__FILE__).'/filebase.class.php';

/**
 * Class Cache_Files
 *
 * Store data for each id in a separate file, recommended for large data sets
 * The data are not in memory, they will read each time they are required.
 *
 * The following settings are supported:
 * - @c token    : used to build unique cache ids (optional)
 * - @c cachedir : Where to store the file with the cached data (optional)
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class Cache_Files extends Cache_FileBase {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public function set( $id, $data, $ttl=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      return $this->WriteFile($this->FileName($id), array($ttl, $data));
    } elseif ($id !== NULL) { // AND $data === NULL
      return $this->delete($id);
    } else { // $id === NULL AND $data === NULL
      return $this->clear();
    }
  } // function set()

  public function get( $id, $expire=0 ) {
    // File exists?
    $file = $this->FileName($id);
    if (!$cached = $this->ReadFile($file)) return;
    // split into ttl and data
    list($ttl, $data) = $cached;
    $ts = filemtime($file);
    // Data valid?
    if ($this->valid($ts, $ttl, $expire)) return $data;
    // else drop data for this key
    $this->delete($id);
  } // function get()

  public function delete( $id ) {
    return $this->RemoveFile($this->FileName($id));
  } // function delete()

  public function flush() {
    $ok = TRUE;
    // DOTs are required in file name to not delete cache file from Cache_File
    $files = glob($this->cachedir . DIRECTORY_SEPARATOR . $this->token . '\.*\.cache');
    // force remove and combine with existing result
    foreach ($files as $file) $ok = ($this->RemoveFile($file) AND $ok);
    return $ok;
  } // function flush()
  /** @} */

}
