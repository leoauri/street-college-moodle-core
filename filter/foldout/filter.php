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
 * Filter to hide content in fold out sections
 *
 * @package    filter
 * @subpackage foldout
 * @copyright  2020 Leo Auri <code@leoauri.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use filter_foldout\entities\foldout;

class filter_foldout extends moodle_text_filter {
    public function filter($text, array $options = array()) {
        // Search text for opening tags
        $openingtags = [];
        preg_match_all('/\[foldout (?<title>.*?)]/', $text, $openingtags, PREG_SET_ORDER);
        
        if (count($openingtags)) {
            // Array for filterobjects
            $replacements = [];
    
            // Create filterobjects for found opening tags
            foreach ($openingtags as $openingtag) {
                $foldout = new foldout($openingtag);
                $replacement = new stdClass();
                $replacement->search = $foldout->search;
                $replacement->replace = $foldout->replacement();
                $replacements[] = $replacement;
            }

            // // If opening tags found, add closing tag filterobject
            // $replacement = new stdClass();
            // $replacement->search = '[/foldout]';
            // $replacement->replace = foldout::closingdivs();
            // $replacements[] = $replacement;
    
            // If opening tags found, require JS for foldout manipulation
            global $PAGE;
            $PAGE->requires->js_call_amd('filter_foldout/foldout_manager', 'init');
    
            // Count closing tags in text.  
            $closingtags = [];
            preg_match_all('/\[\/foldout]/', $text, $closingtags, PREG_SET_ORDER);
            
            // Run the replacements
            foreach($replacements as $r) {
                $text = str_replace($r->search, $r->replace, $text);
            }

            // Replace closing tags, but only as many as there are opening tags
            for ($i = 0; $i < count($openingtags); $i++) {
                $pos = strpos($text, '[/foldout]');
                if ($pos !== false) {
                    $text = substr_replace(
                        $text, 
                        foldout::closingdivs(), 
                        $pos, 
                        strlen('[/foldout]')
                    );
                }
            }
            
            // If there are less than opening tags, append enough closing tags to text
            if (count($closingtags) < count($openingtags)) {
                for ($i = count($openingtags) - count($closingtags); $i > 0; $i--) { 
                    $text .= foldout::closingdivs();
                }
            }
        }

        return $text;
    }
}
