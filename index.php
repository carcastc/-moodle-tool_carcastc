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

$id = required_param('id', PARAM_INT);

// Pass argument to query string.
$url = new moodle_url('/admin/tool/carcastc/index.php', ['id' => $id]);

// Set most used strings in variable.
$pnstring = get_string('pluginname', 'tool_carcastc');
$hwstring = get_string('helloworld', 'tool_carcastc');

$PAGE->set_context(context_system::instance());

$PAGE->set_url($url);

$PAGE->set_pagelayout('report');
$PAGE->set_title($hwstring);
$PAGE->set_heading($pnstring);

// Count users registered in moodle.
$userscount = $DB->count_records('user');

// Get info course based on id param.
$courseinfo = $DB->get_record_sql("SELECT fullname FROM {course} WHERE id = ?", [$id]);

echo $OUTPUT->header();
echo $OUTPUT->heading($hwstring);

// Pass parameter to language string.
echo html_writer::div(get_string('youareviewing', 'tool_carcastc',
        ['userscount' => $userscount, 'coursename' =>
        html_writer::span($courseinfo->fullname ?? get_string('coursenotfound', 'tool_carcastc'), 'font-weight-bold')]));

// Show tool_carcastc table rows.
$tablesql = new  \tool_carcastc\tool_carcastc_tabledata('tool_carcastc', $id);
$tablesql->out(0, false);

echo $OUTPUT->footer();