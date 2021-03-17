<?php
// This file is part of The Course Module Navigation Block
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
 * Course contents block generates a table of course contents based on the section descriptions.
 *
 * @package    block_aprende_coursenavigation
 * @copyright  2019 Pimenko <contact@pimenko.com> <pimenko.com>
 * @author     Sylvain Revenu | Nick Papoutsis | Bas Brands | Pimenko
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/format/lib.php');

/**
 * Define the block course navigation.
 *
 * @package    block_aprende_coursenavigation
 * @copyright  2019 Pimenko <contact@pimenko.com> <pimenko.com>
 * @author     Sylvain Revenu | Nick Papoutsis | Bas Brands | Pimenko
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_aprende_coursenavigation extends block_base {

    /**
     * Initializes the block, called by the constructor.
     */
    public function init() {
        $this->title = get_string(
                'pluginname',
                'block_aprende_coursenavigation'
        );
    }

    /**
     *  Allow parameters in admin settings
     */
    public function has_config() {
        return true;
    }

    /**
     * Amend the block instance after it is loaded.
     */
    public function specialization() {
        if (!empty($this->config->blocktitle)) {
            $this->title = $this->config->blocktitle;
        } else {
            $this->title = get_string(
                    'config_blocktitle_default',
                    'block_aprende_coursenavigation'
            );
        }
    }

    /**
     * Which page types this block may appear on.
     *
     * @return array
     */
    public function applicable_formats() {
        return [
                'site-index' => true,
                'course-view-*' => true
        ];
    }

    /**
     * Populate this block's content object.
     *
     * @return stdClass|stdObject
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function get_content() {
        global $DB, $PAGE, $USER;

        if (!is_null($this->content)) {
            return $this->content;
        }

        $selected = optional_param(
                'section',
                null,
                PARAM_INT
        );
        $intab = optional_param(
                'dtab',
                null,
                PARAM_TEXT
        );

        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text = '';

        if (empty($this->instance)) {
            return $this->content;
        }

        if ($PAGE->pagelayout == 'admin') {
            return $this->content;
        }

        $format = course_get_format($this->page->course);
        $course = $format->get_course(); // Needed to have numsections property available.

        if (!$format->uses_sections()) {
            if (debugging()) {
                $this->content->text = get_string(
                        'notusingsections',
                        'block_aprende_coursenavigation'
                );
            }
            return $this->content;
        }

        if (($format instanceof format_digidagotabs) || ($format instanceof format_horizontaltabs)) {
            // Don't show the menu in a tab.
            if ($intab) {
                return $this->content;
            }
            // Only show the block inside activities of courses.
            if ($this->page->pagelayout == 'incourse') {
                $sections = $format->tabs_get_sections();
            }
        } else {
            $sections = $format->get_sections();
        }

        if (empty($sections)) {
            return $this->content;
        }

        $context = context_course::instance($course->id);

        $modinfo = get_fast_modinfo($course);

        $template = new stdClass();

        $completioninfo = new completion_info($course);

        if ($completioninfo->is_enabled()) {
            $template->completionon = 'completion';
        }

        $completionok = [
                COMPLETION_COMPLETE,
                COMPLETION_COMPLETE_PASS
        ];

        $thiscontext = context::instance_by_id($this->page->context->id);

        $inactivity = false;
        $myactivityid = 0;

        if ($thiscontext->get_level_name() == get_string('activitymodule')) {
            // Uh-oh we are in a activity.
            $inactivity = true;
            if ($cm = $DB->get_record_sql(
                    "SELECT cm.*, md.name AS modname
                                           FROM {course_modules} cm
                                           JOIN {modules} md ON md.id = cm.module
                                           WHERE cm.id = ?",
                    [$thiscontext->instanceid]
            )) {
                $myactivityid = $cm->id;
            }
        }

        if ($format instanceof format_digidagotabs || $format instanceof format_horizontaltabs) {
            $coursesections = $DB->get_records(
                    'course_sections',
                    ['course' => $course->id]
            );
            $mysection = 0;
            foreach ($coursesections as $cs) {
                $csmodules = explode(
                        ',',
                        $cs->sequence
                );
                if (in_array(
                        $myactivityid,
                        $csmodules
                )) {
                    $mysection = $cs->id;
                }
            }

            if ($mysection) {
                if (($format instanceof format_digidagotabs && $DB->get_records(
                                        'format_digidagotabs_tabs',
                                        [
                                                'courseid' => $course->id,
                                                'sectionid' => $mysection
                                        ]
                                )) || ($format instanceof format_horizontaltabs && $DB->get_records(
                                        'format_horizontaltabs_tabs',
                                        [
                                                'courseid' => $course->id,
                                                'sectionid' => $mysection
                                        ]
                                ))) {
                    // This is a module inside a tab of the Dynamic tabs course format.
                    // Prevent showing of this menu.
                    return $this->content;
                }
            }
        }

        $template->inactivity = $inactivity;

        if (count($sections) > 1) {
            $template->hasprevnext = true;
            $template->hasnext = true;
            $template->hasprev = true;
        }

        $courseurl = new moodle_url(
                '/course/view.php',
                ['id' => $course->id]
        );
        $template->courseurl = $courseurl->out();
        $sectionnums = [];
        foreach ($sections as $section) {
            $sectionnums[] = $section->section;
        }
        foreach ($sections as $section) {
            $i = $section->section;

            if (!$section->uservisible) {
                if (!get_config(
                        'block_aprende_coursenavigation',
                        'toggleshowrestricted')) {
                    continue;
                }

            }

            if (!empty($section->name)) {
                $title = format_string(
                        $section->name,
                        true,
                        ['context' => $context]
                );
            } else {
                $summary = file_rewrite_pluginfile_urls(
                        $section->summary,
                        'pluginfile.php',
                        $context->id,
                        'course',
                        'section',
                        $section->id
                );
                $summary = format_text(
                        $summary,
                        $section->summaryformat,
                        [
                                'para' => false,
                                'context' => $context
                        ]
                );
                $title = $format->get_section_name($section);
            }

            $thissection = new stdClass();
            $thissection->number = $i;
            $thissection->title = $title;
            $thissection->url = $format->get_view_url($section);
            $thissection->selected = false;

            if (strlen($title) >= 40) {
                $thissection->shouldbeshort = true;
            }

            if (get_config(
                            'block_aprende_coursenavigation',
                            'toggleclickontitle'
                    ) == 2) {
                // Display the menu.
                $thissection->collapse = true;
            } else {
                // Go to link.
                $thissection->collapse = false;
            }

            if (get_config(
                            'block_aprende_coursenavigation',
                            'togglecollapse'
                    ) == 2) {
                $thissection->selected = true;
            }

            // Show only titles.
            if (get_config(
                            'block_aprende_coursenavigation',
                            'toggletitles'
                    ) == 2) {
                // Show only titles.
                $thissection->onlytitles = true;
            } else {
                // Show  titles and contents.
                $thissection->onlytitles = false;
            }

            if ($i == $selected && !$inactivity) {
                $thissection->selected = true;
            }

            // Show the restricted section
            if (!$section->uservisible) {
                $thissection->restricted = true;
                $thissection->availableinfo = $section->availableinfo;
            }

            // Show subtitle section property if exist
            if (isset($section->subtitle) && isset($section->subtitle_icon)) {
                $thissection->sectionlabel = $section->subtitle;
                $thissection->sectionlabelicon = $section->subtitle_icon;
            }

            $thissection->modules = [];
            if (!empty($modinfo->sections[$i])) {
                foreach ($modinfo->sections[$i] as $modnumber) {
                    $module = $modinfo->cms[$modnumber];
                    if ((get_config('block_aprende_coursenavigation', 'toggleshowlabels') == 1) &&
                        ($module->modname == 'label')) {
                        continue;
                    }

                    if($course->activities_enabled &&
                        in_array($modnumber, explode(",", $course->activitiessection)) &&
                        !$this->page->user_is_editing() &&
                        $USER->profile['folio'] % 2 === 0) {
                        continue;
                    }

                    if (!$module->visible || !$module->visibleoncoursepage) {
                        continue;
                    }

                    if (!$module->uservisible) {
                        if (!get_config(
                                'block_aprende_coursenavigation',
                                'toggleshowrestricted')) {
                            continue;
                        }

                    }

                    $thismod = new stdClass();

                    if ($inactivity) {
                        if ($myactivityid == $module->id) {
                            $thissection->selected = true;
                            $thismod->active = 'active';
                        }
                    }
                    $thismod->name = format_string(
                            $module->name,
                            true,
                            ['context' => $context]
                    );
                    $thismod->url = $module->url;
                    $thismod->onclick = $module->onclick;

                    if (!$module->uservisible) {
                        $thismod->url = '';
                        $thismod->onclick = '';
                        $thismod->disabled = 'true';
                        $thismod->conditions = $module->availableinfo;
                    } else {
                        $thismod->available = 'true';
                    }

                    if ($module->modname == 'label') {
                        // TODO: Confirm the title class on the standp up
                        $htmltitleregexp = '/<h[1-6] class="content-separator">(?<titletext>.+?)<\/h[1-6]>/iu';

                        $titlematch = [];
                        if (!preg_match($htmltitleregexp, str_replace(array("\r","\n"),"",$module->content), $titlematch)) {
                            continue;
                        }

                        print_object($titlematch);

                        $thismod->url = '';
                        $thismod->onclick = '';
                        $thismod->label = 'true';
                        $thismod->labelcontent = htmlspecialchars_decode($titlematch['titletext']);
                    }

                    $statusclass = '\format_aprendetopics\status';
                    if ($module->uservisible && class_exists($statusclass)) {
                        $status = new $statusclass($module->id);
                        if ($status->optional) {
                            $thismod->optional = true;
                        }
                    }

                    $hascompletion = $completioninfo->is_enabled($module);
                    if ($hascompletion) {
                        $thismod->completeclass = 'incomplete';
                    }

                    $completiondata = $completioninfo->get_data(
                            $module,
                            true
                    );
                    if (in_array(
                            $completiondata->completionstate,
                            $completionok
                    )) {
                        $thismod->completeclass = 'completed';
                        $thismod->completed = 'true';
                    }
                    $thissection->modules[] = $thismod;
                }
                $thissection->hasmodules = (count($thissection->modules) > 0);
                $template->sections[] = $thissection;
            }
            if ($thissection->selected) {

                $pn = $this->get_prev_next(
                        $sectionnums,
                        $thissection->number
                );

                $courseurl = new moodle_url(
                        '/course/view.php',
                        [
                                'id' => $course->id,
                                'section' => $i
                        ]
                );
                $template->courseurl = $courseurl->out();

                if ($pn->next === false) {
                    $template->hasnext = false;
                }
                if ($pn->prev === false) {
                    $template->hasprev = false;
                }

                $prevurl = new moodle_url(
                        '/course/view.php',
                        [
                                'id' => $course->id,
                                'section' => $pn->prev
                        ]
                );
                $template->prevurl = $prevurl->out(false);

                $currurl = new moodle_url(
                        '/course/view.php',
                        [
                                'id' => $course->id,
                                'section' => $thissection->number
                        ]
                );
                $template->currurl = $currurl->out(false);

                $nexturl = new moodle_url(
                        '/course/view.php',
                        [
                                'id' => $course->id,
                                'section' => $pn->next
                        ]
                );
                $template->nexturl = $nexturl->out(false);
            }
        }
        if ($intab) {
            $template->inactivity = true;
        }
        $template->coursename = $course->fullname;
        $category = core_course_category::get($course->category, IGNORE_MISSING, true);

        if (!is_null($category)) {
            $template->coursecategory = $category->get_formatted_name();
        }

        $template->config = $this->config;
        $renderer = $this->page->get_renderer(
                'block_aprende_coursenavigation',
                'nav'
        );
        $this->content->text = $renderer->render_nav($template);
        return $this->content;
    }

    /**
     *
     * Function to get the previous and next values in an array.
     *
     * @param $array
     * @param $current
     * @return stdClass
     */
    private function get_prev_next($array, $current) {
        $pn = new stdClass();

        $hascurrent = $pn->next = $pn->prev = false;

        foreach ($array as $a) {
            if ($hascurrent) {
                $pn->next = $a;
                break;
            }
            if ($a == $current) {
                $hascurrent = true;
            } else {
                if (!$hascurrent) {
                    $pn->prev = $a;
                }
            }
        }
        return $pn;
    }

    /**
     * Returns the navigation.
     *
     * @return navigation_node The navigation object to display
     */
    protected function get_navigation() {
        $this->page->navigation->initialise();
        return clone($this->page->navigation);
    }
}
