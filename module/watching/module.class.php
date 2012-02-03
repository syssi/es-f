<?php
/** @defgroup Module-Analyse Module Analyse

*/

/**
 * Module Watching
 *
 * @ingroup    Module
 * @ingroup    Module-Watching
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Module_Watching extends esf_Module {

  /**
   *
   */
  public function IndexAction() {

    esf_Auctions::writeEsniperCfg(TRUE);
    $cmd = array('Watching::WatchedItems',
                 esf_User::UserDir(), Registry::get('cfg_esniper'));
    $rc = Exec::getInstance()->ExecuteCmd($cmd, $res);
    $cmd = Exec::getInstance()->LastCmd;
    esf_Auctions::removeEsniperCfg();

    /// _dbg($res);

    $myitems = array();
    $item = FALSE;

    foreach ($res as $line) {
      // skip empty lines
      if (empty($line)) continue;

      if (stristr($line, 'Login failed')) {
        Messages::Error($res);
        break;
      }

      // Description can contain a ":"...
      list($key, $val) = explode(':', $line, 2);
      $key = str_replace(' ', '_', trim($key));
      $val = trim($val);

      if (strtolower($key) == 'itemnr') {
        $item = $val;
        $myitems[$item]['ITEM'] = $item;
      } else {
        if (!$item) {
          // somthing wrong, possible data line without leading "ItemNr" line...
          // $myitems is still empty
          break;
        }
        $myitems[$item][strtoupper($key)] = $val;
      }
    }

    if (empty($myitems)) {
      if (!empty($res)) {
        $res = array_merge(array('$ '.$cmd, ''), $res);

        $subject = ESF_LONG_TITLE.': Watched items log';
        $body = ESF_FULL_TITLE . "\n"
              . 'Module version: ' . $this->Version . "\n"
              . Session::get('esniperVersion') . "\n\n"
              . implode("\n", $res);
        $header = array('subject' => $subject, 'body' => $body);

        TplData::set('EMAIL', Core::Email($this->Email, $this->Author, TRUE, $header));
        TplData::set('RESULT', $res);
      }
      $this->forward('error');
      return;
    }

    $auctionIds = array_keys(esf_Auctions::$Auctions);
    foreach ($myitems as $item => $data) {
      if (strtolower($data['TIME_LEFT']) != 'ended' OR $this->Ended) {
        $data['ACTIVE']  = in_array($item, $auctionIds);
        $data['ITEMURL'] = htmlspecialchars(sprintf(Registry::get('ebay.ShowUrl'), $item));
        TplData::add('Auctions', $data);
      }
    }

    if (!$this->Ended)
      Messages::Info(Translation::get('Watching.Show_No_Ended'));

    TplData::set('Categories', esf_Auctions::getCategories());
    TplData::set('Groups', esf_Auctions::getGroups());
    TplData::set('GetCategoryFromGroup', FROMGROUP);
  }

  /**
   *
   */
  public function ErrorAction() {

  }

}