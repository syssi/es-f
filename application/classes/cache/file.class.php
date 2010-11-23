<?php
/**
 * Class Cache_File
 *
 * description ...
 *
 * CHANGELOG
 * ---------
 * Version 1.1.0
 * - added locking
 *
 * @ingroup    Cache
 * @version    1.1.0
 */
class Cache_File extends Cache {

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
   * Function remove...
   *
   * @param string $key
   * @return void
   */
  public function remove( $key ) {
    parent::remove($key);
    $this->RemoveFile($this->FileName($key));
  } // function remove()

  /**
   * Function clear...
   *
   * @public
   * @return void
   */
  public function clear() {
    parent::clear();
    $files = glob($this->CacheDir.DIRECTORY_SEPARATOR.$this->Token.'.*.cache');
    foreach ($files as $file) $this->RemoveFile($file);
  } // function clear()

  /**
   * Class destructor saves cached data to file
   *
   * @return void
   */
  public function __destruct() {
    foreach ($this->Data as $key=>$data)
      $this->WriteFile($this->FileName($key), serialize(array($key,$data)));
  } // function __destruct()

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @public
   * @param array $options
   * @return CacheI
   */
  protected function __construct( $Settings=array() ) {
    parent::__construct($Settings);

    $this->Token = !empty($Settings['Token']) ? $Settings['Token'] : md5(__FILE__);
    if (!empty($Settings['CacheDir'])) $this->CacheDir = $Settings['CacheDir'];

    // load cached data
    $files = glob($this->CacheDir.DIRECTORY_SEPARATOR.$this->Token.'.*.cache');
    foreach ($files as $file) {
      $data = $this->ReadFile($file);
      $this->set($data[0], $data[1]);
    }
  }

  /**
   * Function FileName...
   *
   * @param string $key
   * @return string
   */
  protected function FileName( $key='' ) {
    if ($key) $key = '.'.md5(parent::map($key));
    return $this->CacheDir . DIRECTORY_SEPARATOR . $this->Token . $key . '.cache';
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

  /**
   * Function RemoveFile...
   *
   * @param string $file
   * @param string $data
   * @return string
   */
  protected function RemoveFile( $file ) {
    file_exists($file) && unlink($file);
  } // function RemoveFile()

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

}