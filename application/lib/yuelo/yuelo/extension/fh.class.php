<?php

require_once dirname(__FILE__).'/forminput.class.php';

/**
 * Form hidden input field
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_FH extends Yuelo_Extension_FormInput {

  /**
   * Form hidden input field
   *
   * @param string $name Input tag name
   * @param string $value Tag value
   * @return string
   */
  public static function Process() {
    @list($name, $value) = func_get_args();
    return parent::Process('hidden', $name, $value);
  }

}