<?php
/**
 *
 */

/**
 * Command line params
 */
abstract class CLP {

  /**
   *
   */
  public static $Error;

  /**
   *
   */
  public static function setOption( $name, $opt ) {
    $option = array_merge( array(
      'short' => '',
      'long'  => '',
      'opt'   => 'n',
      'help'  => '',
    ), $opt);

    if (!$option['short'] AND !$option['long'])
      throw new Exception(__CLASS__.': Missing "short" or "long" value for "'.$name.'"');

    if (strpos('yno', $option['opt']) === FALSE)
      throw new Exception(__CLASS__.': Wrong "opt" value for "'.$name.'" (y|n|o)');

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
          throw new Exception(__CLASS__.': Unknown parameter: '.$args[$i]);
      } elseif ($lastArg AND $lastArg['opt'] != 'n') {
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
   * @var array
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