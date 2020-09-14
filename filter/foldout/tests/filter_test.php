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
 * Unit tests.
 *
 * @package filter_foldout
 * @category test
 * @copyright 2020 Leo Auri <code@leoauri.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

// The code to test.
require_once($CFG->dirroot . '/filter/foldout/filter.php'); 

class filter_foldout_filter_testcase extends advanced_testcase {
    private $context;

    public function setUp() {
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $this->context = context_course::instance($course->id);
    }

    public function test_create_foldout() {
        $text = "[foldout control]content[/foldout]";

        $filtered = format_text($text, FORMAT_HTML, array('context' => $this->context));

        $this->assertRegExp(
            '/<div class=".*?foldout-outer.*?"><a class="foldout-control" href="#">control<\/a><div class=".*?foldout-inner.*?">content<\/div><\/div>/',
            $filtered
        );
    }

    private function assertEqualOpeningClosing($filtered) {
        // Count opening and closing divs, assert equal numbers
        $opening = [];
        preg_match_all(
            '/<div class=".*?foldout-outer.*?"><a.*?href="#".*?>.*?<\/a><div class=".*?foldout-inner.*?">/',
            $filtered,
            $opening,
            PREG_SET_ORDER
        );
        $closing = [];
        preg_match_all(
            '/<\/div><\/div>/',
            $filtered,
            $closing,
            PREG_SET_ORDER
        );

        $openingcount = count($opening);
        $closingcount = count($closing);

        $this->assertEquals(
            $openingcount, 
            $closingcount, 
            "Unequal number of opening ($openingcount) and closing ($closingcount) tags found in filtered text:\n$filtered"
        );
    }

    public function test_insufficient_closing_tags() {
        $text = "[foldout Click here to fold out] This will be exposed first [foldout Another fold outer] Inner hidden bit [/foldout] This also exposed first";

        $filtered = format_text($text, FORMAT_HTML, array('context' => $this->context));

        $this->assertEqualOpeningClosing($filtered);
    }

    public function test_excessive_closing_tags() {
        $text = '[foldout click on]This is the test![/foldout][/foldout]';

        $filtered = format_text($text, FORMAT_HTML, array('context' => $this->context));

        $this->assertEqualOpeningClosing($filtered);
    }
}