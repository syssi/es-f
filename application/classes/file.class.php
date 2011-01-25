<?php
/**
 * Wrapper for some file functions
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
abstract class File {

  /**
   * Write data to a file with given mode
   *
   * @param string $file File name
   * @param mixed $content Data
   * @param string $mode Same as PHPs fopen()
   * @param string $implode If $content is an array, implode with this
   * @return Bytes written|FALSE on error
   */
  public static function write( $file, $content, $mode='w', $implode="\n" ) {
    if ($fh = @fopen($file, $mode)) {
      if (is_array($content)) $content = implode($implode, $content).$implode;
      $written = fwrite($fh, $content);
      fclose($fh);
      return $written;
    }
    return FALSE;
  }

  /**
   * Append data to a file
   *
   * @see write()
   * @param string $file File name
   * @param mixed $content Data
   * @param string $implode If $content is an array, implode with this
   * @return Bytes written|FALSE on error
   */
  public static function append( $file, $content="\n", $implode="\n" ) {
    return self::write($file, $content, 'a', $implode);
  }

  /**
   * Delete a file
   *
   * @param string $file File name
   */
  public static function delete( $file ) {
    return @unlink($file);
  }

  /**
   * Touch a file
   *
   * @param string $file File name
   */
  public static function touch( $file ) {
    return @touch($file);
  }

  /**
   * Return file permissions
   *
   * @param string $file File name
   * @param boolean $octal Return in integer or octal
   * @return integer|octal
   */
  public static function permissions( $file, $octal=FALSE ) {
    if (!file_exists($file)) return FALSE;
    $perms = fileperms($file);
    $cut = $octal ? 2 : 3;
    return substr(decoct($perms), $cut);
  }

  /**
   * Get file modification time, return 0 if file not exists
   *
   * @param string $file File name
   * @return integer Timestamp
   */
  public static function MTime( $file ) {
    clearStatCache();
    return file_exists($file) ? filemtime($file) : 0;
  }

  /**
   * Get file size, return 0 if file not exists
   *
   * @param string $file File name
   * @return integer Timestamp
   */
  public static function Size( $file ) {
    clearStatCache();
    return file_exists($file) ? filesize($file) : 0;
  }

}
