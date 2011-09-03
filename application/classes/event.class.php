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
 * @version    $Id: v2.4.1-75-g5ea615c 2011-02-11 21:42:26 +0100 $
 * @revision   $Rev$
 */
abstract class Event implements EventI {

  /**
   *
   */
  const BEFORE = '-';

  /**
   *
   */
  const AFTER = '+';

  /**
   * Attach a handler instance
   *
   * @param EventHandlerI $handler
   * @param int $position Force position in event queue if defined
   * @return int Position on which the handler was inserted
   */
  public static function Attach( EventHandlerI $handler, $position=0 ) {
    /// Yryie::StartTimer(__METHOD__, __METHOD__, 'attach event');

    // make sure not overwrite an existing handler, find next free position
    while (isset(self::$HandlerOrder[$position])) $position++;

    $name = strtolower(get_class($handler));

    self::$HandlerOrder[$position] = $name;
    self::$EventHandlers[$name] = new EventHandler($handler);
    ksort(self::$HandlerOrder);

    /* ///
    foreach ($handler->handles() as $method) {
      if (!method_exists($handler, $method)) {
        Messages::Error('Missing: '.get_class($handler).'->'.$method.'()');
      }
    }
    Yryie::Info('Attached event handlers: '.count(self::$EventHandlers));
    Yryie::StopTimer(__METHOD__);
    /// */
    return $position;
  }

  /**
   * Dettach a handler instance
   *
   * @param EventHandlerI $handler
   * @return bool Handler was found and dettached
   */
  public static function Dettach( EventHandlerI $handler ) {
    $name = strtolower(get_class($handler));
    if ($position = array_search($name, self::$HandlerOrder, TRUE)) {
      unset(self::$HandlerOrder[$position], self::$EventHandlers[$name]);
    }
    /// Yryie::Info('Attached event handlers: '.count(self::$EventHandlers));
    return ($position !== FALSE);
  }

  /**
   * Resort handlers in required order
   *
   * $name1 (before|after) $name2
   *
   * @param string $name1 Handler name 1
   * @param string $sequence Event::BEFORE|Event::AFTER
   * @param string $name2 Handler name 2
   */
  public static function setSequence( $name1, $sequence, $name2 ) {
    $name1 = strtolower($name1);
    $name2 = strtolower($name2);

    // init
    $pos1 = $pos2 = NULL;

    // determine positions of handlers
    foreach (self::$HandlerOrder as $pos=>$name) {
      switch ($name) {
        case $name1: $pos1 = $pos; break;
        case $name2: $pos2 = $pos; break;
      }
    }

    // Where both handlers found?
    if (is_null($pos1) OR is_null($pos2)) return;

    // Still in correct sequence ...
    if ($sequence == self::BEFORE AND $pos1 < $pos2 OR
        $sequence == self::AFTER  AND $pos1 > $pos2) return;

    // ... or re-order
    $tmp = array();
    if ($sequence == self::BEFORE) {
      // move handler 1 right before handler 2
      foreach (self::$HandlerOrder as $name) {
        if ($name == $name2) $tmp[] = self::$HandlerOrder[$pos1];
        if ($name != $name1) $tmp[] = $name;
      }
    } elseif ($sequence == self::AFTER) {
      // move handler 1 right after handler 2
      foreach (self::$HandlerOrder as $name) {
        if ($name != $name1) $tmp[] = $name;
        if ($name == $name2) $tmp[] = self::$HandlerOrder[$pos1];
      }
    }
    self::$HandlerOrder = $tmp;
  }

  /**
   * Process all registered functions for an Event
   *
   * @param string $event Event name
   * @param array $params Parameters for event notification
   */
  public static function Process( $event, &$params ) {
    /// $__event = $event;
    /// Yryie::StartTimer($__event);
    $event = strtolower($event);

    if (!isset(self::$BlockedEvents[$event]) OR !self::$BlockedEvents[$event]) {
      foreach (self::$HandlerOrder as $name) {
        if (self::$EventHandlers[$name]->handles($event)) {
          /// Yryie::Info(get_class(self::$EventHandlers[$name]->Handler)
          ///            .'->'.$__event.'('.Yryie::format($params).')');
          self::$EventHandlers[$name]->Handler->$event($params);
        }
      }
    }
    /// else Yryie::Info('Skip "'.$__event.'", actually blocked.');
    /// Yryie::StopTimer($__event);
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
   * Event handler names in defined order
   *
   * @var array $HandlerOrder
   */
  private static $HandlerOrder = array();

  /**
   * Handlers
   *
   * @var array $EventHandlers
   */
  private static $EventHandlers = array();

  /**
   * Blocked events
   *
   * @var array $BlockedEvents
   */
  private static $BlockedEvents = array();

}

/**
 *
 */
class EventHandler {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Handler instance
   *
   * @var EventHandlerI $Handler
   */
  public $Handler;

  /**
   * Class constructor
   *
   * @param EventHandlerI $handler
   */
  public function __construct( EventHandlerI $handler ) {
    $this->Handler = $handler;
    // store handled events for better performance
    $this->_handles = array_change_key_case(array_flip($handler->handles()));
  }

  /**
   * Check if the handler handles the given event
   *
   * @param string $event
   */
  public function handles( $event ) {
    return isset($this->_handles[strtolower($event)]);
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * The handled events by the handler
   *
   * @var array $_handles
   */
  protected $_handles = array();

}