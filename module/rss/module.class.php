<?php
/** @defgroup Module-RSS Module RSS

*/

/**
 * Module RSS
 *
 * @ingroup    Module-RSS
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-74-ga09c8e3 2011-02-11 21:40:20 +0100 $
 */
class esf_Module_RSS extends esf_Module {

  /**
   *
   */
  public function __construct() {
    parent::__construct();

    $feedpath = dirname(__FILE__).'/feed';
    $xml = '';

    $TplData['XMLHEADER'] = '<?xml version="1.0" encoding="utf-8"?'.'>'."\n";
    $TplData['TITLE'] = ESF_TITLE;
    $TplData['LASTBUILD'] = time();
    $TplData['BASEHTML'] = BASEHTML;

    // Get template instance
    $Tpl = esf_Template::getInstance();

    // Suppress all
    Yuelo::set('Verbose', 0);
    // remove default template extension, we provide here complete file names
    $Tpl->Adapter->TemplateExt = '';

    if (!isset($_GET[APPID]) OR !$user = $_GET[APPID] OR !$user = MD5Encryptor::decrypt($user)) {
      $xml = $Tpl->Render($feedpath.'/error.xml', FALSE, '', '');
    } else {
      // define user data
      esf_User::InitUser($user);

    //  $TplData['FEEDURL'] = BASEHTML.'index.php?module=rss&'.APPID.'='.urlencode($_GET[APPID]);
      $TplData['LASTUPDATE'] = Event::ProcessReturn('getLastUpdate');
      $TplData['USER'] = $user;
      $TplData['ITEMS'] = array();

      // force load of auction files
      Registry::set(esf_Extensions::PLUGIN.'.FileSystem.UseSession', FALSE);
      esf_Auctions::Load();

      // sort without category, begining from group...
      uasort(esf_Auctions::$Auctions, array('esf_Auctions', 'SortAuctions_2_Group'));

      // show ended auctions?
      $ended = (!isset($this->Request['ended']) OR $this->Request['ended']);

      foreach (esf_Auctions::$Auctions as $item => $auction) {

        if ($auction['ended'] AND !$ended) continue;

        // Event::Process('DisplayAuction', $auction);

        $tData = array();

        $skip = array('version', 'image', '_extra', '_display');
        foreach ($auction as $var => $aval) {
          if (!in_array($var, $skip)) {
            $var = strtoupper($var);
            $tData['RAW'][$var] = $tData[$var] = $aval;
          }
        }

        // overwrite org. values by display variants (e.g. from plugins)
        if (isset($auction['_display'])) {
          foreach ($auction['_display'] as $var => $aval) {
            $tData[strtoupper($var)] = $aval;
          }
        }

        // in inculed template we can't access __BASEHTML... (yet)
        $tData['BASEHTML'] = BASEHTML;

        $imgurl = sprintf('%s/%s.%s', esf_User::UserDir(), $auction['item'], $auction['image']);
        // try to shorten the image url
        $imgurl = RelativePath($imgurl);
        $tData['IMGURL'] = urlencode(trim(base64_encode($imgurl), '='));

        $tData['END'] = strftime(Registry::get('Format.DateTimeS'), $auction['endts']);
        $tData['ITEMURL'] = htmlspecialchars(sprintf(Registry::get('ebay.ShowUrl'), $item));
        $tData['REMAIN_TS'] = $auction['endts'] - $_SERVER['REQUEST_TIME'];
        $tData['REMAIN'] = esf_Auctions::Timef($tData['REMAIN_TS']);

        $TplData['ITEMS'][] = $tData;
      }

      $feed = $this->Request('feed') ? $this->Request['feed'] : $this->Feed;

      // check for valid feed id
      if (!is_dir($feedpath.'/'.$feed)) $feed = 'default';

      include $feedpath.'/compress.php';

      // load feed specific code
      if (file_exists($feedpath.'/'.$feed.'.php')) include $feedpath.'/'.$feed.'.php';

      Yuelo::set('Layout', $feed);
      $xml = $Tpl->Render('rss.xml', TRUE, $feedpath, $TplData);
    }
    Event::Process('OutputFilter', $xml);

    if (!isset($_GET['pretty'])) {
      Header('Content-type: application/xhtml+xml');
      Header('Content-Length: '.strlen($xml));
    } else {
      $xml = '<pre>' . htmlspecialchars($xml) . '</pre>';
    }

    die($xml);
  }

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array();
  }

}