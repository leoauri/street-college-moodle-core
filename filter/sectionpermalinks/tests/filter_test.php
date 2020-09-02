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
 * @package filter_sectionpermalinks
 * @category test
 * @copyright 2020 Leo Auri <code@leoauri.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

// The code to test.
require_once($CFG->dirroot . '/filter/sectionpermalinks/filter.php'); 

class filter_sectionpermalinks_filter_testcase extends advanced_testcase {
    public function test_section_links_this_course() {
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);

        // Add source and target sections
        $sourcesection = course_create_section($course);
        $targetsection = course_create_section($course);

        $targetsection->summary = 'Oh this other section I skip to';
        $targetsection->name = 'Target Section';
        $sourcesection->summary = "This is summary, it will contain automatic link to targetsection. 
        Here is the link: [topic->Target Section]. Also other text and some /][}?!*#🥰...";

        // Update database
        course_update_section($course, $sourcesection, $sourcesection);
        course_update_section($course, $targetsection, $targetsection);

        $filtered = format_text($sourcesection->summary, FORMAT_HTML, array('context' => $context));

        // Extract links from filtered text
        $foundlinks = [];
        preg_match_all(
            '/<a.*?href="(?<href>.*?)".*?>(?<linktext>.*?)<\/a>/', 
            $filtered, 
            $foundlinks, 
            PREG_SET_ORDER
        );

        try {
            global $CFG;
            $this->assertEquals("$CFG->wwwroot/course/view.php?id=$course->id#section-{$targetsection->section}", $foundlinks[0]['href']);
            $this->assertEquals('Target Section', $foundlinks[0]['linktext']);
        } catch (PHPUnit\Framework\Error\Notice $e) {
            if (strpos($e->getMessage(), 'Undefined index') !== false) {
                $this->fail("No link found in filtered text:\n$filtered");
            } else {
                throw $e;
            }
        }
    }
}
