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
 * Class to match sections named in tags with database.
 *
 * @package    filter_sectionpermalinks
 * @copyright  2020 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace filter_sectionpermalinks\sections;

defined('MOODLE_INTERNAL') || die();

class match {
    public $coursesection;

    public function __construct($reftext) {
        foreach (query::course_sections() as $section) {
            // If name of course section matches reftext, store match
            if ($section->name == $reftext) {
                $this->coursesection = $section;
                break;
            }
        }
    }

    public function link_open() {
        if ($this->coursesection === null) {
            return '';
        }

        global $CFG;
        return \html_writer::start_tag('a', [
            'href' => "$CFG->wwwroot/course/view.php?id={$this->coursesection->course}#section-{$this->coursesection->section}"
            ]);
    }
}