<?php

/**
 * The EventHandler is mostly the oberver pattern
 *
 * @ingroup    Event
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
interface EventHandlerI {

  /**
   * Will be called to detect which events are handled by a plugin
   *
   * @return array Array of event names handled by handler
   */
  public function handles();

}