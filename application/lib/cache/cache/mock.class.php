<?php
/**
 * Mockup class with no functionality
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class Cache_Mock extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @name Implemented abstract functions
   * @{
   */
  public function set( $id, $data, $ttl=0 ) {
    return TRUE;
  }

  public function get( $id, $expire=0 ) {
    return '';
  }

  public function delete( $id ) {
    return TRUE;
  }

  public function flush() {
    return TRUE;
  }
  /** @} */

}