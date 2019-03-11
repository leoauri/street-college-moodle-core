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

use core_user\output\myprofile\tree;

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

function theme_street_college_get_main_scss($theme) {
    global $CFG;

    $scss = '';

    $scss_files = array(
        '/theme/street_college/scss/pre.scss',
        '/theme/boost/scss/preset/default.scss',
        '/theme/street_college/scss/post.scss',
    );

    foreach ($scss_files as $scss_file) {
        $scss_path = $CFG->dirroot . $scss_file;
        if (file_exists($scss_path)) {
            $scss .= file_get_contents($scss_path);
        }
    }

    return $scss;
}

function theme_street_college_myprofile_navigation(tree $tree, $user, $iscurrentuser, $course) {
    theme_street_college_myprofile_add_notes($tree, $user, $iscurrentuser, $course);
}


function theme_street_college_myprofile_add_notes(tree $tree, $user, $iscurrentuser, $course) {
    global $CFG;

    if (empty($CFG->enablenotes)) {
        // Notes are disabled, nothing to do.
        return false;
    }

    if (isguestuser($user)) {
        // No notes for guest users.
        return false;
    }

    $url = new moodle_url(
        '/notes/edit.php', 
        ['userid' => $user->id, 'publishstate' => 'site']
    );

    if (empty($course)) {
        // Site level profile.
        if (!has_capability('moodle/notes:view', context_system::instance())) {
            // No cap, nothing to do.
            return false;
        }
    } else {
        if (!has_capability('moodle/notes:view', context_course::instance($course->id))) {
            // No cap, nothing to do.
            return false;
        }
        $url->param('courseid', $course->id);
    }

    // Add notes category
    $notescategory = new core_user\output\myprofile\category(
        'notes', 
        get_string('usernotes', 'theme_street_college'),
        'contact'
    );
    $tree->add_category($notescategory);
    // Add link to add notes
    $node = new core_user\output\myprofile\node(
        'notes', 
        'newnote', 
        get_string('newnote', 'theme_street_college'), 
        null, 
        $url
    );
    $tree->add_node($node);
}
