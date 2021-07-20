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
 * Class model_test to phpunit
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_carcastc;

use advanced_testcase;

defined('MOODLE_INTERNAL') || die;

/**
 * Class tool_carcastc_model_testcase to unit test
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_carcastc_model_testcase extends advanced_testcase {

    /** @var int */
    protected $course;

    /**
     * The methods specified here is called before each test.
     */
    public function setUp(): void {
        $this->resetAfterTest();
        $this->course = $this->getDataGenerator()->create_course();
    }

    /**
     * Test for tool_carcastc_model::save_row and tool_carcastc_model::get_row
     */
    public function test_insert() {
        $rowid = \tool_carcastc\tool_carcastc_model::save_row((object)[
                'courseid' => $this->course->id,
                'name' => 'Row test 1',
                'completed' => 1,
                'priority' => 0
        ]);

        $row = \tool_carcastc\tool_carcastc_model::get_row(['id' => $rowid]);
        $this->assertEquals($this->course->id, $row->courseid);
        $this->assertEquals('Row test 1', $row->name);
    }

    /**
     * Test for tool_carcastc_model::save_row and tool_carcastc_model::get_row when update
     */
    public function test_update() {
        $rowid = \tool_carcastc\tool_carcastc_model::save_row((object)[
                'courseid' => $this->course->id,
                'name' => 'Row test 1',
                'completed' => 1,
                'priority' => 0
        ]);

        \tool_carcastc\tool_carcastc_model::save_row((object)[
                'id' => $rowid,
                'courseid' => $this->course->id,
                'name' => 'Row test 2',
                'completed' => 1,
                'priority' => 0,
                'descriptionformat' => 0,
                'description' => 'Description update 1'
        ]);

        $row = \tool_carcastc\tool_carcastc_model::get_row(['id' => $rowid]);
        $this->assertEquals($this->course->id, $row->courseid);
        $this->assertEquals('Row test 2', $row->name);
        $this->assertEquals('Description update 1', $row->description);
    }

    /**
     * Test for tool_carcastc_model::save_row and tool_carcastc_model::get_row when use editor
     */
    public function test_editor() {
        $rowid = \tool_carcastc\tool_carcastc_model::save_row((object)[
                'courseid' => $this->course->id,
                'name' => 'Row test 3',
                'completed' => 1,
                'priority' => 0,
                'description_editor' => [
                        'text' => 'Description to row test 3',
                        'format' => FORMAT_HTML
                ]
        ]);

        $row = \tool_carcastc\tool_carcastc_model::get_row(['id' => $rowid]);
        $this->assertEquals($this->course->id, $row->courseid);
        $this->assertEquals('Description to row test 3', $row->description);
        $this->assertEquals('Row test 3', $row->name);

        \tool_carcastc\tool_carcastc_model::save_row((object)[
                'id' => $rowid,
                'courseid' => $this->course->id,
                'name' => 'Row test 3 edited',
                'completed' => 1,
                'description_editor' => [
                        'text' => 'Description to row test 3 edited',
                        'format' => FORMAT_HTML
                ]
        ]);

        $row = \tool_carcastc\tool_carcastc_model::get_row(['id' => $rowid]);

        $this->assertEquals($this->course->id, $row->courseid);
        $this->assertEquals('Description to row test 3 edited', $row->description);
        $this->assertEquals('Row test 3 edited', $row->name);
    }

    /**
     * Test for tool_carcastc_model::delete_row
     */
    public function test_delete() {
        $rowid = \tool_carcastc\tool_carcastc_model::save_row((object)[
                'courseid' => $this->course->id,
                'name' => 'Row test 1',
                'completed' => 1,
                'priority' => 0
        ]);

        \tool_carcastc\tool_carcastc_model::delete_row(['id' => $rowid]);

        $row = \tool_carcastc\tool_carcastc_model::get_row(['id' => $rowid], IGNORE_MISSING);
        $this->assertEmpty($row);
    }
}
