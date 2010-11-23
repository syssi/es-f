<?php
/**
 * Layout switcher
 *
 * @category   Plugin
 * @package    Plugin-LayoutSwitch
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_LayoutSwitch extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('Start', 'OutputFilterHtmlEnd');
  }

  /**
   *
   */
  public function Start() {
    if ($layout = $this->Request('switchlayout')) Session::set('Layout', $layout);
    if (Session::get('Layout')) Registry::set('Layout', Session::get('Layout'));
  }

  /**
   *
   */
  public function OutputFilterHtmlEnd( &$output ) {
    if (Registry::get('esf.ContentOnly')) return;

    $data['LAYOUTS'] = getLayouts();
    $data['LAYOUT'] = Registry::get('Layout');
    $data['TARGET'] = 'footer_after';
    $output .= $this->Render('content', $data);
  }
}

Event::attach(new esf_Plugin_LayoutSwitch);