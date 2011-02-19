<?php
/**
 * Mockup class with no functionality
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-77-gc4bf735 2011-02-13 21:51:53 +0100 $
 */
class Cache_Mock extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public function isAvailable() {
    return TRUE;
  }

  public function set( $id, $data, $ttl=0 ) {
    return TRUE;
  }

  public function get( $id, $expire=0 ) {
    return NULL;
  }

  public function delete( $id ) {
    return TRUE;
  }

  public function flush() {
    return TRUE;
  }
  /** @} */

}