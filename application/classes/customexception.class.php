<?php
/** @defgroup CustomException Custom exception template
 *
 * Idea from http://www.php.net/manual/language.exceptions.php#91159
 *
 * If you intend on creating a lot of custom exceptions, you may find this code
 * useful.
 *
 * I've created an interface and an abstract exception class that
 * ensures that all parts of the built-in Exception class are preserved in child
 * classes. It also properly pushes all information back to the parent
 * constructor ensuring that nothing is lost. This allows you to quickly create
 * new exceptions on the fly.
 *
 * It also overrides the default __toString method
 * with a more thorough one.
 *
 * @version $Id$
 */

/**
 * Custom exception template interface
 *
 * @ingroup CustomException
*/
interface CustomExceptionI {

  /* Protected methods inherited from Exception class */
  public function getMessage();                 // Exception message
  public function getCode();                    // User-defined Exception code
  public function getFile();                    // Source filename
  public function getLine();                    // Source line
  public function getTrace();                   // An array of the backtrace()
  public function getTraceAsString();           // Formated string of trace

  /* Overrideable methods inherited from Exception class */
  public function __construct($message = null, $code = 0);
  public function __toString();                 // formated string for display
}

/**
 * Custom exception template
 *
 * @ingroup CustomException
 */
abstract class CustomException extends Exception implements CustomExceptionI {

  public function __construct($message = null, $code = 0) {
    if (!$message) throw new $this('Unknown '. get_class($this));
    parent::__construct($message, $code);
  }

  public function __toString() {
    return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
                            . "{$this->getTraceAsString()}";
  }

  protected $message = 'Unknown exception';     // Exception message
  protected $code    = 0;                       // User-defined exception code
  protected $file;                              // Source filename of exception
  protected $line;                              // Source line of exception

  private   $string;                            // Unknown
  private   $trace;                             // Unknown

}