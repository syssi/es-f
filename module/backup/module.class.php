<?php
/**
 * Auction backup module
 *
 * @ingroup    Module-Backup
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
 */
class esf_Module_Backup extends esf_Module {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();
    $this->BackupDir = esf_User::UserDir().'/backup';
    if (isset($this->Request['action1']))
      Registry::set('esf.Action', $this->Request['action1']);
    if (isset($this->Request['auction']))
      $this->Auctions = (array)$this->Request['auction'];
    if ($this->Request('clear')) $this->forward('clear');
    $page = $this->Request('page', 1);
    if ($page < 1) $page = 1;
    $this->Start = ($page-1) * $this->AuctionsPerPage + 1;
    $this->End = $page * $this->AuctionsPerPage;
  }

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index', 'show', 'delete', 'restore', 'lock', 'unlock', 'clear');
  }

  /**
   *
   */
  public function IndexAction() {
    $auctions = array();
    foreach (glob($this->BackupDir.'/*.auction') as $file) {
      if ($auction = @unserialize(@file_get_contents($file)))
        $auctions[] = $auction;
    }

    if (!empty($auctions)) {

      uasort($auctions, array(&$this, 'SortAuctions'));

      $options = array(
        'pages'          => ceil(count($auctions) / $this->AuctionsPerPage),
        'shortcount'     => 2,
        'template'       => '{FIRST:|< } {PREVSHORT:%d, | ,|} {PAGE} '
                          . '{NEXTSHORT:%d, | ,|} {LAST: >|}',
        'pagehint'       => Translation::get('Backup.PaginationHint'),
        'linkparameters' => array('module' => 'backup', 'action' => 'index'),
      );
      $Pagination = new Pagination($options);
      TplData::set('Pagination', $Pagination->HTML());
      TplData::set('ShowImages', Registry::get('Module.Backup.Images'));
      TplData::set('AuctionCount', count($auctions));

      $ms = 500;  // max. img. size
      $ts = 20;   // max. thumb size
      $id = 0;

      foreach ($auctions as $auction) {

        if (++$id < $this->Start OR $id > $this->End) continue;

        $item = $auction['item'];
        $tpldata = array('id'=>$id);
        foreach ($auction as $key => $val)
          $tpldata[strtoupper($key)] = $val;
        $tpldata['AuctionURL'] = sprintf(Registry::get('ebay.ShowUrl'), $item);
        $tpldata['ShowURL'] = Core::URL(array('action'=>'show', 'params'=>array('auction'=>$item)));

        $tpldata['Locked'] = file_exists($this->LockFile($item));
        $tpldata['LockURL'] = Core::URL(array('action'=>'lock', 'params'=>array('auction'=>$item)));
        $tpldata['UnlockURL'] = Core::URL(array('action'=>'unlock', 'params'=>array('auction'=>$item)));

        $imgurl = sprintf('%s/%s.%s',$this->BackupDir,$auction['item'],$auction['image']);

        if ($size = @GetImageSize($imgurl)) {
          // show extra large item images max. ImageSize width/height
          $imagesize = min(max($size[0], $size[1]), $ms);

          // calc thumb dimensions
          $scale = $ts / $imagesize;
          $tpldata['ThumbSize']   = $ts;
          $tpldata['ThumbWidth']  = floor($size[0] * $scale);
          $tpldata['ThumbHeight'] = floor($size[1] * $scale);

          // calc tip dimensions
          $scale = max($size[0], $size[1]) / $imagesize;
          $tpldata['IMGSIZE']   = $imagesize;
          $tpldata['IMGWIDTH']  = floor($size[0] * $scale);
          $tpldata['IMGHEIGHT'] = floor($size[1] * $scale);

          // try to shorten the image url
          $imgurl = RelativePath($imgurl);
          $tpldata['IMGURL'] = urlencode(trim(base64_encode($imgurl), '='));
        }
        TplData::add('AUCTIONS', $tpldata);
      }
    } else {
      $this->forward('empty');
    }
  }

  /**
   *
   */
  public function ShowAction() {
    $file = $this->BackupDir.'/'.reset($this->Auctions).'.auction';
    if (file_exists($file)) {
      foreach (unserialize(file_get_contents($file)) as $key => $val) {
        if (substr($key, 0, 1) != '_') {
          TplData::set('AUCTION.'.$key,
                       (is_bool($val) ? ($val ? 'TRUE' : 'FALSE') : htmlspecialchars(print_r($val, TRUE)))
          );
        }
      }
    } else {
      Messages::Error(Translation::get('Backup.AuctionMissing'));
      $this->forward();
    }
  }

  /**
   *
   */
  public function LockAction() {
    foreach ($this->Auctions as $auction) {
      touch($this->LockFile($auction));
    }
    Messages::Success(Translation::get('Backup.Locked'));
    $this->forward();
  }

  /**
   *
   */
  public function UnlockAction() {
    foreach ($this->Auctions as $auction) {
      $file = $this->LockFile($auction);
      file_exists($file) && unlink($file);
    }
    Messages::Success(Translation::get('Backup.Unlocked'));
    $this->forward();
  }

  /**
   *
   */
  public function DeleteAction() {
    if (!$this->isPost()) return;

    $cnt = 0;
    foreach ($this->Auctions as $auction) {
      if (!file_exists(LockFile($auction))) {
        if (Exec::getInstance()->Remove(sprintf('"%s/%s"*', $this->BackupDir, $auction), $result)) {
          Messages::Error($res);
        } else {
          $cnt++;
        }
      }
    }
    if ($cnt > 0) {
      Messages::Success(Translation::get('Backup.Deleted', $cnt));
    }
    $this->forward();
  }

  /**
   *
   */
  public function RestoreAction() {
    if (!$this->isPost()) return;

    foreach ($this->Auctions as $auction) {
      $src = sprintf('"%s/%s"*', $this->BackupDir, $auction);
      if (Exec::getInstance()->copy($src, esf_User::UserDir(), $result)) {
        Messages::Error($res);
      }
      Event::ProcessInform('AuctionFilesChanged');
      Messages::Success(Translation::get('Backup.Restored'));
    }
    $this->redirect('auction');
  }

  /**
   *
   */
  public function ClearAction() {
    if (!$this->isPost()) return;

    $file = sprintf('"%s/"*', $this->BackupDir);
#    Exec::getInstance()->Remove($file, $res);
    Messages::Success(Translation::get('Backup.AllDeleted'));
    $this->redirect('auction');
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   * Requested auctions
   */
  private $Auctions = array();

  /**
   *
   */
  private function LockFile( $item ) {
    return sprintf('%s/%s.lock', $this->BackupDir, $item);
  }

  /**
   *
   */
  private function SortAuctions( $a, $b ) {
    return $a['endts'] < $b['endts'];
  }

}