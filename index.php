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
$PAGE->set_url($url);

// Force users logged and check view capability.
require_login($courseid);
$context = context_course::instance($courseid);
require_capability('tool/carcastc:view', $context);

// Set most used strings in variable.
$pnstring = get_string('pluginname', 'tool_carcastc');
$hwstring = get_string('helloworld', 'tool_carcastc');

$PAGE->set_title($hwstring);
$PAGE->set_heading($pnstring);

// Trigger event.
\tool_carcastc\tool_carcastc_model::trigger_event('viewed', (object)['id' => $courseid, 'courseid' => $courseid]);

$outputpage = new \tool_carcastc\output\tool_carcastc_rows($courseid);
$output = $PAGE->get_renderer('tool_carcastc');
echo $output->header();
echo $output->render($outputpage);
echo $output->footer();
