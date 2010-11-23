<?php

require_once dirname(__FILE__).'/forminput.class.php';

/**
 * Form checkbox input
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_FCB extends Yuelo_Extension_FormInput {

  /**
   * Form checkbox input
   *
   * @param string $name Input tag name
   * @param string $value Tag value
   * @param string $checked if equal to $value, checkbox is checked
   * @param string $class Tag class name
   * @param string $extra Extra tag code
   * @return string
   */
  public static function Process() {
    @list($name, $value, $checked, $class, $extra) = func_get_args();
    if (!$value) $value = 'on';
    if ($value AND $checked==$value) $extra .= ' checked="checked"';
    return parent::Process('checkbox', $name, $value, $class, $extra);
  }

}