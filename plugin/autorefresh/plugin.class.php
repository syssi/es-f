<?php
/** @defgroup Plugin-AutoRefresh Plugin AutoRefresh

Add input to page footer to perform automatic page refresh.

*/

/**
 * Plugin AutoRefresh
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-AutoRefresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_AutoRefresh extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'AnalysePost', 'OutputStart');
  }

  /**
   * Analyse the POST request, extract and remove the parameters
   *
   * @param &$post array
   */
  public function AnalysePost( &$post ) {
    if (!isset($post['autorefresh_active'])) return;

    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;
    if (Registry::get('esf.contentonly')) return;

    $active = $post['autorefresh_active'];
    Session::set('Plugin.Autorefresh.Active', $active);

    if ($active == 'on') {
      $interval = isset($post['autorefresh_interval'])
                ? (int) $post['autorefresh_interval']
                : $this->Interval;
      // store new interval on activation
      Session::set('Plugin.Autorefresh.Interval', $interval);
      TplData::add('HtmlHeader.raw', sprintf('<meta http-equiv="refresh" content="%d">', $interval*60));
      Messages::Info(Translation::get('AutoRefresh.Enabled', $interval));
    }
    Messages::Info(Translation::get('AutoRefresh.Enable_'.$active));
  }

  /**
   * Add meta refresh tag to HTML header
   */
  public function OutputStart() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;
    if (Registry::get('esf.contentonly') OR
        Registry::get('esf.module') != 'auction') return;

    $active = Session::get('Plugin.Autorefresh.Active');
    $interval = Session::get('Plugin.Autorefresh.Interval');
    if (!$interval) $interval = $this->Interval;

    $data['ACTIVE'] = $active;
    $data['INTERVAL'] = $interval;
    $data['INTERVAL_S'] = $interval*60;

    TplData::add('Footer_After', $this->Render('content', $data));
  }

}

Event::attach(new esf_Plugin_AutoRefresh);