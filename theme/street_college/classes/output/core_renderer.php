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

namespace theme_street_college\output;

defined('MOODLE_INTERNAL') || die;

class core_renderer extends \theme_boost\output\core_renderer {
    public function header() {
        global $DB;
        if ($this->page->url->get_path() == '/user/view.php') {
            // debugging('Resetting things for user view');
            $this->page->set_pagetype('user-profile');
            
            // Stealing protected properties
            $shortname = \Closure::bind(
                function() {
                    return $this->_course->shortname;
                }, 
                $this->page, 
                'moodle_page'
            )();

            $url = \Closure::bind(
                function() {
                    return $this->_url;
                }, 
                $this->page, 
                'moodle_page'
            )();
            $userid = \Closure::bind(
                function() {
                    return $this->params['id'];
                }, 
                $url, 
                'moodle_url'
            )();
            $user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
            
            // Override to shorter page title
            $this->page->set_title("$shortname: " . fullname($user));

            // print_object(fullname($user));
            
        }
        
        return parent::header();
    }

    public function context_header($headerinfo = null, $headinglevel = 1) {
        if ($this->page->url->get_path() == '/user/view.php') {
            switch ($headinglevel) {
                // Use the user heading rather than course heading in /user/view
                case 1:
                    global $DB;
                    $userid = $this->page->url->get_param('id');
                    $user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);

                    $headerinfo['user'] = $user;
                    break;

                // Remove repeated heading from /user/view
                case 2:
                    return;
            }
        }

        return parent::context_header($headerinfo, $headinglevel);
    }
}
