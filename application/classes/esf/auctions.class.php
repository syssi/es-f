<?php
/**
 * Auctions handling
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-81-g966abf9 2011-02-18 21:49:18 +0100 $
 */
abstract class esf_Auctions {

  /**
   *
   */
  const FORCEUPGRADE = 'ForceUpgradeAuctions';

  /**
   *
   */
  const CONFIGFILE = '.c';

  /**
   * @var array $Auctions
   */
  public static $Auctions = array();

  /**
   * @var array $Groups
   */
  public static $Groups = array();

  /**
   * @var array $Sellers
   */
  public static $Sellers = array();

  /**
   * Load auctions
   */
  public static function Load() {
    Event::Process('LoadAuctions', $auctions);
    self::LoadGroups();
    $groups = array();

    foreach ($auctions as $item => $auction) {
      $ag = self::getGroup($auction);
      if (!isset($groups[$ag])) {
        $groups[$ag] = self::$NewGroup;
        $groups[$ag]['cat'] = $auction['category'];
        if (isset(self::$Groups[$ag]))
          $groups[$ag] = array_merge($groups[$ag], self::$Groups[$ag]);
      } else {
        $groups[$ag]['a']++;
      }

      if ($auction['endts']-$_SERVER['REQUEST_TIME'] > 0) $groups[$ag]['r']++;

      Event::Process('AuctionLoaded', $auctions[$item]);
    }

    // sort auctions
    uasort($auctions, array('self', 'SortAuctions'));

    // sort & save groups
    self::$Groups = $groups;
    ksort(self::$Groups);

    self::saveGroups(FALSE);

    self::$Auctions = $auctions;

    Event::ProcessInform('AuctionsLoaded');
  }

  /**
   * Set auction data and save
   *
   * @param array $auction Auction id
   * @param bool $save
   */
  public static function set( $auction, $save=TRUE ) {
    // make sure, $auction is an array!
    if ($auction = self::_auction($auction)) {
      self::$Auctions[$auction['item']] = $auction;
      if ($save) self::Save($auction);
    }
  }

  /**
   * Get auction data, also for testing of existence
   *
   * @param string $item Auction id
   * @param bool $KeysUpperCase
   * @return array|bool Auction data if exists or FALSE if not
   */
  public static function get( $item, $KeysUpperCase=FALSE ) {
    $item = self::_item($item);
    if ($item AND isset(self::$Auctions[$item])) {
      $auction = self::$Auctions[$item];
      if ($KeysUpperCase) $auction = array_change_key_case($auction, CASE_UPPER);
      return $auction;
    } else {
      return FALSE;
    }
  }

  /**
   *
   */
  public static function Count() {
    return count(self::$Auctions);
  }

  /**
   *
   */
  public static function AuctionsInGroup( $group ) {
    $Auctions = array();
    foreach (self::$Auctions as $item => $auction)
      if (self::getGroup($auction) == $group) $Auctions[] = $item;
    return $Auctions;
  }

  /**
   * Sort auctions callback function, level 1: category
   *
   * @param array $a auction 1
   * @param array $b auction 2
   */
  public static function SortAuctions( $a, $b ) {
    $ca = strtolower($a['category']);
    $cb = strtolower($b['category']);
    return (empty($ca) AND empty($cb))
         ? self::SortAuctions_2_Group($a, $b)
         : (($ca != $cb) ? ($ca > $cb) : self::SortAuctions_2_Group($a, $b));
  }

  /**
   * Sort auctions callback function, level 2: group
   *
   * @param array $a auction 1
   * @param array $b auction 2
   */
  public static function SortAuctions_2_Group( $a, $b ) {
    $ga = strtolower(self::getGroup($a));
    $gb = strtolower(self::getGroup($b));
    return (empty($a['group']) AND empty($b['group']))
         ? self::SortAuctions_3_End ($a, $b)
         : (($ga != $gb) ? ($ga > $gb) : self::SortAuctions_3_End ($a, $b));
  }

  /**
   * Sort auctions callback function, level 3: end time
   *
   * @param array $a auction 1
   * @param array $b auction 2
   */
  public static function SortAuctions_3_End( $a, $b ) {
    return (!$a['endts'] OR $a['endts'] > $b['endts'] AND $b['endts']);
  }

  /**
   * Read auction info from eBay
   *
   * @param mixed $item Action ID or whole auction array
   * @param boolean $all Fetch all details
   * @param boolean $talk Generate messages
   * @return array Auction
   */
  public static function fetchAuction( $item, $all=TRUE, $talk=TRUE ) {
    if (empty($item)) {
      Messages::Error(Translation::get('Core.Error').': '.Translation::get('Core.NoItem'));
      return FALSE;
    }

    $item = self::_item($item);
    $auction = self::_auction($item);

    // Don't reread still invalid auctions, except it is allowed
    if (isset($auction['invalid']) AND $auction['invalid'] AND !$all) {
      // >> Debug
      Messages::Error('Auction '.$auction['item'].' "'.$auction['name'].'" is invalid.');
      Yryie::Error('Auction "'.$auction['name'].'" ('.$auction['item'].') '
                      . 'is invalid (removed from ebay or to old), ignored re-read request');
      // << Debug
      return $auction;
    }

    // skip ended auctions, $auction can be FALSE!
    if (isset($auction['ended']) AND $auction['ended'] AND !$all) {
      $talk && Messages::Info('Auction '.$auction['item'].' "'.$auction['name'].'" is still ended.');
      // >> Debug
      Yryie::Warning('Auction "'.$auction['name'].'" is still ended, ignored re-read request');
      // << Debug
      return $auction;
    }

    if (!$auction) {
      $auction = self::$NewAuction;
      $auction['item'] = $item;
    }

    /// Yryie::StartTimer('AuctionParse'.$item, 'Parse '.$item);

    $parser = self::getParser($auction, $invalid);

    /// Yryie::StopTimer('AuctionParse'.$item);

    if (!$invalid AND !$parser) {
      Messages::Error(Translation::get('Auction.ErrorRetrieving', $item), TRUE);
      Messages::Info(Translation::get('Auction.ErrorRetrievingTryAgain', $item), TRUE);
      Messages::Info(Translation::get('Auction.ReportAuctionFiles', TEMPDIR, $item, Registry::get('URL.CreateBugTrackerItem')), TRUE);
      return FALSE;
    }

    if (!$invalid) {
      $name = $parser->getDetail($item, 'Title');
      // translate all [´`"] to simple '
      $name = str_replace(array('"', chr(96), chr(180)), '\'', $name);

      if (empty($name)) {
        $invalid = TRUE;
      } else {
        $auction['name'] = $name;
        
        $bid = $parser->getDetail($item, 'Bid');
        $auction['bid'] = toNum($bid);

        $curr = preg_match('~&#\d+;~', $bid, $args)
              ? $args[0]
              : trim(preg_replace('~[\d,.]+~', '', $bid));
        $auction['currency'] = !empty($curr) ? $curr : '?';

        $auction['bidder'] = $parser->getDetail($item, 'Bidder');
        // find out auction win by parsing esniper log file
        // real names are only visible for logged in users (esniper)
        $user = esf_User::getActual();
        $regex = sprintf('~Auction %s: Post-bid info:.*?High bidder: %s!!!\s+won~is', $item, $user);
        $file = self::GroupLogFile(self::getGroup($auction));
        if (file_exists($file) AND preg_match($regex, file_get_contents($file))) {
          $auction['bidder'] = $user;
        } else {
          // check "bid now" log file and rename it to read it only once after a esniper -n
          $regex = sprintf('~High bidder: %s!!!~i', $user);
          $file = self::GroupLogFile($item.'.bidnow');
          if (file_exists($file) AND preg_match($regex, file_get_contents($file))) {
            $auction['bidder'] = $user;
            if (Exec::getInstance()->Move($file, $file.'.last', $res)) Messages::Error($res);
          }
        }

        $auction['bids']    = $parser->getDetail($item, 'NoOfBids');
        $auction['_ts']     = $_SERVER['REQUEST_TIME'];
        if ($auction['endts'] AND ($auction['endts'] < $auction['_ts'])) $auction['ended']++;
        $auction['invalid'] = FALSE;
      }
    }

    if ($invalid) {
      Messages::Error(Translation::get('Core.Error').': '.Translation::get('Core.InvalidItem', $item));
      if (isset(self::$Auctions[$item])) {
        $auction['ended'] = $auction['invalid'] = TRUE;
        // add item id only once to auction title...
        if (!strstr($auction['name'], $item)) $auction['name'] .= ' ('.$item.')';
      } else {
        // new auction, don't add
        $auction = FALSE;
      }
    } else {
      if ($all) {

        $auction['seller'] = $parser->getDetail($item, 'Seller');
        $auction['bin']    = $parser->getDetail($item, 'bin');
        $auction['dutch']  = $parser->getDetail($item, 'dutch');
        $auction['endts']  = $parser->getDetail($item, 'End');

        if (empty($auction['image']))
          // keep auction image on upgrade, may put manual
          $auction['image'] = self::fetchAuctionImage($item);

        $auction['shipping'] = toNum($parser->getDetail($item, 'Shipping'));

        Event::Process('AuctionReadedInitial', $auction);
      } else {
        Event::Process('AuctionReaded', $auction);
      }
      // >> Debug
      $msg = 'Auction %s "%s" found on ebay.%s';
      Yryie::Info(sprintf($msg, $auction['item'], $auction['name'], $auction['parser']));
      Yryie::Debug($auction);
      // << Debug
    }
    return $auction;
  }

  /**
   * Read auction image from ebay and copy to local file
   *
   * @param string $item
   * @param string $url Use the image from this url if given
   * @return string image Event (type)
   */
  public static function fetchAuctionImage( $item, $url='' ) {

    if (Registry::get('Module.Auction.LoadImages')) {
      // get from ebay
      if (empty($url) AND
          ($parser = Registry::get('ebayParser') OR
           $parser = self::getParser(self::$Auctions[$item], $invalid)))
        $url = $parser->getDetail($item, 'Image');
      // no-image image
      if (empty($url))
        $url = Registry::get('Module.Auction.NoImage');
    }

    // >> Debug
    Yryie::Info('Image URL: '.$url);
    // << Debug

    // save image to disk
    if ($url) {
      // find image type
      if ($info = @getimagesize($url) AND $ext = image_type_to_Extension($info[2])) {
        // >> Debug
        Yryie::Debug($info);
        // << Debug
        ob_start();
        readfile($url);
        $img = ob_get_clean();
      } else {
        Messages::Error('Error opening image file: '.$url);
        $url = FALSE;
      }
    }

    if (!$url) {
      // transparent 1 pixel gif
      $img = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAEALAAAAAABAAEAQAICTAEAOw');
      $ext = '.gif';
    }

    // remove old thumbs
    $file = sprintf('"%s/%s.img."*', esf_User::UserDir(), $item);
    if (Exec::getInstance()->Remove($file, $res)) Messages::Error($res);
    $ext = 'img'.$ext;

    // save image
    $file = sprintf('%s/%s.%s', esf_User::UserDir(), $item, $ext);
    File::write($file, $img);
    return $ext;
  }

  /**
   *
   */
  public static function setExtra( &$auction, $key, $value ) {
    $auction['_extra'][$key] = $value;
  }

  /**
   *
   */
  public static function getExtra( $item, $key ) {
    $item = self::_item($item);
    if (isset(self::$Auctions[$item]['_extra'][$key]))
      return self::$Auctions[$item]['_extra'][$key];
  }

  /**
   *
   */
  public static function setDisplay( $item, $key, $value, $append=FALSE ) {
    $item = self::_item($item);
    $auction = self::_auction($item);

    if (!isset(self::$Display[$item][$key])) self::$Display[$item][$key] = '';

    if ($append AND empty(self::$Display[$item][$key]))
      self::$Display[$item][$key] = $auction[$key];
    self::$Display[$item][$key] .= $value;
  }

  /**
   *
   */
  public static function getDisplay( $item ) {
    $item = self::_item($item);
    return isset(self::$Display[$item])
         ? array_merge(self::$Auctions[$item], self::$Display[$item])
         : self::$Auctions[$item];
  }

  /**
   *
   */
  public static function handleCategory( &$auction, $category=NULL ) {
    $item = $auction['item'];

    if ($auction['category'] != $category AND $category != FROMGROUP)
      $auction['category'] = $category;

    $multi = FALSE;
    if (!empty($auction['group'])) {
      foreach (self::$Auctions as $citem => $cauction) {
        if ($auction['item'] == $citem) continue;
        if ($auction['group'] == $cauction['group']) {
          if ($cauction['category'] != $category) {
            $cauction['category'] = $category;
            self::Save($cauction, FALSE);
            $multi = TRUE;
          }
        }
      }
    }
    if ($multi)
      Messages::Success(Translation::get('Auction.MovedGroupToCategory',
                                            $auction['group'], $category));
  }

  /**
   *
   */
  public static function HandleGroup( $group, $src=array(), $talk=TRUE, $start=FALSE ) {
    $g = isset(self::$Groups[$group]) ? self::$Groups[$group] : self::$NewGroup;
    // analyse source array
    if (isset($src['q']) AND $src['q']!=='' AND is_int($src['q'])>=0)
      $g['q'] =  $src['q'];
    if (isset($src['b']) AND $src['b']!=='' AND toNum($src['b'])>=0)
      $g['b'] = toNum($src['b']);
    if (isset($src['t'])) $g['t'] = (boolean)$src['t'];
    if (isset($src['c'])) $g['c'] = $src['c'];
    // set & save group
    self::$Groups[$group] = $g;
    self::SaveGroups($talk);
    $start && self::Start($group);
  }

  /**
   *
   */
  public static function PID( $group ) {
    $pid = 0;
    if (!empty($group)) {
      $group = self::AuctionFile($group, FALSE);
      Exec::getInstance()->ExecuteCmd(array('CORE::AUCTION_PID', $group), $res);
      if (count($res) > 1) {
        Messages::Error(Translation::get('Core.Error').': '.Translation::get('Core.ToMuchProcesses', $group));
        return FALSE;
      }
      if (isset($res[0])) {
        $res = trim($res[0]);
        $pid = substr($res, 0, strpos($res, ' '));
      }
    }
    return $pid;
  }

  /**
   *
   */
  public static function PIDs() {
    $cmd = array('CORE::AUCTION_PIDS', esf_User::getActual(TRUE, TRUE));
    Exec::getInstance()->ExecuteCmd($cmd, $res);

    $pids = array();
    foreach ((array)$res as $pid) {
      $pid = preg_split('~\s+~', trim($pid));
      if (is_numeric($pid[0])) {
        if (preg_match('~(.+)\.'.esf_User::getActual(TRUE).'$~', $pid[count($pid)-1], $args)) {
          $group = $args[1];
          $group = str_replace('_', ' ', $group);
          $pids[$pid[0]] = $group;
        }
      } else {
        trigger_error(__CLASS__.'::PIDs : Error parsing ps result: '.implode("\n", $res));
      }
    }
    return $pids;
  }

  /**
   * Place a bid for an auction right now
   *
   * @param string $item
   * @param numeric $bid
   */
  public static function BidNow( $item, $bid ) {
    $item = self::_item($item);

    if (!$bid = toNum($bid)) return;

    $log = self::GroupLogFile($item.'.bidnow');

    // esniper config. file will be only as long as required on file system
    self::writeEsniperCfg();
    $cmd = array('Core::BidNow', Registry::get('bin_esniper'),
                 esf_User::UserDir(), $item, $bid, $log);
    if (Exec::getInstance()->ExecuteCmd($cmd, $res, Registry::get('SuDo'))) {
      Messages::Error($res);
    } else {
      Messages::Success(Translation::get('Auction.AuctionBiddedNow', $item));
      // refresh auction data
      AuctionHTML::clearBuffer($item);
      $auction = self::fetchAuction($item, FALSE);
      self::$Auctions[$item] = $auction;
      self::Save($auction, FALSE);
    }
    self::removeEsniperCfg();
  }

  /**
   * Start an auction/group
   *
   * @param string $group Auction/group to start
   * @param bool $talk Show message on success
   * @return int PID of esniper process on success
   */
  public static function Start( $group, $talk=TRUE ) {
    if (!isset(self::$Groups[$group])) {
      Messages::Error('ERROR: Group ['.$group.'] not found!');
      return 0;
    }

    $LogFile = self::GroupLogFile($group);
    $groupname = self::getGroupName($group);

    File::append($LogFile, sprintf(
      'es-f:$ cd "%s"; %s "%s"' . "\n\n",
      esf_User::UserDir(), Registry::get('cfg_esniper'), self::AuctionFile($group, FALSE)
    ));

    if (self::$Groups[$group]['b'] == 0 AND @self::$Groups[$group]['a'] == 1) {
      // may be, there is an auction bid for this one auction in group
      foreach (self::$Auctions as $item => $auction) {
        if ($group == self::getGroup($auction) AND $bid=self::getBid($auction)) {
          self::$Groups[$group]['b'] = $bid;
          Messages::Info(Translation::get('Auction.GroupBidUpdated', $groupname));
          self::saveGroups(FALSE);
        }
      }
    }

    $gdata = self::$Groups[$group];
    if ($gdata['b'] == 0) {
      $msg = Translation::get('Auction.MissingAmount');
      File::append($LogFile, '#- '.$msg.' -#');
      Messages::Error($msg);
      return 0;
    }

    // for restart...
    self::Stop($group, FALSE);

    $skip = $lines = array();
    foreach (self::$Auctions as $item => $auction) {
      if ($group == self::getGroup($auction)) {
        // Re-Set auction name to ISO-8859-1 for storage into file system
        $name = Core::fromUTF8($auction['name']);
        $bid = self::getBid($auction);
        if ($auction['ended']) {
          // skip ended actions, report about
          $skip[] = sprintf('Auction %s: %s', $item, $name);
          $skip[] = 'Skipped, still ended.'."\n";
        } elseif ($bid <= eBayIncrements::getNext($auction['bid'], $auction['currency']) AND
                  $auction['bids']) {
          // skip outbid auctions, report about
          $skip[] = sprintf('Auction %s: %s', $item, $name);
          $skip[] = 'Bid price less than minimum bid price.'."\n";
        } else {
          $lines[] = '# '.$name;
          $lines[] = $item . "\t" . $bid;
          $lines[] = '';
        }
      }
    }

    File::append($LogFile, $skip);
    File::append($LogFile);  // a empty line ;-)

    $pid = 0;

    if (count($lines)) {
      $afile = self::AuctionFile($group);
      File::write($afile, array(
        sprintf('# Group: %s', $group),
        sprintf('# Bid  : %.2f%s', $gdata['b'], ($gdata['t'] ? ' (incl. shipping)' : '')),
        ' ',
        sprintf('quantity = %d', ($gdata['q'] ? $gdata['q'] : 1)),
        ' ',
      ));
      File::append($afile, $lines);
      self::writeEsniperCfg();

      $cmd = array('Core::StartAuction',
                   esf_User::UserDir(), Registry::get('cfg_esniper'),
                   self::AuctionFile($group, FALSE), $LogFile);
      $rc = Exec::getInstance()->ExecuteCmd($cmd, $res, Registry::get('SuDo'));
      self::removeEsniperCfg();

      sleep(Registry::get('Module.Auction.Wait4EsniperStart'));

      $pid = self::PID($group);
      if ($rc) {
        $talk && Messages::Error(Exec::getInstance()->LastCmd);
        $talk && Messages::Error($res);
        $talk && Messages::Error('Take also a look into the esniper log!');
      } elseif (!$pid) {
        $talk && Messages::Error(Translation::get('Auction.GroupNotStarted', $groupname, md5($groupname)), TRUE);
      } else {
        $talk && Messages::Success(Translation::get('Auction.GroupStarted', $groupname), TRUE);
      }
    } else {
      File::append($LogFile, 'Sorry, no possible auction found!');
      $talk && Messages::Info('Sorry, no possible auction found!');
    }
    return $pid;
  }

  /**
   * Stop an auction/group
   *
   * @param string $group Auction/group to stop
   * @param bool $talk Show message on success
   */
  public static function Stop( $group, $talk=TRUE ) {
    if (empty($group)) return 0;

    $pid = self::PID($group);

    if ($pid) {
      Exec::getInstance()->ExecuteCmd(array('CORE::KILL', $pid), $res, Registry::get('SuDo'));
      File::append(self::GroupLogFile($group),
        '#----------------------#' . "\n" .
        '### Manually stopped ###' . "\n" .
        '#----------------------#' . "\n\n"
      );
      $talk && Messages::Success(Translation::get('Auction.GroupStopped', $group));
    }

    $file = self::AuctionFile($group);
    if (Exec::getInstance()->Remove($file, $res)) {
      Messages::Error('Error removing "' . $file . '": ');
      Messages::Error($res);
    }

    return self::PID($group);
  }

  /**
   * Save an auction
   *
   * @param array $auction Auction
   * @param bool $talk Show message on success
   */
  public static function Save( $auction, $talk=TRUE ) {
    if ($auction = self::_auction($auction)) {
      Event::Process('SaveAuction', $auction);
      $talk && Messages::Success(Translation::get('Core.AuctionSaved', $auction['name']), TRUE);
    }
  }

  /**
   * Load groups
   *
   * @return array Groups
   */
  public static function LoadGroups() {
    Event::Process('LoadGroups', self::$Groups);
  }

  /**
   * Save groups
   *
   * @param bool $talk Show success message
   */
  public static function SaveGroups( $talk=TRUE ) {
    $groups = array();
    foreach (self::$Groups as $group => $data)
      $groups[$group] = array(
        'q' => (int)@$data['q'],
        'b' => (float)@$data['b'],
        't' => (boolean)@$data['t'],
        'c' => (string)@$data['c']
      );
    Event::Process('SaveGroups', $groups);
    $talk && Messages::Success(Translation::get('Core.GroupSaved'));
  }

  /**
   *
   */
  function LoadSellers() {
    Event::Process('LoadSellers', self::$Sellers);
  }

  /**
   *
   */
  function SaveSellers() {
    Event::Process('SaveSellers', self::$Sellers);
  }

  /**
   * Delete an auction
   *
   * @param string|array $item Auction
   * @param bool $talk Show message on success
   * @return bool|array
   */
  public static function Delete( $item, $talk=TRUE ) {
    $item = self::_item($item);
    if (!$item OR Registry::get('esf.SimulateDelete')) return FALSE;

    Event::Process('DeleteAuction', $item);

    $Exec = Exec::getInstance();

    // delete images
    $file = sprintf('"%s/%s.img."*', esf_User::UserDir(), $item);
    $Exec->Remove($file, $res);

    // delete HTML buffer
    $file = sprintf('"%s/%s"*', TEMPDIR, $item);
    $Exec->Remove($file, $res);

    $group = self::getGroup($item);
    $auction = self::_auction($item);

    // only not ended, not outbid auctions needs to stops esniper
    if (!$auction['ended']) {
      self::$Groups[$group]['r']--;
      if ($auction['bid'] < self::getBid($item) OR self::$Groups[$group]['r'] == 0)
        self::Stop($group, $talk);
    }

    self::$Groups[$group]['a']--;

    $Exec->Remove(self::AuctionFile($group), $res);

    Event::Process('AuctionDeleted', $auction);

    unset(self::$Auctions[$item]);

    if (self::$Groups[$group]['a'] < 1)
      $Exec->Remove(self::GroupLogFile($group), $res);

    $talk && Messages::Success(Translation::get('Auction.AuctionDeleted', $auction['name']), TRUE);

    return array($item, $auction['name']);
  }

  /**
   * Find out auction group
   *
   * @param array $auction Auction data
   * @return string Auction group
   */
  public static function getGroup( $auction ) {
    $auction = self::_auction($auction);
    return is_array($auction)
         ? ( !empty($auction['group'])
           ? $auction['group']
           : $auction['item'] )
         : '';
  }

  /**
   * Find actual auction bid withotu shipping, either from group or from auction
   *
   * @param string|array $auction Auction
   * @return bool|numeric Auction bid
   */
  public static function getBid( $auction ) {
    $auction = self::_auction($auction);
    if (!$auction) return FALSE;
    $group = self::getGroup($auction);
    return !empty($auction['mybid'])
         ? $auction['mybid']
         : ( self::$Groups[$group]['t']
           ? self::$Groups[$group]['b']-$auction['shipping']
           : self::$Groups[$group]['b'] );
  }

  /**
   * Find actual group name, return auction name in case of empty group name
   *
   * @param string $group Auction
   * @return string Group name
   */
  public static function getGroupName( $group ) {
    return isset(self::$Auctions[$group])
         ? self::$Auctions[$group]['name']
         : $group;
  }

  /**
   * Get all categories from the auctions e.g. for select options
   *
   * @param boolean $plain Don't add a "From group" to the result
   * @return array
   */
  public static function getCategories( $plain=FALSE ) {
    $categories = array();
    if (!$plain) {
      $categories[''] = Translation::get('Core.SelectNone');
      $categories[FROMGROUP] = Translation::get('Core.SelectFromGroup');
    }
    foreach (self::$Auctions as $auction) {
      $category = $auction['category'];
      if ($category) $categories[$category] = $category;
    }
    Event::Process('CategoriesRead', $categories);
    ksort($categories);
    return $categories;
  }

  /**
   * Get all groups from the auctions e.g. for select options
   *
   * @return array
   */
  public static function getGroups() {
    $groups = array( '' => Translation::get('Core.SelectNone') );
    foreach (self::$Auctions as $auction)
      if ($g = $auction['group']) $groups[$g] = $g;
    ksort($groups);
    return $groups;
  }

  /**
   * Sanitize group name, remove unwanted characters
   *
   * @param string &$group Group name to change
   */
  public static function SanitizeGroup( $group ) {
    return trim(preg_replace('~[^A-Z0-9_#=.-]+~i', ' ', utf8_unaccent($group)));
  }

  /**
   * Build user specific auction file name
   *
   * @param string $group Group name
   * @param bool $withDir Return with full directory name
   * @return string User auction file name
   */
  public static function AuctionFile( $group, $withDir=TRUE ) {
    $group = preg_replace('~\s+~', '_', $group);
    $return = sprintf('%s.%s', $group, esf_User::getActual(TRUE));
    // secure group AND user name...
    $return = Secure4fs($return);
    if ($withDir) $return = esf_User::UserDir().'/'.$return;
    return $return;
  }

  /**
   * Build user specific group log file name as user auction file name + '.log'
   *
   * @param string $group Group name
   * @param bool $withDir Return with full directory name
   * @return string Group log file name
   */
  public static function GroupLogFile( $group, $withDir=TRUE ) {
    return self::AuctionFile($group, $withDir) . '.log';
  }

  /**
   * Format remaining auction time
   *
   * @param integer $time Remaing seconds
   * @return string Formated time
   */
  public static function Timef( $time ) {
    $t   = abs($time);
    $d   = floor($t/24/60/60);
    $t   = $t - $d*24*60*60;
    $h   = floor($t/60/60);
    $t   = $t - $h*60*60;
    $m   = floor($t/60);
    $s   = $t - $m*60;

    $format = Registry::get('Format.Remain');
    $return  = sprintf($format['day' ][!$d ? 0 : (abs($d)!=1)+1], $d);
    $return .= sprintf($format['hour'][!$d ? 0 : (abs($h)!=1)+1], $h);
    $return .= sprintf($format['min' ][!$d ? 0 : (abs($m)!=1)+1], $m);
    $return .= sprintf($format['sec' ][!$d ? 0 : (abs($s)!=1)+1], $s);

    if ($time < 0) {
      $return = '- '.$return;
    }

    return $return;
  }

  /**
   * Upgrade auctions
   */
  public static function UpgradeAuctions() {
    if (Registry::get('esf.contentonly') OR
        getSessionModuleVar('Auction', 'AuctionsUpToDate') AND
        !isset($_GET[self::FORCEUPGRADE])) {
      return;
    }

    $auctions = array();
    foreach (self::$Auctions as $item => $auction)
      if (version_compare(@$auction['version'], ESF_AUCTION_VERSION, '<') OR
          isset($_GET[self::FORCEUPGRADE])) $auctions[] = $item;

    if (!count($auctions)) return;

    echo '<div id="upgradeauctions"><img style="float:left"'
        .'src="module/auction/layout/default/images/wait.gif">'
        .'<div style="margin-left:50px">'.Translation::get('Auction.Upgrade', ESF_VERSION).'</div>';

    // Overlay divs for each auction via z-index
    // 1. Outer DIV have to have position:relative
    echo '<div style="margin-left:50px;position:relative">';

    // 2. Inner DIV have to have position:absolute, which results in combination
    //    with a relative positioned parent ==> absolute from _parent_ element.
    $div = '<div class="inner" style="z-index:%d">%s</div>';

    $div_msg = '<tt>%02d/' . sprintf('%02d', esf_auctions::Count()) . ' : </tt> %s ...';

    $id = 1;
    foreach ($auctions as $item) {
      $auction = self::_auction($item);
      $msg = sprintf($div_msg, $id++, $auction['name']);
      // force buffer output with a looong string...
      printf_flush($div, $id, $msg);

      // raise to actual version, if failed, change only version
      if ($a = self::fetchAuction($auction, TRUE, FALSE)) $auction = $a;
      $auction['version'] = ESF_AUCTION_VERSION;
      self::set($auction, FALSE);
      self::save($auction, FALSE);
    }
    self::SaveSellers();

    $done = Translation::get('Auction.Upgraded', ESF_VERSION);
    printf($div, --$id, $done);
    echo_flush('</div></div>');

    Event::ProcessInform('setLastUpdate');

    // try to hide the upgrade output and update "last updated" timestamp
    $script = sprintf('$("upgradeauctions").remove();'
                     .'$("messages").appendChild((new Element("div",{"class":"msginfo"}).update("%s")));'
                     .'$("lastupdate").update("%s");',
                      $done,
                      date(Registry::get('Format.DateTime'),
                      Event::ProcessReturn('getLastUpdate')));
    echo_script($script);

    setSessionModuleVar('Auction', 'AuctionsUpToDate', TRUE);
  }

  /**
   * Write esniper config file USERDIR/.c
   *
   * @param bool $short Only user & password
   */
  public static function writeEsniperCfg( $short=FALSE ) {
    if (!$user = esf_User::getActual(TRUE)) return;

    $conf = array();
    $conf[] = 'batch = yes';
    $conf[] = 'username = ' . $user;
    $conf[] = 'password = ' . esf_User::getPass();

    if (!$short) {
      foreach (Esniper::getAll() as $key => $val)
        if (!empty($val)) $conf[] = sprintf('%s = %s', $key, $val);
    }

    File::write(esf_User::UserDir() . DIRECTORY_SEPARATOR . self::CONFIGFILE,
                implode("\n", $conf));
  }

  /**
   * Remove esniper config file USERDIR/.c
   *
   * @param int $delay Sleep in sec.
   * @return void
   */
  public static function removeEsniperCfg( $delay=5 ) {
    if (!Registry::get('Module.Auction.HoldEsniperConfig')) {
      $cmd = array('CORE::SLEEP_RM', $delay, esf_User::UserDir());
      if (Exec::getInstance()->ExecuteCmd($cmd, $res)) Messages::Error($res);
    }
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   * Buffer display attributes
   *
   * @var array $Display
   */
  private static $Display = array();

  /**
   * Default values for a new auction
   *
   * @var array $NewAuction
   */
  private static $NewAuction = array (
    'version'  => ESF_AUCTION_VERSION, // detect here uprades
    'item'     => '',                  // ebay item id
    'name'     => '',                  // item title
    'endts'    => 0,                   // auction end (timestamp)
    'ended'    => 0,                   // is ended?
    'bin'      => FALSE,               // Buy-it-now
    'bid'      => '',                  // actual bid
    'bids'     => 0,                   // # of bids
    'bidder'   => '',                  // highest bidder
    'currency' => '?',
    'seller'   => '',
    'shipping' => 0,
    'category' => '',
    'group'    => '',
    'image'    => '',                  // image type
    'comment'  => '',
    'mybid'    => '',                  // auction spec. bid, owerwrites group bid
    'dutch'    => 1,                   // dutch auction?
    'invalid'  => FALSE,
    'parser'   => '',                  // last used parser
    '_extra'   => array(),             // buffer for extra data, e.g. from plugins
  );

  /**
   * Default values for a new group
   *
   * @var array $NewGroup
   */
  private static $NewGroup = array (
    'q'   => 1,                  // quantity
    'b'   => 0,                  // bid
    't'   => FALSE,              // is bid the total price incl. shipping?
    'c'   => '',                 // comment
    'a'   => 1,                  // auctions in this group
    'r'   => 0,                  // running, not ended autions in this group
    'cat' => '',                 // category
  );

  /**
   * Find a possible parser for an auction
   *
   * @param array &$auction
   * @param bool &$invalid Set on invalid auctions
   */
  private static function getParser( &$auction, &$invalid ) {
    $invalid = FALSE;
    if (!$parser = Registry::get('ebayParser')) {
      $item = $auction['item'];
      if (!empty($auction['parser'])) {
        /// Yryie::Debug('Reuse parser: '.$auction['parser']);
        $po = array($auction['parser']);
      } else {
        $po = Registry::get('ParseOrder');
        if (!is_array($po)) {
          $po = explode(',', Registry::get('ParseOrder'));
          Registry::set('ParseOrder', $po);
        }
      }
      foreach ($po as $tld) {
        $parser = ebayParser::factory(trim($tld));
        if ($parser->getDetail($item, 'Invalid')) {
          $parser = FALSE;
          $invalid = TRUE;
          break;
        } elseif ($parser->getDetail($item, 'dispatch')) {
          Registry::set('ebayParser', $parser);
          $auction['parser'] = $tld;
          break;
        } else {
          $parser = FALSE;
        }
      }
    }
    return $parser;
  }

  /**
   * Get the item id, if id given just return, if auction, return the auction item
   *
   * @param string|array $item
   */
  private static function _item( $item ) {
    return !is_array($item)
         ? $item
         : ( isset($item['item'])  // SHOULD be an auction...
           ? $item['item']
           : NULL );
  }

  /**
   * Get the auction, if auction is given just return, if item, return the auction
   *
   * @param string|array $auction
   */
  private static function _auction( $auction ) {
    return is_array($auction)
         ? ( isset($auction['item'])  // SHOULD be an auction...
           ? $auction
           : NULL )
         : ( isset(self::$Auctions[$auction])
           ? self::$Auctions[$auction]
           : NULL );
  }
}
