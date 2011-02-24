<?php
/**
 * Support module
 *
 * @ingroup    Module-Support
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 */
class esf_Module_Support extends esf_Module {

  /**
   *
   */
  public function __construct() {
    parent::__construct();
    // force re-read of esniper version, if changed during actual session
    Session::set('esniperVersion');
  }

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index', 'download');
  }

  /**
   *
   */
  public function IndexAction() {
    TplData::set('SystemVersion', php_uname());

    if (!Exec::getInstance()->ExecuteCmd('Support::WhichPHP', $res)) {
      TplData::set('PHPCliVersion', implode('<br>', $res));
      unset($res);
    }

    if (!Exec::getInstance()->ExecuteCmd('Support::WhoAmI', $res)) {
      TplData::set('esfUser', implode($res));
      unset($res);
    }

    foreach (array(esf_Extensions::MODULE, esf_Extensions::PLUGIN) as $scope) {
      $uscope = strtoupper($scope);
      foreach (esf_Extensions::getExtensions($scope) as $h) {
        TplData::add('Support.'.$scope, array(
          'NAME'    => $h,
          'STATE'   => esf_Extensions::checkState($scope, $h, esf_Extensions::BIT_ENABLED)
                     ? 'Enabled'
                     : ( esf_Extensions::checkState($scope, $h, esf_Extensions::BIT_INSTALLED)
                       ? 'Disabled'
                       : 'Not installed' ),
          'VERSION' => Registry::get($scope.'.'.$h.'.Version', 0),
          'AUTHOR'  => Core::Email(Registry::get($scope.'.'.$h.'.Email'),
                                   Registry::get($scope.'.'.$h.'.Author')),
        ));
      }
    }

    $data = Registry::getAll();
    unset($data['esf'], $data['ebay']);
    TplData::set('Support.CFG', $data);

    $data = $this->camouflage(Registry::get('esf'));
    unset($data['groups'], $data['auctions']);
    TplData::set('Support.ESF', $data);

    TplData::set('Support.EBAY', Registry::get('ebay'));
    TplData::set('Support.ESNIPER', Esniper::getAll());

    foreach (glob(APPDIR.'/classes/ebayparser/*.ini') as $file) {
      if (!IniFile::Parse($file, TRUE)) {
        Messages::Error(IniFile::$Error);
      } else {
        TplData::set('Support.EBAYPARSER.'.basename($file, '.ini'),
                     array_change_key_case(IniFile::$Data, CASE_UPPER));
      }
    }

    TplData::set('Support.Auctions', esf_Auctions::$Auctions);
    TplData::set('Support.GROUPS', esf_Auctions::$Groups);

    $cmd = array('SUPPORT::LS', esf_User::UserDir());
    Exec::getInstance()->ExecuteCmd($cmd, $res);
    TplData::set('USERDIR', implode("\n", $this->camouflage($res)));
    unset($res);

    TplData::set('SESSION', $this->camouflage($_SESSION));
    // remove auction buffer
    TplData::delete('SESSION.PLUGINS.FileSystem.a');

    ob_start();
    phpinfo(9);
    $info = ob_get_clean();
    $info = preg_replace('~^.*<body>(.*)</body>.*$~msi', '$1', $info);
    $info = preg_replace('~width=([\'"])\d+\\1~msi',     '',   $info);
    TplData::set('PHPINFO', $info);
    unset($info);

    TplData::set('DOWNLOADURL', Core::URL(array('action'=>'download')));
  }

  /**
   *
   */
  public function DownloadAction() {
    Registry::set('esf.contentonly', TRUE);
    // fill data
    $this->IndexAction();
    // output via plugin, now are the constants not set...
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  private function camouflage( $var ) {
    $camo = str_repeat('*', strlen(esf_User::getActual()));
    if (is_array($var)) {
      foreach ($var as $key => $val) {
        $var[$key] = $this->camouflage($val);
      }
    } else {
      // remove user ...
      $var = str_replace(esf_User::getActual(), $camo, $var);
      // ... and lowered user
      $var = str_replace(esf_User::getActual(TRUE), $camo, $var);
    }
    return $var;
  }

}
