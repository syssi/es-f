<?php
/**
 *
 * @package XMLParser
 *
 */

/**
 *
 * @package XMLParser
 *
 */
abstract class XML_Array extends XML_Object implements XML_ArrayI {

  /**
   *
   * @var bool
   */
  public $Key2Lower = TRUE;

  /**
   *
   * qparam $cache Cache
   */
  public function __construct( Cache $Cache ) {
    $this->Cache = $Cache;
  }

  /**
   * Parses XML files into an array of arrays
   *
   * @param string XML file name mask
   * @return array
   */
  public function ParseXMLFiles( $XMLURIs ) {
    $return = array();
    foreach (glob($XMLURIs) as $XMLURI)
      $return[] = $this->ParseXMLFile($XMLURI);
    return $return;
  } // function ParseXMLFiles()

  /**
   * Parses a XML file into an array
   *
   * @param string XML file name
   * @return array
   */
  public function ParseXMLFile( $XMLURI ) {
    /* ///
    $id = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $XMLURI);
    $cached = TRUE;
    DebugStack::StartTimer($id, $id, 'parse XML file');
    /// */
    while ($this->Cache->save($XMLURI, $array, -File::MTime($XMLURI))) {
      /* ///
      DebugStack::Info('Parse: '.$XMLURI);
      $cached = FALSE;
      /// */
      $data = parent::ParseXMLFile($XMLURI);
      $array = is_array($data) ? $this->XML2Array($data[0]->Childs) : '';
    }
    /* ///
    if ($cached) DebugStack::Info('Cached: '.$XMLURI);
    DebugStack::StopTimer($id);
    /// */
    return $array;
  } // function ParseXMLFile()

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   *
   */
  protected function map( $name ) {
    return $this->Key2Lower ? strtolower($name) : strtoupper($name);
  }

  /**
   *
   */
  protected function getChildValue( $child ) {
    switch ($child->Attributes->Type) {
      // ---------------------
      case 'i':
      case 'int':
      case 'integer':
        $value = (int)$child->CData;
        break;

      // ---------------------
      case 'f':
      case 'float':
        $value = (float)$child->CData;
        break;

      // ---------------------
      case 'b':
      case 'bool':
      case 'boolean':
        $v = strtoupper($child->CData);
        if ($v=='TRUE' OR $v=='YES' OR $v=='ON')
          $value = TRUE;
        elseif ($v=='FALSE' OR $v=='NO' OR $v=='OFF' OR $v=='NULL' OR $v=='' OR $v==0)
          $value = FALSE;
        else
          $value = (bool)$child->CData;
        break;

      // ---------------------
      case 'a':
      case 'array':
        $value = $this->XML2Array($child->Childs);
        break;

      // ---------------------
      default:
        $value = $child->CData;
    }
    return $value;
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   */
  private $Cache;

}
