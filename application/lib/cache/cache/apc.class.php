<?php
/**
 * Cache class using APC opcode cache
 *
 * For more information see http://www.php.net/manual/book.apc.php
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-77-gc4bf735 2011-02-13 21:51:53 +0100 $
 */
class Cache_APC extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public function isAvailable() {
    return extension_loaded('apc');
  }

  public function set( $id, $data, $ttl=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      return apc_store($this->id($id), $this->serialize(array($this->ts, $ttl, $data)), 0);
    } elseif ($id !== NULL) { // AND $data === NULL
      return $this->delete($id);
    } else { // $id === NULL AND $data === NULL
      return $this->clear();
    }
  }

  public function get( $id, $expire=0 ) {
    if (!$cached = $this->unserialize(apc_fetch($this->id($id)))) return;
    // split into store time, ttl, data
    list($ts, $ttl, $data) = $cached;
    // Data valid?
    if ($this->valid($ts, $ttl, $expire)) return $data;
    // else drop data for this key
    $this->delete($id);
  }

  public function delete( $id ) {
    return apc_delete($this->id($id));
  }

  public function flush() {
    return apc_clear_cache();
  }
  /** @} */

  /**
   * @name Overloaded functions
   * Use APC own functions
   * @{
   */
  public function inc( $id, $step=1 ) {
    return apc_inc($this->id($id), $step);
  } // function inc()

  public function dec( $id, $step=1 ) {
    return apc_dec($this->id($id), $step);
  } // function dec()
  /** @} */

  public function info() {
    return array_merge(parent::info(), apc_sma_info());
  } // function info()

}