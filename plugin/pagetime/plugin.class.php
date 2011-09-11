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
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_PageTime extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return array();
    if (Registry::get('esf.ContentOnly')) return array();
    return array('LanguageSet', 'OutputStart', 'OutputFilterHtmlEnd');
  }

  /**
   *
   */
  public function OutputStart() {
    TplData::add('HtmlHeader.Script', 'LoadJSLib("sprintf");');
    TplData::add('HtmlHeader.raw', $this->Render('head'));
  }

  /**
   * Add PHP generation time and JS for page generation time
   *
   * @param string &$output
   */
  public function OutputFilterHtmlEnd( &$output ) {
    $ts = explode(' ', microtime());
    $data['PAGETIME'] = $ts[0]+$ts[1] - $_SERVER['REQUEST_TIME'];
    $output = str_ireplace('</body>',
                           $this->Render('end', $data).'</body>',
                           $output);
  }

}

Event::attach(new esf_Plugin_PageTime);