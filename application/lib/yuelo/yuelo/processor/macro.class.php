<?php
/**
 * Pre processor to handle macro definitions
 *
 * Define HTML snippets which are usable in several templates
 *
 * Usage example:
 * Macro syntax in template (always uppercase):
 * @code
 * \@[A-Z]+\@
 * @endcode
 *
 * @usage
 * @code
 * PHP code:
 * // define common table row colors for even/odd rows,
 * // (assumes 2 CSS definitions for tr.tr1 and tr.tr2)
 * $m = new Yuelo_Processor_Macro;
 * $m->Define('TRCLASS', '{cycle:"tr","tr1","tr2"}');
 * ...
 * Yuelo_Compiler::getInstance()->RegisterProcessor($m);
 *
 * Template:
 * \<tr class="@TRCLASS@" ...
 * @endcode
 *
 *
 * @ingroup  Processors
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Processor_Macro extends Yuelo_Processor {

  /**
   * Define macros for often used code sequences
   *
   * @param string $name Macro name
   * @param string $code Replacement code
   */
  public function Define( $name, $code ) {
    $this->Macros[strtoupper($name)] = $code;
  }

  /**
   * Remove macro
   *
   * @param string $name Macro name (lowercase)
   */
  public function Remove( $name ) {
    if ($position = array_search(strtoupper($name), $this->Macros, TRUE))
      unset($this->Macros[$position]);
  }

  /**
   * Description of extension ...
   *
   * All parameters:
   * @param string &$page Template code
   * @return void
   */
  public function PreProcess( &$page ) {
    $macros = $codes = array();
    foreach ($this->Macros as $macro=>$code) {
      $macros[] = '@'.$macro.'@';
      $codes[]  = $code;
    }
    $page = str_replace($macros, $codes, $page);
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  private $Marcos = array();

}