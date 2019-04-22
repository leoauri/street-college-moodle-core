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
 * Behat navigation overrides.
 *
 * @package   theme_street_college
 * @category  test
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

/**
 * Behat navigation overrides for street college theme.
 * 
 * @copyright 2019 Leo Auri <code@leoauri.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_street_college_behat_navigation extends behat_navigation {
    /**
     * Locates the administration menu in the <header> element and returns its xpath
     *
     * @param bool $mustexist if specified throws an exception if menu is not found
     * @return null|string
     */
    protected function find_header_administration_menu($mustexist = false) {
        $menuxpath = '//header[@id=\'page-header\']//div[contains(@class,\'moodle-actionmenu\')]';
        if ($mustexist) {
            $exception = new ElementNotFoundException($this->getSession(), 'Page header administration menu is not found');
            $this->find('xpath', $menuxpath, $exception);
        } else if (!$this->getSession()->getPage()->find('xpath', $menuxpath)) {
            return null;
        }
        return $menuxpath;
    }

    /**
     * Locates the administration menu on the page (but not in the header) and returns its xpath
     *
     * @param bool $mustexist if specified throws an exception if menu is not found
     * @return null|string
     */
    protected function find_page_administration_menu($mustexist = false) {
        $menuxpath = '//div[@id=\'region-main-settings-menu\']';
        if ($mustexist) {
            $exception = new ElementNotFoundException($this->getSession(), 'Page administration menu is not found');
            $this->find('xpath', $menuxpath, $exception);
        } else if (!$this->getSession()->getPage()->find('xpath', $menuxpath)) {
            return null;
        }
        return $menuxpath;
    }

    /**
     * Toggles administration menu
     *
     * @param string $menuxpath (optional) xpath to the page administration menu if already known
     */
    protected function toggle_page_administration_menu($menuxpath = null) {
        if (!$menuxpath) {
            $menuxpath = $this->find_header_administration_menu() ?: $this->find_page_administration_menu();
        }
        if ($menuxpath && $this->running_javascript()) {
            $this->find('xpath', $menuxpath . '//a[@data-toggle=\'dropdown\']')->click();
            $this->wait_for_pending_js();
        }
    }

    /**
     * Finds and clicks a link on the admin page (site administration or course administration)
     *
     * @param array $nodelist
     */
    protected function select_on_administration_page($nodelist) {
        $parentnodes = $nodelist;
        $lastnode = array_pop($parentnodes);
        $xpath = '//section[@id=\'region-main\']';

        // Check if there is a separate tab for this submenu of the page. If found go to it.
        if ($parentnodes) {
            $tabname = behat_context_helper::escape($parentnodes[0]);
            $tabxpath = '//ul[@role=\'tablist\']/li/a[contains(normalize-space(.), ' . $tabname . ')]';
            if ($node = $this->getSession()->getPage()->find('xpath', $tabxpath)) {
                if ($this->running_javascript()) {
                    // Click on the tab and add 'active' tab to the xpath.
                    $node->click();
                    $xpath .= '//div[contains(@class,\'active\')]';
                } else {
                    // Add the tab content selector to the xpath.
                    $tabid = behat_context_helper::escape(ltrim($node->getAttribute('href'), '#'));
                    $xpath .= '//div[@id = ' . $tabid . ']';
                }
                array_shift($parentnodes);
            }
        }

        // Find a section with the parent name in it.
        if ($parentnodes) {
            // Find the section on the page (links may be repeating in different sections).
            $section = behat_context_helper::escape($parentnodes[0]);
            $xpath .= '//div[@class=\'row\' and contains(.,'.$section.')]';
        }

        // Find a link and click on it.
        $linkname = behat_context_helper::escape($lastnode);
        $xpath .= '//a[contains(normalize-space(.), ' . $linkname . ')]';
        if (!$node = $this->getSession()->getPage()->find('xpath', $xpath)) {
            throw new ElementNotFoundException($this->getSession(), 'Link "' . join(' > ', $nodelist) . '"" not found on the page');
        }
        $node->click();
        $this->wait_for_pending_js();
    }

    /**
     * Finds a page edit cog and select an item from it
     *
     * If the page edit cog is in the page header and the item is not found there, click "More..." link
     * and find the item on the course/frontpage administration page
     *
     * @param array $nodelist
     * @throws ElementNotFoundException
     */
    protected function select_from_administration_menu($nodelist) {
        // Find administration menu.
        if ($menuxpath = $this->find_header_administration_menu()) {
            $isheader = true;
        } else {
            $menuxpath = $this->find_page_administration_menu(true);
            $isheader = false;
        }

        $this->toggle_page_administration_menu($menuxpath);

        if (!$isheader || count($nodelist) == 1) {
            $lastnode = end($nodelist);
            $linkname = behat_context_helper::escape($lastnode);
            $link = $this->getSession()->getPage()->find('xpath', $menuxpath . '//a[contains(normalize-space(.), ' . $linkname . ')]');
            if ($link) {
                $link->click();
                $this->wait_for_pending_js();
                return;
            }
        }

        if ($isheader) {
            // Course administration and Front page administration will have subnodes under "More...".
            $linkname = behat_context_helper::escape(get_string('morenavigationlinks'));
            $link = $this->getSession()->getPage()->find('xpath', $menuxpath . '//a[contains(normalize-space(.), ' . $linkname . ')]');
            if ($link) {
                $link->click();
                $this->execute('behat_general::wait_until_the_page_is_ready');
                $this->select_on_administration_page($nodelist);
                return;
            }
        }

        throw new ElementNotFoundException($this->getSession(),
            'Link "' . join(' > ', $nodelist) . '" not found in the current page edit menu"');
    }

    public function i_navigate_to_in_current_page_administration($nodetext) {
        $nodelist = array_map('trim', explode('>', $nodetext));
        $this->select_from_administration_menu($nodelist);
    }
}