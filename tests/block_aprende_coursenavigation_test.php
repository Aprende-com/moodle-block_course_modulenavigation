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

    /**
     * @testdox Without any moodle_page state asociated, block's content object should be empty
     * @test
    */
    public function test_block_content_object_empty_value() {
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
}
