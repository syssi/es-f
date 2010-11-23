<?php
/**
 * @category   Plugin
 * @package    Plugin-CurrencyDisplay
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Add a link to "More seller items" to seller name
 *
 * @category   Plugin
 * @package    Plugin-CurrencyDisplay
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_CurrencyDisplay extends esf_Plugin {

  /**
   *
   */
  public function __construct() {
    parent::__construct();

    foreach (explode('|', $this->Mapping) as $value) {
      @list($c1, $c2) = explode('=', $value);
      $this->Mappings[$c1] = $c2;
    }
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('DisplayAuction');
  }

  /**
   *
   */
  public function DisplayAuction( &$auction ) {
    if (isset($this->Mappings[$auction['currency']]))
      esf_Auctions::setDisplay($auction, 'currency', $this->Mappings[$auction['currency']]);
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  private $Mappings = array();
}

Event::attach(new esf_Plugin_CurrencyDisplay);