<?php

require_once dirname(__FILE__).'/options.class.php';

/**
 * Form drop down input
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('OPTIONS', array(1=>'V1', 2=>'V2'));
 *
 * Template:
 *   {fdd:"name",OPTIONS[,"default"[,"class"]]]}
 *
 * Output:
 *   &lt;select name="name" class="class"&gt;&lt;option value="1"&gt;V1&lt;/select&gt;...
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_FDD extends Yuelo_Extension {

  /**
   * Form drop down input
   *
   * @param string $name Input name
   * @param array  $options Input values
   * @param string $default Default (selected) value
   * @param string $class Tag class name
   * @param string $extra Extra tag code
   * @return string
   */
  public static function Process() {
    @list($name, $options, $default, $class, $extra) = func_get_args();
    if (!empty($class)) $class = 'class="'.$class.'"';
    $options = Yuelo_Extension_Options::Process($options, $default);
    return sprintf('<select name="%s" %s %s>%s</select>', $name, $class, $extra, $options);
  }

}