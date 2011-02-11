<?php
/** @defgroup Event Event handling

Event handling is implemented like the observer / observed patterns.

This class processes the events and delegates them to the handlers registered
before.

*/

/**
 * Event handler
 *
 * @ingroup    Event
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-29-gacb4bc2 - Fri Jan 7 21:24:31 2011 +0100 $
 */
abstract class Event implements EventI {

  // >> Debug
  /**
   * Shorten debugging parameter to this length
   *
   * @var int $DBGLEN
   */
  public static $DBGLEN = 100;
  // << Debug

  /**
   * Attach a handler instance
   *
   * @param $handler EventHandlerI
   * @param $position Force position in event queue if defined
   */
  public static function Attach( EventHandlerI $handler, $position=0 ) {
    /// Yryie::StartTimer(__METHOD__, __METHOD__, 'attach event');

    // make sure not overwrite an existing handler, find next free position
    while (isset(self::$EventHandlers[$position])) $position++;

    self::$EventHandlers[$position] = $handler;
    ksort(self::$EventHandlers);

    // store handled events for better performance
    self::$HandlerMethods[$position] =
      array_change_key_case(array_flip($handler->handles()));

    /* ///
    foreach (self::$HandlerMethods[$position] as $key=>$value)
      if (!method_exists($handler, $key))
        Messages::Error('Missing: '.get_class($handler).'->'.$key.'()');
    Yryie::Info('Attached event handlers: '.count(self::$EventHandlers));
    Yryie::StopTimer(__METHOD__);
    /// */
  }

  /**
   * Dettach a handler instance
   *
   * @param $handler EventHandlerI
   */
  public static function Dettach( EventHandlerI $handler ) {
    if ($position = array_search($handler, self::$EventHandlers, TRUE))
      unset(self::$EventHandlers[$position], self::$HandlerMethods[$position]);
    /// Yryie::Info('Attached event handlers: '.count(self::$EventHandlers));
  }

  /**
   * Process all registered functions for an Event
   *
   * @param $event string Event name
   * @param $params array Parameters for event notification
   */
  public static function Process( $event, &$params ) {
    $event = strtolower($event);
    /// Yryie::StartTimer($event);

    if (!isset(self::$BlockedEvents[$event]) OR !self::$BlockedEvents[$event]) {
      foreach (self::$EventHandlers as $position=>$EventHandler) {
        if (!isset(self::$HandlerMethods[$position][$event])) continue;
        // >> Debug
        Yryie::Info(get_class($EventHandler).'->'.$event.'('.Yryie::format($params).')');
        // << Debug
        $EventHandler->$event($params);
      }
    }
    /// else Yryie::Info('Skip "'.$event.'", actually blocked.');

    /// Yryie::StopTimer($event);
  }

  /**
   * Process all registered functions for an event
   *
   * Don't manipulate the parameter and returns the result
   *
   * @param $event string Event name
   * @param $params array Parameters for event notification
   * @return mixed Using $params[0] as return value
   */
  public static function ProcessReturn( $event, $params=NULL ) {
    self::Process($event, $params);
    if (isset($params[0])) return $params[0];
  }

  /**
   * Process all registered handlers for an event
   *
   * For information about an event only, no further parameters required.
   * Mostly used for event 'Debug'
   *
   * @param $event string Event name
   * @param $params array Parameters for event notification
   * @return void
   */
  public static function ProcessInform( $event, $params=NULL ) {
    self::Process($event, $params);
  }

  /**
   * Block / un-block an event, that should not executed (at the moment)
   *
   * @param $event string Event name
   * @param $block bool block / un-block
   * @return void
   */
  public static function Block( $event, $block=TRUE ) {
    self::$BlockedEvents[strtolower($event)] = $block;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Event handler instances
   *
   * @var array $EventHandlers
   */
  private static $EventHandlers = array();

  /**
   * Handler methods
   *
   * @var array $HandlerMethods
   */
  private static $HandlerMethods = array();

  /**
   * Blocked events
   *
   * @var array $BlockedEvents
   */
  private static $BlockedEvents = array();

}