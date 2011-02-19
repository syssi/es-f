<?php

require_once dirname(__FILE__).'/../packeri.if.php';

/**
 * Packer class for cached data
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.1.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class Cache_Packer_GZ implements Cache_PackerI {

  /**
   *
   */
  public function __construct( $level=5 ) {
    $this->active = extension_loaded('zlib');
    $this->level = in_array($level, range(1,9)) ? $level : 5;
  }

  /**
   * Pack function
   *
   * @param &$data mixed
   * @return string
   */
  public function pack( &$data ) {
    if ($this->active) $data = base64_encode(gzcompress(serialize($data), $this->level));
  }

  /**
   * Unpack function
   *
   * @param &$data string
   * @return mixed
   */
  public function unpack( &$data ) {
    if ($this->active) $data = unserialize(gzuncompress(base64_decode($data)));
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   *
   */
  protected $active;

  /**
   *
   */
  protected $level;

}
