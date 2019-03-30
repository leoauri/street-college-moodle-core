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
 * Course participants block class definition tests.
 *
 * @package    block_course_participants
 * @copyright  2019 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');
require_once($CFG->dirroot . '/blocks/course_participants/block_course_participants.php');

/**
 * Course participants block class definition tests.
 *
 * @package    block_course_participants
 * @copyright  2019 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_course_participants_testcase extends advanced_testcase {
    public function setUp() {
        $this->resetAfterTest();
        $this->setAdminUser();
    }

    /**
     * Test object initialisation
     */
    public function test_object_init() {
        $object = new block_course_participants();
        $this->assertNotNull($object->title);
    }

    /**
     * Returns a course participants block in course context.
     *
     * @param \stdClass $course Course object
     * @return \block_course_participants Block instance object
     * @copyright 2017 The Open University
     */
    protected function create_block($course = null) {
        $page = self::construct_page($course);
        $page->blocks->add_block_at_end_of_default_region('course_participants');

        // Load the block.
        $page = self::construct_page($course);
        $page->blocks->load_blocks();
        $blocks = $page->blocks->get_blocks_for_region($page->blocks->get_default_region());
        $block = end($blocks);
        return $block;
    }

    /**
     * Constructs a page object for the test course.
     *
     * @param \stdClass $course Moodle course object
     * @return \moodle_page Page object representing course view
     * @copyright 2017 The Open University
     */
    protected static function construct_page($course = null) {
        // If course is not provided, setup a system context (no course)
        $page = new \moodle_page();
        if (!$course) {
            $context = \context_system::instance();
            $pagetype = 'my-index';
        } else {
            $context = \context_course::instance($course->id);
            $pagetype = 'course-view';
            $page->set_course($course);
        }
        $page->set_context($context);
        $page->set_pagelayout('standard');
        $page->set_pagetype($pagetype);
        $page->blocks->load_blocks();
        return $page;
    }

    /**
     * Test block manager contains block in a course context
     */
    public function test_block_manager_contains() {
        // Create course and add block.
        $generator = $this->getDataGenerator();
        $course = $generator->create_course();
        $block = $this->create_block($course);

        $this->assertInstanceOf('\block_course_participants', $block);
    }
    
    /**
     * Test block available in course context
     */
    public function test_block_available_course_context() {
        $generator = $this->getDataGenerator();
        $course = $generator->create_course();
        $page = self::construct_page($course);
        
        $addableblocks = $page->blocks->get_addable_blocks();

        $this->assertArrayHasKey('course_participants', $addableblocks);
    }

    /**
     * Test block unavailable in system context
     */
    public function test_block_unavailable_wrong_context() {
        $page = self::construct_page();

        $addableblocks = $page->blocks->get_addable_blocks();

        $this->assertArrayNotHasKey('course_participants', $addableblocks);
    }

    /**
     * Test block retrieves course participants
     */
    public function test_object_fetches_course_participants() {
        $generator = $this->getDataGenerator();
        $course = $generator->create_course();

        // Create some users and enroll in course
        for ($i = 0; $i < 4; $i++) {
            $user = $generator->create_user();
            $generator->enrol_user($user->id, $course->id);
            $participants[$user->id] = $user;
        }
        $block = $this->create_block($course);

        // Test individual attributes as some are added by intervening machinery
        $retrievedusers = $block->participants;
        foreach ([
            'id', 
            'username', 
            'firstname', 
            'lastname', 
            'email', 
            'lastnamephonetic', 
            'firstnamephonetic', 
            'middlename', 
            'alternatename',
        ] as $testattribute) {
            foreach ($retrievedusers as $key => $retrieveduser) {
                $this->assertEquals($participants[$key]->$testattribute, $retrieveduser->$testattribute);
            }
        }

        // Also test that repeated retrieval yields identical results (test magic_get_)
        $reretrieval = $block->participants;
        $this->assertEquals($retrievedusers, $reretrieval);
    }
}
