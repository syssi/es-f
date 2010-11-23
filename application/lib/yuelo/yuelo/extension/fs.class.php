<?php

require_once dirname(__FILE__).'/forminput.class.php';

/**
 * Form submit button
 *
 * @usage
 * @code
 * Template:
 *   {fs:["value"[,"name"[,"class"[,"extra"]]]]}
 *   {fs:"Go"}
 *   {fs:}
 *
 * Output:
 *   &lt;input type="submit" name="name" value="value" class="class" extra&gt;
 *   &lt;input type="submit" value="Go"&gt;
 *   &lt;input type="submit"&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_FS extends Yuelo_Extension_FormInput {

  /**
   * Form submit button
   *
   * @param string $value Tag value
   * @param string $name Input tag name
   * @param string $class Tag class name
   * @param string $extra Extra tag code
   * @return string
   */
  public static function Process() {
    @list($value, $name, $class, $extra) = func_get_args();
    return parent::Process('submit', $name, $value, $class, $extra);
  }

}