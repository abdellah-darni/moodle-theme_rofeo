<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/moove/lib.php');

function theme_rofeo_get_main_scss_content($theme) {
    global $CFG;
    $scss  = theme_moove_get_main_scss_content(theme_config::load('moove'));
    $scss .= file_get_contents($CFG->dirroot . '/theme/rofeo/scss/rofeo.scss');
    return $scss;
}

function theme_rofeo_get_pre_scss($theme) {
    return theme_moove_get_pre_scss(theme_config::load('moove'));
}

function theme_rofeo_get_extra_scss($theme) {
    return theme_moove_get_extra_scss(theme_config::load('moove'));
}

function theme_rofeo_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM && $filearea === 'heroimage') {
        $theme = theme_config::load('rofeo');
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }

    send_file_not_found();
}