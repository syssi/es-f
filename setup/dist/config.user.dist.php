<?php
/*
 * Copyright (c) 2006-2008 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 */

defined('_ESF_OK') || die('No direct call allowed.');

# *****************************************************************************
# USER SPECIFIC CONFIGURATION
# *****************************************************************************
#
# Only useful on multi user installations or for development.
#
# copy this file to
#   <RUNDIR>/.<username>/config.php
# and adjust your settings
#
# YOU CAN ONLY REDEFINE THE FOLLOWING VARIABLES


# *****************************************************************************
# esniper settings, see "man esniper" for details
#
# Seconds before the end of an auction.
#$esniper['seconds'] = 10;

# *****************************************************************************
# default language
#
#$cfg['LANGUAGE'] = 'en';

# -----------------------------------------------------------------------------
# Users time zone, different from server or global definition
#
# see the time zones in include/tz.ini
#
#$cfg['TIMEZONE'] = 'CET';

# -----------------------------------------------------------------------------
# default / start module
#
#$cfg['STARTMODULE'] = 'index';

# *****************************************************************************
# layout configuration

# -----------------------------------------------------------------------------
# Global layout
#
#$cfg['LAYOUT'] = 'default';

# -----------------------------------------------------------------------------
# Menu style layout, for 3 levels of menus
#
#$cfg['MENUSTYLE'] = 'image,text,full';
