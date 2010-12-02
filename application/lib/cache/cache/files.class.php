<?php

require_once dirname(__FILE__).'/filebase.class.php';

/**
 * Class Cache_Files
 *
 * Store data for each id in a separate file, recommended for large data sets
 * The data are not in memory, they will read each time they are required.
 *
 * The following settings are supported:
 * - token    : used to build unique cache ids (optional)
 * - cachedir : Where to store the file with the cached data (optional)
 *
 * CHANGELOG
 * ---------
 * Version 1.1.0
 * - added locking
 *
 * @ingroup    Cache
 * @version    1.1.0
 */
class Cache_Files extends Cache_FileBase {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Function set...
   *
   * @param string $id
   * @param mixed $data
   * @param int $expire Timestamp for expiration, if set to 0, expire never
   * @return mixed
   */
  public function set( $id, $data, $expire=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      return $this->WriteFile($this->FileName($id), array($expire, $data));
    } elseif ($id !== NULL) { // AND $data === NULL
      return $this->delete($id);
    } else { // $id === NULL AND $data === NULL
      return $this->clear();
    }
  } // function set()

  /**
   * Function get...
   *
   * @param string $id
   * @return mixed
   */
  public function get( $id ) {
    if (!$data = $this->ReadFile($this->FileName($id))) return;

    if ($data[$id][0] === 0 OR // expire never
        $data[$id][0] >= $this->ts) return $data[$id][1];

    // else drop data for this key
    $this->delete($id);
  } // function get()

  /**
   * Function delete...
   *
   * @param string $id
   * @return void
   */
  public function delete( $id ) {
    return $this->RemoveFile($this->FileName($id));
  } // function delete()

  /**
   * Function flush...
   *
   * @return void
   */
  public function flush() {
    $ok = TRUE;
    // DOTs are required in file name to not delete cache file from Cache_File
    $files = glob($this->cachedir . DIRECTORY_SEPARATOR . $this->token . '\.*\.cache');
    // force remove and combine with existing result
    foreach ($files as $file) $ok = ($this->RemoveFile($file) AND $ok);
    return $ok;
  } // function flush()

}
