<?php
/**
 * Auction module
 *
 * @ingroup    Module
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-79-g85bf9fc 2011-02-15 18:24:07 +0100 $
 * @revision   $Rev$
 */
class esf_Module_Auction extends esf_Module {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();

    // menu entry for cleaning up
    if (esf_Auctions::Count()) {
      esf_Menu::addModule( array(
        'module' => 'auction',
        'action' => 'cleanup',
        'extra'  => 'onclick="return CreatePopupWindow(\'PopupCleanupAuctions\',200)"',
        'title'  => Translation::get('Auction.MenuDeleteEnded'),
        'hint'   => Translation::get('Auction.MenuHintDeleteEnded'),
        'img'    => 'module/auction/images/delete.gif',
        'style'  => 'image',
        'id'     => -100,
      ));
    }

    Yuelo::set('SuppressCurrency', Registry::get('Currency'));

    $this->Item     = checkR('item');
    $this->Group    = checkR('group');
    $this->Auctions = checkR('auctions');

    TplData::setConstant('CATEGORIES', esf_Auctions::getCategories());
    TplData::setConstant('GROUPS', esf_Auctions::getGroups());

    Registry::set('esf.ContentOnly', Request::check('auction', '_dump'));
  }

  /**
   * Run, if all prepare is done!
   */
  public function IndexAction() {

    TplData::set('Auctions', array());

    $maxImgSize = getModuleVar('Auction', 'ImageSize');

    $LastCategory = $LastGroup = FALSE;

    $Pids = esf_Auctions::PIDs();
    $time = time();
    $next = FALSE;

    // count auctions per category BEFORE processing
    foreach (esf_Auctions::$Auctions as $auction) {
      $c =& $auction['category'];

      if (!isset($catcount[$c])) {
        $catcount[$c] = array(1, FALSE, FALSE);
      } else {
        $catcount[$c][0]++;
      }

      if (!$auction['ended'] AND $auction['endts'] AND
          (!$catcount[$c][1] OR $auction['endts'] < $catcount[$c][1])) {
        $catcount[$c][1] = $auction['endts'];
        $catcount[$c][2] = $auction['item'];
      }

      // skip endless and ended auctions
      if (!$auction['endts'] OR $auction['endts']-$time < 0) continue;

      if (!$next OR $auction['endts'] < $next['endts']) $next = $auction;
    }

    TplData::set('Ended', Translation::get('Auction.Ended'));

    foreach (esf_Auctions::$Auctions as $auction) {

      Event::Process('DisplayAuction', $auction);

      $TplData = $this->getAuctionTplData($auction);
      $TplData['NEXTAUCTION'] = ($auction['item'] == $next['item']);

      $TplData['CATEGORY'] = array(
        'NAME'    => $auction['category'],
        'NEW'     => ($auction['category'] !== $LastCategory),
        'COUNT'   => $catcount[$auction['category']][0],
        'NEXTEND' => esf_Auctions::Timef($catcount[$auction['category']][1]-$time),
        'ITEM'    => $catcount[$auction['category']][2],
      );
      $LastCategory = $auction['category'];

      $AuctionGroup = esf_Auctions::getGroup($auction);
      $TplData['GROUP'] = self::getGroupTplData($AuctionGroup);
      $TplData['GROUP']['NEW'] = ($LastGroup !== $AuctionGroup);
      if ($AuctionGroup != $LastGroup) {
        if (esf_Auctions::$Groups[$AuctionGroup]['r']) {
          $TplData['GROUP']['ACTIVE'] = in_array($AuctionGroup, $Pids);
          $UrlParams = array('group' => $AuctionGroup);
          if ($TplData['GROUP']['BID'] > 0) {
            $ga = iif($TplData['GROUP']['ACTIVE'], 'stop', 'start');
            $TplData['GROUP']['ACTION']       = $ga;
            $TplData['GROUP']['STARTSTOPURL'] = Core::URL(array('action'=>$ga, 'params'=>$UrlParams));
          } else {
            $TplData['GROUP']['ACTION']       = '';
            $TplData['GROUP']['STARTSTOPURL'] = '';
          }
          $TplData['GROUP']['EDITURL']      = Core::URL(array('action'=>'editgroup', 'params'=>$UrlParams));
        } else {
          $TplData['GROUP']['ACTIVE']       = FALSE;
          $TplData['GROUP']['ACTION']       = FALSE;
          $TplData['GROUP']['STARTSTOPURL'] = '';
          $TplData['GROUP']['EDITURL']      = '';
        }
      }
      $LastGroup = $AuctionGroup;

      // remove found groups from process list
      unset($Pids[array_search($AuctionGroup, $Pids)]);

      $end   = strftime(Registry::get('Format.DateTimeS'), $auction['endts']);
      $ended = FALSE;

      if (!$auction['endts']) { // e.g. Buy-It-Now
        $end = Translation::get('Auction.Endless');
        $remain = '';
      } elseif ($auction['endts']-$time > 0) {
        $remain = esf_Auctions::Timef($auction['endts']-$time);
      } else {
        $remain = Translation::get('Auction.Ended');
        $ended  = TRUE;
      }

      $ImgUrl = sprintf('%s/%s.%s', esf_User::UserDir(), $auction['item'], $auction['image']);

      if ($getImgSize = @GetImageSize($ImgUrl)) {
        $maxSize = $maxImgSize;
        if ($maxSize <= 0) {
          $maxSize = max($getImgSize[0], $getImgSize[1]);
        }
        // show extra large item images max. ImageSize width/height
        $ImageSize = min(max($getImgSize[0], $getImgSize[1]), $maxSize);
        $Scale = $ImageSize / max($getImgSize[0], $getImgSize[1]);
        // calc dimensions
        $TplData['IMGSIZE']   = $ImageSize;
        $TplData['IMGWIDTH']  = floor($getImgSize[0] * $Scale);
        $TplData['IMGHEIGHT'] = floor($getImgSize[1] * $Scale);
      }

      // try to shorten the image url
      $ImgUrl = RelativePath($ImgUrl);
      $TplData['IMGURL'] = urlencode(trim(base64_encode($ImgUrl), '='));

      $TplData['ITEMURL'] = htmlspecialchars(sprintf(Registry::get('ebay.ShowUrl'), $auction['item']));
      $TplData['END']     = $end;
      $TplData['REMAIN']  = $remain;

      $UrlParams = array('item' => $auction['item']);
      $TplData['EDITAUCTIONURL'] = !$ended
                                    ? Core::URL(array('action'=>'editauction', 'params'=>$UrlParams))
                                    : '';
      $TplData['DELETEURL']      = Core::URL(array('action'=>'delete', 'params'=>$UrlParams));

      $TplData['ACLASS'] = '';
      $bid = esf_Auctions::getBid($auction);

      if (strtolower($auction['bidder']) == esf_User::getActual(TRUE)) {
        $TplData['ACLASS'] = !$ended ? 'highest' : 'won';
      } elseif ($bid AND $auction['bids']) {
        if ($ended) {
          $TplData['ACLASS'] = 'lost';
        } elseif ($bid <= eBayIncrements::getNext($auction['bid'], $auction['currency']))  {
          // get increments only for active auctions
          $TplData['ACLASS'] = 'outbid';
        }
      }

      $TplData['AUCTIONGROUP'] = !isset(esf_Auctions::$Auctions[$AuctionGroup])
                               ? $AuctionGroup : '';

      TplData::add('Auctions', $TplData);
    }

    // silently stop orphan esnipers but only for actual user!!
    foreach ($Pids as $pid) esf_Auctions::Stop($pid, FALSE);
  }

  /**
   *
   */
  public function EditAuctionAction() {
    if ($this->isPost()) {
      if ($this->Item AND ($this->Request('confirm') OR $this->Request('confirm_x'))) {

        $data = array(esf_Auctions::get($this->Item), $this->Request);
        Event::Process('AuctionEdited', $data);
        $auction = $data[0];

        // get new image
        if ($this->Request('image') OR $this->Request('imagere')) {
          if ($this->Request('image') == '?') $this->Request['image'] = '';
          $auction['image'] = esf_Auctions::fetchAuctionImage($this->Item, $this->Request('image'));
        }

        if (!$this->Request('currency')) $this->Request['currency'] = $auction['currency'];
        $auction['currency'] = $this->Request('currencydef')
                             ? Registry::get('Currency')
                             : $this->Request('currency');

        if ($this->Request('categorynew')) $this->Request['category'] = $this->Request('categorynew');

        if ($this->Request('groupnew')) $this->Request['group'] = $this->Request('groupnew');
        $this->Request['group'] = esf_Auctions::SanitizeGroup($this->Request('group'));

        $oldgroup = esf_Auctions::getGroup($auction);
        $auction['group'] = $this->Request('group');
        $this->Group = esf_Auctions::getGroup($auction);

        if (!isset(esf_Auctions::$Groups[$this->Group])) {
          esf_Auctions::$Groups[$this->Group] = esf_Auctions::$Groups[$oldgroup];
          esf_Auctions::$Groups[$this->Group]['cat'] = $this->Request('category');
        } elseif ($this->Group != $oldgroup) {
          if ($this->Request('category') == FROMGROUP)
             $this->Request['category'] = esf_Auctions::$Groups[$this->Group]['cat'];
        }
        esf_Auctions::handleCategory($auction, $this->Request('category'));

        if ($this->Request('rotate') AND $angle=(int)$this->Request('rotate')) {
          // absolute image file name
          $ImgFile = sprintf('%s/%s.%s', esf_User::UserDir(), $auction['item'], $auction['image']);
          // rotate image using our own image.php
          $Image = sprintf('%shtml/image.php?n&i=%s&r=%d', BASEHTML, $ImgFile, $angle);
          if ($Image = file_get_contents($Image)) {
            // remove all old thumbs
            if (Exec::getInstance()->Remove(sprintf('"%s/%s.img."*', esf_User::UserDir(), $auction['item']), $err))
              Messages::Error($err);
            // save new image
            if (!File::write($ImgFile, $Image)) Messages::Error('Error writing image file: '.$ImgFile);
          }
        }
        $auction['shipping'] = toNum($this->Request('shipping'));
        if ($this->Request('shippingfree')) $auction['shipping'] = 'FREE';
        if (isset($this->Request['comment'])) $auction['comment'] = $this->Request('comment');
        $auction['mybid'] = toNum($this->Request('mybid'));

        // save data
        esf_Auctions::set($auction);
        esf_Auctions::SaveGroups(FALSE);

        $running = esf_Auctions::PID($this->Group);
        esf_Auctions::Stop($oldgroup, FALSE);
        esf_Auctions::Stop($this->Group, FALSE);

        if ($this->Request('now')) esf_Auctions::BidNow($this->Item, $this->Request('now'));

        // restart auction if required
        if ($running) esf_Auctions::Start($this->Group);
        // redirect in case of inline editing
        $this->Request('ajax') && $this->redirect('auction') || $this->forward();
      } else {
        $this->forward();
      }
    } elseif ($auction = esf_Auctions::get($this->Item)) {

      TplData::set('PopupForm', FALSE);
      TplData::set('SubTitle2', Translation::get('Auction.EditAuction'));
      TplData::set($this->getAuctionTplData($auction));
      TplData::set('Group', self::getGroupTplData(esf_Auctions::getGroup($auction)));
      TplData::set('Category', array('NAME'=>$auction['category']));
      $imgUrl = sprintf('%s/%s.%s', esf_User::UserDir(), $auction['item'], $auction['image']);
      TplData::set('ImgURL', urlencode(trim(base64_encode($imgUrl), '=')));

    } else {
      $this->forward();
    }

  }

  /**
   *
   */
  public function DeleteAction() {
    if ($this->isPost()) {
      if ($this->Request('confirm') OR $this->Request('confirm_x')) {
        esf_Auctions::Delete($this->Item);
        // redirect in case of inline editing
        $this->Request('ajax') && $this->redirect('auction') || $this->forward();
      } else {
        $this->forward();
      }
    } elseif ($auction = esf_Auctions::get($this->Item)) {
      TplData::set('SubTitle2', Translation::get('Auction.DeleteAuction'));
      TplData::set($this->getAuctionTplData($auction));
    } else {
      $this->forward();
    }

  }

  /**
   * Delete all ended Auctions
   */
  public function CleanUpAction() {
    if ($this->isPost()) {
      $cnt = 0;
      foreach (esf_Auctions::$Auctions as $item => $auction) {
        if ($auction['ended']) {
          esf_Auctions::Delete($item, FALSE);
          $cnt = $cnt+1;
        }
      }
      Messages::Success(
        ( $cnt
        ? Translation::get('Auction.DeletedEnded', $cnt)
        : Translation::get('Auction.NoDeletedEnded') )
      );
      // redirect in case of inline editing
      $this->Request('ajax') && $this->redirect('auction');
    }
    $this->forward();
  }

  /**
   * add Auctions
   */
  public function AddAction() {
    if ($this->Auctions) {
      // auctions from auctions or watched items
      if (!is_array($this->Auctions))
        $this->Auctions = preg_split('~\D+~',trim($this->Auctions));

      if ($this->Request('groupnew')) $this->Group = $this->Request('groupnew');
      $this->Group = esf_Auctions::SanitizeGroup($this->Group);

      $category = $this->Request('categorynew')
                ? $this->Request('categorynew')
                : ( $this->Request('category')
                  ? $this->Request('category')
                  : '' );

      $doStart = ($this->Request('start') OR $this->Request('start_x'));

      // get all new auctions data
      foreach ($this->Auctions as $item) {
        // check if auction is still monitored an got correct suction data back
        if ($auction = esf_Auctions::get($item)) {
          Messages::Info(Translation::get('Auction.SkipMonitored', $item, $auction['name']));
          continue;
        }

        if (!$auction = esf_Auctions::fetchAuction($item)) continue;

        $auction['group'] = $this->Group;
        if ($category == FROMGROUP AND isset(esf_Auctions::$Groups[$this->Group]))
          $category = esf_Auctions::$Groups[$this->Group]['cat'];

        esf_Auctions::handleCategory($auction, $category, FALSE);
        esf_Auctions::set($auction);

        // if NO group, each auction as own group
        if (empty($this->Group))
          esf_Auctions::HandleGroup($item, $this->Request, FALSE, $doStart);
      }

      if ($this->Group)
        esf_Auctions::HandleGroup($this->Group, $this->Request, TRUE, $doStart);
    }
    $this->forward();
  }

  /**
   *
   */
  public function EditGroupAction() {
    if (isset(esf_Auctions::$Groups[$this->Group])) {
      if ($this->isPost()) {
        if ($this->Request('save') OR $this->Request('save_x') OR
           $this->Request('start') OR $this->Request('start_x')) {
          $groupNew = checkR('groupnew');
          $groupNew = esf_Auctions::SanitizeGroup($groupNew);
          $doStart  = ($this->Request('start') OR $this->Request('start_x'));
          if (/* !empty($groupNew) AND */ $this->Group != $groupNew) {
            // rename group
            esf_Auctions::Stop($this->Group, FALSE);
            Exec::getInstance()->Remove(esf_Auctions::GroupLogFile($this->Group), $res);

            unset(esf_Auctions::$Groups[$this->Group]);
            foreach (esf_Auctions::$Auctions as $item => $auction) {
              // change all auctions of "old" group
              if (esf_Auctions::getGroup($auction) == $this->Group) {
                $auction['group'] = $groupNew;
                esf_Auctions::set($auction);
                $group = esf_Auctions::getGroup($auction);
                esf_Auctions::HandleGroup($group, $this->Request, FALSE, (!$groupNew AND $doStart));
              }
            }
            $this->Group = $groupNew;
          }
          if ($this->Group)
            esf_Auctions::HandleGroup($this->Group, $this->Request, TRUE, $doStart);
          // redirect in case of inline editing
          $this->Request('ajax') && $this->redirect('auction') || $this->forward();
        } else {
          $this->forward();
        }
      } else {
        TplData::set('PopupForm', FALSE);
        TplData::set('SubTitle2', Translation::get('Auction.EditGroup'));
        TplData::set('Group', $this->getGroupTplData($this->Group));
      }
    } else {
      Messages::Error('Unknown group: '.$this->Group);
      $this->forward();
    }
  }

  /**
   *
   */
  public function StartAction() {
    esf_Auctions::Start($this->Group);
    $this->forward();
  }

  /**
   *
   */
  public function StopAction() {
    esf_Auctions::Stop($this->Group);
    $this->forward();
  }

  // -------------------------------------------------------------------------
  // Multiple actions
  // -------------------------------------------------------------------------

  /**
   *
   */
  public function MCategoryAction() {
    if ($this->isPost() AND $this->Auctions) {
      $category = $this->Request('categorynew')
                ? $this->Request('categorynew')
                : $this->Request('category');

      foreach ($this->Auctions as $item) {
        $auction = esf_Auctions::get($item);
        esf_Auctions::handleCategory($auction, $category);
        esf_Auctions::set($auction);
      }
    }
    $this->forward();
  }

  /**
   *
   */
  public function MGroupAction() {
    if ($this->isPost() AND $this->Auctions) {

      if ($this->Request('groupnew')) $this->Group = $this->Request('groupnew');
      $this->Group = esf_Auctions::SanitizeGroup($this->Group);

      $running = esf_Auctions::PID($this->Group);
      esf_Auctions::Stop($this->Group);
      $doStart = ($this->Request('start') OR $this->Request('start_x'));

      $category = '';
      if (isset(esf_Auctions::$Groups[$this->Group])) {
        $category = esf_Auctions::$Groups[$this->Group]['cat'];
      } else {
        // find first not empty category
        foreach ($this->Auctions as $item) {
          if (!$auction = esf_Auctions::get($item)) continue;
          if (!empty($auction['category'])) {
            $category = $auction['category'];
            break;
          }
        }
      }

      foreach ($this->Auctions as $item) {
        if (!$auction = esf_Auctions::get($item)) continue;
        // stop old auction group(s)
        esf_Auctions::Stop(esf_Auctions::getGroup($auction), FALSE);
        $auction['group'] = $this->Group;
        $auction['category'] = $category;
        esf_Auctions::set($auction);
        if (!$this->Group)
          esf_Auctions::HandleGroup(esf_Auctions::getGroup($auction), $this->Request, TRUE, $doStart);
      }

      if ($this->Group)
        esf_Auctions::HandleGroup($this->Group, $this->Request, TRUE, $doStart);

      // restart group
      if ($running) esf_Auctions::Start($this->Group);
    }
    $this->forward();
  }

  /**
   *
   */
  public function MImageAction() {
    if ($this->isPost() AND $this->Auctions AND $image=$this->Request('image')) {
      foreach ($this->Auctions as $item) {
        if (!$auction = esf_Auctions::get($item)) continue;
        $auction['image'] = esf_Auctions::fetchAuctionImage($a, $image);
        esf_Auctions::set($auction);
      }
    }
    $this->forward();
  }

  /**
   *
   */
  public function MCommentAction() {
    if ($this->isPost() AND $this->Auctions)
      self::setAuctionMultiValue($this->Auctions, 'comment', $this->Request('comment'));
    $this->forward();
  }

  /**
   *
   */
  public function MBidAction() {
    if ($this->isPost() AND $this->Auctions AND $mybid=toNum($this->Request('mybid'))) {
      self::setAuctionMultiValue($this->Auctions, 'mybid', $mybid);
    }
    $this->forward();
  }

  /**
   *
   */
  public function MCurrencyAction() {
    if ($this->isPost() AND $this->Auctions)
      self::setAuctionMultiValue($this->Auctions, 'currency', $this->Request('currency'));
    $this->forward();
  }

  /**
   *
   */
  public function MDelAction() {
    if ($this->isPost() AND $this->Auctions) {
      if (!$this->Request('mdel_confirm')) {
        Messages::Error(Translation::get('Auction.PleaseConfirmDelete'), TRUE);
      } else {
        $i = 0;
        foreach ($this->Auctions as $item) {
          esf_Auctions::Delete($item);
          $i = $i + 1;
        }
        if ($i) Messages::Success(Translation::get('Auction.AuctionsDeleted', $i), TRUE);
      }
    }
    $this->forward();
  }

  /**
   *
   */
  public function MDelGAction() {
    if ($this->isPost() AND $this->Auctions) {
      if (!$this->Request('mdelg_confirm')) {
        Messages::Error(Translation::get('Auction.PleaseConfirmDelete'));
      } else {
        foreach ($this->Auctions as $item) {
          $group = esf_Auctions::getGroup($item);
          Messages::Info(Translation::get('Auction.DeleteAuctionsOfGroup', $group));
          foreach (esf_Auctions::$Auctions as $auction)
            if ($group == esf_Auctions::getGroup($auction)) esf_Auctions::Delete($auction);
        }
      }
    }
    $this->forward();
  }

  /**
   *
   */
  public function MRefreshAction() {
    if ($this->Auctions) {
      Session::set('Module.Refresh.Items', $this->Auctions);
      $this->redirect('refresh');
    } else {
      $this->forward();
    }
  }

  /**
   *
   */
  public function MStartAction() {
    if ($this->isPost() AND $this->Auctions) {
      $h = array();
      foreach ($this->Auctions as $item) $h[] = esf_Auctions::getGroup($item);
      // start each group only once...
      foreach (array_unique($h) as $group) esf_Auctions::Start($group);
    }
    $this->forward();
  }

  /**
   *
   */
  public function MStopAction() {
    if ($this->isPost() AND $this->Auctions) {
      $h = array();
      foreach ($this->Auctions as $item) $h[] = esf_Auctions::getGroup($item);
      // stop each group only once...
      foreach (array_unique($h) as $group) esf_Auctions::Stop($group);
    }
    $this->forward();
  }

  // -------------------------------------------------------------------------
  // Refresh
  // -------------------------------------------------------------------------

  /**
   *
   */
  public function RefreshGroupAction() {
    if ($this->Group) {
      $items = array();
      $groups = (array)$this->Group;
      foreach (esf_Auctions::$Auctions as $item => $auction) {
        // check for raw name and for hash
        $group = esf_Auctions::getGroup($auction);
        if (in_array($group, $groups) OR in_array(md5($group), $groups))
          $items[] = $item;
      }
      if (count($items)) {
        Session::set('Module.Refresh.Items', $items);
        $this->redirect('refresh');
      }
    }
    $this->forward();
  }

  /**
   *
   */
  public function RefreshCategoryAction() {
    if ($this->Request('category')) {
      $items = array();
      $category = (array)$this->Request('category');
      foreach (esf_Auctions::$Auctions as $item => $auction) {
        // check for raw name and for hash
        if (in_array($auction['category'], $category) OR
            in_array(md5($auction['category']), $category)) {
          $items[] = $item;
        }
      }
      if (count($items)) {
        Session::set('Module.Refresh.Items', $items);
        $this->redirect('refresh');
      }
    }
    $this->forward();
  }

  /**
   *
   */
  public function RefreshAction() {
    $this->redirect('refresh');
  }

  // -------------------------------------------------------------------------

  /**
   * Undocumented function to dump all auctions for Copy & Paste
   */
  public function _DumpAction() {
    Header('Content-Type: text/plain');
    foreach (esf_AUctions::$Auctions as $item=>$dummy) echo $item, "\n";
    exit;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Build template auction data
   *
   * @param array $auction
   * @return array
   */
  private function getAuctionTplData( $auction ) {
    $return = array();
    $skip = array('version', 'category', 'group', 'image', '_extra');
    foreach ($auction as $key => $val)
      if (!in_array($key, $skip)) $return['RAW'][strtoupper($key)] = $val;
    foreach (esf_Auctions::getDisplay($auction) as $key => $val)
      if (!in_array($key, $skip)) $return[strtoupper($key)] = $val;
    return $return;
  }

  /**
   * Build template group data
   *
   * @param string $group
   * @return array
   */
  private function getGroupTplData( $group ) {
    $g = esf_Auctions::$Groups[$group];
    return array(
      'NAME'         => $group,
      'AUCTIONGROUP' => (!isset(esf_Auctions::$Auctions[$group]) ? $group : ''),
      'QUANTITY'     => $g['q'],
      'BID'          => $g['b'],
      'TOTAL'        => $g['t'],
      'COMMENT'      => $g['c'],
      'COUNT'        => $g['a'],
      'ENDED'        => ($g['r'] < 1),
    );
  }

  /**
   * Build template category data
   *
   * @param string $category
   * @return array
   */
  private function getCategoryTplData( $category ) {
    return array(
      'NAME' => $category,
    );
  }

  /**
   *
   */
  private function setAuctionMultiValue( $auctions, $key, $value ) {
    foreach ($auctions as $item) {
      if ($auction = esf_Auctions::get($item)) {
        $auction[$key] = $value;
        esf_Auctions::set($auction);
      }
    }
  }

  # ----------------------------------------------------------------------------
  #$gradientcolors = array( '00B000', '808000', 'FA0000');
  #private $gradientcolors = array( '00A000', '808000', 'FA0000');

  /**
   * Build gradient color for remaining auction time
   *
   * @param array $gradientcolors
   * @param integer $remain Remain time
   * @param integer $begin Start to find color [minutes]
   * @param integer $split Start second range [minutes]
   * @return array Colors (R,G,B)
   * /
  private function RemainGradientColor( $gradientcolors, $remain, $begin=60, $split=10 ) {
    if ($remain > $begin) $remain = $begin;
    if ($remain > $split)
      $c = getGradientColor ( $gradientcolors[1], $gradientcolors[0],
                              $begin-$split, round($begin*$remain/$begin-$split) );
    else
      $c = getGradientColor ( $gradientcolors[2], $gradientcolors[1],
                              $split, $remain );
    return $c;
  }

  /*
  $h = 60;
  $s = 10;
  for ($i=$h; $i>=0; $i--) {
    $c = RemainGradientColor($gradientcolors,$i,$h,$s);
    echo '<div style="float:left;color:rgb('.implode(',',$c).')">'
        .'&nbsp;'.$i.'&nbsp;</div>';
  # echo '<div style="float:left;background-color:rgb('.implode(',',$c).')">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
  }
  echo '<div style="float:left;color:rgb(255,0,0)">&nbsp;ended</div>';
  */
}
