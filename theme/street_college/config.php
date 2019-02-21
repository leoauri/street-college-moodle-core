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


// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// $THEME is defined before this page is included and we can define settings by adding properties to this global object.

$THEME->name = 'street_college';

// Inherit from parent theme
$THEME->parents = ['boost'];

$THEME->scss = function($theme) {
    return theme_street_college_get_main_scss($theme);
};

// The render factory that allows us to override other renderers
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

// Show the theme in selector
$THEME->hidefromselector = false;


// There's no dock 
$THEME->enable_dock = false;

// Required blocks
$THEME->requiredblocks = '';

// Don't use "add a block" block to save space
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;

// CSS sheets to include (we use SCSS instead)
$THEME->sheets = [];

// Practically deprecated things
$THEME->editor_sheets = [];
$THEME->yuicssmodules = array();
