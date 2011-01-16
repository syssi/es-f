<?php
/**
 * Refresh module
 *
 * @ingroup    Module-Refresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Module_Refresh extends esf_Module {

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index');
  }

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
    $this->redirect($lastmodule ? $lastmodule : STARTMODULE);
  }

}