<?php

define( 'YUELO_BASE', dirname(__FILE__).DIRECTORY_SEPARATOR );

define( 'YUELO_BASE_PROCESSOR', YUELO_BASE.'yuelo'.DIRECTORY_SEPARATOR.'processor'.DIRECTORY_SEPARATOR );

/**
 * Check for required PHP version for autoloading
 */
if (function_exists('spl_autoload_register')) {

  /**
   * Autoload wrapper for Yuelo classes
   *
   * @param string $class Yuelo class to require
   */
  function __autoload_yuelo( $class ) {
    $path = YUELO_BASE . str_replace('_', DIRECTORY_SEPARATOR, strtolower($class));

    foreach (array('.class.php', '.if.php') as $file) {
      $file = $path . $file;
      if (file_exists($file)) {
        require_once $file;
        return;
      }
    }
  }

  /**
   * Register autoload function
   */
  spl_autoload_register('__autoload_yuelo');
  
  Yuelo::set('AutoLoad', TRUE);

} else {

  /**
   * Handle manually
   */
  require_once YUELO_BASE . 'yuelo.class.php';
  require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'adapter.class.php';
  require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'template.class.php';
  require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'compiler.class.php';
  require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'exception.class.php';
  require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'extension.class.php';
  require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'filter.class.php';
  require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'processor.class.php';
  require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'cache.class.php';

}
