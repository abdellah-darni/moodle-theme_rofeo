<?php
namespace theme_rofeo\output;

defined('MOODLE_INTERNAL') || die();

use context_course;
use context_coursecat;
use core_course_category;
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class catalogue implements renderable, templatable {

    /** Below this many visible courses, render one ungrouped grid instead of grouping by category. */
    private const FLAT_LAYOUT_THRESHOLD = 6;

    public function export_for_template(renderer_base $output): array {
        $categories = [];
        $allcourses = [];

        // get_children() already excludes categories the current viewer has no
        // capability to see, so a hidden top-level category only reaches this
        // loop for viewers who hold moodle/category:viewhiddencategories there.
        foreach (core_course_category::top()->get_children() as $category) {
            $courses = [];

            foreach ($category->get_courses(['recursive' => true]) as $course) {
                // get_courses() already excludes hidden courses the viewer has no
                // moodle/course:viewhiddencourses capability for, so a hidden
                // course reaching here is one the viewer is entitled to see.
                $coursecategory = core_course_category::get($course->category, IGNORE_MISSING, true);
                $categoryhidden = !$coursecategory || !$coursecategory->visible;

                if ($categoryhidden) {
                    // Unlike the course-level check, get_courses() does not verify
                    // moodle/category:viewhiddencategories, so it must be checked here.
                    $catcontext = $coursecategory ? context_coursecat::instance($coursecategory->id) : null;
                    if (!$catcontext || !has_capability('moodle/category:viewhiddencategories', $catcontext)) {
                        continue;
                    }
                }

                $exported = $this->export_course($course, !$course->visible || $categoryhidden);
                $courses[] = $exported;
                $allcourses[] = $exported;
            }

            if (empty($courses)) {
                continue;
            }

            $categories[] = [
                'name' => $category->get_formatted_name(),
                'courses' => $courses,
            ];
        }

        $totalcourses = count($allcourses);

        return [
            'categories' => $categories,
            'courses' => $allcourses,
            'hascourses' => $totalcourses > 0,
            'totalcourses' => $totalcourses,
            'flatlayout' => $totalcourses > 0 && $totalcourses < self::FLAT_LAYOUT_THRESHOLD,
        ];
    }

    private function export_course($course, bool $hidden = false): array {
        global $CFG;

        $image = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $image = moodle_url::make_file_url(
                    $CFG->wwwroot . '/pluginfile.php',
                    '/' . $file->get_contextid() . '/' . $file->get_component() . '/'
                        . $file->get_filearea() . $file->get_filepath() . $file->get_filename()
                )->out(false);
                break;
            }
        }

        $summary = '';
        if ($course->has_summary()) {
            $summary = shorten_text(
                html_to_text(
                    format_text($course->summary, $course->summaryformat,
                        ['context' => context_course::instance($course->id)]),
                    0, false
                ),
                140
            );
        }

        $niveau = $this->get_niveau($course->id);

        return [
            'fullname' => $course->get_formatted_name(),
            'summary'  => $summary,
            'image'    => $image,
            'hasimage' => (bool) $image,
            'hidden'   => $hidden,
            'niveau'   => $niveau,
            'hasniveau' => $niveau !== null,
            'infourl' => (new moodle_url('/local/rofeo/course.php', ['id' => $course->id]))->out(false),
            // 'infourl'  => (new moodle_url('/course/info.php', ['id' => $course->id]))->out(false),
            'enrolurl' => (new moodle_url('/enrol/index.php', ['id' => $course->id]))->out(false),
        ];
    }

    /**
     * Read the course's "niveau" custom field, if set.
     *
     * Filters the generic customfield export by shortname rather than assuming
     * a fixed set of configured option values, since those are admin-editable
     * through the course custom fields UI.
     */
    private function get_niveau(int $courseid): ?string {
        $handler = \core_course\customfield\course_handler::create();

        foreach ($handler->export_instance_data($courseid, true) as $data) {
            if ($data->get_shortname() !== 'niveau') {
                continue;
            }

            $value = $data->get_value();
            if ($value === '' || $value === null || $value === '-') {
                return null;
            }

            return format_string($value);
        }

        return null;
    }
}