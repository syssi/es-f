<?php

/**
 * XML parser
 *
 * Idea from http://php.net/function.xml_parse
 * UCN from james @at@ mercstudio dot Com dot nospam at 02-Apr-2006 09:10
 */
class XMLParser {

  public $data = array();

  public function loadFile( $file ) {
    $xmldata = file_get_contents($file);
    return $this->parse($xmldata);
  }

  public function parse($XML) {
    $parser = xml_parser_create ();
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

  // called on each xml tree
  function tagOpen($parser, $name, $attrs) {
    $tag = array( 'node' => $name, 'attr' => $attrs );
    array_push($this->data, $tag);
  }

  // called on data for xml
  function tagData($parser, $tagData) {
    if(trim($tagData)) {
      if(isset($this->data[count($this->data)-1]['value'])) {
        $this->data[count($this->data)-1]['value'] .= $this->parseXMLValue($tagData);
      } else {
        $this->data[count($this->data)-1]['value'] = $this->parseXMLValue($tagData);
      }
    }
  }

  // called when finished parsing
  function tagClosed($parser, $name) {
    $this->data[count($this->data)-2]['childs'][] = $this->data[count($this->data)-1];
/*
    if(count ($this->data[count($this->data)-2]['childs'] ) == 1) {
     $this->data[count($this->data)-2]['firstchild'] =& $this->data[count($this->data)-2]['childs'][0];
    }
*/
    array_pop($this->data);
  }

  function toArray() {
    //not used, we can call loadString or loadFile instead...
  }

  private function parseXMLValue( $value ) {
    return htmlentities($value);
  }

  private function toXML( $tob=NULL ) {
    // return xml
    $result = '';

    if ($tob == NULL) {
      $tob = $this->data;
    }

    if (!isset($tob)) {
      echo 'XML Array empty...';
      return null;
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

  public function getXML( $tob = null ) {
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