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
 * Class tabledata
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_carcastc;

defined('MOODLE_INTERNAL') || die();

use context_course;
require_once($CFG->libdir . '/tablelib.php');

/**
 * Class tool_carcastc_tabledata to output data.
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_carcastc_tabledata extends \table_sql {

    /** @var int */
    protected $courseid;

    /** @var int */
    protected $context;

    /**
     * Set up the tool_carcastc table.
     *
     * @param string $uniqueid unique id of form.
     * @param int $courseid course id to display in table.
     */
    public function __construct($uniqueid, $courseid) {
        global $PAGE;

        parent::__construct($uniqueid);

        $this->set_attribute('id', 'tool_carcastc_overview');

        $this->courseid = $courseid;

        $columns = array('coursename', 'name', 'description', 'completed', 'priority', 'timecreated', 'timemodified');

        $headers = array(
                get_string('coursename', 'tool_carcastc'),
                get_string('name', 'tool_carcastc'),
                get_string('description', 'tool_carcastc'),
                get_string('completed', 'tool_carcastc'),
                get_string('priority', 'tool_carcastc'),
                get_string('timecreated', 'tool_carcastc'),
                get_string('timemodified', 'tool_carcastc'),
        );

        // Set context.
        $this->context = \context_course::instance($this->courseid);

        // Add action colum when user can edit.
        if ($this->can_edit()) {
            $columns[] = 'action';
            $headers[] = get_string('action', 'tool_carcastc');
        }

        $this->define_columns($columns);

        $this->define_headers($headers);

        $this->pageable(false);
        $this->collapsible(false);
        $this->sortable(false);
        $this->is_downloadable(false);

        $this->define_baseurl($PAGE->url);
    }

    /**
     * Displays course fullname
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_coursename($row) {
        return format_string($row->coursename);
    }

    /**
     * Displays name
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_name($row) {
        return format_string($row->name);
    }

    /**
     * Displays column description
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_description($row) {

        $context = context_course::instance($row->courseid);
        $editoroptions = ['trusttext' => true, 'subdirs' => true, 'maxfiles' => -1, 'maxbytes' => 0,
                'context' => $context, 'noclean' => true];
        $description = file_rewrite_pluginfile_urls($row->description, 'pluginfile.php',
                $editoroptions['context']->id, 'tool_carcastc', 'rowfile', $row->id, $editoroptions);
        return format_text($description, $row->descriptionformat, $editoroptions);
    }

    /**
     * Displays complete status
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_completed($row) {
        return $row->completed ? get_string('yes') : get_string('no');
    }

    /**
     * Displays priority status
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_priority($row) {
        return $row->priority ? get_string('yes') : get_string('no');
    }

    /**
     * Displays timecreated
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timecreated($row) {
        return userdate($row->timecreated);
    }

    /**
     * Displays timemodified
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timemodified($row) {
        return userdate($row->timemodified);
    }

    /**
     * Generate the actions column.
     *
     * @param \stdClass $row
     * @return string
     */
    public function col_action($row) {
        global $OUTPUT;
        $actions = '';

        if ($this->can_edit()) {
            // Edit row.
            $link = new \moodle_url('/admin/tool/carcastc/edit.php', ['id' => $row->id]);;
            $icon = new \pix_icon('t/edit', get_string('edit', 'tool_carcastc'), 'core');
            $actions .= $OUTPUT->action_icon($link, $icon, null);

            // Delete row.
            $link = new \moodle_url('/admin/tool/carcastc/edit.php', ['delete' => $row->id, 'sesskey' => sesskey()]);
            $icon = new \pix_icon('t/delete', get_string('delete', 'tool_carcastc'), 'core');
            $actions .= $OUTPUT->action_icon($link, $icon, null, ['data-action' => 'deleterow', 'data-rowid' => $row->id,
                    'data-courseid' => $row->courseid]);
        }

        return $actions;
    }

    /**
     * Check capability tool/carcastc:edit for current user user.
     *
     * @return bool
     */
    public function can_edit() {
        return \has_capability('tool/carcastc:edit', $this->context);
    }


    /**
     * Set up the table_sql. Store results in the object for use by build_table.
     *
     * @param int $pagesize size of page for paginated displayed table.
     * @param bool $useinitialsbar do you want to use the initials bar. Bar
     * will only be used if there is a fullname column defined for the table.
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        $total = $DB->count_records('tool_carcastc');
        $this->pagesize($pagesize, $total);
        $this->rawdata = $this->get_rows_table();

        // Set initial bars.
        if ($useinitialsbar) {
            $this->initialbars($total > $pagesize);
        }
    }

    /**
     * Query to fill table filter by current course id.
     *
     * @return array
     */
    private function get_rows_table() {
        global $DB;

        $sql = "SELECT tc.id, tc.courseid, c.fullname as coursename, tc.name, tc.completed,
                tc.description, tc.descriptionformat, tc.priority, tc.timecreated, tc.timemodified
                FROM {tool_carcastc} tc
                JOIN {course} c ON c.id = tc.courseid
                WHERE tc.courseid = ? ";

        return $DB->get_records_sql($sql, [$this->courseid]);
    }
}
