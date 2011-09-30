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
    esf_Auctions::Load();

    if ($auctions = Session::takeout('Module.Refresh.Items')) {
      // check auction existence
      foreach ($auctions as $id=>$item) {
        if (!isset(esf_Auctions::$Auctions[$item])) {
          unset($auctions[$id]);
        }
      }
    } else {
      // all auctions
      $auctions = array_keys(esf_Auctions::$Auctions);
    }

    if (!empty($auctions)) {
      // refresh...
      $r = 0;
      foreach ($auctions as $item) {
        $auction = esf_Auctions::get($item);
        if (!$auction['ended'] AND
            $auction = esf_Auctions::fetchAuction($auction, $this->FullRefresh)) {
          esf_Auctions::set($auction, FALSE);
          esf_Auctions::Save($auction, FALSE);
          $r++;
        }
      }
      Event::ProcessInform('setLastUpdate');
      Messages::Success(Translation::get('Refresh.Done', $r, count($auctions)-$r));
    }

    // redirect to last module, mostly "auction"
    $lastmodule = Session::get('Module.Refresh.Module');
    $this->redirect($lastmodule ? $lastmodule : STARTMODULE);
  }

}