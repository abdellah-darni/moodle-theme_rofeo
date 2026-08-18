<?php
namespace theme_rofeo\output;

defined('MOODLE_INTERNAL') || die();

use context_course;
use core_course_category;
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class catalogue implements renderable, templatable {

    public function export_for_template(renderer_base $output): array {
        $categories = [];

        foreach (core_course_category::top()->get_children() as $category) {
            if (!$category->visible) {
                continue;
            }

            $courses = [];

            foreach ($category->get_courses(['recursive' => true]) as $course) {
                if (!$course->visible) {
                    continue;
                }

                $coursecategory = core_course_category::get($course->category, IGNORE_MISSING, true);
                if (!$coursecategory || !$coursecategory->visible) {
                    continue;
                }

                $courses[] = $this->export_course($course);
            }

            if (empty($courses)) {
                continue;
            }

            $categories[] = [
                'name' => $category->get_formatted_name(),
                'courses' => $courses,
            ];
        }

        return [
            'categories' => $categories,
            'hascategories' => !empty($categories),
        ];
    }

    private function export_course($course): array {
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

        return [
            'fullname' => $course->get_formatted_name(),
            'summary'  => $summary,
            'image'    => $image,
            'hasimage' => (bool) $image,
            'infourl' => (new moodle_url('/local/rofeo/course.php', ['id' => $course->id]))->out(false),
            // 'infourl'  => (new moodle_url('/course/info.php', ['id' => $course->id]))->out(false),
            'enrolurl' => (new moodle_url('/enrol/index.php', ['id' => $course->id]))->out(false),
        ];
    }
}