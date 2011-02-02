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
 * @version    $Id$
 */
class Cache_Session extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public function set( $id, $data, $expire=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      $_SESSION[$this->token][$this->id($id)] = $this->serialize(array($expire, $data));
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

    $data = $this->unserialize($_SESSION[$this->token][$id]);

    if ($data[0] === 0 OR // expire never
        $data[0] >= $this->ts) return $data[1];

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

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @param array $settings
   */
  protected function __construct( $settings=array() ) {
    parent::__construct($settings);
    session_start();
  } // function __construct()

}