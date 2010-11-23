<?php
/**
 * Form begin tag
 *
 * @usage
 * @code
 * Template:
 *   {form:["action"[,"method"[,"name"[,"ExtraStuff"]]]]}
 *   {form:}
 *
 * Output:
 *   &lt;form name="name" action="action" method="method"&gt;
 *   &lt;form action="index.php" method="post" ExtraStuff&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Form extends Yuelo_Extension {

  /**
   * Form begin tag
   *
   * @param string $action Form action, default same file
   * @param string $method post/get
   * @param string $name Form name, e.g. for JS relevant
   * @param string $extra Extra tag code
   * @return string
   */
  public static function Process() {
    @list($action, $method, $name, $extra) = func_get_args();
    if (!$action) $action = preg_replace('~\?.*$~', '', $_SERVER['REQUEST_URI']);
    if (!$method) $method = 'POST';
    if ($name) $name = 'name="'.$name.'"';
    return sprintf('<form %s action="%s" method="%s" %s>',
                   $name, $action, $method, $extra);
  }

}