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
 * Class definition for the course participants block.
 *
 * @package    block_course_participants
 * @copyright  2019 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Course participants block class.
 *
 * @package    block_course_participants
 * @copyright  2019 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_course_participants extends block_list {
    /**
     * Array of user objects of course participants
     */
    protected $_participants = null;

    protected function magic_get_participants() {
        if ($this->_participants === null) {
            // global $DB;
            $context = context_course::instance($this->page->course->id);
            $this->_participants = get_role_users(5, $context);
        }
        return $this->_participants;
    }

    public function __get($name) {
        $getmethod = 'magic_get_' . $name;
        if (method_exists($this, $getmethod)) {
            return $this->$getmethod();
        } else {
            return parent::__get();
        }
    }

    public function init() {
        $this->title = get_string('pluginname', 'block_course_participants');
    }

    public function applicable_formats() {
        return array('course-view' => true);
    }
}