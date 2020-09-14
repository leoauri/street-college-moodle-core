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
 * Fold out sections filter manager.
 *
 * @package    filter_foldout
 * @copyright  2020 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/*eslint no-trailing-spaces: "warn"*/
/*eslint no-unused-vars: "warn"*/
/*eslint no-console: "warn"*/

define([], function() {
    return {
        init: function() {
            const toggleVisible = function(event) {
                // get parent .foldout-outer node
                const foldout = event.target.parentElement;

                // // get sibling .foldout-inner node
                // const foldout = event.target.parentElement.querySelector('.foldout-inner');

                foldout.classList.toggle('closed');
                event.preventDefault();
            };

            // Find all foldout sections on page
            const foldoutSections = document.querySelectorAll(".foldout-outer");

            // Add event listeners
            foldoutSections.forEach(foldout => {
                foldout.querySelector("a.foldout-control")
                    .addEventListener('click', toggleVisible);
            });
        }
    };
});