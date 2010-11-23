<?php
/**
 * @category   Module
 * @package    Module-RSS
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * RSS module
 *
 * @category   Module
 * @package    Module-RSS
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Module_RSS extends esf_Module {

  /**
   *
   */
  public function __construct() {
    parent::__construct();

    $feedpath = dirname(__FILE__).'/feed';

    $tpldata['XMLHEADER'] = '<?xml version="1.0" encoding="utf-8"?'.'>'."\n";
    $tpldata['TITLE'] = ESF_TITLE;
    $tpldata['LASTBUILD'] = time();
    $tpldata['BASEHTML'] = BASEHTML;

    // Get template instance
    $Tpl = esf_Template::getInstance();

    // Suppress all
    Yuelo::set('Verbose', 0);
    // remove default template extension, we provide here complete file names
    $Tpl->Adapter->TemplateExt = '';

    if (!isset($_GET[APPID]) OR !$user = $_GET[APPID] OR !$user = MD5Encryptor::decrypt($user)) {
      $feed = $Tpl->Render($feedpath.'/error.xml', FALSE, '', '');
    } else {
      // define user data
      esf_User::InitUser($user);

    //  $tpldata['FEEDURL'] = BASEHTML.'index.php?module=rss&'.APPID.'='.urlencode($_GET[APPID]);
      $tpldata['LASTUPDATE'] = Event::ProcessReturn('getLastUpdate');
      $tpldata['USER'] = $user;
      $tpldata['ITEMS'] = array();

      // force load of auction files
      Registry::set(esf_Extensions::PLUGIN.'.FileSystem.UseSession', FALSE);
      esf_Auctions::Load();

      // sort without category, begining from group...
      uasort(esf_Auctions::$Auctions, array('esf_Auctions', 'SortAuctions_2_Group'));

      foreach (esf_Auctions::$Auctions as $item => $auction) {

        // Event::Process('DisplayAuction', $auction);

        $_tpldata = array();

        $skip = array('version', 'image', '_extra', '_display');
        foreach ($auction as $var => $aval) {
          if (!in_array($var, $skip)) {
            $var = strtoupper($var);
            $_tpldata['RAW'][$var] = $_tpldata[$var] = $aval;
          }
        }

        // overwrite org. values by display variants (e.g. from plugins)
        if (isset($auction['_display'])) {
          foreach ($auction['_display'] as $var => $aval) {
            $_tpldata[strtoupper($var)] = $aval;
          }
        }

        // in inculed template we can't access __BASEHTML... (yet)
        $_tpldata['BASEHTML'] = BASEHTML;

        $imgurl = sprintf('%s/%s.%s', esf_User::UserDir(), $auction['item'], $auction['image']);
        // try to shorten the image url
        $imgurl = RelativePath($imgurl);
        $_tpldata['IMGURL'] = urlencode(trim(base64_encode($imgurl), '='));

        $_tpldata['END'] = strftime(Registry::get('Format.DateTimeS'), $auction['endts']);
        $_tpldata['ITEMURL'] = htmlspecialchars(sprintf(Registry::get('ebay.ShowUrl'), $item));
        $_tpldata['REMAIN_TS'] = $auction['endts'] - $_SERVER['REQUEST_TIME'];
        $_tpldata['REMAIN'] = esf_Auctions::Timef($_tpldata['REMAIN_TS']);

        $tpldata['ITEMS'][] = $_tpldata;
      }

      $feed = isset($this->Request['feed']) ? $this->Request['feed'] : $this->Feed;

      // check for valid feed id
      if (!is_dir($feedpath.'/'.$feed)) $feed = 'default';

      // load feed specific code
      if (file_exists($feedpath.$feed.'.php')) include $feedpath.'/'.$feed.'.php';

      $feedpath .= '/'.$feed;
      $feed = $Tpl->Render('rss.xml', TRUE, dirname($feedpath), $tpldata);
    }

    if (!isset($_GET['pretty'])) {
      Header('Content-type: application/xhtml+xml');
      Header('Content-Length: '.strlen($feed));
    } else {
      $feed = '<pre>' . htmlspecialchars($feed) . '</pre>';
    }

    die($feed);
  }

}