<?php
/**
 * Build a MD5-hash over the provided parameter
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('LINKNAME', "Some Value with special chars...");
 *
 * Template:
 *   &lt;a href="#{LINKNAME|hash}"&gt;Go&lt;/a&gt;
 *   &lt;a name="{LINKNAME|hash}"&gt;&lt;/a&gt;
 *
 * Output:
 *   &lt;a href="#8b975ee878aa0aef7bd94cbf23388f83"&gt;Go&lt;/a&gt;
 *   &lt;a name="8b975ee878aa0aef7bd94cbf23388f83"&gt;&lt;/a&gt;
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_Hash extends Yuelo_Filter {

  /**
   * Build a MD5-hash over the provided parameter
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return md5(print_r($param, TRUE));
  }

}