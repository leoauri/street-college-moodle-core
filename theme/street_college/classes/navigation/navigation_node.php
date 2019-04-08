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
 * Single node to be rendered by flat_navigation mustache template.
 * 
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace theme_street_college\navigation;

use moodle_url;
use pix_icon;

defined('MOODLE_INTERNAL') || die;

/**
 * Single node to be rendered by flat_navigation mustache template.
 * 
 * @package   theme_street_college
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navigation_node {
    /** @var int Used to identify this node a leaf (default) 0 */
    const NODETYPE_LEAF =   0;
    /** @var int Used to identify this node a branch, happens with children  1 */
    const NODETYPE_BRANCH = 1;
    
    /**
     * @var int Whether to start a new sub group with this node
     */
    public $showdivider;

    /**
     * @var string href value for the link.  Empty for no link
     */
    public $action;

    /**
     * @var bool Render css active and bold
     */
    public $isactive;

    /**
     * @var string data-key attribute
     */
    public $key;

    /**
     * @var int data-isexpandable attribute
     */
    public $isexpandable = false;

    /**
     * @var int Node indent level
     */
    public $get_indent = 0;

    /**
     * @var int data-type attribute
     */
    public $type;

    /**
     * @var int data-nodetype attribute
     */
    public $nodetype = self::NODETYPE_LEAF;

    /**
     * @var int data-collapse attribute
     */
    public $collapse = false;

    /**
     * @var int data-forceopen attribute
     */
    public $forceopen = false;

    /**
     * @var int data-hidden attribute
     */
    public $hidden;

    /**
     * @var int data-preceedwithhr attribute
     */
    public $preceedwithhr = false;

    /**
     * @var navigation_node parent node
     */
    public $parent;

    /**
     * @var \pix_icon icon object
     */
    public $icon;

    /**
     * @var string display text
     */
    public $text;

    /**
     * Constructor
     */
    public function __construct(
        string $key, 
        string $text, 
        moodle_url $action = null, 
        pix_icon $icon = null,
        int $indent = 0
    ) {
        $this->key = $key;
        $this->text = $text;
        $this->action = $action;
        $this->icon = $icon;
        $this->get_indent = $indent;
    }
}