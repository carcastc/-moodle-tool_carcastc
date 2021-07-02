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
 * Extending plugin tool_carcastc functions
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


function tool_carcastc_extend_navigation_course($navigation, $course, $context) {
    $navigation->add(
            get_string('pluginname', 'tool_carcastc'),
            new moodle_url('/admin/tool/carcastc/index.php', ['id' => $course->id]),
            navigation_node::TYPE_SETTING,
            get_string('pluginname', 'tool_carcastc'),
            'carcastc',
            new pix_icon('icon', '', 'tool_carcastc'));
}