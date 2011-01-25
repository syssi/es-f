<?php
/** @defgroup Plugin-Categories Plugin Categories

Add predefined categories

*/

/**
 * Plugin Categories
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-Categories
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class esf_Plugin_Categories extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('ConfigurationSave', 'CategoriesRead');
  }

  /**
   * Remove white spaces around commas
   *
   * @param array &$data Data to check
   */
  public function ConfigurationSave( &$data ) {
    if ($data['scope'] == esf_Extensions::PLUGIN AND
        $data['extension'] == 'categories' AND
        $data['var'] == 'CATEGORIES')
      $data['value'] = preg_replace('~\s*,\s*~', ',', $data['value']);
  }

  /**
   * Add predefined categories to dropdowns
   *
   * @param array &$categories Existing categories
   */
  public function CategoriesRead( &$categories ) {
    if (!$this->Categories OR !isset($categories[FROMGROUP])) return;
    // ONLY extend category lists used by select options

    foreach (explode(',', $this->Categories) as $category)
      $categories[$category] = $category;
  }

}

Event::attach(new esf_Plugin_Categories);