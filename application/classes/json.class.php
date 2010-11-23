<?php
/**
 *
 */

abstract class JSON {
  /**
   * Transform a PHP value into JSON string format for javascript
   *
   * idea from http://php.net/manual/function.json-encode.php,
   * UCN by jjoss, 24-Oct-2007 09:07
   *
   * @param mixed $val Value
   * @return string JSON
   */
  public static function encode( $val ) {
    if (is_null($val)) {
      return 'null';
    } elseif ($val === FALSE) {
      return 'false';
    } elseif ($val === TRUE) {
      return 'true';
    } elseif (is_scalar($val)) {
      if (is_float($val)) {
        // Always use '.' for floats.
        $val = str_replace(',', '.', strval($val));
      }

      // All scalars are converted to strings to avoid indeterminism.
      // PHP's "1" and 1 are equal for all PHP operators, but
      // JS's "1" and 1 are not. So if we pass "1" or 1 from the PHP backend,
      // we should get the same result in the JS frontend (string).
      // Character replacements for JSON.
      static $jsonReplaces = array(
        array('\\',   '/',   "\n",  "\t",  "\r",  "\b",  "\f",  '"' ),
        array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
      );
      return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $val) . '"';
    }

    $isList = TRUE;
    $cnt = count($val);
    for ($i=0, reset($val); $i<$cnt; $i++, next($val)) {
      if (key($val) !== $i) {
        $isList = FALSE;
        break;
      }
    }
    $result = array();
    if ($isList) {
      foreach ($val as $v) $result[] = self::encode($v);
      return '[ ' . join(', ', $result) . ' ]';
    } else {
      foreach ($val as $k => $v) $result[] = self::encode($k).': '.self::encode($v);
      return '{ ' . join(', ', $result) . ' }';
    }
  }

  /**
   * idea from http://www.php.net/manual/en/function.json-decode.php
   * UCN by www at walidator dot info, 30-May-2009 02:16
   *
   * @param string $json JSON data
   * @return mixed
   */
  public static function decode( $json ) {
    $comment = FALSE;
    $data = '$return=';
    for ($i=0; $i<strlen($json); $i++) {
      if (!$comment) switch (TRUE) {
        case $json[$i] == '{':  $data .= ' array(';  break;
        case $json[$i] == '}':  $data .= ')';        break;
        case $json[$i] == ':':  $data .= '=>';       break;
        default:           $data .= $json[$i];
      } else
        $data .= $json[$i];
      if ($json[$i] == '"') $comment = !$comment;
    }
    eval($data . ';');
    return $return;
  }
}