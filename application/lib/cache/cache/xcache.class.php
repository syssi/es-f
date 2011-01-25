<?php
/**
 * Cache class using APC opcode cache
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
   * Class constructor
   *
   * @throws CacheException
   * @param array $settings
   */
  public function __construct( $settings=array() ) {
    if (!self::available())
      throw new CacheException(__CLASS__.': Extension XCache not loaded.', 9);
    parent::__construct($settings);
  }

  /**
   *
   */
  public static function available() {
    return extension_loaded('xcache');
  }

  /**
   * Function set...
   *
   * @param string $id
   * @param mixed $data
   * @param $ttl int Time to live or timestamp
   *                 0  - expire never
   *                 >0 - Time to live
   *                 <0 - Timestamp of expiration
   * @return bool
   */
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

  /**
   * Function get...
   *
   * @param string $id
   * @param $expire int Time to live or timestamp
   *                    0  - expire never
   *                    >0 - Time to live
   *                    <0 - Timestamp of expiration
   * @return mixed
   */
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

  /**
   * Function delete...
   *
   * @param string $id
   * @return bool
   */
  public function delete( $id ) {
    return xcache_unset($this->id($id));
  }

  /**
   * Function flush...
   *
   * @return bool
   */
  public function flush() {
    return xcache_clear_cache();
  }

}
