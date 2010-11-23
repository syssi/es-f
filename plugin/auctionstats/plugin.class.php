<?php
/**
 * @category   Plugin
 * @package    Plugin-AuctionStats
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */
class esf_Plugin_AuctionStats extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputStart');
  }

  /**
   * Handle OutputStart
   *
   * @access private
   * @param string $output HTML to filter
   * @return void
   */
  public function OutputStart() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    // require valid login
    if (!esf_User::isValid() OR !Request::check('auction')) return;

    // show only if at least one auction available
    if (!$cnt = esf_Auctions::count()) return;

    // prepare for template usage
    $data = array(
      'AuctionCount' => $cnt,
      'GroupCount'   => count(esf_Auctions::$Groups)
    );

    TplData::add('Header_Right', $this->Render('content', $data));
  }

}

Event::attach(new esf_Plugin_AuctionStats);