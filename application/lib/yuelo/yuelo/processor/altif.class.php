<?php
/**
 * Pre processor to replace if / else / elseif / endif tags into template syntax
 *
 * Usage example:
 *
 * Before:
 * @code
 * <if ... >
 * ...
 * <elseif ... >
 * ...
 * <else>
 * ...
 * <endif> OR </if>
 * @endcode
 *
 * After:
 * @code
 * <!-- IF ... -->
 * ...
 * <!-- ELSEIF ... -->
 * ...
 * <!-- ELSE -->
 * ...
 * <!-- ENDIF -->
 * @endcode
 *
 * @ingroup  Processors
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Processor_AltIf extends Yuelo_Processor {

  /**
   * @param string &$page Template code
   * @return void
   */
  public function PreProcess( &$page ) {

    list($this->ControlBegin, $this->ControlEnd) =
      Yuelo_Compiler::getInstance()->getControlDelimiters();

    $page = preg_replace_callback('~<(else)?if\s+([^>]+)>~i', array($this, 'replace'), $page);
    $page = preg_replace('~<(else|endif)>~ie',
                         '$this->ControlBegin.strtoupper(\'$1\').$this->ControlEnd', $page);
    $page = preg_replace('~</if>~i', $this->ControlBegin.'ENDIF'.$this->ControlEnd, $page);
  }

  /**
   * Callback function
   */
  private function replace( $args ) {
    return $this->ControlBegin.strtoupper($args[1]).'IF '.$args[2].$this->ControlEnd;
  }

}