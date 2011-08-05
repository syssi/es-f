<?php
/**
 * Include textile parser
 */
require_once dirname(__FILE__).'/textile/textile5.class.php';

/**
 * Transform message with textile parser
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 */
class Translation_Filter_Textile implements Translation_Filter {

  /**
   * Class constructor
   */
  public function __construct() {
    $this->textile = new Textile5;
  }

  /**
   * Transform message with textile parser
   *
   * @param string &$message  The message to modify
   * @param string $namespace The namespace the message comes from
   * @param string $id        The ID which the message stands for
   */
  public function process( &$message, $namespace, $id ) {
    $message = $this->textile->TextileThis($message);
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Tetile instance
   * @var instance $texttile
   */
  protected $textile;

}