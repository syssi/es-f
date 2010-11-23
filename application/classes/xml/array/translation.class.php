<?php
/**
 *
 */
class XML_Object_Translation extends XML_Object {

  /**
   *
   */
  public function ParseXMLFile( $XMLURI ) {
    $data = parent::ParseXMLFile($XMLURI);
    return $this->XML2Array($data);
  }

  /**
   *
   */
  protected function XML2Array( $childs ) {
    $data = array();
    foreach ($childs[0]->Childs as $child) {
      $d = array( 's' => $child->CData );
      if ($child->Attributes->Type) $d['type'] = $child->Attributes->Type;

      if (is_array($child->Childs))
        foreach ($child->Childs as $sp)
          $d[$sp->Name] = $sp->CData;

      $data[strtolower($child->Name)] = $d;
    }

    return array(
      'language'  => $childs[0]->Attributes->Language,
      'namespace' => $childs[0]->Attributes->Namespace,
      'phrases'   => $data
    );
  }

}