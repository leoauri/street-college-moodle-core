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
 * Unit tests for flat navigation.
 *
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_street_college\navigation\navigation_node;
use theme_street_college\navigation\flat_navigation;

defined('MOODLE_INTERNAL') || die();

/**
 * Unit tests for flat navigation.
 *
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_street_college_navigation_test extends advanced_testcase {
    protected function setUp() {
        $this->resetAfterTest(true);
        set_config('theme', 'street_college');
    }

    /**
     * Get the flatnav into an array for a page
     * @var moodle_page
     * @return array of navigation_nodes produced by flat_navigation
     */
    protected static function flat_navigation_array($page) {
        $flatnav = new flat_navigation($page);
        $flatnav->add_hierarchy();

        $flatnavarray = [];
        foreach ($flatnav as $node) {
            $flatnavarray[] = $node;
        }

        return $flatnavarray;
    }

    /**
     * Assert that the passed navigation_node is valid for the dashboard
     * @var theme_street_college\navigation\navigation_node
     */
    protected function assertIsDashboard(navigation_node $node) {
        $this->assertEquals('myhome', $node->key);
        $this->assertEquals('Dashboard', $node->text);
        $this->assertEquals('/moodle/my/', $node->action->get_path());
        $this->assertEquals('i/dashboard', $node->icon->pix);
    }

    /**
     * Assert number of nodes flat_navigation as array contains
     * @var array flat_navigation nodes already copied into an array
     * @var int expected number of nodes
     */
    protected function assertNumberOfNodes(array $flatnavarray, int $number) {
        $this->assertEquals($number, count($flatnavarray), "Failed asserting that flat_navigation contains $number nodes");
    }

    /**
     * Test that hierarchy contains the root dashboard node.
     */
    public function test_dashboard_node() {
        $page = new moodle_page();

        $flatnavarray = self::flat_navigation_array($page);

        $this->assertNumberOfNodes($flatnavarray, 1);
        $this->assertIsDashboard($flatnavarray[0]);
    }

    /**
     * Test that hierarchy in course context:
     *  - contains 2 elements
     *  - contains dashboard node
     *  - contains course node
     */
    public function test_hierarchy_course_context() {
        $page = new moodle_page();
        $course = $this->getDataGenerator()->create_course();
        $page->set_context(context_course::instance($course->id));
        $page->set_course($course);

        $flatnavarray = self::flat_navigation_array($page);
        $this->assertIsDashboard($flatnavarray[0]);

        $this->assertNumberOfNodes($flatnavarray, 2);

        $finalnode = end($flatnavarray);

        $this->assertEquals('course_' . $course->id, $finalnode->key);
        $this->assertEquals(format_string(
            $page->course->shortname, 
            true, 
            array('context' => $page->context)
        ), $finalnode->text);
        $this->assertEquals(
            new moodle_url('/course/view.php', array('id' => $course->id)), 
            $finalnode->action
        );
        $this->assertEquals('i/course', $finalnode->icon->pix);
    }
}
