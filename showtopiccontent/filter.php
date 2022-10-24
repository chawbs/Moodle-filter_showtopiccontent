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
 * Filter main class for the filter_showtopiccontent plugin.
 *
 * @package   filter_showtopiccontent
 * @copyright 2022, DJ Dave <chawbs@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/mod/lesson/locallib.php');

class filter_showtopiccontent extends moodle_text_filter {
    // local function to get list of numeric values
    function get_numerics ($str) {
        preg_match_all('/\d+/', $str, $matches);
        return $matches[0];
    }

    function filter($text, array $options = array()) {
        global $DB, $CFG, $PAGE;

        // If we have empty data, just ignore everything
        if(!is_string($text) or empty($text)) {
            // ignore
            return $text;
        }

        $showtopiccontentarray = array();
        // if we are not in moodle/course/view, ignore
        if(strpos($_SERVER['REQUEST_URI'],'moodle/course/view')===false) {
            return $text;
        }
        $searchkeyword = get_config('filter_showtopiccontent', 'searchkeyword');
        $predivcss = get_config('filter_showtopiccontent', 'predivcss');
        $twocolumns = get_config('filter_showtopiccontent', 'maketwocolumns');
        $listdivcss = get_config('filter_showtopiccontent', 'listdivcss');

        // String to search for keyword and following numbers
        $searchstring = '/{' . $searchkeyword . ' ([0-9, ]*)}/';
        // String to search for when replacing contents
        $replaceme = '/\{'.$searchkeyword.' [\d\, ]*\}/';
        // if we do not have show_topic_contents, ignore
        if(preg_match($searchstring, $text, $showtopiccontentarray)===false) {
                return $text;
        }
        if(!count($showtopiccontentarray)) {
            return $text;
        }

        // Course modules are defined in following format:
        //      {search-key-word x,y,z}
        $showtopiccontentstring = $showtopiccontentarray[0];
        $topic_ids = self::get_numerics($showtopiccontentstring);

        // get the course we are in
        $coursectx = $this->context->get_course_context(false);
        if (!$coursectx) {
            return $text;
        }
        $courseid = $coursectx->instanceid;
        $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

        $newtext = '';
        foreach($topic_ids as $topicid) {
            // get the cm - making sure its a valid topic
            $cm = null;
            $cminfo = get_fast_modinfo($courseid);
            if($cminfo!=null) {
                try {
                    $cm = $cminfo->get_cm($topicid);
                }
                catch (Exception $e) {
                    $cm = null;
                }
            }

            // initialise our local list
            $ulistarray = array();
            $amarabic = false;
            $cmid = 0;

            // if $cm does not exist, skip
            if($cm==null) {
                // We have an error - show error string
                $ulistarray[] = get_string('missingtopicid','filter_showtopiccontent',['topicid'=>$topicid]);
            } else {
                $cmid = $cm->id;
                // Get the appropriate Lesson and find headings
                $lesson = new lesson($DB->get_record('lesson',array('id' => $cm->instance), '*', MUST_EXIST));

                // Check if invalid ID given
                if($lesson->properties()->course != $courseid) {
                    // We have an error - show error string
                    $ulistarray[] = get_string('badtopicid','filter_showtopiccontent',['topicid'=>$topicid]);
                } else {
                    $pageid = $lesson->firstpageid;
    
                    // Loop through until last page in unit
                    while ($pageid != 0) {
                        $page = $lesson->load_page($pageid);
                        $url = new moodle_url('/mod/lesson/view.php', array(
                            'id'     => $cm->id,
                            'pageid' => $page->id
                        ));
                        $pageid = $page->nextpageid;
                        // check for Arabic text (simplified test!!!)
                        if (preg_match('/[اأإء-ي]/ui', $page->title)) {
                            $amarabic = true;
                        }
                        $ulistarray[] = html_writer::link($url, format_string($page->title, true), array('id' => 'lesson-' . $page->id));
                    }
                }
            }
    
            $prehtml = "<div style='".$predivcss."' class='lesson-list-".$cmid."'>";
            $posthtml = "</br></div>";
            $divattributesl['style']="float: left; ".$listdivcss;
            $divattributesr['style']="float: right; ".$listdivcss;
            if($amarabic) {
                $arabic['dir']="rtl";
                $divattributesl['style'].=" text-align: right;";
                $divattributesr['style'].=" text-align: right;";
                }
            else {
                $arabic = null;
            }
    
            $arrlen = (int) count($ulistarray);
            if($twocolumns) {
                $half = (int) round($arrlen / 2);
                if($arrlen > 4) {
                    $firsthalf = array_slice($ulistarray,0,$half);
                    $secondhalf = array_slice($ulistarray,$half,$arrlen);
                    $newtext .= $prehtml.html_writer::div(html_writer::alist($firsthalf,$arabic),'',$divattributesl) .
                        html_writer::div(html_writer::alist($secondhalf,$arabic),'',$divattributesr).$posthtml;
                }
                else {
                    $thelist = html_writer::alist($ulistarray,$arabic);
                    $newtext .= $prehtml.html_writer::div($thelist,'',$divattributesl).$posthtml;
                }
            } else {
                $thelist = html_writer::alist($ulistarray,$arabic);
                $newtext .= $prehtml.html_writer::div($thelist,'',$divattributesl).$posthtml;
            }
        }
    
        // Return the modified text.
        $text = preg_replace($replaceme,$newtext,$text);
        return $text;
    }
}
