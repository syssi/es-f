<?php
/**
 *
 */

/**
 *
 */
class XML_Array_Exec extends XML_Array {

  /**
   *
   */
  public function XML2Array( $childs ) {

    if (!is_array($childs)) return $childs;

    $data = array();
    foreach ($childs as $child) {
      if ($child->Name == 'section') {
        $data[$this->map($child->Attributes->Name)] = $this->XML2Array($child->Childs);
      } elseif ($child->Name == 'cmd') {
        $data[$this->map($child->Attributes->Name)] = $child->CData;
      }
    }
    return $data;
  }
}