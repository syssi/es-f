<?php
/**
 * XML object to ebayparser array
 *
 * @ingroup    XMLArray
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-80-g4acbac1 2011-02-15 22:22:16 +0100 $
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
      } elseif ($child->Name == 'pattern') {
        $data[$this->map($child->Attributes->Name)] = explode("\n", $this->getChildValue($child));
      } else { // Version, URL
        $name = $this->map($child->Attributes->Name);
        if ($name)
          $data[$this->map($child->Name)][$name] = $this->getChildValue($child);
        else
          $data[$this->map($child->Name)] = $this->getChildValue($child);
      }
    }
    return $data;
  }

}