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
 * Strings for component 'tool_carcastc', language 'en'
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'My first Moodle plugin';
$string['coursenotfound'] = 'Course not found!';
$string['helloworld'] = 'Hello World';
$string['youareviewing'] = 'The course name is {$a->coursename} and there are in moodle {$a->userscount} user(s) registered';
$string['nameexist'] = 'The name {$a} exists and must be unique in this course';

// Table columns.
$string['coursename'] = 'Course fullname';
$string['name'] = 'Name';
$string['completed'] = 'Completed';
$string['priority'] = 'Priority';
$string['timecreated'] = 'Created at';
$string['timemodified'] = 'Modified at';
$string['edit'] = 'Edit row';
$string['delete'] = 'Delete row';
$string['new'] = 'Add row';
$string['action'] = 'Actions';

// Capabilities.
$string['carcastc:view'] = 'View carcastc data';
$string['carcastc:edit'] = 'Edit carcastc data';