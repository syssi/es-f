<?php
/**
 * Updates files of an application
 *
 * @ingroup    AppUpdate
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class AppUpdate {

  /**
   * Updatable flag for files
   */
  const UPD = '%u';

  /**
   * ID to cache in session
   */
  const SESSIONID = '.AutoUpdate';

  /**
   * Class constructor
   *
   * Configure the class instance using this parameters
   *  - server         => Server to update from, e.g. http://example.com
   *  - update_file    => File name with update infos, e.g. application.upd
   *  - check_version  => Check actual app. against version, e.g. 1.0.0
   *  - cache_lifespan => Cache the update infos for xxx seconds
   *
   * @param cURL $curl cURL instance
   * @param array $Config Configuration data
   */
  public function __construct( cURL $curl, $Config=array() ) {
    $this->curl = $curl;
    if (!is_array($Config))
      throw new AppUpdateException(__CLASS__.': Constructor parameter $Config must be an array!');
    $this->Config = $Config + $this->Config;
  }

  /**
   * Checks whatever there are updates for the software
   *
   * To perform additional checks, provide a callback function following
   * this definition:
   *
   * @verbatim
     /**
      * @param bool &$update Update given file
      * @param string $file File to update
      * @param string $version Available version
      * /
     function CheckUpdateCallback( &$update, $file, $version ) {
       // modify the update flag as you need
       // e.g. check against result of last update, saved by the callback
       // during $this->Update()
     }
     @endverbatim
   *
   * @throws AppUpdateException In case of error during getting update informations
   * @param string $callback Callback function for additional tests
   * @return void
   */
  public function CheckUpdates( $callback='' ) {
    $this->Application    = array();
    $this->UpdatableFiles = 0;
    $this->Files          = array();

    if (session_id() == '') session_start();

    if ($this->get('cache_lifespan') > 0 AND
        isset($_SESSION[self::SESSIONID]['time']) AND
        time() < $_SESSION[self::SESSIONID]['time']+$this->get('cache_lifespan')) {
      $this->Application    = $_SESSION[self::SESSIONID]['Application'];
      $this->UpdatableFiles = $_SESSION[self::SESSIONID]['UpdatableFiles'];
      $this->Files          = $_SESSION[self::SESSIONID]['Files'];
      return;
    }

    // Use a temporary file
    $tempname = tempnam(ini_get('upload_tmp_dir'), 'upd.');
    $fh = @fopen($tempname, 'w');

    $p = urlencode( ( ($dash = strpos(PHP_VERSION, '-'))
                      ? substr(PHP_VERSION, 0, $dash)
                      : PHP_VERSION
                    ) . '|' . $_SERVER['SERVER_SOFTWARE'] );

    $res = $this->curl
         ->setOpt(CURLOPT_URL, $this->get('server').'/'.$this->get('file').'?'.$p)
         ->setOpt(CURLOPT_FILE, $fh)
         ->setOpt(CURLOPT_HEADER, FALSE)
         ->exec($ret);

    if ($res) throw new AppUpdateException($this->curl->error());

    @fclose($fh);
    $this->Files = parse_ini_file($tempname, TRUE);
    @unlink($tempname);

    if (isset($this->Files['application'])) {
      $this->Application = array_merge($this->Application, $this->Files['application']);
      unset($this->Files['application']);
    }

    foreach ($this->Files as $file => $data) {
      if (!isset($data['version']) OR !isset($data['source']))
        throw new AppUpdateException('Format error in ['.$file.']', 4);
      if (!isset($data['comment'])) $this->Files[$file]['comment'] = '';
    }

    ksort($this->Files);

    foreach ($this->Files as $file => $data) {
      $update = version_compare($this->get('check_version'), $data['version'], '<');
      if ($callback)
        call_user_func_array($callback, array(&$update, $file, $data['version']));
      $this->Files[$file][self::UPD] = $update;
      if ($update) $this->UpdatableFiles++;
    }

    if (!$this->get('cache_lifespan')) return;

    $_SESSION[self::SESSIONID] = array(
      'time'           => time(),
      'Application'    => $this->Application,
      'UpdatableFiles' => $this->UpdatableFiles,
      'Files'          => $this->Files
    );
  }

  /**
   * Get actual application information
   *
   * @return array Array ( 'version' => version, 'comment' => comment )
   */
  public function getApplicationVersion() {
    return $this->Application;
  }

  /**
   * How many files are updatable
   *
   * @return int
   */
  public function getUpdatableCount() {
    return $this->UpdatableFiles;
  }

  /**
   * Return the list of all files available
   *
   * @param bool $all Return all files or only updatable
   * @return array Array of File => Version
   */
  public function getFiles( $all=TRUE ) {
    $return = array();
    foreach ($this->Files as $file=>$data)
      if ($all OR $data[self::UPD]) $return[$file] = $data;
    return $return;
  }

  /**
   * Checks if the file is writable or not.
   *
   * @param string $file File name
   * @return bool
   */
  public function isWritable( $file ) {
    // will work in despite of Windows ACLs bug
    // NOTE: use a trailing slash for folders!!!
    // see http://bugs.php.net/bug.php?id=27609
    // see http://bugs.php.net/bug.php?id=30931

    $exists = file_exists($file);
    if ($fh = @fopen($file, 'a')) {
      fclose($fh);
      if (!$exists) unlink($file);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Updates the files with the ones from the URL
   *
   * Skip all, if at least one file ist not writable
   *
   * To perform additional actions, provide a callback function following
   * this definition:
   *
   * @verbatim
     /**
      * @param string $file Updated file
      * @param string $version New version
      * /
     function AfterUpdateCallback( $file, $version ) {
       // e.g. save the new version and use it during the next update run
       // via the callback in $this->CheckForUpdates() to skip up-to-date files
     }
     @endverbatim
   *
   * @param string $callback Callback function after update
   * @return bool
   */
  public function UpdateFiles( $callback='' ) {

    // extract updatable files and check if writable
    $files = array();
    foreach ($this->Files as $file=>$data) {
      if ($data[self::UPD]) {
        if (!$this->isWritable($file)) return FALSE;
        $files[$file] = $data;
      }
    }

    // all required wriable, let's go
    foreach ($files as $file=>$data) {

      $source = $this->get('server') . $data['source'];
      if (!$rh = @fopen($source ,'rb'))
        throw new AppUpdateException('Can\'t open '.$source, 11);

      if (!$wh = @fopen($file, 'wb'))
        throw new AppUpdateException('Can\'t open '.$file.' for writing.', 12);

      while (!feof($rh)) {
        if (fwrite($wh, fread($rh, 512)) === FALSE) {
          fclose($wh);
          throw new AppUpdateException('Can\'t write to '.$file, 13);
        }
      }
      fclose($rh);  fclose($wh);

      if ($callback)
        call_user_func_array($callback, array($file, $data['version']));
    }

    if (session_id() == '') session_start();
    unset($_SESSION[self::SESSIONID]);

    return TRUE;
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Initial configuration
   */
  protected $Config = array(
    'server'         => '',
    'file'           => '',
    'check_version'  => '1',
    'cache_lifespan' => 300,
  );

  /**
   * Application version
   */
  protected $Application = array(
    'version' => '0',
    'comment' => '',
    'url'     => '',
  );

  /**
   * Count of updatable files
   *
   * @var int $UpdatableFiles
   */
  protected $UpdatableFiles;

  /**
   * Whole file list
   *
   * @var array $Files
   */
  protected $Files;

  /**
   * Get a config value
   *
   * @param string $key
   */
  protected function get( $key ) {
    return isset($this->Config[$key]) ? $this->Config[$key] : NULL;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * cURL instance
   *
   * @var cURL $curl
   */
  private $curl;

}

/**
 * Exception for AppUpdate
 *
 * @ingroup AppUpdate
 */
class AppUpdateException extends Exception {}
