<?php
/**
 * Module Refresh plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Refresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Module_Refresh extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'BuildMenu', 'ProcessStart');
  }

  /**
   *
   */
  function BuildMenu() {
    if (!esf_User::isValid()) return;

    if (Registry::get('esf.Module') == 'auction')
      esf_Menu::addModule( array('module' => 'refresh') );

    if (Registry::get('esf.Module') != 'refresh')
      Session::set('Module.Refresh.Module', Registry::get('esf.Module'));
  }

  /**
   *
   */
  function ProcessStart() {
    if (!esf_User::isValid()) return;

    // skip refresh, when only content is required (e.g. auction / group edit)
    if (Registry::get('esf.contentonly')) return;

    $maxage = (int) Registry::get('Module.Refresh.MaxAge') * 60;

    if (Registry::get('esf.module') == 'auction' AND $maxage > 0 AND
        $_SERVER['REQUEST_TIME']-$maxage > Event::ProcessReturn('getLastUpdate')) {
      TplData::add('HtmlHeader.raw', StylesAndScripts('module/refresh', Session::get('Layout')));
      $items = array_keys(esf_Auctions::$Auctions);
    } else {
      $items = Session::get('Module.Refresh.Items', NULL, TRUE);
    }

    if (empty($items)) return;

    $auctions = array();
    foreach ($items as $item) {
      if (isset(esf_Auctions::$Auctions[$item])) $auctions[] = $item;
    }

    if (!$count = count($auctions)) return;

    $r = 0;
    foreach ($auctions as $item) {
      $auction = esf_Auctions::get($item);
      if (!$auction['ended'] AND
          $auction = esf_Auctions::fetchAuction($auction, $this->FullRefresh)) {
        esf_Auctions::set($auction, FALSE);
        esf_Auctions::Save($auction, FALSE);
        $r = $r + 1;
      }
    }
    Event::ProcessInform('setLastUpdate');
    Messages::Success(Translation::get('Refresh.Done', $r, $count-$r));
  }
}

Event::attach(new esf_Plugin_Module_Refresh, 100);