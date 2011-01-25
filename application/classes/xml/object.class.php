<?php
/** @defgroup XMLParser XML Parser classes

*/

/**
 * XML Object
 *
 * @ingroup    XMLParser
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class XML_Object {

  /**
   * Contains parser error
   */
  public $Error;

  /**
   *
   */
  public function ParseXMLFile( $XMLURI ) {
  
    // existing and readable uri?
    if (is_file($XMLURI) && is_readable($XMLURI)) {
      // parse data
      $this->URI = $XMLURI;
      $data = $this->ParseXMLString(@file_get_contents($XMLURI));
      unset($this->URI);
      return $data;
    } else {
      $this->Error = sprintf('Supplied argument [%s] is not a URI to a (readable) file.', $XMLURI);
      return FALSE;
    }
  }

  /**
   *
   */
  public function ParseXMLString( $XMLString ) {
    // clear previously parsed file and related variables
    $this->XMLArray = $this->Stack = array();
    $this->Error = '';
    // set up parser
    $Parser = xml_parser_create();
    // enable parser within object
    xml_set_object($Parser, $this);
    xml_parser_set_option($Parser, XML_OPTION_CASE_FOLDING, FALSE);
    xml_set_element_handler($Parser, '_TagOpen', '_TagClose');
    xml_set_character_data_handler($Parser, '_TagData');
    if (!xml_parse($Parser, $XMLString, TRUE)) {
      // inspect problems
      $this->Error = sprintf('%s%s [%d] on line %d, column %d',
                             ($this->URI ? $this->URI.': ' : ''),
                             xml_error_string(xml_get_error_code($Parser)),
                             xml_get_error_code($Parser),
                             xml_get_current_line_number($Parser),
                             xml_get_current_column_number($Parser)+1);
      return FALSE;
    }
    // free parser
    xml_parser_free($Parser);
    return $this->XMLArray;
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   * Last scanned file name
   */
  private $URI;

  /**
   * xml array from parsed data
   */
  private $XMLArray = array();

  /**
   *
   */
  private $Stack = array();

  /**
   *
   */
  private function _TagOpen( $Parser, $TagName, $TagAttributes ) {
    $Element = new XML_Element($TagName, $TagAttributes);
    $Cnt = count($this->Stack);

    if ($Cnt == 0) {
      $this->XMLArray[] = $Element;
    } else {
      $this->Stack[$Cnt-1]->Childs[] = $Element;
    }

    // add (push) actual element onto stack
    array_push($this->Stack, $Element);
  }

  /**
   *
   */
  private function _TagData( $Parser, $TagData ) {
    // append data, e.g. a 	<![CDATA[  line triggers an empty $TagData
    $this->Stack[count($this->Stack)-1]->CData .= trim($TagData);
  }

  /**
   *
   */
  private function _TagClose( $Parser, $TagName ) {
    // remove (pop) actual element from stack
    array_pop($this->Stack);
  }

}

/**
 * XML Element
 *
 * @ingroup    XMLParser
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class XML_Element {

  /**
   *
   */
  public $Name;

  /**
   *
   */
  public $Attributes;

  /**
   *
   */
  public $CData;

  /**
   *
   */
  public $Childs;

  /**
   *
   */
  public function __construct( $Name, $Attributes=array() ) {
    $this->Name = $Name;
    $this->Attributes = new XML_Attributes($Attributes);
  }

}

/**
 * XML Attributes
 *
 * @ingroup    XMLParser
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class XML_Attributes {

  /**
   *
   */
  public function __construct( $Attributes=array() ) {
    $this->_Attr = array_change_key_case($Attributes, CASE_LOWER);
  }

  /**
   *
   */
  public function __get( $Name ) {
    $Name = strtolower($Name);
    if (isset($this->_Attr[$Name])) {
      $value = $this->_Attr[$Name];
      $_value = strtoupper($value);
      switch (TRUE) {
        case $_value == 'TRUE':   $value = TRUE;   break;
        case $_value == 'FALSE':  $value = FALSE;  break;
        case $_value == 'NULL':   $value = NULL;   break;
      }
    } else {
      $value = NULL;
    }
    return $value;
  }

  /**
   *
   */
  public function get() {
    return $this->_Attr;
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   */
  private $_Attr = array();

}
