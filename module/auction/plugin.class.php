<?php
/**
 * 
 *
 * @category   Plugin
 * @package    Plugin-Auction
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_Module_Auction extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('ProcessStart', 'BuildMenu', 'OutputContent');
  }

  /**
   * NOT YET HANDELD
   */
  public function PageStart() {
    if (PluginEnabled('Validate')) DefineValidator('item', 'integer');
  }

  /**
   *
   */
  public function ProcessStart() {
    if (!esf_User::isValid() OR !Request::check('auction')) return;

    $auctions = array();
    foreach (esf_Auctions::$Auctions as $item => $auction) {
      // find ended auction without ended state
      if ($auction['endts'] AND !$auction['ended'] AND
          $auction['endts']-$_SERVER['REQUEST_TIME'] < 0) {
        $auctions[] = $item;
      }
    }
    // put into refresh stack on content output
    if (count($auctions)) Session::set('Module.Refresh.Items', $auctions);
  }

  /**
   *
   */
  public function BuildMenu() {
    if (!esf_User::isValid()) return;
    esf_Menu::addMain( array( 'module' => 'auction' ) );
  }

  /**
   *
   */
  public function OutputContent() {
    if (Request::check('auction') AND esf_User::isValid())
      esf_Auctions::UpgradeAuctions();
  }

}

Event::attach(new esf_Plugin_Module_Auction);