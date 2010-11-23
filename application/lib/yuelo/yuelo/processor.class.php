<?php
/**
@defgroup Processors Processors classes

- Pre process
The pre processor will receive the template and can modify it before it's
parsed/compiled.

- Post process
Same thing than above, but post processors can act on the php source after_
the template has been compiled.

*/

// --------------------------------------------------------------------------

/**
 * Processor base class
 *
 * @ingroup  Processors
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Processor {

  /**
   * Pre processing method
   *
   * @param string $page HTML template content before compilation
   * @return string
   */
  public function PreProcess( &$page ) {}

  /**
   * Post processing method
   *
   * @param string $page HTML template content after compilation
   * @return string
   */
  public function PostProcess( &$page ) {}

}