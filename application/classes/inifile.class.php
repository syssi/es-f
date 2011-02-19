<?php
/**
 * Extended parse ini file function
 *
 * Handles multiple line values
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
abstract class IniFile {

  /**
   * @var string $File
   */
  public static $File;

  /**
   *
   * @var string $Data
   */
  public static $Data;

  /**
   *
   * @var string $Error
   */
  public static $Error;

  /**
   *
   * @var string $SectionsUppercase
   */
  public static $SectionsUppercase = TRUE;

  /**
   *
   * @var string $CommentLine
   */
  public static $CommentLine = ';';

  /**
   *
   * @var string $debug
   */
  public static $debug = 0;

  /**
   * @param string $file
   * @param bool $sections
   * @return bool
   */
  public static function Parse( $file='', $sections=FALSE ) {
    if (!$file) $file = self::$File;
    $file = str_replace('/', DIRECTORY_SEPARATOR, $file);

    self::Reset();

    if (file_exists($file)) {
      if (is_bool($sections)) {
        $section =& self::$Data;
      } else {
        $section =& self::$Data[self::map($sections)];
      }

      $lines = file($file, FILE_IGNORE_NEW_LINES);

      if ($lines) {
        $var = FALSE;
        $constants = array( array_keys(get_defined_constants()),
                            array_values(get_defined_constants()) );

        foreach ($lines as $id => $line) {
          $line = trim($line);
        
          if (empty($line) OR (strpos($line[0], self::$CommentLine) === 0)) {
            if (isset($var)) {
              self::checkVar($var);
              unset($var);
            }
            continue;
          }
        
          self::debug(2, sprintf('Line %3d: %s', ($id+1), $line));

          if (preg_match('~^\[(.*?)\]$~', $line, $args)) {
            // section
            if ($sections !== FALSE) {
              $s = self::map($args[1]);
              self::debug(2, sprintf('Section: %s', $s));
              $section =& self::$Data[$s];
            }
            if (isset($var)) unset($var);
          } elseif (preg_match('~^(.*?)\s+=(\s+(.*?))?$~', $line, $args)) {
            self::debug(3, $args, TRUE);

            // 1. line of array definition
            if (preg_match('~^(.*?)\[(.*)\]$~', $args[1], $arr)) {
              self::debug(3, $arr, TRUE);
              if (empty($arr[2]))
                $var =& $section[$arr[1]][];
              else
                $var =& $section[$arr[1]][$arr[2]];
            } else
              $var =& $section[$args[1]];

            $val = isset($args[3]) ? trim($args[3]) : '';
            $var = str_replace($constants[0], $constants[1], $val);
          } elseif (isset($var)) {
            // another definition line
            $var .= ' ' . trim($line);
          } else {
            self::$Error = sprintf(__CLASS__.': Ini file format error in file "%s" in line %d: %s', $file, ($id+1), $line);
            return FALSE;
          }
        } // foreach

        if (isset($var)) self::checkVar($var);

      } else {
        self::debug(1, '[empty file]');
      }
      self::debug(1, self::$Data);
      return TRUE;
    } else {
      self::$Error = __CLASS__.': Missing file: '.$file;
      return FALSE;
    }
  }

  /**
   * Write data to file
   *
   * @param array $data
   * @param string $file File name
   */
  public static function Write( $data=FALSE, $file=FALSE ) {
    if (!$data) $data = self::$Data;
    if (!$file) $file = self::$File;
    $sections = $content = '';

    foreach ($data as $key => $item) {
      if (is_array($item)) {
        $sections .= "\n".'['.$key.']'."\n";
        foreach ($item as $key2 => $item2) {
          $sections .= $key2.' = '.self::getValue($item2)."\n";
        }
      } else {
        $content .= $key.' = '.self::getValue($item)."\n";
      }
    }
    $content .= $sections;

    if (($hd = @fopen($file, 'w'))) {
      fwrite($hd, $content);
      fclose($hd);
      return TRUE;
    } else {
      self::$Error = __CLASS__.': Can\'t open file ['.$file.'] for writing.';
      return FALSE;
    }
  }

  /**
   * Clean up and free some space
   */
  public static function Reset() {
    self::$File = self::$Error = NULL;
    self::$Data = array();
  }

  //-------------------------------------------------------------------------
  // Private
  //-------------------------------------------------------------------------

  /**
   * Transform value in correct format, trim also " or ' from strings
   *
   * @param string &$var
   */
  private static function checkVar( &$var ) {
    $uval = strtoupper($var);
    // by default, do nothing
    switch (TRUE) {
      case ($uval == 'TRUE'):
        $var = TRUE;                break;
      case ($uval == 'FALSE'):
        $var = FALSE;               break;
      case ($uval == 'NULL'):
        $var = NULL;                break;
      case preg_match('~^[-\d]+$~', $var):
        $var = intval($var);        break;
      case preg_match('~^(\'|")(.*)\\1$~', $var, $val):
        // trim "..." or '...'
        $var = $val[2];            break;
    }
  }

  /**
   * Find out from value type the correct "write out" value
   *
   * @param mixed $value Value to analyse
   */
  private static function getValue( $value ) {
    switch (TRUE) {
      case ($value === TRUE):   $value = 'TRUE';            break;
      case ($value === FALSE):  $value = 'FALSE';           break;
      case ($value === NULL):   $value = 'NULL';            break;
      case is_numeric($value):
      case is_float($value):    /* leave as is */           break;
      default:                  $value = '"'.addslashes($value).'"';
    }
    return $value;
  }

  /**
   *
   * @param string $section
   */
  private static function map( $section ) {
    return self::$SectionsUppercase ? strtoupper($section) : strtolower($section);
  }
  
  /**
   *
   * @param string $level
   * @param string $data
   * @param string $remove0
   */
  private static function debug( $level, $data, $remove0=FALSE ) {
    if (self::$debug < $level) return;

    if ($remove0) unset($data[0]);

    if (function_exists('_dbg'))
      _dbg($data);
    else
      echo '<pre>', htmlspecialchars(print_r($data, TRUE)), '</pre>';
  }

}