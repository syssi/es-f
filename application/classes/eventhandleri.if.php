<?php

/**
 * The EventHandler is mostly the oberver pattern
 *
 * @ingroup    Event
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 */
interface EventHandlerI {

  /**
   * Will be called to detect which
   * - actions are handled by a module
   * - events are handled by a plugin
   *
   * @return array Array of event names handled by handler
   */
  public function handles();

}