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

//this method execute the clean_param
$id = optional_param('id', 0, PARAM_INT);

$url = new moodle_url('/admin/tool/carcastc/index.php');


$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title(get_string('helloworld', 'tool_carcastc'));
$PAGE->set_heading(get_string('pluginname', 'tool_carcastc'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('helloworld', 'tool_carcastc'));

echo html_writer::div(get_string('helloworld', 'tool_carcastc'));
echo html_writer::div(get_string('youareviewing', 'tool_carcastc', $id));

echo $OUTPUT->footer();