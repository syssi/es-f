<?php
/**
 * Filesystem handling
 *
 * @ingroup    Plugin-FileSystem
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 */
class esf_Plugin_FileSystem extends esf_Plugin {

  /**
   *
   */
  const AUCTIONFILE = '%s.auction';

  /**
   *
   */
  const GROUPFILE = '.group';

  /**
   *
   */
  const SELLERFILE = '.seller';

  /**
   *
   */
  const LASTFILE = '.last';

  /**
   *
   */
  const SESSIONKEY = 'Plugin.Filesystem.Auctions';

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LoadAuctions', 'LoadAuction', 'SaveAuction', 'DeleteAuction',
                 'getLastUpdate', 'setLastUpdate',
                 'LoadGroups', 'SaveGroups',
                 'LoadSellers', 'SaveSellers');
  }

  /**
   *
   */
  public function LoadAuctions( &$auctions ) {
    $fs = Session::get(self::SESSIONKEY);
    self::getLastUpdate($last);    // reload each hour
    if ($fs AND $last < $fs[0] AND $fs[0]+3600 > $_SERVER['REQUEST_TIME']) {
      $auctions = $fs[1];
      /// Yryie::Info('Used auctions from session.');
    } else {
      $files = glob(esf_User::UserDir().'/'.sprintf(self::AUCTIONFILE, '*'));
      $auctions = array();
      foreach ($files as $file)
        if ($auction = $this->LoadArrayFile($file))
          $auctions[$auction['item']] = $auction;
      Session::set(self::SESSIONKEY, array($_SERVER['REQUEST_TIME'], $auctions));
      /// Yryie::Info('Read auctions from file system.');
    }
    return $auctions;
  }

  /**
   *
   */
  public function LoadAuction( &$auction ) {
    $item = $auction;
    $auction = FALSE;
    $fs = Session::get(self::SESSIONKEY);
    if (is_array($fs) AND isset($fs[1][$item]) AND
        self::getLastUpdate() < $fs[0] AND
        // reload each hour
        $fs[0]+3600 > $_SERVER['REQUEST_TIME']) {
      $auction = $fs[1][$item];
    } else {
      $auction = $this->LoadArrayFile(sprintf(self::AUCTIONFILE, $item));
    }
    return $auction;
  }

  /**
   *
   */
  public function SaveAuction( &$auction ) {
    $this->AuctionFilesChanged();
    $this->SaveArrayFile(sprintf(self::AUCTIONFILE, $auction['item']), $auction);
  }

  /**
   *
   */
  public function DeleteAuction( $item ) {
    $this->AuctionFilesChanged();
    // Inform BEFORE deletion
    Event::ProcessInform('DeleteAuctionFileSystem', $item);
    return Exec::getInstance()->Remove(sprintf('"%s/%s"*', esf_User::UserDir(), $item), $res);
  }

  /**
   *
   */
  public function getLastUpdate( &$last ) {
    $last = array(File::MTime(esf_User::UserDir().'/'.self::LASTFILE));
  }

  /**
   *
   */
  public function setLastUpdate() {
    File::touch(esf_User::UserDir().'/'.self::LASTFILE);
    self::getLastUpdate($last);
    return $last[0];
  }

  /**
   *
   */
  public function LoadGroups( &$groups ) {
    if (!$groups = $this->LoadArrayFile(self::GROUPFILE)) $groups = array();
    return $groups;
  }

  /**
   *
   */
  public function SaveGroups( &$groups ) {
    $this->SaveArrayFile(self::GROUPFILE, $groups);
  }

  /**
   *
   */
  public function LoadSellers( &$sellers ) {
    if (!$sellers = $this->LoadArrayFile(self::SELLERFILE)) $sellers = array();
    return $sellers;
  }

  /**
   *
   */
  public function SaveSellers( &$sellers ) {
    $this->SaveArrayFile(self::SELLERFILE, $sellers);
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   */
  private function LoadArrayFile( $file ) {
    if (substr($file, 0, 1) != '/') $file = esf_User::UserDir().'/'.$file;
    /// Yryie::Info($file);
    return file_exists($file) ? unserialize(file_get_contents($file)) : FALSE;
  }

  /**
   *
   */
  private function SaveArrayFile( $file, $array ) {
    if (substr($file, 0, 1) != '/') $file = esf_User::UserDir().'/'.$file;
    /// Yryie::Info($file);
    file_put_contents($file, serialize($array));
  }

  /**
   *
   */
  private function AuctionFilesChanged() {
    // reset auctions in session
    Session::set(self::SESSIONKEY);
  }

}

Event::attach(new esf_Plugin_FileSystem);