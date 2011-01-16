<?php
/**
 * Logout module
 *
 * @ingroup    Module-Logout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Module_Logout extends esf_Module {

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