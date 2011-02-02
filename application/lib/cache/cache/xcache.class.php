<?php
/**
 * Cache class using XCache opcode cache
 *
 * The following settings are supported:
 * - token    : used to build unique cache ids (optional)
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id$
 */
class Cache_XCache extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public static function available() {
    return extension_loaded('xcache');
  }

  public function set( $id, $data, $ttl=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      return xcache_set($this->id($id), $this->serialize(array($this->ts, $ttl, $data)));
    } elseif ($id !== NULL) { // AND $data === NULL
      return $this->delete($id);
    } else { // $id === NULL AND $data === NULL
      return $this->clear();
    }
  }

  public function get( $id, $expire=0 ) {
    if (!$cached = $this->unserialize(xcache_get($this->id($id)))) return;
    // split into store time, ttl, data
    list($ts, $ttl, $data) = $cached;
    // Data valid?
    if (isset($expire)) {
      // expiration timestamp set
      if ($expire === 0 OR
          $expire > 0 AND $this->ts+$expire >= $ts+$ttl OR
          $expire < 0 AND $ts >= -$expire) return $data;
    } else {
      // expiration timestamp NOT set
      if ($ttl === 0 OR
          $ttl > 0 AND $ts+$ttl >= $this->ts OR
          $ttl < 0 AND -$ttl >= $this->ts) return $data;
    }
    // else drop data for this key
    $this->delete($id);
  }

  public function delete( $id ) {
    return xcache_unset($this->id($id));
  }

  public function flush() {
    return xcache_clear_cache();
  }
  /** @} */

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @throws CacheException
   * @param array $settings
   */
  protected function __construct( $settings=array() ) {
    if (!self::available())
      throw new CacheException(__CLASS__.': Extension XCache not loaded.', 9);
    parent::__construct($settings);
  }

}