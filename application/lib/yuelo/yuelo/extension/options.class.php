<?php
/**
 * Creates HTML DropDown Option list from an array
 *
 * Usage Example I:
 * @code
 * Content:
 *   $template->assign('PICK', array( "on", "off" ) );
 *
 * Template:
 *   Choose: &lt;select name="onoff"&gt; {options:PICK} &lt;/select&gt;
 *
 * Result:
 *   Choose: &lt;select name="onoff"&gt; &lt;option&gt;on&lt;/option&gt;&lt;option&gt;off&lt;/option&gt; &lt;/select&gt;
 * @endcode
 *
 * with forced key values:
 * @code
 * Template:
 *   Choose: &lt;select name="onoff"&gt; {options:PICK,,"true"} &lt;/select&gt;
 *
 * Result:
 *   Choose: &lt;select name="onoff"&gt; &lt;option value="0"&gt;on&lt;/option&gt;&lt;option value="1"&gt;off&lt;/option&gt; &lt;/select&gt;
 * @endcode
 *
 * Usage Example II:
 * @code
 * Content:
 *   $template->assign('color',   array( "FF0000" => "Red", "00FF00" => "Green", "0000FF" => "Blue" ) );
 *   $template->assign('default', "00FF00" );
 *
 * Template:
 *   Color: &lt;select name="col"&gt; {options:color,default} &lt;/select&gt;
 *
 * Result:
 *   Color: &lt;select name="col"&gt; &lt;option value="FF0000"&gt;Red&lt;/option&gt;&lt;option value="00FF00" selected&gt;Green&lt;/option&gt;&lt;option value="0000FF"&gt;Blue&lt;/option&gt; &lt;/select&gt;
 * @endcode
 *
 * Creates HTML DropDown Option list from an constant list,
 * numeric intervals optional with stepping
 *
 * Usage Example III:
 * @code
 * Template:
 *   {options:"1990-2000"}
 *   {options:"1990-2000","1992",,"2"}
 *   {options:"red,green,blue"}
 *
 * Result:
 *   &lt;option&gt;1990&lt;/option&gt;&lt;option&gt;1991&lt;/option&gt;...
 *   &lt;option&gt;1990&lt;/option&gt;&lt;option selected&gt;1992&lt;/option&gt;...
 *   &lt;option&gt;red&lt;/option&gt;&lt;option&gt;green&lt;/option&gt;&lt;option&gt;blue&lt;/option&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Options extends Yuelo_Extension {

  /**
   * Creates HTML DropDown Option list from an array
   *
   * @param mixed $params Parameter array or list
   * @param string $default Default (selected) value
   * @param bool $force Force integer keys
   * @param int $step Step width
   * @return string
   */
  public static function Process() {
    @list($params, $default, $force, $step) = func_get_args();
    if (empty($params)) return '';

    if (!$default) $default = '_DEFAULT_';
    if (!$step) $step = 1;

    if (!is_array($params)) {
      if (preg_match('~^([0-9]+)-([0-9]+)$~', $params, $args)) {
        $params = array();
        if ($args[1] <= $args[2])
          for ($i=$args[1]; $i<=$args[2]; $i+=$step) $params[$i] = $i;
        else
          for ($i=$args[1]; $i>=$args[2]; $i-=$step) $params[$i] = $i;
      } else {
        $params = explode(',', $params);
      }
    }

    $output = '';
    $index  = 0;

    foreach ($params as $key => $value) {
      $value = trim($value);
      if (!$force AND $key == $index++ AND is_numeric($key)) {
        $key = $value;
        $selected = ($value == $default) ? ' selected="selected"' : '';
      } else {
        $selected = ($key == $default) ? ' selected="selected"' : '';
      }
      $output .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, htmlspecialchars($value));
    }

    return $output;
  }

}