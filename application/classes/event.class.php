<?php
/**
 * Event handling
 *
 * @package Event
 */

/**
 * Events handling class
 */
abstract class Event implements EventI {

  // >> Debug
  /**
   * Shorten debugging parameter to this length
   *
   * @var int
   */
  public static $DBGLEN = 100;
  // << Debug

  /**
   *
   */
  public static function Attach( EventHandlerI $handler, $position=0 ) {
    // DebugStack::StartTimer(__METHOD__, __METHOD__, 'attach event');

    // make sure not overwrite an existing handler, find next free position
    while (isset(self::$EventHandlers[$position])) $position++;

    self::$EventHandlers[$position] = $handler;
    ksort(self::$EventHandlers);

    // store handled events for better performance
    self::$HandlerMethods[$position] = array_change_key_case(array_flip($handler->handles()));

    /* ///
    foreach (self::$HandlerMethods[$position] as $key=>$value)
      if (!method_exists($handler, $key))
        Message::Error('Missing: '.get_class($handler).'->'.$key.'()');
    DebugStack::Info('Attached event handlers: '.count(self::$EventHandlers));
    /// */
    // DebugStack::StopTimer(__METHOD__);
  }

  /**
   *
   */
  public static function Dettach( EventHandlerI $handler ) {
    if ($position = array_search($handler, self::$EventHandlers, TRUE))
      unset(self::$EventHandlers[$position], self::$HandlerMethods[$position]);
    /// DebugStack::Info('Attached event handlers: '.count(self::$EventHandlers));
  }

  /**
   * Process all registered functions for an Event
   *
   * @public
   * @static
   * @param string $event Event name
   * @param array $params Parameters for event notification
   */
  public static function Process( $event, &$params ) {
    $event = strtolower($event);
    // DebugStack::StartTimer($event);

    if (!isset(self::$BlockedEvents[$event])) {
      foreach (self::$EventHandlers as $position=>$EventHandler) {
        if (!isset(self::$HandlerMethods[$position][$event])) continue;
        // >> Debug
        DebugStack::Info(get_class($EventHandler).'->'.$event.'('.DebugStack::format($params).')');
        // << Debug
        $EventHandler->$event($params);
      }
    }
    /// else DebugStack::Info('Skip "'.$event.'", actually blocked.');
    // DebugStack::StopTimer($event);
  }

  /**
   * Process all registered functions for an event
   *
   * Don't manipulate the parameter and returns the result
   *
   * @public
   * @static
   * @param string $event Event name
   * @param array $params Parameters for event notification
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
   * @public
   * @static
   * @param string $event Event name
   * @return void
   */
  public static function ProcessInform() {
    $params = func_get_args();
    // shift out Event name
    $event = array_shift($params);
    self::Process($event, $params);
  }

  /**
   * Block an event, that should not executed (at the moment)
   *
   * @public
   * @static
   * @param string $event Event name
   * @return void
   */
  public static function Block( $event ) {
    self::$BlockedEvents[strtolower($event)] = TRUE;
  }

  /**
   * Unblock an event
   *
   * @public
   * @static
   * @param string $event Event name
   * @return void
   */
  public static function unBlock( $event ) {
    $event = strtolower($event);
    if (isset(self::$BlockedEvents[$event]))
		  unset(self::$BlockedEvents[$event]);
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Array of handlers
   *
   * @var array
   */
  private static $EventHandlers = array();

  /**
   * Array of handler methods
   *
   * @var array
   */
  private static $HandlerMethods = array();

  /**
   * @var array
   */
  private static $BlockedEvents = array();

}