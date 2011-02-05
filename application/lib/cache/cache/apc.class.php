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
 * @version    $Id$
 */
class Cache_APC extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public static function available() {
    return extension_loaded('apc');
  }

  public function set( $id, $data, $ttl=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      return apc_store($this->id($id), $this->serialize(array($this->ts, $ttl, $data)));
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

  /**
   *
   * @return array
   */
  public function info() {
    return apc_sma_info();
  } // function info()

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  protected function __construct( $settings=array() ) {
    if (!self::available())
      throw new CacheException(__CLASS__.': Extension APC not loaded.', 9);
    parent::__construct($settings);
  }

}