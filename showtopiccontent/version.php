<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version metadata for the filter_pluginname plugin.
 *
 * @package   filter_showtopiccontent
 * @copyright 2022, DJ Dave <chawbs@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2022102401;
$plugin->requires = 2019111800;
$plugin->component = 'filter_showtopiccontent'; // Full name of the plugin (used for diagnostics)
$plugin->maturity = MATURITY_STABLE;
$plugin->release = 'v1.0-r1';

$plugin->dependencies = array(
    'mod_lesson' => 2019111800,                 // Written with this version, untested with others
);

?>
