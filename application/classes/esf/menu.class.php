<?php
/**
 * Menu builder
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
abstract class esf_Menu {

  /**
   * Look for icons for menu entries following this expression:
   * module/$module/images/menu$IconSet.gif
   *
   * Plugins have to provide a full path to their menu icons!
   * $ImagePath/menu$IconSet.gif
   *
   * @var string $IconSet
   */
  public static $IconSet = 'menu.gif';

  /**
   * @name Adding menu entries
   * @{
   * Add menu entry to defined menu
   *
   * For modules: Image name is relative from module specific layout dir, so
   * "images/menu.gif" means "module/<module>/layout/<layout>/images/menu.gif"
   * Plugins have to provide a full image path!
   *
   * If the menu position is still allocated,
   * move the entry to the next free position
   */

  /**
   * Add to application main menu
   *
   * @param string|array $params $Module|array('module' => '...', 'action' => '...'), ...)
   */
  public static function addMain( $params ) {
    return self::add(0, $params);
  }

  /**
   * Add to module specific sub menu
   *
   * @param string|array $params $Module|array('module' => '...', 'action' => '...'), ...)
   */
  public static function addModule( $params ) {
    return self::add(1, $params);
  }

  /**
   * Add to application system menu
   *
   * @param string|array $params $Module|array('module' => '...', 'action' => '...'), ...)
   */
  public static function addSystem( $params ) {
    return self::add(2, $params);
  }
  /** @} */

  /**
   * Get application main menu data
   *
   * @param string $style Full|Image|Text
   */
  public static function getMain( $style ) {
    return self::get(0, $style);
  }

  /**
   * Get module specific sub menu
   *
   * @param string $style Full|Image|Text
   */
  public static function getModule( $style ) {
    return self::get(1, $style);
  }

  /**
   * Get module specific sub menu
   *
   * @param string $style Full|Image|Text
   */
  public static function getSystem( $style ) {
    return self::get(2, $style);
  }

  //-----------------------------------------------------------------------------
  // PROTECTED
  //-----------------------------------------------------------------------------

  /**
   * Menu definitions
   *
   * @var array $Menu
   */
  protected static $Menu = array(
    0 => array(), // Main
    1 => array(), // Module
    2 => array(), // System
  );

  /**
   * Internal menu handling function
   *
   * @param integer $MenuId Internal menu ID
   * @param array $params
   */
  protected static function add( $MenuId, $params ) {
    $params = array_merge( array(
      'url'    => '',
      'module' => '',
      'action' => '',
      'params' => array(),
      'title'  => '',
      'hint'   => '',
      'img'    => '',
      'alt'    => '',
      'extra'  => '',
      'style'  => '',
    ), $params);

    if ($params['module'] AND !$params['img']) $params['img'] = self::$IconSet;

    // plugins have to provide full image paths!
    if ($params['module'] AND !file_exists($params['img'])) {
      $img = sprintf('module/%s/images/%s', $params['module'], $params['img']);
      if (file_exists($img)) $params['img'] = $img;
    }

    // To avoid overwriting, move entry after still existing entries!
    $id = 1;
    if (isset($params['id'])) {
      $id = $params['id'];
      unset($params['id']);
    }
    while (isset(self::$Menu[$MenuId][$id])) $id += 1;
    
    self::$Menu[$MenuId][$id] = $params;
    return $params;
  }

  /**
   * Get menu data
   *
   * @param integer $MenuId Internal menu ID
   * @param string $style Full|Image|Text
   * @return array
   */
  protected static function get( $MenuId, $style ) {
    $menu = self::$Menu[$MenuId];
    ksort($menu);
    $TplData = array();

    foreach ($menu as $id=>$menuitem) {
      $Entry['ID']    = $id;
      $Entry['Style'] = !empty($menuitem['style']) ? $menuitem['style'] : $style;
      $Entry['Image'] = $menuitem['img'];
      $Entry['alt']   = $menuitem['alt'];
      $Entry['Extra'] = $menuitem['extra'];

      if ($menuitem['module']) {
        $Entry['Title']  = $menuitem['title']
                         ? $menuitem['title']
                         : Translation::get($menuitem['module'].'.Menu');
        $Entry['Hint']   = $menuitem['hint']
                         ? $menuitem['hint']
                         : Translation::get($menuitem['module'].'.MenuHint');
        $Entry['URL']    = Core::URL(array('module' => $menuitem['module'],
                                           'action' => $menuitem['action'],
                                           'params' => $menuitem['params'],
                                           'url'    => $menuitem['url']));
      } else {
        $Entry['Title']  = $menuitem['title'];
        $Entry['Hint']   = $menuitem['hint'];
        $Entry['URL']    = $menuitem['url'];
      }

      $Entry['URL'] = str_replace('&', '&amp;', $Entry['URL']);

      $TplData[] = $Entry;
    }

    return $TplData;
  }
}
