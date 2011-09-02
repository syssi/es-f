<?php
/**
 * Auction plugin
 *
 * @ingroup    Module-Auction
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Module_Auction extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'Start', 'ProcessStart', 'BuildMenu', 'OutputContent');
  }

  /**
   *
   */
  public function Start() {
    if (esf_User::isValid()) esf_Auctions::Load();
  }

  /**
   *
   */
  public function ProcessStart() {
    if (PluginEnabled('Validate')) DefineValidator('item', 'integer');

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