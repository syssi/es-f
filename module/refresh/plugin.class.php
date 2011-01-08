<?php
/**
 * Refresh auctions
 *
 * @ingroup    Plugin-ModuleRefresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Plugin_Module_Refresh extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu', 'OutputStart', 'OutputContent');
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
  function OutputStart() {
    // skip refresh, when only content is required (e.g. auction / group edit)
    if (Registry::get('esf.contentonly')) return;

    $auctions = Session::get('Module.Refresh.Items');
    $maxage = (int)Registry::get('Module.Refresh.MaxAge')*60;

    if (esf_User::isValid() AND
        ($auctions OR
         Registry::get('esf.module') == 'auction' AND $maxage > 0 AND
         $_SERVER['REQUEST_TIME']-$maxage > Event::ProcessReturn('getLastUpdate'))) {

      TplData::add('HtmlHeader.raw', StylesAndScripts('module/refresh', Session::getP('Layout')));

      if (!$auctions)
        Session::set('Module.Refresh.Items', array_keys(esf_Auctions::$Auctions));
    }
  }

  /**
   *
   */
  function OutputContent() {
    $items = Session::get('Module.Refresh.Items');
    Session::set('Module.Refresh.Items');

    if (!esf_User::isValid() OR !count($items)) return;

    $auctions = array();
    foreach ($items as $item)
      if (isset(esf_Auctions::$Auctions[$item])) $auctions[] = $item;

    if (!$count = count($auctions)) return;

    echo '<div id="autorefresh"><img style="float:left" src="module/refresh/layout/'
       . Registry::get('Module.Refresh.Layout', 'default').'/images/wait.gif">'
       . '<div style="margin-left:50px">'.Translation::get('Refresh.Message').'</div>';

    // Overlay divs for each auction via z-index
    // 1. Outer DIV have to have position:relative
    echo '<div style="margin-left:50px;position:relative">';

    // 2. Inner DIV have to have position:absolute, which results in combination
    //    with a relative positioned parent ==> absolute from _parent_ element.
    $div = '<div class="inner" style="z-index:%d">%s</div>';

    $div_msg = ($count > 1)
           ? '<tt>%1$02d/'.sprintf('%02d',$count).' : </tt> %2$s ...'
           : '%2$s ...';

    $id = 1;
    $r = 0;

    foreach ($auctions as $item) {
      $auction = esf_Auctions::get($item);
      $msg = sprintf($div_msg, $id++, $auction['name']);
      // force buffer output with a looong string...
      printf_flush($div, $id, $msg);
      // ... and read auction from ebay
      if (!$auction['ended'] AND
          $auction = esf_Auctions::fetchAuction($auction, $this->FullRefresh)) {
        esf_Auctions::set($auction, FALSE);
        esf_Auctions::Save($auction, FALSE);
        $r = $r + 1;
      }
    }

    $done = Translation::get('Refresh.Done', $r, $count-$r);
    printf($div, $id++, $done);
    echo_flush('</div></div>'."\n");

    Event::ProcessInform('setLastUpdate');

    // try to remove the refreshing output and update "last updated" timestamp
    $script = sprintf('$("autorefresh").remove();'
                     .'$("lastupdate").update("%s");',
                      date(Registry::get('Format.DateTime'),
                           Event::ProcessReturn('getLastUpdate')));
    echo_script($script);
  }

}

Event::attach(new esf_Plugin_Module_Refresh);