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
 * Class backup_tool_carcastc_plugin
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/backup_tool_plugin.class.php');

/**
 * Class backup_tool_carcastc_plugin to set backup info
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_tool_carcastc_plugin extends backup_tool_plugin {

    /**
     * Define the elements will included in backup file.
     *
     */
    protected function define_course_plugin_structure() {
        $plugin = $this->get_plugin_element();

        $rows = new backup_nested_element('tool_carcastc', array('id'), array(
                'courseid', 'name', 'completed', 'priority',
                'timecreated', 'timemodified', 'description', 'descriptionformat'));

        $rows->set_source_table('tool_carcastc', array('courseid' => backup::VAR_COURSEID));

        $rows->annotate_files('tool_carcastc', 'rowfile', null);

        $plugin->add_child($rows);

        return $plugin;
    }
}
