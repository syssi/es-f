<?php
/**
 * Rewrite urls
 *
 * @category   Plugin
 * @package    Plugin-Analyse
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */
class esf_Plugin_Module_Analyse extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu');
  }

  /**
   *
   */
  public function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    // require valid login
    if (!esf_User::isValid() OR !Request::check('auction')) return;

    // link as sub-item to auctions
    esf_Menu::addModule( array( 'module' => 'analyse', 'id' => 10 ));

    ModuleRequireModule( 'Analyse', 'Auction', '0.4.0' );

    Event::dettach($this);
  }
}

Event::attach(new esf_Plugin_Module_Analyse);