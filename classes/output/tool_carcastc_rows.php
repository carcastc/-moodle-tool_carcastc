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
 * Class tool_carcastc\output\rows
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_carcastc\output;

defined('MOODLE_INTERNAL') || die();

use html_writer;
use renderer_base;
use stdClass;
use moodle_url;
use tool_carcastc\tool_carcastc_tabledata;
use context_course;

/**
 * Class rows to handle data to render
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_carcastc_rows implements \templatable, \renderable {

    /** @var int */
    protected $courseid;

    /**
     * Constructor to set courseid when renderer was instantiated.
     * @param int $courseid
     */
    public function __construct($courseid) {
        $this->courseid = $courseid;
    }

    /**
     * Function to export the renderer data in a format that is suitable for a
     * mustache template. This means:
     * 1. No complex types - only stdClass, array, int, string, float, bool
     * 2. Any additional info that is required for the template is pre-calculated (e.g. capability checks).
     *
     * @param renderer_base $output Used to do a final render of any components that need to be rendered for export.
     * @return stdClass|array
     */
    public function export_for_template(renderer_base $output) {
        $course = \tool_carcastc\tool_carcastc_model::get_rows_sql("SELECT * FROM {course} WHERE id = ?", [$this->courseid]);
        $userscount = \tool_carcastc\tool_carcastc_model::get_rows_sql("SELECT COUNT(id) as countusers FROM {user}");
        $context = context_course::instance($this->courseid);
        $data['info'] = [
                'userscount' => $userscount->countusers,
                'coursename' => format_string($course->fullname, true, ['context' => $context])
        ];

        // Display table.
        ob_start();
        $table = new tool_carcastc_tabledata('tool_carcastc', $this->courseid);
        $table->out(20, false);
        $data['table'] = ob_get_clean();
        ob_end_clean();

        // Link to add new row.
        if (has_capability('tool/carcastc:edit', $context)) {
            $url = new moodle_url('/admin/tool/carcastc/edit.php', ['courseid' => $this->courseid]);
            $data['addrow'] = html_writer::div(html_writer::link($url,
                    get_string('new', 'tool_carcastc'), ['class' => 'btn btn-primary']));
        }
        return $data;
    }
}