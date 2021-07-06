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
 * Class tool_carcast_formdata
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_carcastc;

defined('MOODLE_INTERNAL') || die();

//moodleform is defined in formslib.php
require_once($CFG->libdir.'/formslib.php');

/**
 * Class tool_carcast_formdata to display form insert and edit
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_carcast_formdata extends \moodleform {

    /**
     * Form definition
     */
    protected function definition() {
        $mform = $this->_form; // Don't forget the underscore!

        // Add name text-element to form.
        $mform->addElement('text', 'name', get_string('name', 'tool_carcastc'));
        $mform->setType('name', PARAM_NOTAGS);

        // Add completed checkbox-element to form.
        $mform->addElement('advcheckbox', 'completed', get_string('completed', 'tool_carcastc'));

        // Add courseid hidden-element to form.
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        // Add id hidden-element to form for edit form.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();

    }

    /**
     * Form definition
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        global $DB;

        // Call parent validation to avoid missing various checks.
        $errors = parent::validation($data, $files);

        if ($foundname = $DB->get_records_select('tool_carcastc', 'name = :name AND courseid = :courseid AND id <> :id',
                ['name' => $data['name'], 'courseid' => $data['courseid'], 'id' => $data['id']])) {
                $errors['name']= get_string('nameexist', 'tool_carcastc', reset($foundname));
        }

        return $errors;
    }
}