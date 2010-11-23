<?php
/**
* Copyright (c) 2006-2009 Knut Kohl <knutkohl@users.sourceforge.net>
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
* @package Module-Backend
* @subpackage Languages
* @desc English language definitions
*/

defined('_ESF_OK') || die('No direct call allowed.');

# -----------------------------------------------------------------------------
#
# Don't "htmlspecialchar" your translation,
# just type '<text>' and NOT '&lt;text&gt;'!
#
# line format (php array):
# 'english text' => 'translated text',
#
Translation::Define('BACKEND', array(
# -----------------------------------------------------------------------------

# menu
'Menu'                      => 'Backend',
'Menuhint'                  => 'Edit modules and plugins',
'Title'                     => 'Backend',
'TitleIndex'                => 'Backend',

'Category'                  => 'Category',
'Modules'                   => 'Modules',
'Plugins'                   => 'Plugins',

'Extension'                 => 'Extension',
'Version'                   => 'Version',
'Description'               => 'Description',
'Actions'                   => 'Actions',

'State'                     => 'State',
'Enable'                    => 'activate',
'Enabled'                   => 'activ',

'Disable'                   => 'deactivate',
'Disabled'                  => 'inactiv',

'Install'                   => 'install',
'Deinstall'                 => 'deinstall',
'Reinstall'                 => 'reinstall',
'Installed'                 => 'installed',
'NotInstalled'              => 'not installed',

'CoreInfo'                  => 'Core function, can\'t deactivated/deinstalled',

'EditConfiguration'         => 'Edit configuration',

# %1$s => scope, %2$s => module/plugin name
'CantChangeProtected'       => 'html:Nice try <tt>;-)</tt>, but %s "%s" '
                             . 'is protected, you can\'t change state from here!',

'InstallSuccessed'          => 'Installation successful.',
'InstallFailed'             => 'Installation failed.',
'DeinstallSuccessed'        => 'Deinstallation successful.',
'DeinstallFailed'           => 'Deinstallation failed.',
'EnableSuccessed'           => 'Activation successful.',
'EnableFailed'              => 'Activation failed.',
'DisableSuccessed'          => 'Deactivation successful.',
'DisableFailed'             => 'Deactivation failed.',

# %s => info url
'LookForInfo'               => 'html:
Please take a look at the <a href="%s">information page</a>
to find out, if there are additional requirements!',

# %s => directory name
'CreateDirectory'           => 'Create directory [%s]',
# %s => directory name
'CantMakeDirectory'         => 'Can\'t create directory [%s].',
# %s => work directory name
'MakeDirectoryWritable'     => 'Make directory [%s] writable!',

# %s => file name
'CreateFile'                => 'Create file [%s]',
# %s => file name
'CantMakeFile'              => 'Can\'t create file [%s].',

# %s => zip file name
'ExtractArchive'            => 'Extract archive [%s]',
# %s => file name
'MissingFile'               => 'Missing file [%s]!',
# %s => directory name
'RemoveDirectory'           => 'Remove directory [%s]',
# %s => file name
'RemoveFile'                => 'Remove file [%s]',

'Done'                      => 'Done',
'Failed'                    => 'Failed',

# -----------------------------------------------------------------------------
));
