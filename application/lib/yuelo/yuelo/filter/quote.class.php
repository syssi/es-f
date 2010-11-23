<?php
/**
 * Quote string for usage in HTML tag values
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('TITLE', 'The "Ttile"');
 *
 * Template:
 *   &lt;img src="..." title="{TITLE|quote}"&gt;
 *
 * Output:
 *   &lt;img src="..." title="The &amp;quot;Ttile&amp;quot;"&gt;
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_Quote extends Yuelo_Filter {

  /**
   * Quote string for usage in HTML tag values
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return htmlspecialchars($param);
  }

}