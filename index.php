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
 * Page API
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('courseid', PARAM_INT);

// Pass argument to query string.
$url = new moodle_url('/admin/tool/carcastc/index.php', ['courseid' => $courseid]);

// Set most used strings in variable.
$pnstring = get_string('pluginname', 'tool_carcastc');
$hwstring = get_string('helloworld', 'tool_carcastc');

$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');

// Force users logged and check view capability.
require_login();
$context = context_course::instance($courseid);
require_capability('tool/carcastc:view', $context);

$PAGE->set_title($hwstring);
$PAGE->set_heading($pnstring);

// Count users registered in moodle.
$userscount = \tool_carcastc\tool_carcastc_model::get_rows_sql("SELECT COUNT(id) as countusers FROM {user}");

// Get info course based on id param.
$courseinfo = \tool_carcastc\tool_carcastc_model::get_rows_sql("SELECT fullname FROM {course} WHERE id = ?", [$courseid]);

echo $OUTPUT->header();
echo $OUTPUT->heading($hwstring);

// Pass parameter to language string.
echo html_writer::div(get_string('youareviewing', 'tool_carcastc',
        ['userscount' => $userscount->countusers, 'coursename' =>
        html_writer::span(format_string($courseinfo->fullname) ??
                get_string('coursenotfound', 'tool_carcastc'), 'font-weight-bold')]));

// Show tool_carcastc table rows.
$tablesql = new  \tool_carcastc\tool_carcastc_tabledata('tool_carcastc', $courseid);
$tablesql->out(0, false);

// Check capability and allow add row.
if (has_capability('tool/carcastc:edit', $context)) {
    echo html_writer::div(html_writer::link(new moodle_url('/admin/tool/carcastc/edit.php', ['courseid' => $courseid]),
            get_string('new', 'tool_carcastc'), ['class' => 'btn btn-primary']));
}

echo $OUTPUT->footer();