<?php
/**
 *
 */

/**
 *
 */
abstract class esf_Menu {

  /**
   * Look for icons for menu entries following this expression:
   * module/<module>/images/menu$IconSet.gif
   *
   * Plugins have to provide a full path to their menu icons!
   * <image path>/menu$IconSet.gif
   *
   * @var string
   */
  public static $IconSet = 'menu.gif';

  /**#@+
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
   * @param string|array $module <Module>|array('module' => '...', 'action' => '...'), ...)
   * @param string $title Menu title
   * @param string $image Menu icon
   * @param string $hint Longer hint text
   * @param integer $id Position in menu
   */
  public static function addMain( $params ) {
    return self::add(0, $params);
  }

  /**
   * Add to module specific sub menu
   *
   * @param string|array $module <Module>|array('module' => '...', 'action' => '...'), ...)
   * @param string $title Menu title
   * @param string $image Menu icon
   * @param string $hint Longer hint text
   * @param integer $id Position in menu
   */
  public static function addModule( $params ) {
    return self::add(1, $params);
  }

  /**
   * Add to application system menu
   *
   * If $module is false, $title MUST contain a full <a ...></a> link!
   *
   * @param string|array $module <Module>|array('module' => '...', 'action' => '...'), ...)
   * @param string $title Menu title
   * @param string $image Menu icon
   * @param string $hint Longer hint text
   * @param integer $id Position in menu
   */
  public static function addSystem( $params ) {
    return self::add(2, $params);
  }
  /**#@-*/

  /**
   * Get application main menu data
   */
  public static function getMain( $style ) {
    return self::get(0, $style);
  }
  /**
   * Get module specific sub menu
   */
  public static function getModule( $style ) {
    return self::get(1, $style);
  }

  /**
   * Get module specific sub menu
   */
  public static function getSystem( $style ) {
    return self::get(2, $style);
  }

  //-----------------------------------------------------------------------------
  // PROTECTED
  //-----------------------------------------------------------------------------

  protected static $Menu = array(
    0 => array(), // Main
    1 => array(), // Module
    2 => array(), // System
  );

  /**
   * Internal menu handling function
   *
   * @internal
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
   * @internal
   */
  protected static function get( $MenuId, $style ) {
    $menu = self::$Menu[$MenuId];
    ksort($menu);
    $tpldata = array();

    foreach ($menu as $id=>$menuitem) {
      $_tpldata['ID']    = $id;
      $_tpldata['Style'] = !empty($menuitem['style']) ? $menuitem['style'] : $style;
      $_tpldata['Image'] = $menuitem['img'];
      $_tpldata['alt']   = $menuitem['alt'];
      $_tpldata['Extra'] = $menuitem['extra'];

      if ($menuitem['module']) {
        $_tpldata['Title']  = $menuitem['title']
                            ? $menuitem['title']
                            : Translation::get($menuitem['module'].'.Menu');
        $_tpldata['Hint']   = $menuitem['hint']
                            ? $menuitem['hint']
                            : Translation::get($menuitem['module'].'.MenuHint');
        $_tpldata['URL']    = Core::URL(array('module' => $menuitem['module'],
                                              'action' => $menuitem['action'],
                                              'params' => $menuitem['params'],
                                              'url'    => $menuitem['url']));
      } else {
        $_tpldata['Title']  = $menuitem['title'];
        $_tpldata['Hint']   = $menuitem['hint'];
        $_tpldata['URL']    = $menuitem['url'];
      }

      $_tpldata['URL'] = str_replace('&', '&amp;', $_tpldata['URL']);

      $tpldata[] = $_tpldata;
    }

    return $tpldata;
  }
}
