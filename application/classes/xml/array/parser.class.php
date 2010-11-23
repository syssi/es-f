<?php
/**
 *
 */

/**
 *
 */
class XML_Array_Parser extends XML_Array {

  /**
   *
   */
  public function XML2Array( $childs ) {

    if (!is_array($childs)) return $childs;

    $data = array();
    foreach ($childs as $child) {
      if ($child->Name == 'section') {
        $data[$this->map($child->Attributes->Name)] = $this->XML2Array($child->Childs);
      } elseif ($child->Name == 'item') {
        $name = $child->Attributes->Name;
        if (!$child->Attributes->CaseSensitive) $name = $this->map($name);
        if ($name)
          $data[$name] = $this->getChildValue($child);
        else
          $data[] = $this->getChildValue($child);
      }
    }
    return $data;
  }
}