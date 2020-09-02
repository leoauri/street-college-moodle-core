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
 * Query and cache for fetching course sections from database.
 *
 * @package    filter_sectionpermalinks
 * @copyright  2020 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace filter_sectionpermalinks\sections;

defined('MOODLE_INTERNAL') || die();

class query {
    public static $sections = null;

    public static function course_sections() {
        if (self::$sections === null) {
            // Fetch course sections from database
            global $DB;
            self::$sections = $DB->get_records('course_sections');
        }

        return self::$sections;
    }
}