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

use Behat\Mink\Exception\ExpectationException as ExpectationException;
use Behat\Mink\Exception\ElementNotFoundException as ElementNotFoundException;

class behat_theme_street_college extends behat_base {
    /**
     * Check that the given selector matches a given number of times.
     * 
     * @Then /^there should be "(?P<instances_number>\d+)" instances? of "(?P<element_string>(?:[^"]|\\")*)" "(?P<selector_string>(?:[^"]|\\")*)"$/
     * @throws ExpectationException
     * @param int $instances expected number of occurences
     * @param $element element selector
     * @param $selectortype selector type
     */
    public function should_be_instances_of(int $instances, $element, $selectortype) {
        list($selector, $locator) = $this->transform_selector($selectortype, $element);

        $count = count($this->find_all($selector, $locator));

        if ($count != $instances) {
            throw new ExpectationException(
                "\"$element\" \"$selectortype\" found $count times", 
                $this->getSession()
            );
        }
    }

}
