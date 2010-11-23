<?php
/**
 * Post processor to compress compiled template
 *
 * Usage example:
 * @code
 * @endcode
 *
 * @ingroup  Processors
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Processor_Compress extends Yuelo_Processor {

  /**
   * Compress compiled PHP code
   *
   * @param string &$page Template code
   * @return void
   */
  public function PostProcess( &$page ) {
    $pattern = array(
      '~\s*\?'.'>\s+<\?php\s+echo\s*~si' => ' echo \' \',',
      '~\s*\?'.'><\?php\s+echo\s*~si'    => ' echo ',
      '~\s*\?'.'>\s+<\?php\s*~si'        => ' ',
      '~\s*\?'.'>\s*<\?php\s*~si'        => '',
    );
  
    $page = preg_replace(array_keys($pattern), array_values($pattern), $page);
  }

}