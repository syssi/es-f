<?php
/** @defgroup Module-Refresh Module Refresh

*/

/**
 * Module Refresh
 *
 * @ingroup    Module
 * @ingroup    Module-Refresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Module_Refresh extends esf_Module {

  /**
   *
   */
  public function IndexAction() {
    // set marker for plugin
    if (!Session::get('Module.Refresh.Items')) {
      // all auctions
      esf_Auctions::Load();
      Session::set('Module.Refresh.Items', array_keys(esf_Auctions::$Auctions));
      Event::ProcessInform('setLastUpdate');
    }

    // redirect to last module
    $lastmodule = Session::get('Module.Refresh.Module');
    $this->redirect($lastmodule ? $lastmodule : STARTMODULE);
  }

}