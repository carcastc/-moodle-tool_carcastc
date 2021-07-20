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
 * Services for tool carcastc
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Definition of services to handle delete row.
$functions = array(
        'tool_carcastc_delete_row' => array(
                'classname'    => 'tool_carcastc\tool_carcastc_external',
                'methodname'   => 'delete_row',
                'description'  => 'Deletes an row',
                'type'         => 'write',
                'capabilities' => 'tool/carcastc:edit',
                'ajax'         => true,
        ),
        'tool_carcastc_display_rows' => array(
                'classname'    => 'tool_carcastc\tool_carcastc_external',
                'methodname'   => 'display_rows',
                'description'  => 'Returns list of rows',
                'type'         => 'read',
                'capabilities' => 'tool/carcastc:view',
                'ajax'         => true,
        ),
);