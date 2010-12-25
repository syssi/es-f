<?php

/**
 * The Event is mostly the observerable pattern
 *
 * @ingroup    Event
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
interface EventI {

  public static function Attach( EventHandlerI $handler, $position=0 );

  public static function Dettach( EventHandlerI $handler );

  public static function Process( $event, &$params );

}