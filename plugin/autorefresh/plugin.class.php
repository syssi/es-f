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
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_AutoRefresh extends esf_Plugin {

  /**
   * GET parameter (_PluginAutoRefresh)
   */
  const URLPARAM = 'autorefresh_active_set';

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('AnalyseRequest', 'OutputStart');
  }

  /**
   * Analyse the POST request, extract and remove the parameters
   *
   * @param &$request array
   */
  public function AnalyseRequest( &$request ) {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    if (Registry::get('esf.contentonly')) return;

    if (!isset($request['autorefresh_active'])) return;

    Session::set('Plugin.Autorefresh.Active', $request['autorefresh_active']);
    if ($request['autorefresh_active'] === 2 AND isset($request['autorefresh_interval'])) {
      // store new interval on activation
      Session::set('Plugin.Autorefresh.Interval', $request['autorefresh_interval']);
    }
    // redirect to force GET request
    Core::Redirect(Core::URL(array(
      'module' => 'auction',
      'params' => array(self::URLPARAM=>$request['autorefresh_active'])
    )));
  }

  /**
   * Add meta refresh tag to HTML header
   */
  public function OutputStart() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    if (Registry::get('esf.contentonly') OR
        Registry::get('esf.module') != 'auction') return;

    $active = (int) Session::get('Plugin.Autorefresh.Active');

    if ($active === 2) {
      if (!$interval = Session::get('Plugin.Autorefresh.Interval'))
        $interval = $this->Interval;
      TplData::add('HtmlHeader.raw', sprintf('<meta http-equiv="refresh" content="%d">', $interval*60));
      Messages::Info(Translation::get('AutoRefresh.EnableState', $interval));
    }

    $r = (int) $this->Request(self::URLPARAM);
    if ($r === 1 OR $r === 2) {
      Messages::Info(Translation::get('AutoRefresh.Enable_'.$r));
      $active = $r;
    }

    $data['ACTIVE'] = $active;
    $interval = Session::get('Plugin.Autorefresh.Interval');
    $data['INTERVAL']   = $interval ? $interval : $this->Interval;
    $data['INTERVAL_S'] = $data['INTERVAL']*60;

    TplData::add('Footer_After', $this->Render('content', $data));
  }

}

Event::attach(new esf_Plugin_AutoRefresh);