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
 * Class restore_tool_carcastc_plugin
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/restore_tool_plugin.class.php');

/**
 * Class backup_tool_carcastc_plugin to set restore info based on backup
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_tool_carcastc_plugin extends restore_tool_plugin {

    /**
     * Define the path when restore course process select data related with current plugin.
     *
     */
    protected function define_course_plugin_structure() {
        $paths = array();
        $paths[] = new restore_path_element('tool_carcastc', '/course/tool_carcastc');
        return $paths;
    }

    /**
     * Process to restore tool_carcastc rows and mapping data.
     *
     * @param stdClass $data Data given from above paths.
     */
    public function process_tool_carcastc($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->courseid = $this->task->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = time();

        // Insert the tool_carcastc record.
        $data->id = $DB->insert_record('tool_carcastc', $data);

        $this->set_mapping('tool_carcastc', $oldid, $data->id, true);

        $this->set_mapping('itemid', $oldid, $data->id, true);

    }

    /**
     * Process to execute after restore tool_carcastc and handle files.
     *
     */
    protected function after_restore_course() {
        // Add tool_carcastc related files, no need to maching itemname with itemid.
        $this->add_related_files('tool_carcastc', 'rowfile', 'itemid');
    }
}
