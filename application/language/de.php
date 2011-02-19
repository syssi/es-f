<?php
/**
 * German core configuration
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * Auktionsrestzeit                        0         1        >1
 */
Registry::set('Format.Remain.day',  array('',      '%dT. ', '%dT. '));
Registry::set('Format.Remain.hour', array('%02d:', '%02d:', '%02d:'));
Registry::set('Format.Remain.min',  array('%02d:', '%02d:', '%02d:'));
Registry::set('Format.Remain.sec',  array('%02d',  '%02d',  '%02d' ));

/**
 * Datums-/Zeit-Format für date()
 */
Registry::set('Format.Date',     'd.m.Y');
Registry::set('Format.Time',     'H:i:s');
// Registry::set('Format.DateTime', 'd.m.Y / H:i:s');
Registry::set('Format.DateTime', 'D. d.m.Y / H:i:s');

/**
 * Datums-/Zeit-format für strftime()
 */
Registry::set('Format.DateS',     '%d.%m.%Y');
Registry::set('Format.TimeS',     '%H:%M:%S');
// Registry::set('Format.DateTimeS', '%d.%m.%Y / %H:%M:%S');
Registry::set('Format.DateTimeS', '%a. %d.%m.%Y / %H:%M:%S');

Registry::set('Format.DecimalChar',        ',');
Registry::set('Format.ThousandsSeparator', '.');
Registry::set('Format.DecimalPlaces',      2);
