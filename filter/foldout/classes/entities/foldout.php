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
 * Output of foldout sections.
 *
 * @package    filter_foldout
 * @copyright  2020 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace filter_foldout\entities;

defined('MOODLE_INTERNAL') || die();

class foldout {
    static $i = 0;

    public $instance;
    
    public $search;

    private $title;

    private function outeropeningdiv() {
        return \html_writer::start_div('foldout-outer closed');
    }

    private function controllink() {
        return \html_writer::link('#', $this->title, ['class' => 'foldout-control']);
    }

    private function inneropeningdiv() {
        return \html_writer::start_div('foldout-inner');
    }
    
    public function replacement() {
        return $this->outeropeningdiv() . $this->controllink() . $this->inneropeningdiv();
    }

    public static function closingdivs() {
        return \html_writer::end_div() . \html_writer::end_div();
    }
    
    public function __construct($openingtag) {
        $this->instance = self::$i++;
        $this->search = $openingtag[0];
        $this->title = $openingtag['title'];
    }
}