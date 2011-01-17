<?php
/**
 * esniper protocols module
 *
 * @ingroup    Module-Protocol
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
 */
class esf_Module_Protocol extends esf_Module {

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index', 'show', 'delete', 'empty');
  }

  /**
   *
   */
  public function IndexAction() {
    // links on page top
    TplData::set('Reversed', $this->ReverseLog);

    foreach (esf_Auctions::$Groups as $group => $g) {

      $log = @file_get_contents(esf_Auctions::GroupLogFile($group));
      $log = Core::toUTF8($log);

      // "bid now" logs for each auction of group
      $logLast = array();
      foreach (esf_Auctions::AuctionsInGroup($group) as $item) {
        if ($_log = @file_get_contents(esf_Auctions::GroupLogFile($item.'.bidnow').'.last')) {
          $logLast[] = Core::toUTF8($_log);
        }
      }

      if ($log OR count($logLast) OR getModuleVar('Protocol', 'EmptyLogs')) {

        $tpldata = array();

        if ($g['a'] == 1 AND isset(esf_Auctions::$Auctions[$group]) AND
            empty(esf_Auctions::$Auctions[$group]['group'])) {
          $tpldata['GROUP'] = esf_Auctions::$Auctions[$group]['name'];
        } else {
          $tpldata['GROUP'] = $group;
        }

        $tpldata['AUCTIONFILE'] = @file_get_contents(esf_Auctions::AuctionFile($group));
        $tpldata['AUCTIONS'] = array();

        foreach (esf_Auctions::$Auctions as $auction) {
          if (esf_Auctions::getGroup($auction) == $group) {
            $tpldata['AUCTIONS'][] = array(
              'NAME'    => $auction['name'],
              'ITEMURL' => sprintf(Registry::get('ebay.ShowUrl'), $auction['item']),
            );
          }
        }

        if ($this->ReverseLog)
          $log = implode("\n", array_reverse(explode("\n", $log)));

        $log = preg_replace('~^(.*?Sleeping for.*?)$~mi','<strong>$1</strong>',$log);
        $log = preg_replace('~^#.*?#$~m','<strong style="color:red">$0</strong>',$log);
        $tpldata['LOG']  = $log;
        $tpldata['LOG1'] = $logLast;
        $tpldata['SHOWURL'] = Core::URL(array('action'=>'show', 'params'=>array('group'=>$group)));
        $tpldata['DELETEURL'] = Core::URL(array('action'=>'delete', 'params'=>array('group'=>$group)));

        TplData::add('Protocols', $tpldata);
      }
    }

    if (TplData::isEmpty('Protocols')) $this->forward('empty');
  }

  /**
   *
   */
  public function ShowAction() {
    if (!isset(esf_Auctions::$Groups[$this->Request('group')])) {
      Messages::Error(Translation::get('Protocol.NoGroup'));
      $this->forward();
      return;
    } else {
      Registry::set('esf.contentonly', TRUE);
    }

    TplData::add('Subtitle1', ' / '.Translation::get('Protocol.Group') . ': ' . $this->Request('group'));
    if ($AutoRefresh = Registry::get('Module.Protocol.AutoRefresh') AND
        esf_Auctions::$Groups[$this->Request('group')]['r'] > 0) {
      // auto refresh until auction ended
      TplData::add('HtmlHeader.raw', '<meta http-equiv="refresh" content="'.$AutoRefresh.'">');
    }
    $log = @file_get_contents(esf_Auctions::GroupLogFile($this->Request('group')));
    $log = Core::toUTF8($log);
    TplData::set('Log', $log);
  }

  /**
   *
   */
  public function DeleteAction() {
    if (!isset(esf_Auctions::$Groups[$this->Request('group')])) {
      Messages::Error(Translation::get('Protocol.NoGroup'));
    } else {
      Exec::getInstance()->Remove(esf_Auctions::GroupLogFile($this->Request('group')), $res);
    }
    $this->forward();
  }
}