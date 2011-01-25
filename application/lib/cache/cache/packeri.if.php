<?php
/**
 * Interface for Packer classes
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id$
*/
interface Cache_PackerI {

  /**
   * Pack function
   *
   * @param $data mixed
   * @return string
   */
  public function pack( &$data );

  /**
   * Unpack function
   *
   * @param $data string
   * @return mixed
   */
  public function unpack( &$data );

}
