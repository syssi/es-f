<?php
/**
 * Prints a annotaion marker, e.g. for required form fields
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('REQUIRED', 'Required field');

 * Template:
 *   {anno:}
 *   {anno:REQUIRED}
 *   {anno:"some info..."}
 *
 * Output:
 *   &lt;sup style="color:red"&gt;*&lt;/sup&gt;
 *   &lt;sup style="color:red"&gt;*&lt;/sup&gt; Required field
 *   &lt;sup style="color:red"&gt;*&lt;/sup&gt; some info...
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Anno extends Yuelo_Extension {

  /**
   * Prints a annotaion marker, e.g. for required form fields
   *
   * @param string $text Annotation text
   * @return string
   */
  public static function Process() {
    @list($text) = func_get_args();
    $code = '<sup style="color:red; font-weight:bold">*</sup>';
    if ($text) $code .= ' '.$text;
    return $code;
  }

}