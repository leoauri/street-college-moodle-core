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
 * Manages customization of core navigation functionality.
 * 
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace theme_street_college\navigation;

defined('MOODLE_INTERNAL') || die;

class manager {
    /**
     * Trims the flat_navigation object as it is about to be passed to mustache template. We retain
     * just what we need to enable easy navigation and highlight intended site hierarchy.
     * 
     * @param flat_navigation $flatnav object about to hit the mustache template
     * @return flat_navigation trimmed to our needs
     */
    public static function trimmed_flatnav(\flat_navigation $flatnav) {
        // Array of nodes to remove
        $removenodes = [
            'home',
            'calendar',
            'privatefiles',
        ];
        // Only what is really present in the received object
        $removenodes = array_intersect($removenodes, $flatnav->get_key_list());
        
        // Remove the nodes
        foreach ($removenodes as $removenode) {
            $flatnav->remove($removenode);
        }

        return $flatnav;
    }

    /**
     * Build flat_navigation for street college theme.  
     * Calls object to add hierarchy
     * @return flat_navigation object ready for rendering by the mustache template for our theme
     */
    public static function get_flatnav(\moodle_page &$page) {
        $flatnav = new flat_navigation($page);
        $flatnav->add_hierarchy();
        // $flatnav->add_admin();
        return $flatnav;
    }
}
