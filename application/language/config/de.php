<?php
/**
* German core configuration
*
* @package    es-f
* @subpackage Languages
* @author     Knut Kohl <knutkohl@users.sourceforge.net>
* @copyright  2007-2009 Knut Kohl
* @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
* @since      File available since Release 2.0.1
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
