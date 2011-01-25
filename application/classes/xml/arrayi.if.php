<?php
/**
 * XMLArray Interface
 *
 * @ingroup    XMLArray
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
interface XML_ArrayI {

  /**
   * Analyse XML childs
   *
   * @param array $childs
   */
  public function XML2Array( $childs );

}