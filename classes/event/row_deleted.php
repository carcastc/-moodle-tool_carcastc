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
 * Class tool_carcastc\event\row_deleted
 *
 * @package   tool_carcastc
 * @since      Moodle 3.11
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_carcastc\event;

defined('MOODLE_INTERNAL') || die();

use core\event\base;

/**
 * Class to create event when row is deleted
 *
 * @package    tool_carcastc
 * @copyright  2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license    http://www.gnuZc.org/copyleft/gpl.html GNU GPL v3 or later
 */
class row_deleted extends base {

    /**
     * @inheritDoc
     */
    protected function init() {
        $this->data['objecttable'] = 'tool_carcastc';
        $this->data['crud'] = 'd';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventrowdeleted', 'tool_carcastc');
    }

    /**
     * Returns non-localised description when row is deleted.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' deleted the row with id '$this->objectid'
                associated with course id '$this->courseid'.";
    }

    /**
     * Returns relevant URL.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/admin/tool/carcastc/index.php', ['courseid' => $this->courseid]);
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();

        if ($this->contextlevel != CONTEXT_COURSE) {
            throw new \coding_exception('Context level must be CONTEXT_COURSE.');
        }
    }

    /**
     * This is used when restoring course logs where it is required that we
     * map the objectid to it's new value in the new course.
     *
     * @return string the name of the restore mapping the objectid links to
     */
    public static function get_objectid_mapping() {
        return base::NOT_MAPPED;
    }

    /**
     * This is used when restoring course logs where it is required that we
     * map the information in 'other' to it's new value in the new course.
     *
     * @return array an array of other values and their corresponding mapping
     */
    public static function get_other_mapping() {
        // Nothing to map.
        return false;
    }

}
