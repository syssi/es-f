<?php
/**
 * A purely implementation of a MemCache client in PHP
 *
 * http://www.phpclasses.org/package/4094-PHP-memcache-client-in-pure-PHP.html
 *
 * Adjusted for PHP 5:
 *
 * @b CHANGED
 * - define -> const internal
 * - var -> private
 * - removed constructor, made connect() compatible to memcache
 *
 * @b NEW
 * - delete()
 * - flush()
 * - increment()
 * - decrement()
 *
 * @ingroup    Cache
 * @author     Cesar D. Rodas (saddor@cesarodas.com)
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-64-gee8a889 2011-02-03 23:16:23 +0100 $
 */

/* *************************************************************************
   **  gMemCache                                                           *
   **  Cesar D. Rodas (saddor@cesarodas.com)                               *
   *************************************************************************
   **  A purely implementation of a MemCache client in php.                *
   **  With this class you could connect to a memcache server, store,      *
   **  get vars without download nothing more than this class.             *
   *************************************************************************
   **  Bugs Report at:                                                     *
   **      http://www.phclasses.org/gmemcache (in forums)                  *
   *************************************************************************
   **  If you are a window$ user you get a port of memcache here           *
   **  http://jehiah.com/projects/memcached-win32                          *
   *************************************************************************
   **  The author disclaims the copyright of this project                  *
   **  You are legaly free to do what you want with this code              *
   ************************************************************************* */

class gMemCache {

  const CONNECTED     = 0xF0;
  const DISCONNECTED  = 0x00;
  const IS_STRING     = 0x02;
  const IS_ARRAY      = 0x04;
  const IS_COMPRESSED = 0x08;

  const EOL           = "\r\n";

  private $host;
  private $port;
  private $status;
  private $socket;

  /*
   *  Connect to a memcache server.
   *  On fail return FALSE.
   */
  function connect($host, $port=0) {
    $this->host = $host;
    $this->port = $port;
    if ($this->isConnected()) return FALSE;
    $this->status = self::DISCONNECTED;
    if ($this->host == '' || $this->port == 0) return FALSE;
    $this->socket = fsockopen($this->host, $this->port);
    if ($this->socket !== FALSE) $this->status = self::CONNECTED;
    return $this->isConnected();
  }

  function isConnected() {
    return ($this->status == self::CONNECTED);
  }

  /*
   *  Read the content from of $name from memcache
   *  On fail return FALSE.
   */
  function get($name) {
    if (!$this->isConnected() ) return FALSE;
    $buf = '';
    fwrite($this->socket, 'get "'.$name.'"'.self::EOL);
    while ($c = fread($this->socket,2048))  {
      $buf .= $c;
      if ( substr($c,-5,3) == "END") break;
    }
    /* Getting first line */
    $lines = explode(self::EOL,$buf,2);
    $parts = explode(' ',$lines[0]);

    $value = substr($lines[1], 0, $parts[3]);

    if ($parts[2] & self::IS_COMPRESSED)
      $value = gzuncompress($value);

    if ($parts[2] & self::IS_ARRAY)
      $value = unserialize($value);

    return $value;
  }

  /*
   *  Set the var $name with the content $value
   *  into the $lifetime seconds (forever=0; max = 2592000 [30 days])
   *  Also can compress variables, for reduce network overhead.
   *
   *  On fail return FALSE.
   */
  function set($name, $value, $lifetime = 0, $compress = FALSE) {
    if (! $this->isConnected() ) return FALSE;

    $magic = $this->getVarType($value);
    if ( $magic == self::IS_ARRAY)
      $value = serialize($value);

    if ($compress) {
      $magic |= self::IS_COMPRESSED;
      $value = gzcompress($value);
    }

#   $len = strlen($value);
#   fwrite($this->socket, 'set "'.$name.'" '.$magic.' 0 '.$len.self::EOL.$value.self::EOL);

    $value = sprintf('set "%2$s" %3$s 0 %4$d %1$s%5$s%1$s', self::EOL, $name, $magic, strlen($value), $value);
    fwrite($this->socket, $value);

    $buf = '';
    while ($c = fread($this->socket, 2048))  {
      $buf .= $c;
      if (substr($c, -2, 2) == self::EOL) break;
    }
    return (trim($buf) == 'STORED');
  }

  /**
   *
   */
  public function delete($name) {
    if (!$this->isConnected()) return FALSE;
    fwrite($this->socket, 'delete "'.$name.'"'.self::EOL);
    return (trim($this->fetch()) == 'DELETED');
  }

  /**
   *
   */
  public function flush() {
    if (!$this->isConnected()) return FALSE;
    fwrite($this->socket, 'flush_all'.self::EOL);
    return (trim($this->fetch()) == 'OK');
  }

  /**
   *
   */
  public function increment($name, $value=1) {
    if (!$this->isConnected()) return FALSE;
    fwrite($this->socket, 'incr '.$name.' '.$value.self::EOL);
    $ret = trim($this->fetch());
    return ($ret != 'NOT_FOUND') ? $ret : NULL;
  }

  /**
   *
   */
  public function decrement($name, $value=1) {
    if (!$this->isConnected()) return FALSE;
    fwrite($this->socket, 'decr '.$name.' '.$value.self::EOL);
    $ret = trim($this->fetch());
    return ($ret != 'NOT_FOUND') ? $ret : NULL;
  }

  /*
   *  Disconnect from a memcache server.
   *  On fail return FALSE.
   */
  function disconnect() {
    if (!$this->isConnected()) return FALSE;
    fclose($this->socket);
  }

  /*
   *  This method return the type of the var.
   *  The possible results are self::IS_ARRAY (need serialize)
   *  or self::IS_STRING (do not need)
   */
  function getVarType( &$var ) {
    switch (gettype($var)) {
      case 'array':
      case 'object':
        $r = self::IS_ARRAY;
        break;
      default:
        $r = self::IS_STRING;
    }
    return $r;
  }

}
