<?php
/** @defgroup Module-Snipe Module Snipe

*/

/**
 * Module Snipe
 *
 * @ingroup    Module
 * @ingroup    Module-Snipe
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Module_Snipe extends esf_Module {

  /**
   *
   */
  public function __construct( $name ) {
    parent::__construct($name);
    Event::block('OutputFilterFooter');
    Event::block('OutputFilterHtmlEnd');
    Registry::set('esf.contentonly', TRUE);
  }

  /**
   *
   */
  public function Before() {
    $this->LanguageSet(Session::get('Language'));
  }

  /**
   *
   */
  public function IndexAction() {
    if (!esf_User::isValid()) {
      Session::setP('LoginReturn', $_GET);
      $tpldata['LoginMsg'] = Messages::toStr(Translation::get('SNIPE.LOGIN'));
      $this->redirect('login');
    } elseif ($loginreturn = Session::getP('LoginReturn')) {
      $_GET = $loginreturn;
      unset($loginreturn);
      Session::setP('LoginReturn');
    }

    if (esf_User::isValid() AND !isset($this->Request['snipe'])) {
      TplData::set('Error', 'Missing parameter.');
      $this->forward('error');
      return;
    }

    $h = parse_url($this->Request['snipe']);
    $item = 0;
    if (isset($h['query'])) {
      parse_str($h['query'],$query);
      if (isset($query['item']) AND preg_match('~\d+~',$query['item'])) {
        $item = $query['item'];
      }
    } else {
      $query = array();
    }

    if (!$item AND preg_match('~item[^\d]*(\d+)~i', $h['path'], $args))
      $item = $args[1];

    if (!$item AND preg_match('~/(\d{8,})~i', $h['path'], $args))
      $item = $args[1];

    if ($item) {
      TplData::set('SubTitle1', Translation::get('Snipe.AddAuction'));
      TplData::set('Item', $item);

      $name = isset($this->Request['title'])
            ? $this->Request['title']
            : 'Item: '.$item;
      TplData::set('Name', $name);
      Session::set('Module.Snipe.Title', $name);

      TplData::set('Categories', esf_Auctions::getCategories());
      TplData::set('Groups', esf_Auctions::getGroups());

      TplData::set('Comment', $this->Request('comment'));
    } else {
      TplData::set('Error', 'Can\'t find item id in submited url: '
                          . htmlspecialchars($this->Request['snipe']));
      $this->forward('error');
    }
  }

  /**
   *
   */
  public function SaveAction() {

    if (!$this->isPost()) return;

    if ($this->Request('item')) {
      $auction = esf_Auctions::fetchAuction($this->Request('item'));

      $item = $auction['item'];

      // make sure is set
      $category = getNewRequest($this->Request, 'category');
      $group = getNewRequest($this->Request, 'group');
      $group = esf_Auctions::SanitizeGroup($group);

      $auction['group'] = $group;
      if ($category == FROMGROUP AND isset(esf_Auctions::$Groups[$group]))
        $category = esf_Auctions::$Groups[$group]['cat'];

      esf_Auctions::handleCategory($auction, $category, FALSE);

      if ($this->Request('comment'))  $auction['comment'] = $this->Request('comment');
      if ($this->Request('shipping')) $auction['shipping'] = $this->Request('shipping');
      if ($this->Request('mybid'))    $auction['mybid'] = toNum($this->Request('mybid'));
      if ($this->Request('now'))      esf_Auctions::BidNow($item, $this->Request('now'));

      esf_Auctions::set($auction);

      // groups not set yet, esf_Auctions::getGroup() don't work!
      esf_Auctions::HandleGroup(iif($group, $group, $item), $this->Request, FALSE,
                                (isset($this->Request['start']) OR isset($this->Request['start_x'])));

      TplData::set('Name', Session::get('Module.Snipe.Title'));
      Session::set('Module.Snipe.Title');

    } else {
      TplData::set('Error', 'Missing form parameter: snipe');
      $this->forward('error');
    }
  }

  /**
   *
   */
  public function ErrorAction() {}
}