<?php
/**
 * Definitions for eBay
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ----------------------------------------------------------------------------
// Your prefered ebay homepage (e.g. of your land)
//
Registry::set('ebay.Homepage', 'ebay.'.Registry::get('EbayTLD'));
//
// if you have your own PHProxy running, you can access the auctions via
// your proxy using this type of URL: (see also docs/TIPS & TRICKS)
//
// 'http://<PROXY>/index.php?q='.urlencode('ebay.'.Registry::get('EbayTLD'));

// ----------------------------------------------------------------------------
// Your prefered ebay item page (e.g. of your land), %s for item number
//
Registry::set('ebay.ShowUrl', 'http://www.ebay.'.Registry::get('EbayTLD').'/itm/%s');
//
// if you have your own PHProxy running, you can access the auctions via
// your proxy using this type of URL: (see also docs/TIPS & TRICKS)
//
// 'http://<PROXY>/index.php?q='.urlencode('http://search.ebay.'.Registry::get('EbayTLD').'/').'%s';
