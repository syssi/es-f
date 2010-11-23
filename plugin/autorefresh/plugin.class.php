<?php
/**
 * Automatic page refresh
 *
 * @category   Plugin
 * @package    Plugin-AutoRefresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */
class esf_Plugin_AutoRefresh extends esf_Plugin {

  /**
   * GET parameter (_PluginAutoRefresh)
   */
  const URLPARAM = '_par';

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('AnalyseRequest', 'OutputStart', 'OutputFilterHtmlEnd');
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

    if (isset($request['autorefresh_active'])) {
      Session::set('Plugin.Autorefresh.Active', $request['autorefresh_active']);
      if ($request['autorefresh_active'] and isset($request['autorefresh_interval'])) {
        // store new interval on activation
        Session::set('Plugin.Autorefresh.Interval', $request['autorefresh_interval']);
      }
      // redirect to force GET request
      Core::Redirect(Core::URL(array(
        'module' => 'auction',
        'params' => array(self::URLPARAM=>(int)$request['autorefresh_active'])
      )));
    }
  }

  /**
   * Add meta refresh tag to HTML header
   */
  public function OutputStart() {
    if (!Registry::get('esf.contentonly') AND
        Registry::get('esf.module') == 'auction' AND
        Session::get('Plugin.Autorefresh.Active')) {
      if (!$interval = Session::get('Plugin.Autorefresh.Interval'))
        $interval = $this->Interval;
      TplData::add('HtmlHeader.raw', sprintf('<meta http-equiv="refresh" content="%d">', $interval*60));
    }
    $r = $this->Request(self::URLPARAM);
    if ($r === 0 OR $r === 1) Messages::Info(Translation::get('AutoRefresh.Enable_'.$r));
  }

  /**
   * Add form to footer
   *
   * @param &$output string Footer HTML
   */
  public function OutputFilterHtmlEnd( &$output ) {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    if (Registry::get('esf.contentonly') OR Registry::get('esf.module') != 'auction') return;

    $data['ACTIVE'] = Session::get('Plugin.Autorefresh.Active', FALSE);
    $interval = Session::get('Plugin.Autorefresh.Interval');
    $data['INTERVAL'] = $interval ? $interval : $this->Interval;
    $data['INTERVAL_S'] = $data['INTERVAL']*60;
    $output .= $this->Render('content', $data);
  }
}

Event::attach(new esf_Plugin_AutoRefresh);