<?php
/**
 *
 */

/**
 *
 */
class cURL {

  /**
   * @var int
   */
  public $retry = 0;

  /**
   * Class constructor
   *
   * @param string $url First session can initilized here
   */
  public function __construct( $url='', $opts=array() ) {
    if ($url) $this->add($url, $opts);
  }
  /**
  * Adds a cURL session to stack
  *
  * @param $url string session's URL
  * @param $opts array optional array of cURL options and values
  * @return int Return the Session id for later use
  */
  public function add( $url, $opts=array() ) {
    $key = md5($url);
    $this->Sessions[$key] = curl_init( $url );
    if (count($this->Sessions) == 1) $this->default = $key;
    if (is_array($opts) AND !empty($opts)) $this->setOptArray($opts, $key);
    return $key;
  }

  /**
  * Sets an option to a cURL session
  *
  * @param int $option cURL option
  * @param mixed $value value of option
  * @param string $key session key returned by add
  * @return reference $this
  */
  public function setOpt( $option, $value, $key=NULL ) {
    if (!isset($key)) $key = $this->default;
    curl_setopt($this->Sessions[$key], $option, $value);
    return $this;
  }

  /**
  * Sets an array of options to a cURL session
  *
  * @param array $options array of cURL options and values
  * @param string $key session key returned by add
  * @return reference $this
  */
  public function setOptArray( $options, $key=NULL ) {
    if (!isset($key)) $key = $this->default;
    curl_setopt_array( $this->Sessions[$key], $options );
    return $this;
  }

  /**
   * Executes as cURL session
   *
   * @param string $key, session key returned by add
   * @return mixed FALSE if an error occured
   */
  public function exec( $key=FALSE ) {
    return (count($this->Sessions) == 1)
		     ? $this->execOne(NULL, $this->retry)
		     : ( $key !== FALSE
           ? $this->execOne($key, $this->retry)
           : $this->execAll()
           );
  }

  /**
  * Closes cURL Sessions
  *
   * @param string $key, session key returned by add
  */
  public function close( $key=NULL ) {
    if (!isset($key))
      foreach ($this->Sessions as $session) curl_close($session);
    else
      curl_close($this->Sessions[$key]);
  }

  /**
  * Returns an array of session information
  *
  * @param string $key session key returned by add
  * @param int $opt optional option to return
  */
  public function info( $key=NULL, $opt=0 ) {
    if (!isset($key))
      foreach ($this->Sessions as $key=>$session) {
        $info[$key] = curl_getinfo($session, $opt);
    else
      $info = curl_getinfo($this->Sessions[$key], $opt);
    return $info;
  }

  /**
   * Remove all cURL Sessions
   */
  public function clear() {
    $this->close();
    $this->Sessions = array();
  }

  /**
   * Returns an array of session error numbers
   *
   * @param string $key, session key returned by add
   * @return array of error codes
   */
  public function errorNo( $key=NULL ) {
    if (!isset($key))
      foreach( $this->Sessions as $session )
        $error[$key] = curl_errno($session);
    else
      $error = curl_errno($this->Sessions[$key]);
    return $error;
  }

  /**
   * Returns an array of errors
   *
   * @param string $key, session key returned by add
   * @return array of error messages
   */
  public function error( $key=NULL ) {
    if (!isset($key))
      foreach ($this->Sessions as $key=>$session)
        $error[$key] = curl_error($session);
    else
      $error = curl_error($this->Sessions[$key]);
    return $error;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   *
   */
  private $Sessions = array();

  /**
   * @var string
   */
  private $default;

  /**
   * Executes a single cURL session
   *
   * @param string $key, session key returned by add
   * @param int $retry # of retries
   * @return array of content if CURLOPT_RETURNTRANSFER is set
   */
  private function execOne( $key, $retry ) {
    if (!isset($key)) $key = $this->default;
    $code = 0;
    $res = FALSE;
    while ($retry-- >= 0 AND ($code[0] == 0 OR $code[0] >= 400 )) {
      $res = curl_exec($this->Sessions[$key]);
      $code = $this->info($key, CURLINFO_HTTP_CODE);
    }
    return $res;
  }

  /**
   * Executes a stack of Sessions
   *
   * @return array of content if CURLOPT_RETURNTRANSFER is set
   */
  private function execAll() {
    $mh = curl_multi_init();

    // Add all Sessions to multi handle
    foreach ($this->Sessions as $key=>$url)
      curl_multi_add_handle($mh, $this->Sessions[$key]);

    do
      $mrc = curl_multi_exec($mh, $active);
    while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
      if (curl_multi_select($mh) != -1) {
        do
          $mrc = curl_multi_exec($mh, $active);
        while ($mrc == CURLM_CALL_MULTI_PERFORM);
      }
    }

#    if ($mrc != CURLM_OK)
#      $this->Error = 'Curl multi read error: '.$mrc;

    // Get content foreach session, retry if applied
    foreach ($this->Sessions as $key=>$url) {
      $code = $this->info($key, CURLINFO_HTTP_CODE);
      $res[$key] = $code[0] > 0 && $code[0] < 400
			           ? curl_multi_getcontent($this->Sessions[$key])
			           : $this->execOne($key, $this->retry-1);
      curl_multi_remove_handle($mh, $this->Sessions[$key]);
    }
    curl_multi_close( $mh );
    return $res;
  }

}