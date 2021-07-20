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
 * Class tool_carcastc_external
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_carcastc;

defined('MOODLE_INTERNAL') || die();

use external_api;
use external_description;
use external_function_parameters;
use external_single_structure;
use external_value;
use tool_carcastc\output\tool_carcastc_rows;

/**
 * Class tool_carcastc_external to handle delete row ajax requests
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_carcastc_external extends external_api {

    /**
     * Returns description of received method parameters
     * @return external_function_parameters
     */
    public static function delete_row_parameters() {
        return new external_function_parameters(
                array('id' => new external_value(PARAM_INT, 'Id of an row', VALUE_REQUIRED))
        );
    }

    /**
     * Process to delete row
     * @param int $id
     * @return array ['result' => true ] if all delete process is ok.
     */
    public static function delete_row($id) {
        // Parameter validation.
        $params = self::validate_parameters(self::delete_row_parameters(),
                array('id' => $id));

        $rowtodelete = ['id' => $params['id']];

        $row = tool_carcastc_model::get_row($rowtodelete);

        // Security checks, ws don't call require_login(), but we can validate_context.
        $context = \context_course::instance($row->courseid);
        self::validate_context($context);
        require_capability('tool/carcastc:edit', $context);

        tool_carcastc_model::delete_row($rowtodelete);

        return ['result' => true];
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_row_returns() {
        return new external_single_structure([
            'result' => new external_value(PARAM_BOOL, 'True or false value on delete row process')
        ]);
    }

    /**
     * Returns description of received method parameters
     * @return external_function_parameters
     */
    public static function display_rows_parameters() {
        return new external_function_parameters(
                array('courseid' => new external_value(PARAM_INT, 'Id of course', VALUE_REQUIRED))
        );
    }

    /**
     * Process to delete row
     * @param int $courseid
     * @return  array $template
     */
    public static function display_rows($courseid) {
        global $PAGE;

        // Parameter validation.
        $params = self::validate_parameters(self::display_rows_parameters(),
                array('courseid' => $courseid));

        // Security checks, ws don't call require_login(), but we can validate_context.
        $context = \context_course::instance($params['courseid']);
        self::validate_context($context);
        require_capability('tool/carcastc:view', $context);

        $outputpage = new tool_carcastc_rows($params['courseid']);
        $output = $PAGE->get_renderer('tool_carcastc');
        $return = $outputpage->export_for_template($output);

        $return['result'] = true;

        return $return;
    }

    /**
     * Returns description of method result value
     * @return external_single_structure
     */
    public static function display_rows_returns() {
        return new external_single_structure(
                array(
                        'result' => new external_value(PARAM_BOOL, 'True or false value on display rows process'),
                        'info' => new external_single_structure(
                                array(
                                        'userscount' => new external_value(PARAM_INT, 'count of users registered'),
                                        'coursename' => new external_value(PARAM_TEXT, 'name of course displayed'),
                                )
                        ),
                        'table' => new external_value(PARAM_RAW, 'Table sql'),
                        'addrow' => new external_value(PARAM_RAW, 'Link to add an row', VALUE_OPTIONAL),
                )
        );
    }
}