<?php
/**
 * Description of processor ...
 *
 * Usage example:
 * @code
 * @endcode
 *
 * @ingroup  Processors
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Processor_RemovePHP extends Yuelo_Processor {

  /**
   * Mask all PHP tags before template compiling
   *
   * @param string &$page Template code
   * @return void
   */
  public function PreProcess( &$page ) {
    $page = str_ireplace('<?php', '&lt;?php', $page);
    $page = str_ireplace('?'.'>', '?&gt;', $page);
  }

}