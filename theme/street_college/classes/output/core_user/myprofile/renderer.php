<?php
// This file is part of The Bootstrap Moodle theme
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

namespace theme_street_college\output\core_user\myprofile;

defined('MOODLE_INTERNAL') || die;

class renderer extends \core_user\output\myprofile\renderer {
    public function render(\renderable $widget) {
        // if widget is tree, duplicate as our tree (which we can modify) and trim categories
        if (get_class($widget) == 'core_user\output\myprofile\tree') {
            $widgetclone = new tree();
            $widgetclone->duplicate_tree($widget);
            $widgetclone->remove_categories([
                'privacyandpolicies',
                'miscellaneous',
                'reports',
                'administration',
                'loginactivity',
                'coursedetails',
            ]);
            $widget = $widgetclone;
            // print_object($widget->categories);
        }
        return parent::render($widget);
    }
}
