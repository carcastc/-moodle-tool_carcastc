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
 * Edit page
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$id = optional_param('id', 0, PARAM_INT);

if ($id) {
    // If id is sent as param then return the row filter by this id.
    $row = $DB->get_record('tool_carcastc', ['id' => $id], '*', MUST_EXIST);
    $courseid = $row->courseid;
    $urlparams = ['id' => $id];
    $title = get_string('edit', 'tool_carcastc');
} else {

    // Process to delete row.
    if ($deleteid = optional_param('delete', null, PARAM_INT)) {
        require_sesskey();
        $record = $DB->get_record('tool_carcastc', ['id' => $deleteid], '*', MUST_EXIST);
        require_login(get_course($record->courseid));
        require_capability('tool/carcastc:edit', context_course::instance($record->courseid));
        $DB->delete_records('tool_carcastc', ['id' => $deleteid]);
        redirect(new moodle_url('/admin/tool/carcastc/index.php', ['courseid' => $record->courseid]));
    }

    // Else index.php sent courseid as parameter so is a new row.
    $courseid = required_param('courseid', PARAM_INT);

    // Cast var $row to object to populate the form.
    $row = (object)['courseid' => $courseid];

    $urlparams = ['courseid' => $courseid];
    $title = get_string('new', 'tool_carcastc');
}

// Pass argument to query string.
$url = new moodle_url('/admin/tool/carcastc/edit.php', $urlparams);

// Set most used strings in variable.
$pnstring = get_string('pluginname', 'tool_carcastc');

$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');

// Force users logged and check view capability.
require_login();
$context = context_course::instance($courseid);
require_capability('tool/carcastc:edit', $context);

$PAGE->set_title($title);
$PAGE->set_heading($pnstring);

// Instantiate tool_carcast_formdata.
$mform = new \tool_carcastc\tool_carcast_formdata();

// Set default data (if any).
$mform->set_data($row);

// Set url for redirect.
$home = new \moodle_url('/admin/tool/carcastc/index.php', ['courseid' => $courseid]);

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
    redirect($home);
} else if ($form = $mform->get_data()) {
    // In this case you process validated data. $form->get_data() returns data posted in form.
    if ($form->id) {
        // Edit entry only some fields.
        $DB->update_record('tool_carcastc', (object)[
                'id' => $form->id,
                'name' => $form->name,
                'completed' => $form->completed,
                'timemodified' => time()
        ]);
    } else {
        // Add new entry.
        $DB->insert_record('tool_carcastc', [
                'courseid' => $form->courseid,
                'name' => $form->name,
                'completed' => $form->completed,
                'priority' => 0,
                'timecreated' => time(),
                'timemodified' => time()
        ]);
    }

    redirect($home);
}

// Displays the form.
echo $OUTPUT->header();
echo $OUTPUT->heading($title);
$mform->display();

echo $OUTPUT->footer();