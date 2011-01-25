<?php
/**
 * TMX Reader
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class TMX {

  /**
   * Class constructor
   *
   * @param $file string TMX (XML) file name
   * @param $language string ISO language identifier
   */
  public function __construct( $file, $language ) {
    // reset array
    $this->Data = array();
    // set selecteed language
    $this->Language = strtoupper($language);
    
    // creates a new XML parser to be used by the other XML functions
    $parser = xml_parser_create();
    // the following function allows to use parser inside object
    xml_set_object($parser, $this);
    // disable case-folding for this XML parser
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, FALSE);
    // sets the element handler functions for the XML parser
    xml_set_element_handler($parser, 'StartHandler', 'EndHandler');
    // sets the character data handler function for the XML parser
    xml_set_character_data_handler($parser, 'CDataHandler');
    // start parsing an XML document
    if(!xml_parse($parser, file_get_contents($file))) {
      throw new Exception(sprintf('ERROR '.__CLASS__.' :: XML error: %s at line %d',
                                  xml_error_string(xml_get_error_code($parser)),
                                  xml_get_current_line_number($parser)));
    }
    // free this XML parser
    xml_parser_free($parser);
  }

  /**
   * Sets the start element handler function for the XML parser
   *
   * @param $parser resource A reference to the XML parser calling the handler
   * @param $name string Name of the element for which this handler is called
   * @param $attribs array Array with the element's attributes (if any)
   */
  private function StartHandler( $parser, $name, $attribs ) {
    switch(strtolower($name)) {
      // ------------
      case 'header':
        // header section
        $this->Header = $attribs;
        break;
      // ------------
      case 'tu':
        // translation unit element
        if (isset($attribs['tuid']))
          $this->tuid = $attribs['tuid'];
        break;
      // ------------
      case 'tuv':
        // translation unit variant
        if (isset($attribs['xml:lang']))
          $this->xml_lang = strtoupper($attribs['xml:lang']);
        break;
      // ------------
      case 'prop':
        // prop element
        if (isset($attribs['type']))
          $this->propType = strtolower($attribs['type']);
        $this->curData = '';
        break;
      // ------------
      case 'note':
        // note element
        $this->isNote = TRUE;
        $this->curData = '';
        break;
      // ------------
      case 'seg':
        // segment, it contains the translated text
        $this->isSeg = TRUE;
        $this->curData = '';
        break;
    } // switch
  }

  /**
   * Sets the end element handler function for the XML parser
   *
   * @param $parser resource Reference to the XML parser calling the handler.
   * @param $name string Name of the element for which this handler is called
   */
  private function EndHandler( $parser, $name ) {
    switch(strtolower($name)) {
      // ------------
      case 'tu':
        // translation unit element
        $this->tuid = FALSE;
        break;
      // ------------
      case 'tuv':
        // translation unit variant
        $this->xml_lang = FALSE;
        break;
      // ------------
      case 'prop':
        // prop element
        if ($this->propType AND $this->curData !== '') {
          if (!$this->tuid) {
            // header props
            $this->Header[$this->propType] = $this->curData;
          } else {
            // find nouns definitions
            $m = strrpos($this->tuid, '-');
            if ($m !== FALSE)
              $this->tuid = substr($this->tuid, 0, $m);
            $this->Data[$this->tuid][$this->propType] = $this->curData;
          }
        }
        $this->propType = FALSE;
        break;
      // ------------
      case 'note':
        // note element
        if ($this->curData !== '') {
          // find nouns definitions
          $m = strrpos($this->tuid, '-');
          if ($m !== FALSE) {
            $this->tuid = substr($this->tuid, 0, $m);
          }
          $this->Data[$this->tuid]['note'] = $this->curData;
        }
        $this->isNote = FALSE;
        break;
      // ------------
      case 'seg':
        // segment, it contains the translated text
        if ($this->curData !== '') {
          $this->Data[$this->tuid][''] = $this->curData;
        }
        $this->isSeg = FALSE;
        break;
    }
  }

  /**
   * Sets the character data handler function for the XML parser
   *
   * @param $parser resource A reference to the XML parser calling the handler
   * @param $data string Contains the character data as a string
   */
  private function CDataHandler( $parser, $data ) {
    if ($this->isSeg AND $this->tuid AND $this->xml_lang == $this->Language OR
        $this->propType OR
        $this->isNote) {
      $this->curData .= $data;
    }
  }

  /**
   * Returns the header array containing the header attributes
   *
   * @return array
   */
  public function getHeader() {
    return $this->Header;
  }

  /**
   * Returns the resource array containing the translated word/sentences.
   *
   * @return array
   */
  public function getData() {
    return $this->Data;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * TMX Header attributes
   * @var array $Header
   */
  private $Header = array();

  /**
   * Key-Translation couples
   * @var array $Data
   */
  private $Data = array();

  /**
   * Current tu -> tuid value
   * @var string $tuid
   */
  private $tuid = FALSE;

  /**
   * Current tuv -> xml:lang value.
   * @var string $xml_lang
   */
  private $xml_lang = FALSE;

  /**
   * Current property type
   * @var string $propType
   */
  private $propType = FALSE;

  /**
   * Current data value.
   * @var string $curData
   */
  private $curData = FALSE;

  /**
   * Is TRUE when we are inside a note element
   * @var boolean $isNote
   */
  private $isNote = FALSE;

  /**
   * Is TRUE when we are inside a seg element
   * @var boolean $isSeg
   */
  private $isSeg = FALSE;

  /**
   * ISO language identifier
   * @var string $Language
   */
  private $Language = FALSE;

}
