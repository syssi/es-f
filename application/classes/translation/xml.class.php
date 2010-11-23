<?php
/**
 * @package    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @since      File available since Release 0.0.1
 */
abstract class Translation_XML {

  /**
   * Load translations from XML files
   *
   * @param string $XMLURIs Files pattern
   */
  public static function LoadXMLFiles( $XMLURIs ) {
    foreach (glob($XMLURIs) as $XMLURI) self::LoadXMLFile($XMLURI);
  }

  /**
   * Load translations from XML file
   *
   * @param string $XMLURI
   */
  public static function LoadXMLFile( $XMLURI ) {
    $xml = new XML_Array_Translation(Cache::getInstance());
    // make keys uppercase
    $xml->Key2Lower = FALSE;

    $trans = $xml->ParseXMLFile($XMLURI);

    if (!$trans) throw new Exception($xml->Error);

    foreach ($trans as $Namespace => $translations) {
      foreach ($translations as $key => $data) {
        $str = $data['data'];

        if ($filter = strtolower($data['filter'])) {

          $isHTML = FALSE;

          if ($filter == 'html') {
            // text contains html tags
            $isHTML = TRUE;
          } elseif ($filter == 'nl2br') {
            $str = nl2br($str);
            // un-HTML
            $str = str_replace(self::$Masks[0], self::$Masks[1], $str);
          } elseif ($filter == 'p') {
            $str = str_replace("\n\n", '</p><p>', $str);
            $str = '<p>' . nl2br($str) . '</p>';
            // un-HTML
            $str = str_replace(self::$Masks[0], self::$Masks[1], $str);
          } elseif ($filter == 'file') {
            // include text file
            $str = file_exists($str)
                 ? file_get_contents($str)
                 : Messages::toStr('ERROR: Missing translation file ['.$str.']', Messages::ERROR);
            // file is by default interpreted as HTML!
            $isHTML = TRUE;
          }

          if (!$isHTML) {
            $str = htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
            $str = str_replace('"', '&quot;', $str);
          }

          // re-HTML
          $str = str_replace(self::$Masks[1], self::$Masks[0], $str);
        }

        self::$Translation[$Namespace][$key] = $str;
      }
    }
  }
}