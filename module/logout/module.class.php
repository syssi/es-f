<?php
/**
 * @category   Module
 * @package    Module-Logout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Homepage module
 *
 * @category   Module
 * @package    Module-Logout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Module_Logout extends esf_Module {

  /**
   *
   */
  public function IndexAction() {
    Session::setP('ClearCache', TRUE);
    // logout user and restart session
    Core::StartSession(TRUE);
    Messages::addSuccess(Translation::get('Logout.Success'));
    // redirect to default start page/module
    $this->redirect(Registry::get('StartModule'));
  }

}