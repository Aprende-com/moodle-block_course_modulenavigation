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

namespace block_aprende_coursenavigation\tests;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/blocks/moodleblock.class.php');
require_once($CFG->dirroot . '/blocks/aprende_coursenavigation/block_aprende_coursenavigation.php');

/**
 * Testsuite class for block course navigation.
 *
 * @package    block_aprende_coursenavigation
 * @copyright  David OC <davidherzlos@aprende.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_aprende_coursenavigation_testcase extends \advanced_testcase {

    protected $blockname;
    protected $course;
    protected $page;

    /**
     * SetUp for tests
     */
    protected function setUp(): void {
        $this->resetAfterTest();

        $this->blockname = 'aprende_coursenavigation';

        // Set up a normal course page
        $this->course = $this->getDataGenerator()->create_course();
        $this->page = new \moodle_page();
        $this->page->set_course($this->course);
        $this->page->set_pagetype('course-view-topics');
        $this->page->set_url('/course/view.php', [
            'id' => $this->course->id
        ]);
    }

    /**
     * @testdox Without any block instance set, block's content object should be empty
     * @test
    */
    public function test_block_content_object_empty_value(): void {
        // Default structure for block's content object
        $output = new \stdClass();
        $output->footer = '';
        $output->text = '';

        // Block's content object
        $block = new \block_aprende_coursenavigation();

        $expected = $output;
        $actual = $block->get_content();
        $this->assertEquals($expected, $actual, 'Values should be equals');
    }

    /**
     * @testdox Setting a block instance, instance's text property should not be empty
     * @test
     */
    public function test_block_instance_text_prop_non_empty(): void {
        // Setup a block
        $record = $this->new_block_record($this->page);
        $block = block_instance($this->blockname, $record, $this->page);

        $this->assertInstanceOf(\block_base::class, $block);

        $expected = true;
        $actual = $block->get_content()->text;
        $this->assertEquals($expected, !empty($actual), 'Values should be equals');
    }

    /**
     * Utility method to create block record
     * TODO: Refactor this method into a plugin instance generator
     */
    protected function new_block_record(\moodle_page $page): \stdClass {
        global $DB;

        $blockrecord = new \stdClass;
        $blockrecord->blockname = $this->blockname;
        $blockrecord->parentcontextid = $page->context->id;
        $blockrecord->showinsubcontexts = true;
        $blockrecord->pagetypepattern = 'course-view-*';
        $blockrecord->subpagepattern = null;
        $blockrecord->defaultregion = 'side-pre';
        $blockrecord->defaultweight = 0;
        $blockrecord->configdata = '';
        $blockrecord->timecreated = time();
        $blockrecord->timemodified = $blockrecord->timecreated;
        $blockrecord->id = $DB->insert_record('block_instances', $blockrecord);

        return $blockrecord;
    }
}
