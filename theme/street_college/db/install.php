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
 * Trigger install processes:
 * 1. Remove unwanted default dashboard blocks
 * 
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

function xmldb_theme_street_college_install() {
    /**
     * 1. Remove unwanted default dashboard blocks
     */
    // First purge all blocks
    blocks_delete_all_for_context(context_system::instance()->id);

    // clone of blocklib's blocks_add_default_system_blocks() with our desired values
    global $DB;

    $page = new moodle_page();
    $page->set_context(context_system::instance());

    if (
        $defaultmypage = $DB->get_record(
            'my_pages', 
            array('userid' => null, 'name' => '__default', 'private' => 1)
        )
    ) {
        $subpagepattern = $defaultmypage->id;
    } else {
        $subpagepattern = null;
    }

    $newcontent = array('recentlyaccessedcourses', 'myoverview');
    $page->blocks->add_blocks(array('content' => $newcontent), 'my-index', $subpagepattern);
}