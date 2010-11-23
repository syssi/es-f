<?php

/**
 * Mostly observerable pattern
 */
interface EventI {

  public static function Attach( EventHandlerI $handler, $position=0 );

  public static function Dettach( EventHandlerI $handler );

  public static function Process( $event, &$params );

}