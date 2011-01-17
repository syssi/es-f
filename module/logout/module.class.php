<?php
/**
 * Logout module
 *
 * @ingroup    Module-Logout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
 */
class esf_Module_Logout extends esf_Module {

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
    Cookie::set('ttl');
    Session::setP('ClearCache', TRUE);
    // logout user and restart session
    Core::StartSession(TRUE);
    Messages::Success(Translation::get('Logout.Success'));
    // redirect to default start page/module
    $this->redirect(STARTMODULE);
  }

}