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
 * Display Settings for the filter_showtopiccontent plugin.
 *
 * @package   filter_showtopiccontent
 * @copyright 2022, DJ Dave <chawbs@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext(
        'filter_showtopiccontent/searchkeyword',
        get_string('searchkeyword', 'filter_showtopiccontent'),
        get_string('searchkeyworddesc', 'filter_showtopiccontent'),
        'show_module_pages'));

    $settings->add(new admin_setting_configtextarea(
        'filter_showtopiccontent/predivcss',
        get_string('predivcss', 'filter_showtopiccontent'),
        get_string('predivcssdesc', 'filter_showtopiccontent'),
        'clear:both; padding-top: 10px;'));

    $settings->add(new admin_setting_configcheckbox(
        'filter_showtopiccontent/maketwocolumns',
        get_string('maketwocolumns', 'filter_showtopiccontent'),
        get_string('maketwocolumnsdesc', 'filter_showtopiccontent'),
        '1'));

    $settings->add(new admin_setting_configtextarea(
        'filter_showtopiccontent/listdivcss',
        get_string('listdivcss', 'filter_showtopiccontent'),
        get_string('listdivcssdesc', 'filter_showtopiccontent'),
        'width: 40%; padding: 10px; box-sizing: border-box; background-color: #f3f3f3;'));
}
