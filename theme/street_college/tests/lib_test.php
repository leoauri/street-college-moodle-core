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
 * Unit tests for adding the notes section to user profile in .
 *
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/theme/street_college/lib.php');

/**
 * Unit tests for adding the notes section to user profile.
 *
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_street_college_myprofile_add_notes_test extends advanced_testcase {
    protected $testtree;
    protected $testuser;
    protected $testcourse;

    protected function setUp() {
        $this->resetAfterTest(true);
        set_config('theme', 'street_college');
        set_config('enablenotes', true);
        
        $this->testtree = new \core_user\output\myprofile\tree;

        // Copy the tree for comparitive tests
        $this->testtreecopy = clone $this->testtree;

        $this->testuser = $this->getDataGenerator()->create_user();
        $this->setUser($this->testuser);
        
        $this->testcourse = $this->getDataGenerator()->create_course();
    }

    protected function get_roleid($context = null) {
        global $USER;
        if ($context === null) {
            $context = context_system::instance();
        }
        if (is_object($context)) {
            $context = $context->id;
        }
        if (empty($this->roles)) {
            $this->roles = array();
        }
        if (empty($this->roles[$USER->id])) {
            $this->roles[$USER->id] = array();
        }
        if (empty($this->roles[$USER->id][$context])) {
            $this->roles[$USER->id][$context] = create_role('Role for '.$USER->id.' in '.$context, 'role'.$USER->id.'-'.$context, '-');
            role_assign($this->roles[$USER->id][$context], $USER->id, $context);
        }
        return $this->roles[$USER->id][$context];
    }
    
    protected function assign_capability($capability, $permission = CAP_ALLOW, $contextid = null) {
        if ($contextid === null) {
            $contextid = context_system::instance();
        }
        if (is_object($contextid)) {
            $contextid = $contextid->id;
        }
        assign_capability($capability, $permission, $this->get_roleid($contextid), $contextid, true);
        accesslib_clear_all_caches_for_unit_testing();
    }

    public function test_notes_added_context_system() {
        $this->assign_capability('moodle/notes:view');

        // Function modifies tree in place
        theme_street_college_myprofile_navigation(
            $this->testtree, $this->testuser, null, null
        );
        
        $this->assertArrayHasKey('notes', $this->testtree->categories);
        $this->assertArrayHasKey('newnote', $this->testtree->nodes);
        $this->assertEquals('notes', $this->testtree->nodes['newnote']->parentcat);
    }

    public function test_notes_added_context_course() {
        $context = context_course::instance($this->testcourse->id);
        $this->assign_capability('moodle/notes:view', CAP_ALLOW, $context);

        // Function modifies tree in place
        theme_street_college_myprofile_navigation(
            $this->testtree, $this->testuser, null, $this->testcourse
        );
        
        $this->assertArrayHasKey('notes', $this->testtree->categories);
        $this->assertArrayHasKey('newnote', $this->testtree->nodes);
        $this->assertEquals('notes', $this->testtree->nodes['newnote']->parentcat);
        
        // Test that url has courseid param
        $nodeurl = new ReflectionObject($this->testtree->nodes['newnote']->url);
        $nodeparams = $nodeurl->getProperty('params');
        $nodeparams->setAccessible(true);
        $this->assertEquals(
            // The course id
            $this->testcourse->id, 
            // The url param stored in the tree object
            $nodeparams->getValue($this->testtree->nodes['newnote']->url)['courseid']
        );
    }

    public function test_no_capability() {
        theme_street_college_myprofile_navigation(
            $this->testtree, $this->testuser, null, null
        );

        $this->assertEquals($this->testtreecopy, $this->testtree);
    }

    public function test_no_capability_context_course() {
        theme_street_college_myprofile_navigation(
            $this->testtree, $this->testuser, null, $this->testcourse
        );

        $this->assertEquals($this->testtreecopy, $this->testtree);
    }


    public function test_notes_disabled() {
        set_config('enablenotes', false);

        // Function modifies tree in place
        theme_street_college_myprofile_navigation(
            $this->testtree, $this->testuser, null, $this->testcourse
        );

        $this->assertEquals($this->testtreecopy, $this->testtree);
    }
}