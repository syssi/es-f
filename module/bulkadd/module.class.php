<?php
/**
 * Bulk auction add module
 *
 * @ingroup    Module-BulkAdd
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
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