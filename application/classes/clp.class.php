<?php
/**
 * Command line parameters
 *
 * @ingroup    clp
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
abstract class CLP {

  /**
   * Set an single option
   *
   * @usage
   * @code
   * // a verbose parameter
   * CLP::setOption('verbose', array('short'=>'v', 'long'=>'verbose',
   *                                 'opt'=>1, 'help'=>'...'));
   * @endcode
   *
   * @param string $name Parameter name
   * @param array  $opt Array of settings, can contain the following parts
   *                    - 'short'
   *                    - 'long' at least one of short or long must be defined
   *                    - 'opt' parameter is optional (0/1)
   *                    - 'help' help text
   */
  public static function setOption( $name, $opt ) {
    $option = array_merge( array(
      'short' => '',
      'long'  => '',
      'opt'   => 1,
      'help'  => '',
    ), $opt);

    if (!$option['short'] AND !$option['long'])
      throw new CLPException(__CLASS__.': Missing "short" or "long" value for "'.$name.'"');

    if (strpos('01', $option['opt']) === FALSE)
      throw new CLPException(__CLASS__.': Wrong "opt" value for "'.$name.'" (0|1)');

    self::$options[$name] = $option;
  }

  /**
   *
   */
  public static function setOptions( $options ) {
    foreach ($options as $name=>$data) self::setOption($name, $data);
  }

  /**
   *
   */
  public static function analyzeArguments( $args, $ignoreUnknown=TRUE ) {
    if (!is_array($args)) $args = preg_split('~\s+~', $args);
    $return = array();
    $c = count($args);
    $lastArg = NULL;
    for ($i=0; $i<$c; $i++) {
      if ($args[$i] == '--') {
        unset($args[$i]);
        break;
      }

      if (substr($args[$i], 0, 1) == '-') {
        if ($lastArg = self::getParam($args[$i]))
          $return[$lastArg['name']] = '';
        elseif (!$ignoreUnknown)
          throw new CLPException(__CLASS__.': Unknown parameter: '.$args[$i]);
      } elseif ($lastArg AND $lastArg['opt'] != 1) {
        $return[$lastArg['name']] = $args[$i];
        $lastArg = NULL;
      } else {
        break;
      }
    }
    // shift parameters out, hold files
    for (; $i>0; $i--) array_shift($args);
    $return['--'] = $args;
    return $return;
  }

  /**
   *
   */
  public static function getHelp( $script, $help='', $option=TRUE, $file=TRUE ) {
    $lshort = $llong = 0;
    foreach (self::$options as $option) {
      $l = strlen($option['short']);
      if ($l > $lshort) $lshort = $l;
      $l = strlen($option['long']);
      if ($l > $llong) $llong = $l;
    }
    $return = 'Usage: '.$script;
    if ($option) $return .= ' [OPTION]...';
    if ($file) $return .= ' [FILE]...';
    $return .= "\n";
    if ($help) $return .= $help . "\n";
    $return .= "\n";

    foreach (self::$options as $option) {
      if ($option['short'])
        $line = '  -' . $option['short'] . str_repeat(' ', $lshort-strlen($option['short']));
      else
        $line = '   ' . str_repeat(' ', $lshort);
      $line .= ($option['short'] AND $option['long']) ? ', ' : '  ';

      if ($option['long'])
        $line .= '--' . $option['long'] . str_repeat(' ', $llong-strlen($option['long']));
      else
        $line .= '  ' . str_repeat(' ', $llong);

      $line .= '  ';

      $line .= $option['help'];

      $return .= $line . "\n";
    }

    return $return;
  }

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Defined parameters
   *
   * @var array $options
   */
  protected static $options = array();

  /**
   * @return array|void
   */
  protected static function getParam( $opt ) {
    if (substr($opt, 0, 2) == '--') {
      $short = NULL;
      $long  = substr($opt, 2);
    } elseif (substr($opt, 0, 1) == '-') {
      $short = substr($opt, 1);
      $long  = NULL;
    } else return;

    foreach (self::$options as $name => $option) {
      if ((isset($short) AND $option['short'] == $short) OR 
          (isset($long)  AND $option['long']  == $long )) {
        $option['name'] = $name;
        return $option;
      }
    }
  }
}

/**
 * Exception for CLP
 *
 * @ingroup clp
 */
class CLPException extends Exception {}