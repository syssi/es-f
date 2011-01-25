<?php
/**
 * XML object to configuration definition array
 *
 * @ingroup    XMLArray
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class XML_Array_Definition extends XML_Array {

  /**
   *
   */
  public function XML2Array( $childs ) {

    if (!is_array($childs)) return $childs;

    $data = array();
    foreach ($childs as $child) {
      if ($child->Name == 'definition') {
        $type = substr($child->Attributes->Type, 0, 1);
        if (empty($type)) $type = 's';
        $data[$child->Attributes->Name] = array(
          'description' => $child->CData,
          'type'        => $type,
          'length'      => $child->Attributes->Length
                         ? $child->Attributes->Length
                         : ( $type=='i'
                             ? 5
                             : ( $type=='s'
                               ? 75
                               : 0
                               )
                           ),
          'option'      => $this->XML2Array($child->Childs),
        );
      } elseif ($child->Name == 'option') {
        $data[$child->Attributes->Value] = $child->CData;
      }
    }
    return $data;
  }
}
