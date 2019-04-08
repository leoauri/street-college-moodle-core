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
 * Builds flat navigation for street college theme.
 * 
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace theme_street_college\navigation;

use moodle_url;
use pix_icon;

defined('MOODLE_INTERNAL') || die;

/**
 * Builds flat navigation for street college theme.
 * 
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class flat_navigation implements \IteratorAggregate {
    /** 
     * @var moodle_page the moodle page that the navigation belongs to 
     */
    protected $page;

    /**
     * @var array collection of navigation_node objects
     */
    protected $collection = array();

    /**
     * Constructor.
     *
     * @param moodle_page $page
     */
    public function __construct(\moodle_page &$page) {
        if (during_initial_install()) {
            return false;
        }
        $this->page = $page;
    }

    public function add_node(navigation_node $node) {
        $this->collection[] = $node;
    }

    /**
     * Add nodes mapping the position in the site hierarchy
     */
    public function add_hierarchy() {
        $indent = 0;
        // Add root dashboard node
        $this->add_node(new navigation_node(
            'myhome', 
            get_string('myhome'), 
            new moodle_url('/my/'),
            new pix_icon('i/dashboard', ''),
            $indent++
        ));

        // If we are not in site context, add second node for course context
        if ($this->page->course->id != SITEID) {
            $this->add_node(new navigation_node(
                'course_' . $this->page->course->id,
                format_string(
                    $this->page->course->shortname, 
                    true, 
                    array('context' => $this->page->context)
                ),
                new moodle_url('/course/view.php', ['id' => $this->page->course->id]),
                new pix_icon('i/course', ''),
                $indent++
            ));
        }
    }

    /**
     * @return IteratorAggregate nodes from the collection
     */
    public function getIterator() {
        foreach ($this->collection as $node) {
            yield $node;
        }
    }
}