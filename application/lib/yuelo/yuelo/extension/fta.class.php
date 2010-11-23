<?php
/**
 * Form textarea input
 *
 * @usage
 * <code>
 * Template:
 *   {fta:"name"[,"value"[,"class"]]}
 *
 * Output:
 *   &lt;textarea [class="class"] name="name"&gt;[value]&lt;/textarea&gt;
 * </code>
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_FTA extends Yuelo_Extension {

  /**
   * Form textarea input
   *
   * @param string $name Tag name
   * @param string $value Input value
   * @param string $class Class name for tag
   * @param string $extra Extra tag code
   * @return string
   */
  public static function Process() {
    @list($name, $value, $class, $extra) = func_get_args();
    if ($class) $class = 'class="'.$class.'"';
    return sprintf('<textarea name="%s" %s %s>%s</textarea>',
                   $name, $class, $extra, htmlentities(trim($value)));
  }

}