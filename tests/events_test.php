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
 * Class events_test to phpunit
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_carcastc;

use advanced_testcase;

defined('MOODLE_INTERNAL') || die;

/**
 * Class tool_carcastc_events_testcase to unit test
 *
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_carcastc_events_testcase extends advanced_testcase {

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
     * Test for event row_created
     */
    public function test_row_created() {

        // Capture the event.
        $sink = $this->redirectEvents();

        // Create row.
        $rowid = \tool_carcastc\tool_carcastc_model::save_row((object)[
                'courseid' => $this->course->id,
                'name' => 'Row event create 1',
                'completed' => 1,
                'priority' => 0
        ]);

        // Recovery the triggered event.
        $events = $sink->get_events();

        // Expected only one event.
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\tool_carcastc\event\row_created', $event);
        $this->assertEquals($this->course->id, $event->courseid);
        $this->assertEquals($rowid, $event->objectid);
    }

    /**
     * Test for event row_created
     */
    public function test_row_updated() {

        // Create row.
        $rowid = \tool_carcastc\tool_carcastc_model::save_row((object)[
                'courseid' => $this->course->id,
                'name' => 'Row event update 1',
                'completed' => 1,
                'priority' => 0
        ]);

        // Capture the event.
        $sink = $this->redirectEvents();

        // Update row.
        \tool_carcastc\tool_carcastc_model::save_row((object)[
                'id' => $rowid,
                'courseid' => $this->course->id,
                'name' => 'Row event update 1',
                'completed' => 1,
                'priority' => 0,
                'descriptionformat' => 0,
                'description' => 'Description update 1'
        ]);

        // Recovery the triggered event.
        $events = $sink->get_events();

        // Expected only one event.
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\tool_carcastc\event\row_updated', $event);
        $this->assertEquals($this->course->id, $event->courseid);
        $this->assertEquals($rowid, $event->objectid);
    }

    /**
     * Test for event row_created
     */
    public function test_row_deleted() {

        // Create row.
        $rowid = \tool_carcastc\tool_carcastc_model::save_row((object)[
                'courseid' => $this->course->id,
                'name' => 'Row event update 1',
                'completed' => 1,
                'priority' => 0
        ]);

        // Capture the event.
        $sink = $this->redirectEvents();

        // Delete row.
        \tool_carcastc\tool_carcastc_model::delete_row(['id' => $rowid]);

        // Recovery the triggered event.
        $events = $sink->get_events();

        // Expected only one event.
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\tool_carcastc\event\row_deleted', $event);
        $this->assertEquals($this->course->id, $event->courseid);
        $this->assertEquals($rowid, $event->objectid);
    }
}
