<?php
/**
@defgroup  Filters Filters classes

Filterss handle exact one paramemter and can be queued.

For a perfect not case sensitive hash you can use

@code
{VAR|trim|lower|hash}
@endcode
*/

// --------------------------------------------------------------------------

/**
 * Filter base class
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
abstract class Yuelo_Filter {

  /**
   * Abstract processing method
   *
   * @param mixed $param
   * @return string
   */
  public static function Process( $param ) {}

}