<?php
/**
 * Pre processor to handle block definitions
 *
 * Define HTML code once for multiple use inside the same template,
 * e.g. pagination links on top and bottom of page
 *
 * Usage example:
 * @code
 * {BEGIN BLOCK xvy}
 * ...
 * {END BLOCK xyz}
 * ...
 * {BLOCK xyz}
 * ...
 * {BLOCK xyz}
 * @endcode
 *
 * @ingroup  Processors
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Processor_Block extends Yuelo_Processor {

  /**
   * Description of extension ...
   *
   * All parameters:
   * @param string &$page Template code
   * @return void
   */
  public function PreProcess( &$page ) {

    list($ControlBegin, $ControlEnd) = Yuelo_Compiler::getInstance()->getControlDelimiters();

    $regex = sprintf(
    //                   1        2
      '~%1$sBEGIN BLOCK (\w+)%2$s(.*)%1$sEND BLOCK \\1%2$s~s',
      preg_quote($ControlBegin, '~'), preg_quote($ControlEnd, '~')
    );

    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        // remove block definition
        $page = str_replace($arg[0], '', $page);
        // replace block place holder
        $block = sprintf('%sBLOCK %s%s', $ControlBegin, $arg[1], $ControlEnd);
        $page = str_replace($block, $arg[2], $page);
      }
    }

    // Check for missing block definitions, always mark as error!
    $regex = sprintf('~%1$sBLOCK (\w+).*?%2$s~', $ControlBegin, $ControlEnd);
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        $code = sprintf('<span style="color:red">Missing block definition [%s]!</span>', $arg[1]);
        $page = str_replace($arg[0], $code, $page);
      }
    }


  }

}