<?php

/**
 * Mostly oberver pattern
 */
interface EventHandlerI {

  /**
   * Will be called to detect which events are handled by a plugin
   *
   * @return array Array of event names handled by plugin
   */
  public function handles();

}