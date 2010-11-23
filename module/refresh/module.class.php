<?php
/**
 * @category   Module
 * @package    Module-Refresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Refresh module
 *
 * @category   Module
 * @package    Module-Refresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Module_Refresh extends esf_Module {

  /**
   *
   */
  public function IndexAction() {
    // set marker for plugin
    if (!Session::get('Module.Refresh.Items')) {
      // all auctions
      Session::set('Module.Refresh.Items', array_keys(esf_Auctions::$Auctions));
      Event::ProcessInform('setLastUpdate');
    }

    // redirect to last module
    $lastmodule = Session::get('Module.Refresh.Module');
    $this->redirect($lastmodule ? $lastmodule : Registry::get('StartModule'));
  }

}