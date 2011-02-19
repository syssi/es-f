<?php
/** @defgroup Plugin-PageTime Plugin PageTime

Get page creation/processing time

*/

/**
 * Plugin PageTime
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-PageTime
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Plugin_PageTime extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputStart', 'OutputFilterFooter', 'OutputFilterHtmlEnd');
  }

  /**
   *
   */
  public function OutputStart() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    if (Registry::get('esf.ContentOnly')) return;

    TplData::add('HtmlHeader.Script', 'LoadJSLib("sprintf");');
    TplData::add('HtmlHeader.raw', $this->Render('head'));
  }

  /**
   *
   */
  public function OutputFilterFooter( &$output ) {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    if (Registry::get('esf.ContentOnly')) return;

    // create footer
    $ts = explode(' ',microtime());
    $data['PAGETIME'] = $ts[0]+$ts[1] - $_SERVER['REQUEST_TIME'];
    $output .= $this->Render('footer', $data);
  }

  /**
   *
   */
  public function OutputFilterHtmlEnd( &$output ) {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    if (Registry::get('esf.ContentOnly')) return;

    $output = str_ireplace('</body>',
                           $this->Render('end').'</body>',
                           $output);
  }

}

Event::attach(new esf_Plugin_PageTime);