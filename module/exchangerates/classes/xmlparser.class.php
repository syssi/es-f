<?php
/**
 * XML parser
 *
 * Idea from http://www.php.net/manual/function.xml-parse.php#63871
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class XMLParser {

  /**
   * Parsed data
   *
   * @var array $data
   */
  public $data = array();

  /**
   * Load data from file / URL etc.
   *
   * @param string $file
   */
  public function loadFile( $file ) {
    $xmldata = file_get_contents($file);
    return $this->parse($xmldata);
  }

  /**
   * Parse XML string
   *
   * @param string $XML
   */
  public function parse( $XML ) {
    $parser = xml_parser_create();
    xml_set_object($parser, $this);
    xml_set_element_handler($parser, 'tagOpen', 'tagClosed');
    xml_set_character_data_handler($parser, 'tagData');

    $strXmlData = xml_parse($parser, $XML);

    if(!$strXmlData) {
       die(sprintf('XML error: %s at line %d',
             xml_error_string(xml_get_error_code($parser)),
             xml_get_current_line_number($parser)));
    }

    xml_parser_free($parser);

    return $this->data;
   }

  /**
   * Called on each xml tree
   *
   * @param resource $parser
   * @param string $name
   * @param array $attrs
   */
  private function tagOpen( $parser, $name, $attrs ) {
    $tag = array( 'node' => $name, 'attr' => $attrs );
    array_push($this->data, $tag);
  }

  /**
   * Called on data for xml
   *
   * @param resource $parser
   * @param string $data
   */
  private function tagData($parser, $data) {
    if (trim($data)) {
      if(isset($this->data[count($this->data)-1]['value'])) {
        $this->data[count($this->data)-1]['value'] .= $this->parseXMLValue($data);
      } else {
        $this->data[count($this->data)-1]['value'] = $this->parseXMLValue($data);
      }
    }
  }

  //
  /**
   * Called when finished parsing
   *
   * @param resource $parser
   * @param string $name
   */
  function tagClosed($parser, $name) {
    $this->data[count($this->data)-2]['childs'][] = $this->data[count($this->data)-1];
    array_pop($this->data);
  }

  /**
   *
   * @param string $value
   */
  private function parseXMLValue( $value ) {
    return htmlentities($value);
  }

  /**
   *
   * @param array $tob
   */
  private function toXML( $tob=NULL ) {
    // return xml
    $result = '';

    if (!isset($tob)) $tob = $this->data;

    if (!isset($tob)) {
      echo 'XML Array empty...';
      return NULL;
    }

    $cnt = count($tob);
    for ($c=0; $c<$cnt; $c++) {
      $result .= '<'.$tob[$c]['node'];

      while (list($key, $value) = each($tob[$c]['attr'])) {
        $result .= sprintf(' %s="%s"', $key, $this->parseXMLValue($value));
      }

      $result .= '>';

      //assign node value
      if (isset($tob[$c]['value'])) {
        $result .= $tob[$c]['value'];
      }

      if (count($tob[$c]['childs'])) {
        $result .= "\r\n" . $this->toXML($tob[$c]['childs']) . '';
      }

      $result .= '</' . $tob[$c]['node'] . '>' . "\r\n";


    }
    return $result;
  }

  /**
   *
   * @param array $tob
   */
  public function getXML( $tob=NULL ) {
    return '<?xml version="1.0" ?'.'>' . "\r\n" . $this->toXML($tob);
  }

}

/**
examples below:

$xx = new xmlParser();
$data = $xx->loadFile("http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml");

$curr = array('EUR'=> array('rate'=>1));

foreach ($data[0]['childs'][2]['childs'][0]['childs'] as $c) {
  $curr[$c['attr']['CURRENCY']] = array('rate' => $c['attr']['RATE']);
}

ksort($curr);

echo '<pre>';

print_r($curr);

# print_r($data);

echo '</pre>';
*/