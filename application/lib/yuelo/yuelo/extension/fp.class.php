<?php

require_once dirname(__FILE__).'/forminput.class.php';

/**
 * Form password input field
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_FP extends Yuelo_Extension_FormInput {

  /**
   * Form password input field
   *
   * @param string $name Input tag name
   * @param string $value Tag value
   * @param string $class Tag class name
   * @param string $extra Extra tag code
   * @return string
   */
  public static function Process() {
    @list($name, $value, $class, $extra) = func_get_args();
    return parent::Process('password', $name, $value, $class, $extra);
  }

}