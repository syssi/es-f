<?php
/**
 * Class Cache
 *
 * The following settings are supported:
 * - token    : used to build unique cache ids (optional)
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-77-gc4bf735 2011-02-13 21:51:53 +0100 $
 */
class Cache_Session extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public function isAvailable() {
    if (function_exists('session_start')) {
      session_start();
      return TRUE;
    }
  }

  public function set( $id, $data, $ttl=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      $_SESSION[$this->token][$this->id($id)] = $this->serialize(array($this->ts, $ttl, $data));
      return TRUE;
    } elseif ($id !== NULL) { // AND $data === NULL
      return $this->delete($id);
    } else { // $id === NULL AND $data === NULL
      return $this->clear();
    }
  } // function set()

  public function get( $id, $expire=0 ) {
    $id = $this->id($id);
    if (!isset($_SESSION[$this->token][$id])) return;
    $cached = $this->unserialize($_SESSION[$this->token][$id]);
    list($ts, $ttl, $data) = $cached;
    // Data valid?
    if ($this->valid($ts, $ttl, $expire)) return $data;
    // else drop data for this key
    $this->delete($id);
  } // function get()

  public function delete( $id ) {
    $id = $this->id($id);
    if (isset($_SESSION[$this->token][$id])) unset($_SESSION[$this->token][$id]);
  } // function delete()

  public function flush() {
    unset($_SESSION[$this->token]);
  } // function flush()
  /** @} */

}