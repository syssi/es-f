<?php
/** @defgroup Module-BulkAdd Bulk auction add module

*/

/**
 * Module Bulk auction add
 *
 * @ingroup    Module-BulkAdd
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Module_BulkAdd extends esf_Module {

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index');
  }

  /**
   * Handles index action
   */
  public function IndexAction() {
    // Correct form posted? (and NOT e.g. layoutswitch)
    if ($this->isPost() AND isset($this->Request['bulkadd_x'])) {

      $auctions = trim(@$this->Request['auctions']);
      if ($_FILES['auctions']['error'] == 0)
        $auctions .= ' ' . trim(@file_get_contents($_FILES['auctions']['tmp_name']));

      $auctions = trim($auctions);
      // add auctions using auction modules functionalities
      if (!empty($auctions)) $this->redirect('auction', 'add', $this->Request);
    }
    Tpldata::set('CATEGORIES', esf_Auctions::getCategories());
    Tpldata::set('GROUPS', esf_Auctions::getGroups());
  }

}