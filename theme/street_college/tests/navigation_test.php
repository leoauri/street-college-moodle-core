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

    public function test_dashboard_node() {
        $page = new moodle_page();
        $flatnav = new theme_street_college\navigation\flat_navigation($page);
        $flatnav->add_hierarchy();

        $flatnavarray = [];
        foreach ($flatnav as $node) {
            $flatnavarray[] = $node;
        }

        $this->assertEquals('myhome', $flatnavarray[0]->key);
        $this->assertEquals('Dashboard', $flatnavarray[0]->text);
        $this->assertEquals('/moodle/my/', $flatnavarray[0]->action->get_path());
        $this->assertEquals('i/dashboard', $flatnavarray[0]->icon->pix);
    }
}