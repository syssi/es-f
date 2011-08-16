<?php
/**
 * Own session handling
 *
 * @ingroup    Plugin-Session
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Session extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('InitSession');
  }

  /**
   *
   */
  public function InitSession() {
    Session::setSavePath($this->Core['localpath']);
    Session::setHandler(array($this, 'open'),     array($this, 'close'),
                        array($this, 'read'),     array($this, 'write'),
                        array($this, 'destroy'),  array($this, 'gc'));
    register_shutdown_function(array($this, 'close'));

    // remove cookie domain on local hosts
    if (strpos($_SERVER['HTTP_HOST'], '.') === FALSE)
      ini_set('session.cookie_domain', '');

    /**
     * Correct the session handling on debian, where the automatic garbage
     * collection is disabled and handled via cron jobs!
     */
    ini_set('session.gc_probability',  10);
    ini_set('session.gc_divisor',     100);
    ini_set('session.gc_maxlifetime', $this->MaxLifeTime);
  }

  /**#@+
   * Function to register with session_set_save_handler()
   */

  /**
   * Open session function
   *
   * @param string $path
   * @param string $name
   * @return boolean Always TRUE
   */
  public function open( $path, $name ) {
    /// Yryie::Info($name . ' from ' . $path);
    return TRUE;
  }

  /**
   * Read session data 
   *
   * @param string $id Session id
   * @return string
   */
  public function read( $id ) {
    $file = $this->FileName($id);
    /// Yryie::Info($id);
    /// Yryie::Info(sprintf('%s (%s)', $file, date('r', @filemtime($file))));
    touch($file);
    $content = (string)file_get_contents($file);
    if ($content) {
      if ($this->Encrypt) $content = $this->decode($content);
      $content = unserialize($content);
    }
    /// Yryie::Info($content);
    return $content;
  }

  /**
   * Write session data
   *
   * @param string $id Session id
   * @param string $content
   * @return boolean Write succes
   */
  public function write( $id, $content ) {
    /// Yryie::Info($content . ' to ' . $id);
    if ($fp = @fopen($this->FileName($id), 'w')) {
      if ($content) {
        $content = serialize($content);
        if ($this->Encrypt) $content = $this->encode($content);
      }
      $return = fwrite($fp, $content);
      fclose($fp);
      return $return;
    } else {
      return FALSE;
    }
  }

  /**
   * Close session
   * 
   * @return boolean Always TRUE
   */
  public function close() {
    /// Yryie::Info('Close session');
    return TRUE;
  }

  /**
   * Destroy session
   *
   * @param string $id Session id
   * @return boolean
   */
  public function destroy( $id ) {
    /// Yryie::Info($id);
    return @unlink($this->FileName($id));
  }

  /**
   * Perform session garbage collection
   *
   * @param intger $maxlifetime
   * @return boolean Always TRUE
   */
  public function gc( $maxlifetime ) {
    /// Yryie::Info($maxlifetime);
    $now = time();
    foreach (glob($this->FileName('*')) as $file) {
      if (filemtime($file)+$maxlifetime < $now) @unlink($file);
    }
    return TRUE;
  }
  /**#@-*/

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   * Build session file name
   *
   * @param string $id Session ID
   */
  private function FileName( $id ) {
    return session_save_path() . '/' . $id . '.esf';
  }

  /**
   *
   */
  private function encode( $str ) {
    return $this->str_rot(base64_encode($str), 8);
  }

  /**
   *
   */
  private function decode( $str ) {
    return base64_decode($this->str_rot($str, -8));
  }

  /**
   * http://www.php.net/manual/en/function.str-rot13.php
   * UCN by shaunspiller at spammenotgmail dot com at 26-Sep-2009 03:54
   */
  private function str_rot($s, $n = 13) {
    $n = (int)$n % 26;
    if (!$n) return $s;
    for ($i = 0, $l = strlen($s); $i < $l; $i++) {
      $c = ord($s[$i]);
      if ($c >= 97 && $c <= 122) {
        $s[$i] = chr(($c - 71 + $n) % 26 + 97);
      } else if ($c >= 65 && $c <= 90) {
        $s[$i] = chr(($c - 39 + $n) % 26 + 65);
      }
    }
    return $s;
  }
}

Event::attach(new esf_Plugin_Session);