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

use html_writer;
use context_header;
use stdClass;

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

    /**
     * Renders the header bar.
     *
     * @param context_header $contextheader Header bar object.
     * @return string HTML for the header bar.
     */
    protected function render_context_header(context_header $contextheader) {

        $showheader = empty($this->page->layout_options['nocontextheader']);
        if (!$showheader) {
            return '';
        }

        $html = '';

        // Headings.
        if (!isset($contextheader->heading)) {
            $headings = $this->heading($this->page->heading, $contextheader->headinglevel);
        } else {
            $headings = $this->heading($contextheader->heading, $contextheader->headinglevel);
        }
        
        $html .= html_writer::tag('div', $headings, array('class' => 'page-header-headings'));
        
        // Buttons.
        if (isset($contextheader->additionalbuttons)) {
            $html .= html_writer::start_div('btn-group header-button-group');
            foreach ($contextheader->additionalbuttons as $button) {
                if (!isset($button->page)) {
                    // Include js for messaging.
                    if ($button['buttontype'] === 'togglecontact') {
                        \core_message\helper::togglecontact_requirejs();
                    }
                    $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', array(
                        'class' => 'iconsmall',
                        'role' => 'presentation'
                    ));
                    $image .= html_writer::span($button['title'], 'header-button-title');
                } else {
                    $image = html_writer::empty_tag('img', array(
                        'src' => $button['formattedimage'],
                        'role' => 'presentation'
                    ));
                }
                $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
            }
            $html .= html_writer::end_div();
        }
        return $html;
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->image = $this->context_header_image();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        return $this->render_from_template('theme_street_college/header', $header);
    }

    public function context_header_image($headerinfo = null, $headinglevel = 1) {
        global $DB;
        if ($this->page->url->get_path() == '/user/view.php') {
            switch ($headinglevel) {
                // Use the user heading rather than course heading in /user/view
                case 1:
                    $userid = $this->page->url->get_param('id');
                    $user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);

                    $headerinfo['user'] = $user;
                    break;

                // Remove repeated heading from /user/view
                case 2:
                    return;
            }
        }

        $context = $this->page->context;
        $imagedata = null;
        
        if (isset($headerinfo['user']) || $context->contextlevel == CONTEXT_USER) {
            if (isset($headerinfo['user'])) {
                $user = $headerinfo['user'];
            } else {
                // Look up the user information if it is not supplied.
                $user = $DB->get_record('user', array('id' => $context->instanceid));
            }

            // Only provide user information if the user is the current user, or a user which the current user can view.
            // When checking user_can_view_profile(), either:
            // If the page context is course, check the course context (from the page object) or;
            // If page context is NOT course, then check across all courses.
            $course = ($this->page->context->contextlevel == CONTEXT_COURSE) ? $this->page->course : null;
    
            if (user_can_view_profile($user, $course)) {
                $imagedata = $this->user_picture($user, array('size' => 100));
            }
        }

        return $imagedata;
    }
}
