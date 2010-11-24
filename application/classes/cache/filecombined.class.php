<?php
/**
 * Class Cache_File_Combined
 *
 * description ...
 *
 * @ingroup  Cache
 * @version  1.0.0
 */
class Cache_FileCombined extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   *
   * @public
   * @var string
   */
  public $CacheDir = '/tmp';

  /**
   *
   * @public
   * @var string
   */
  public $Token;

  /**
   * Class destructor saves cached data to file
   *
   * @return void
   */
  public function __destruct() {
    $this->WriteFile($this->FileName(), serialize($this->Data));
  } // function __destruct()

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @param array $Settings
   * @return void
   */
  protected function __construct( $Settings=array() ) {
    parent::__construct($Settings);

    $this->Token = !empty($Settings['Token']) ? $Settings['Token'] : md5(__FILE__);
    if (!empty($Settings['CacheDir'])) $this->CacheDir = $Settings['CacheDir'];

    // load cached data
    $file = $this->FileName();
    if (file_exists($file)) $this->Data = unserialize($this->ReadFile($file));
  } // function __construct()

  /**
   * Function FileName...
   *
   * @param string $key
   * @return string
   */
  protected function FileName() {
    return $this->CacheDir . DIRECTORY_SEPARATOR . $this->Token . '.cache';
  } // function FileName()

  /**
   * Function ReadFile...
   *
   * @param string $file
   * @return string
   */
  protected function ReadFile( $file ) {
    // php.net suggested I should use rb to make it work under Windows
    if (!$fp = @fopen($file, 'rb')) return;
    // Get a shared lock
    flock($fp, LOCK_SH);
    $buff = '';
    // Be gentle, so read in 4k blocks
    while ($tmp = fread($fp, 4096)) $buff .= $tmp;
    // Release lock
    flock($fp, LOCK_UN);
    fclose($fp);
    // Return
    return $buff;
  } // function ReadFile()

  /**
   * Function WriteFile...
   *
   * @param string $file
   * @param string $data
   * @return string
   */
  protected function WriteFile( $file, $data ) {
    $return = FALSE;
    // Lock file, ignore warnings as we might be creating this file
    $fpt = @fopen($file, 'rb');
    @flock($fpt, LOCK_EX);
    // php.net suggested I should use wb to make it work under Windows
    if ($fp = @fopen($file, 'wb+')) {
      fwrite($fp, $data, strlen($data));
      fclose($fp);
      $return = TRUE;
    }
    // Release lock
    @flock($fpt, LOCK_UN);
    @fclose($fpt);
    // Return
    return $return;
  } // function WriteFile()

}
