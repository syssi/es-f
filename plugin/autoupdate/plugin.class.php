<?php
/** @defgroup Plugin-AutoUpdate Plugin AutoUpdate

*/

/**
 * Plugin AutoUpdate
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-AutoUpdate
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2010 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-70-g8bb353a 2011-02-07 20:54:16 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_AutoUpdate extends esf_Plugin {

  /**
   * Flag to mark check is session
   */
  const SESSIONFLAG = 'AppIsUpToDate';

  /**
   * Time between checks in sec., 1 day
   */
  const LIFESPAN = 86400;

  /**
   * Parameter to trigger update now
   * PluginAutoUpdateNow
   */
  const URLPARAM = '_PAUN';

  /**
   *
   */
  public function __construct() {
    parent::__construct();
    $this->UpdateCount = 0;
    $this->LocalPath = $this->Core['localpath'].'/version';
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'Start', 'ProcessStart', 'OutputContent');
  }

  /**
   *
   */
  public function Start() {

    // 1st call, check once per session or each day
    $this->Update1 = (!$last = Session::get(self::SESSIONFLAG) OR
                      $last + self::LIFESPAN < time());
    /// Yryie::Info('Update1: '.($this->Update1?'TRUE':'FALSE'));

    // 2nd call, check url parameter
    $this->Update2 = ($this->isPost() AND $this->Request(self::URLPARAM));
    /// Yryie::Info('Update2: '.($this->Update2?'TRUE':'FALSE'));

    if ($this->Update1 OR $this->Update2) {

      !is_dir($this->LocalPath) && Exec::getInstance()->MkDir($this->LocalPath, $res);

      Loader::Load(dirname(__FILE__).'/classes/appupdate.class.php');

      $this->cURL = new cURL;
      $this->cURL->setOpt(CURLOPT_CONNECTTIMEOUT, 3) // set very short timeouts
                 ->setOpt(CURLOPT_TIMEOUT,        3)
                 ->setOpt(CURLOPT_VERBOSE,        Registry::get('cURL.Verbose'));

      $options = array(
        'server'         => $this->Server,
        'file'           => $this->URL,
        'check_version'  => ESF_VERSION,
        'cache_lifespan' => 0,
      );

      try {
        $this->Updater = new AppUpdate($this->cURL, $options);
        $this->Updater->CheckUpdates(array(&$this, 'CheckFileVersion'));
        $this->UpdateCount = $this->Updater->getUpdatableCount();
        Session::set(self::SESSIONFLAG, time());
        // >> Debug
        Yryie::Debug($this->cURL->info());
        if (Registry::get('cURL.Verbose')) Yryie::Debug($this->cURL->getDebug());
        // << Debug
      } catch (AppUpdateException $e) {
        // ignore errors and try later
        /// Messages::Error($e->getMessage());
        unset($this->Updater);
      }

    }
  }

  /**
   *
   */
  public function ProcessStart() {

    if (!$this->Update1) return;

    Loader::Load(dirname(__FILE__).'/classes/checkversion.class.php');

    try {
      $CheckVersion = new CheckVersion( $this->cURL, $this->VersionURL );
      $v = $CheckVersion->Version();
      if (version_compare(ESF_VERSION, $v, '<')) {
        if (!file_exists('web-install.php'))
          Messages::Info(Translation::get('AutoUpdate.LatestAppVersion', $v), TRUE);
        else
          Messages::Info(Translation::get('AutoUpdate.UpgradeDirect', $v), TRUE);
      }
    } catch (CheckVersionException $e) {
      // ignore errors and try later
      /// Messages::Error($e->getMessage());
    }

    if (!$this->Updater OR $this->Update2) return;

    // check application version
    if (!$this->UpdateCount) return;

    Messages::Success(Translation::get('AutoUpdate.FilesUpdatable', $this->UpdateCount), TRUE);

    $err = FALSE;

    // get updatable files
    foreach ($this->Updater->getFiles(FALSE) as $file => $data) {
      $msg = '<tt>'.$file.' ('.$data['version'].')</tt>';
      if ($data['comment']) $msg .= ' : ' . $data['comment'];
      Messages::Info($msg, TRUE);

      // check if the file is writable
      if (!$this->Updater->isWritable($file)) {
        Messages ::addError(Translation::get('AutoUpdate.FileNotWritable'), TRUE);
        $err = TRUE;
      }
    }
    if (!$err) {
      $form = '<form method="post"><input type="submit" name="'
            . self::URLPARAM . '" value="'
            . Translation::get('AutoUpdate.UpdateNow').'"></form>';
      Messages::Info($form, TRUE);
    }
  }

  /**
   *
   */
  public function OutputContent() {

    if (!$this->Updater OR !$this->UpdateCount OR !$this->Update2) return;

    echo '<div class="msginner"><strong class="msginfo">',
         Translation::get('AutoUpdate.UpdateFiles'),
         '</strong><br>', str_repeat(' ', 1024);
    flush();

    try {
      $this->Updater->UpdateFiles(array(&$this, 'SaveFileVersion'));
    } catch (AppUpdateException $e) {
      echo $e->getMessage();
    }

    echo '</div>';

    file_put_contents($this->CheckFile, ESF_VERSION);
    Session::set(self::SESSIONFLAG, time());
  }

  /**
   * Callback function for add. version check
   *
   * Must be public!
   *
   * @param bool &$update Modify to force/deny file update
   * @param string $file File name to check
   * @param string $version New file version
   */
  public function CheckFileVersion( &$update, $file, $version ) {
    if ($last = @file_get_contents($this->FileName($file))) {
      // compare with last known file version
      $update = version_compare($last, $version, '<');
    }
    /// Yryie::Info($file.' ('.$version.') update: '.(int)$update);
  }

  /**
   * Callback function to store current version
   *
   * Must be public!
   *
   * @param string $file File name to check
   * @param string $version New file version
   */
  public function SaveFileVersion( $file, $version ) {
    echo '<tt class="msgsuccess">'.$file.'  =&gt;  '.$version.'</tt><br>',
         str_repeat(' ', 1024);
    flush();
    // store new file version
    file_put_contents($this->FileName($file), $version);
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   *
   */
  private $cURL;

  /**
   *
   */
  private $Updater;

  /**
   * Helper function for callbacks
   *
   * Generate file name for version info
   *
   * @param string $file File name to check or updated
   */
  private function FileName( $file ) {
    return $this->LocalPath.'/'.str_replace(array('/','\\'), '_', $file);
  }

}

Event::attach(new esf_Plugin_AutoUpdate);