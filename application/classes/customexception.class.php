<?php
/**
 * idea from http://www.php.net/manual/en/language.exceptions.php
 * UCN by ask at nilpo dot com, 27-May-2009 07:19
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
 *
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