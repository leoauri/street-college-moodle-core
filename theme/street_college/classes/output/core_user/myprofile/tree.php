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

class tree extends \core_user\output\myprofile\tree {
    /**
     * @var category[] Array of categories in the tree.
     */
    public $categories = array();

    /**
     * @var node[] Array of nodes in the tree that were directly added to the tree.
     */
    public $nodes = array();

    /**
     * @var array List of properties accessible via __get.
     */
    public $properties = array('categories', 'nodes');


    public function duplicate_tree($tree) {
        $this->categories = $tree->categories;
        $this->nodes = $tree->nodes;
    }

    public function keep_only_categories(array $keep_categories) {    
        $this->categories = array_intersect_key($this->categories, array_flip($keep_categories));
    }
}