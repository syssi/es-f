<?php
/**
 * Category jump link
 *
 * @category   Plugin
 * @package    Plugin-CategoryJump
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_CategoryJump extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputFilterContent');
  }

  /**
   * Manipulate HTML content before output
   *
   * @param array &$output
   * @global array
   */
  public function OutputFilterContent( &$output ) {
    if (!Request::check('auction', 'index') OR !esf_User::isValid()) return;

    $c = esf_Auctions::getCategories(TRUE);
    sort($c);

    if (count($c) >= $this->Count) {
      $data['Category'] = $c;
      $data['DropDown'] = $this->DropDown;
      $output = $this->Render('content', $data) . $output;
    }
  }

}

Event::attach(new esf_Plugin_CategoryJump);