<?php
/**
 * Print MailTo-Link from email address
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('CONTACT', 'email@example.com' );
 *
 * Template:
 *   Mail to Webmaster: {mailto:CONTACT}
 *   Mail to {mailto:CONTACT,"WEBMASTER"}
 *
 * Output:
 *   Mail to Webmaster: &lt;a href="email@example.com"&gt;email@example.com&lt;/a&gt;
 *   Mail to &lt;a href="email@example.com"&gt;Webmaster&lt;/a&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_MailTo extends Yuelo_Extension {

  /**
   * Print MailTo-Link from email address
   *
   * @param string $file File to load, absolute or relative from document root
   * @return string
   */
  public static function Process() {
    @list($email, $name) = func_get_args();
    if (!$name) $name = $email;
    return sprintf('<a href="mailto:%s">%s</a>', $email, $name);
  }

}