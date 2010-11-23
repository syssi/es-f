<?php
/**
 * @category   Module
 * @package    Module-BulkAdd
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Bulk auction add module
 *
 * @category   Module
 * @package    Module-BulkAdd
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Module_BulkAdd extends esf_Module {

  /**
   *
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