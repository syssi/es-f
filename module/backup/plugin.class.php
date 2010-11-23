<?php
/**
 *
 *
 * @category   Plugin
 * @package    Plugin-ModuleBackup
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_Module_Backup extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu', 'AnalyseRequest', 'DeleteAuctionFileSystem');
  }

  /**
   *
   */
  function __construct() {
    parent::__construct();
    ModuleRequireModule('Backup', 'Auction', '0.6.0');
  }

  /**
   *
   */
  public function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    // require valid login
    if (!esf_User::isValid() OR !Request::check('auction')) return;

    // sub-item to auctions
    esf_Menu::addModule( array( 'module' => 'backup', 'id' => 90 ) );
  }

  /**
   * There are 2 select options for the action...
   */
  function AnalyseRequest( &$request ) {
    if (!empty($request['action1'])) {
      $request['action'] = $request['action1'];
      unset($request['action1']);
    } elseif (!empty($request['action2'])) {
      $request['action'] = $request['action2'];
      unset($request['action2']);
    }
  }

  /**
   * Backup deleted auctions
   */
  function DeleteAuctionFileSystem( $item ) {
    if (is_array($item)) $item = @$item[0];

    if ($auction = esf_Auctions::get($item)) {
      $UserDir = esf_User::UserDir();
      $Exec = Exec::getInstance();
      $src = sprintf('%s/%s.auction', $UserDir, $item);
      $dest = sprintf('%s/backup/', $UserDir);
      if ($Exec->Copy($src, $dest, $res)) Messages::addError($res);
      if ($this->Images) {
        $src = sprintf('"%s/%s.%s"', $UserDir, $item, $auction['image']);
        if ($Exec->Copy($src, $dest, $res)) Messages::addError($res);
      }
    }
  }

}

Event::attach(new esf_Plugin_Module_Backup);