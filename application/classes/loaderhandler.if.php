<?php
/**
 * Class / file loader handler
 *
 * @ingroup    Loader
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
interface LoaderHandler {

  /**
   *
   * @param &$file string File name which will be loaded
   * @return void
   */
  public function BeforeLoad( &$file );

  /**
   *
   * @param &$file string File name which will be loaded
   * @return void
   */
  public function AfterLoad( &$file );

}