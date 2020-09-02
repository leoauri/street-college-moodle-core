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
 * Filter to add links to course sections
 *
 * @package    filter
 * @subpackage sectionpermalinks
 * @copyright  2020 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class filter_sectionpermalinks extends moodle_text_filter {
    public function filter($text, array $options = array()) {
        // Get array of section link tags
        $foundtags = [];
        preg_match_all('/\[topic-&gt;(?<topictitle>.*?)]/', $text, $foundtags, PREG_SET_ORDER);

        $linkedsections = [];
        foreach ($foundtags as $foundtag) {
            $linkedsections[] = $foundtag['topictitle'];
        }
        $linkedsections = array_unique($linkedsections);

        // Build filterobjects according to whether specified sections can be found
        $filters = [];
        foreach ($linkedsections as $sectiontitle) {
            $section = new filter_sectionpermalinks\sections\match($sectiontitle);

            // Tag to search for in text
            $searchtext = "[topic-&gt;$sectiontitle]";

            // If there is a match, filterobject with link
            if ($section) {
                $filters[] = new filterobject(
                    $searchtext, 
                    $section->link_open(),
                    '</a>',
                    true,
                    false,
                    $sectiontitle
                );
            } else {
                // If no match, filterobject with plain text title
                $filters[] = new filterobject($searchtext, '', '', true, false, $sectiontitle);
            }
        }

        // Replace section link tags with links or plain text if no such section is found
        $text = filter_phrases($text, $filters);

        return $text;
    }
}
