<?php
/**
 * XML object to array class
 *
 * @ingroup    XMLArray
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-29-gacb4bc2 - Fri Jan 7 21:24:31 2011 +0100 $
 */
abstract class XML_Array extends XML_Object implements XML_ArrayI {

  /**
   * Keys to lowercase
   *
   * @var bool $Key2Lower
   */
  public $Key2Lower = TRUE;

  /**
   * Class constructor
   *
   * @param Cache $cache Cache instance
   */
  public function __construct( Cache $cache ) {
    $this->Cache = $cache;
  }

  /**
   * Parses XML files into an array of arrays
   *
   * @param string $XMLURIs file name mask
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
   * @param string $XMLURI XML file name
   * @return array
   */
  public function ParseXMLFile( $XMLURI ) {
    /// $id = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $XMLURI);
    /// $cached = TRUE;
    /// Yryie::StartTimer($id, $id, 'parse XML file');
    while ($this->Cache->save($XMLURI, $array, -File::MTime($XMLURI))) {
      /// Yryie::Info('Parse: '.$XMLURI);
      /// $cached = FALSE;
      $data = parent::ParseXMLFile($XMLURI);
      $array = is_array($data) ? $this->XML2Array($data[0]->Childs) : '';
    }
    /// if ($cached) Yryie::Info('Cached: '.$XMLURI);
    /// Yryie::StopTimer($id);
    return $array;
  } // function ParseXMLFile()

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Map key name to common name
   *
   * @param string $name
   * @return string Uppercase or lowercase $name
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
   * Cache instance
   * @var Cache $Cache
   */
  private $Cache;

}
