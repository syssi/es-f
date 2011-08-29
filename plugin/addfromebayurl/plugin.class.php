<?php
/** @defgroup Plugin-AddFromEbayUrl Plugin AddFromEbayUrl

Add auctions from ebay URL

*/

/**
 * Plugin AddFromEbayUrl
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-AddFromEbayUrl
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
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
    return array('LanguageSet', 'AnalyseRequest', 'OutputFilterContent');
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
    Yryie::Info($str);
    // << Debug

    $h = '';
    foreach ($this->Pattern as $p) {
      if (preg_match_all($p, $str, $args, PREG_SET_ORDER)) {
        // >> Debug
        Yryie::Debug($p);
        Yryie::Debug($args);
        // << Debug
        foreach ($args as $arg) $h .= ' ' . $arg[1];
      }
    }
    if ($h) $str = trim($h);
  }
}

Event::attach(new esf_Plugin_AddFromEbayUrl);