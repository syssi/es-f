<?php
/**
 * @category   Plugin
 * @package    Plugin-AddFromEbayUrl
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Auction statistics
 *
 * @category   Plugin
 * @package    Plugin-AddFromEbayUrl
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_AddFromEbayUrl extends esf_Plugin {

  /**
   *
   */
  public function __construct() {
    parent::__construct();
    if (!is_array($this->Pattern)) $this->Pattern = array($this->Pattern);
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('AnalyseRequest', 'OutputFilterContent');
  }

  /**
   *
   */
  public function AnalyseRequest( &$request ) {
    if (isset($request['action']) AND $request['action'] == 'add' AND !empty($request['auctions'])) {
      if (!is_array($request['auctions'])) {
        $this->scan($request['auctions']);
      } else {
        foreach (array_keys($request['auctions']) as $id)
          $this->scan($request['auctions'][$id]);
      }
    }
  }

  /**
   *
   */
  public function OutputFilterContent( &$output ) {
    if (Registry::get('esf.Module') == 'bulkadd') {
      $output .= '<p id="addfromurl">'.Translation::get('AddFromEbayUrl.BulkAddComment').'</p>'
               . '<script type="text/javascript">addLoadEvent($("addfromurl").move("content_after"))</script>';
    }
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   */
  private function scan( &$str ) {
    // >> Debug
    DebugStack::Info($str);
    // << Debug

    $h = '';
    foreach ($this->Pattern as $p) {
      if (preg_match_all($p, $str, $args, PREG_SET_ORDER)) {
        // >> Debug
        DebugStack::Debug($p);
        DebugStack::Debug($args);
        // << Debug
        foreach ($args as $arg) $h .= ' ' . $arg[1];
      }
    }
    if ($h) $str = trim($h);
  }
}

Event::attach(new esf_Plugin_AddFromEbayUrl);