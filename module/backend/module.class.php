<?php
/**
 * @category   Module
 * @package    Module-Backend
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Auction Backend module
 *
 * @category   Module
 * @package    Module-Backend
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Module_Backend extends esf_Module {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();

    // Set first defined frontend user as admin, if no other defined
    if (!$this->Admins) $this->Admins = esf_User::$Admin;
    // Is logged in user an admin?
    if (!in_array(esf_User::getActual(TRUE),
                  explode('|', strtolower($this->Admins)))) {
      Messages::addError(Translation::get('Backend.YouArNotAllowed'));
      $this->redirect(Registry::get('StartModule'));
    }

    @list($this->Scope, $this->Extension) = explode('-', $this->Request('ext'));

    $modes = array('install', 'deinstall', 'reinstall', 'enable', 'disable', 'toggle');

    if ($this->Scope AND $this->Extension) {
      if (!is_dir($this->Scope.'/'.$this->Extension)) {
        $this->Scope = '';
        $this->Extension = '';
        $this->forward();
      } elseif (esf_Extensions::checkState($this->Scope, $this->Extension, esf_Extensions::BIT_PROTECTED) AND
                in_array(Registry::get('esf.Action'), $modes)) {
        Messages::addError(Translation::get('Backend.CantChangeProtected', $this->Scope, $this->Extension));
        $this->redirect();
      }
    }

    if (in_array(Registry::get('esf.Action'), $modes)) {
      // clear cache to force recreate menus in header
      Session::setP('ClearCache', TRUE);
    }

    // >> Debug
    DebugStack::Info(ucwords(Registry::get('esf.Action').': '.$this->Scope.'/'.$this->Extension));
    // << Debug
  }

  /**
   *
   */
  public function IndexAction() {
    $this->CreateList(esf_Extensions::MODULE, Translation::get('Backend.Modules'));
    $this->CreateList(esf_Extensions::PLUGIN, Translation::get('Backend.Plugins'));
    foreach ($this->InstallMsgs as $msg) Messages::add($msg[0], $msg[1], $msg[2]);
  }

  /**
   *
   */
  public function InfoAction() {
    $tpldata['SCOPE'] = $this->Scope;
    $tpldata['TYPE']  = ucwords($this->Scope);

    $protected = esf_Extensions::checkState($this->Scope, $this->Extension, esf_Extensions::BIT_PROTECTED);
    $installed = esf_Extensions::checkState($this->Scope, $this->Extension, esf_Extensions::BIT_INSTALLED);
    $enabled   = esf_Extensions::checkState($this->Scope, $this->Extension, esf_Extensions::BIT_ENABLED);
    $SE = $this->Scope.'.'.$this->Extension;

    $tpldata['INSTALLABLE']  = (DEVELOP OR $protected);
    $tpldata['COREFUNCTION'] = $protected;
    $tpldata['INSTALLED']    = $installed;
    $tpldata['ENABLED']      = $enabled;

    $tpldata['ACTIONS']      = $this->ActionLinks($this->Scope, $this->Extension);
    $tpldata['NAME']         = ucwords($this->Extension);
    $tpldata['CATEGORY']     = Registry::get($SE.'.Category');
    $tpldata['DESCRIPTION']  = Registry::get($SE.'.Name');
    $tpldata['VERSION']      = Registry::get($SE.'.Version');
    $tpldata['AUTHOR']       = Core::Email(Registry::get($SE.'.Email'),
                                           Registry::get($SE.'.Author'));

    $tpldata['CHANGELOG'] = '';
    $file = $this->Scope.'/'.$this->Extension.'/CHANGELOG';
    if (file_exists($file)) {

      $changes = '';
      $ul = FALSE;

      foreach ((array)file($file) as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        switch (TRUE) {
          case preg_match('~^\s*Version\s+([\d.]+)\s*$~i', $line, $args):
            $tpldata['CHANGELOG'][$args[1]]['VERSION'] = $line;
            // get pointer to actual changes entry
            $changes =& $tpldata['CHANGELOG'][$args[1]]['CHANGES'];
            $ul = FALSE;
            break;
          case preg_match('~^--+$~', $line, $args):
            // do nothing
            break;
          case preg_match('~^-(.*?)$~', $line, $args):
            if (!$ul) {
              $changes .= '<ul>';
              $ul = TRUE;
            }
            $changes .= '<li>'.trim($args[1]).'</li>';
            break;
          default:
            if ($ul) {
              $changes .= '</ul>'."\n";
              $ul = FALSE;
            }
            $changes .= '<strong>'.$line.'</strong><br>'."\n";
            break;
        }
      }
    }

    $urlparams = array('ext' => $this->Scope.'-'.$this->Extension);
    $tpldata['HELPURL'] = Core::URL(array('module'=>'help', 'action'=>'show', 'params'=>$urlparams));
    $tpldata['CONFIGURL'] = file_exists($this->Scope.'/'.$this->Extension.'/configuration.xml')
                          ? Core::URL(array('module'=>'configuration', 'action'=>'edit', 'params'=>$urlparams))
                          : '';

    $installer = $this->getInstaller();
    $tpldata['INFO'] = $installer ? $installer->Info() : '';

    // configuration
    $tpldata['CONFIG'] = array();
    foreach (Registry::get($SE) as $key => $val) {
      $key = strtoupper($key);
      if (!in_array($key, array('NAME','ACTIONS','CATEGORY','DESCRIPTION','VERSION','EMAIL','AUTHOR'))) {
        $tpldata['CONFIG'][] = array('VARIABLE' => $key, 'VALUE' => fmtVar($val));
      }
    }
    foreach ($tpldata as $key=>$value) TplData::set($key, $value);
    foreach ($this->InstallMsgs as $msg) Messages::add($msg[0], $msg[1], $msg[2]);
  }

  /**
   *
   */
  public function InstallAction() {
    $this->InstallError = $this->InstallExtension() OR
                          $this->ToggleExtension(TRUE);
    $this->forward($this->ForceInfo ? 'info' : 'index');
  }

  /**
   *
   */
  public function DeinstallAction() {
    $this->InstallError = $this->ToggleExtension(FALSE) OR
                          $this->DeinstallExtension();
    $this->forward();
  }

  /**
   *
   */
  public function ReinstallAction() {
    $this->InstallError = $this->ToggleExtension(FALSE) OR
                          $this->DeinstallExtension() OR
                          $this->InstallExtension() OR
                          $this->ToggleExtension(TRUE);
    $this->forward($this->ForceInfo ? 'info' : 'index');
  }

  /**
   *
   */
  public function EnableAction() {
    $this->InstallError = $this->ToggleExtension(TRUE);
    $this->Finished('Enable');
    $this->forward($this->ForceInfo ? 'info' : 'index');
  }

  /**
   *
   */
  public function DisableAction() {
    $this->InstallError = $this->ToggleExtension(FALSE);
    $this->Finished('Disable');
    $this->forward();
  }

  /**
   *
   */
  public function ToggleAction() {
    if (!esf_Extensions::checkState($this->Scope, $this->Extension, esf_Extensions::BIT_ENABLED))
      $this->EnableAction();
    else
      $this->DisableAction();
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * @var array
   */
  private $InstallMsgs = array();

  /**
   *
   */
  private $ForceInfo = FALSE;

  /**
   *
   */
  private function InstallExtension() {
    if (!$this->Scope OR !$this->Extension) return TRUE;
    $Error = FALSE;

    if ($installer = $this->getInstaller()) {
      $Error = ($installer->Install() === TRUE);
      $this->InstallMsgs = array_merge($this->InstallMsgs, $installer->Messages);
    }

    $this->CheckState($Error, esf_Extensions::STATE_INSTALLED, 'Install');
    return $Error;
  }

  /**
   * InstallMsg
   */
  private function DeinstallExtension() {
    if (!$this->Scope OR !$this->Extension) return TRUE;

    $Error = FALSE;

    if ($installer = $this->getInstaller()) {
      $Error = ($installer->Deinstall() === TRUE);
      $this->InstallMsgs = array_merge($this->InstallMsgs, $installer->Messages);
    }

    if (!$Error) {
      $cmd = sprintf('rm -rf "local/%s/%s"', $this->Scope, $this->Extension);
      if (Exec::getInstance()->Execute($cmd, $res)) {
        $this->InstallMsg($res, Messages::ERROR);
        $Error = TRUE;
      }
    }

    $this->CheckState($Error, esf_Extensions::STATE_NOTHING, 'Deinstall');
    return $Error;
  }

  /**
   * Dual use, for Enable and Disable
   */
  private function ToggleExtension( $enable ) {
    if (!$this->Scope OR !$this->Extension) return TRUE;

    if (!esf_Extensions::checkState($this->Scope, $this->Extension, esf_Extensions::BIT_INSTALLED)) {
      $this->InstallMsg('Not installed yet!', Messages::ERROR);
      return TRUE;
    }

    $Error = FALSE;
    $method = iif($enable, 'Enable', 'Disable');

    if ($enable AND !Core::checkRequired($this->Scope, $this->Extension, $Err)) {
      $this->InstallMsg($Err, Messages::ERROR);
      $Error = TRUE;
    } else {
      if ($installer = $this->getInstaller()) {
        $Error = ($installer->$method() === TRUE);
        $this->InstallMsgs = array_merge($this->InstallMsgs, $installer->Messages);
        if ($enable) $this->ForceInfo = $installer->ForceInfo;
      }
    }
    $this->CheckState($Error,
                      iif($enable, esf_Extensions::STATE_ENABLED, esf_Extensions::STATE_INSTALLED),
                      $method);
    return $Error;
  }

  /**
   *
   */
  private function CreateList( $scope, $header ) {
    $data = TplData::get('Scope.'.$header);
    $data['CATEGORY'] = array();

    foreach (esf_Extensions::getExtensions($scope) as $name) {
      if (esf_Extensions::checkState($scope, $name, esf_Extensions::BIT_HIDDENCORE))
        continue;

      $protected = esf_Extensions::checkState($scope, $name, esf_Extensions::BIT_PROTECTED);
      $installed = esf_Extensions::checkState($scope, $name, esf_Extensions::BIT_INSTALLED);
      $enabled   = esf_Extensions::checkState($scope, $name, esf_Extensions::BIT_ENABLED);

      $_tpldata = array();
      $_tpldata['PROTECTED'] = (!DEVELOP AND $protected);
      $_tpldata['COREFUNCTION'] = $protected;
      $_tpldata['INSTALLED'] = $installed;
      $_tpldata['ENABLED'] = $enabled;

      $_tpldata['NAME'] = ucwords($name);
      $_tpldata['VERSION'] = Registry::get($scope.'.'.$name.'.Version', 0);

      $url = $scope.'/'.$name.'/thumbnail.gif';
      $_tpldata['THUMBURL'] = file_exists($url) ? $url : FALSE;

      $_tpldata['DESCRIPTION'] = Registry::get($scope.'.'.$name.'.Name');
      $_tpldata['REQUIREJS'] = Registry::get($scope.'.'.$name.'.RequireJS', FALSE);

      $_tpldata['ACTIONS'] = $this->ActionLinks($scope, $name, FALSE);

      $urlparams = array('ext' => $scope.'-'.$name);
      $_tpldata['INFOURL'] = Core::URL(array('action'=>'info', 'params'=>$urlparams));

      if ($enabled AND file_exists($scope.'/'.$name.'/configuration.xml')) {
        $urlparams['returnto'] = encodeReturnTo(array('module'=>'backend', 'action'=>'index'));
        $_tpldata['CONFIGURL'] = Core::URL(array('module'=>'configuration', 'action'=>'edit', 'params'=>$urlparams));
      }

      $category = ucwords(Registry::get($scope.'.'.$name.'.Category', 'Core'));
      if ($category == 'Core') {
        // Force Core to first position on key sort ;-)
        $category = "\x01".$category;
      }
      $data['CATEGORY'][$category]['EXTENSIONS'][] = $_tpldata;
    }
    ksort($data['CATEGORY']);
    TplData::set('Scope.'.$header, $data);
  }

  /**
   *
   */
  private function ActionLinks( $scope, $name, $config=TRUE ) {
    $urlparams = array('ext' => $scope.'-'.$name);

    $protected = esf_Extensions::checkState($scope, $name, esf_Extensions::BIT_PROTECTED);
    $installed = esf_Extensions::checkState($scope, $name, esf_Extensions::BIT_INSTALLED);
    $enabled   = esf_Extensions::checkState($scope, $name, esf_Extensions::BIT_ENABLED);

    $actions = array();
    if (DEVELOP OR !$protected) {
      if (!$installed) {
        $actions[10] = array('URL'   => Core::URL(array('action'=>'install', 'params'=>$urlparams)),
                           'TITLE' => Translation::get('BACKEND.INSTALL'));
      } else {
        $actions[10] = array('URL'   => Core::URL(array('action'=>iif($enabled, 'disable', 'enable'), 'params'=>$urlparams)),
                             'TITLE' => iif($enabled, Translation::get('BACKEND.DISABLE'), Translation::get('BACKEND.ENABLE')));
        $actions[11] = array('URL'   => Core::URL(array('action'=>'deinstall', 'params'=>$urlparams)),
                             'TITLE' => Translation::get('BACKEND.DEINSTALL'));
        $actions[12] = array('URL'   => Core::URL(array('action'=>'reinstall', 'params'=>$urlparams)),
                             'TITLE' => Translation::get('BACKEND.REINSTALL'));
      }
    }

    if ($config AND $enabled AND ModuleEnabled('Configuration')) {
      $urlparams['returnto'] = encodeReturnTo(array('module' => 'backend', 'action' => 'info'));
      $actions[1] = array('URL'   => Core::URL(array('module'=>'configuration', 'action'=>'edit', 'params'=>$urlparams)),
                          'TITLE' => Translation::get('BACKEND.EDITCONFIGURATION'));
    }

    ksort($actions);
    return $actions;
  }

  /**
   * InstallMsg
   */
  private function InstallMsg( $msg, $type=Messages::INFO, $formated=FALSE ) {
    $this->InstallMsgs[] = array($msg, $type, $formated);
  }

  /**
   *
   */
  private function CheckState( &$Error, $State, $Type ) {
    $msg = 'Backend.' . $Type;
    if (!$Error) {
      esf_Extensions::setState($this->Scope, $this->Extension, $State);
      $file = 'local/config/state.xml';
      $rc = esf_Extensions::saveStates($file);
      if ($rc > 0) {
        $this->InstallMsg(Translation::get($msg.'Successed'),Messages::SUCCESS);
      } else {
        $this->InstallMsg('Error writing state file ['.$file.']!', Messages::ERROR);
        $this->InstallMsg(Translation::get($msg.'Failed'),Messages::ERROR);
        $Error = TRUE;
      }
    } else {
      $this->InstallMsg(Translation::get($msg.'Failed'),Messages::ERROR);
    }
  }

  /**
   *
   */
  private function Finished ( $action ) {
    foreach ($this->InstallMsgs as $msg) Messages::add($msg[0], $msg[1], $msg[2]);
    $this->InstallMsgs = array();
    if (!$this->InstallError AND $installer = $this->getInstaller()) {
      $method = $action . 'Finished';
      $installer->$method();
    }
  }

  /**
   *
   */
  private function &getInstaller() {
    $file = $this->Scope.'/'.$this->Extension.'/install.class.php';
    if (Loader::Load($file, TRUE, FALSE)) {
      $class = 'esf_Install_'.$this->Scope.'_'.$this->Extension;
      return new $class;
    }
  }

}
