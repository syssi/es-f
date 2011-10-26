<?php
/**
@defgroup Module-Index Module Index


*/

/**
 * Module Index
 *
 * @ingroup    Module
 * @ingroup    Module-Index
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Module_Index extends esf_Module {

  /**
   *
   */
  public function IndexAction() {
    if ($user = esf_User::isValid()) {
      TplData::set('Link', 'es-f@'.php_uname('n'));

      $token = esf_User::getToken() . "\x00" .
               $user . "\x00" .
               esf_User::getPass(TRUE) . "\x00" .
               Session::get('Layout');
      $token = Core::$Crypter->encrypt($token, APPID);
      $url = str_replace('index.php', '', $_SERVER['PHP_SELF']);
      TplData::set('TokenURL', $url . '?lt=' . urlencode($token));
    }
  }

}