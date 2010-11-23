<?php
/**
 * Return a default value if variable is empty
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('IMAGE1', 'image.gif');
 *
 * Template:
 *   &lt;img src="{nvl:IMAGE1,"pix.gif"}"&gt; / &lt;img src="{nvl:IMAGE2,"pix.gif"}"&gt;
 *
 * Result:
 *   &lt;img src="image.gif"&gt; / &lt;img src="pix.gif"&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_NVL extends Yuelo_Extension {

  /**
   * Return a default value if variable is empty
   *
   * @param string $param
   * @param string $default
   * @return string
   */
  public static function Process() {
    @list($param, $default) = func_get_args();
    return (strlen($param)) ? $param : $default;
  }

}