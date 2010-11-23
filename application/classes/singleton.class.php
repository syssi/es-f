<?php

abstract class Singleton {

  public static function &get( $name, $args = array() )  {
  
    if (!isset(self::$instance)) self::$instance = array();

    $name = strtoupper($name);

    if (!isset(self::$instance[$name])) self::$instance[$name] = $name($args);

    return self::$instance[$name];
  }

  private static $instance;

}