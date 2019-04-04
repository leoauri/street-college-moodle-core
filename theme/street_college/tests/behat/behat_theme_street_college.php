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
 * Behat step definitions for street college theme.
 *
 * @package   theme_street_college
 * @category  test
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Mink\Exception\ElementNotFoundException as ElementNotFoundException;

class behat_theme_street_college extends behat_base {
    /**
     * Opens the flat navigation drawer if it is not already open
     *
     * @When /^I open flat navigation drawer$/
     * @throws ElementNotFoundException Thrown by behat_base::find
     */
    public function i_open_flat_navigation_drawer() {
        if (!$this->running_javascript()) {
            // Navigation drawer is always open without JS.
            return;
        }
        $xpath = "//button[contains(@data-action,'toggle-drawer')]";
        $node = $this->find('xpath', $xpath);
        $expanded = $node->getAttribute('aria-expanded');
        if ($expanded === 'false') {
            $node->click();
            $this->ensure_node_attribute_is_set($node, 'aria-expanded', 'true');
            $this->wait_for_pending_js();
        }
    }
}