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

function theme_rofeo_frontpage_defaults(): array {
    return [
        'herotitle'      => 'La robotique, pour tous.',
        'herosubtitle'   => 'Des ateliers pratiques pour apprendre à construire et programmer, dès 8 ans.',
        'heroctalabel'   => 'Voir les formations',
        'heroctaurl'     => '#catalogue',
        'cataloguetitle' => 'Catalogue des formations',
        'cataloguesubtitle' => 'Des parcours conçus par des experts pour transformer votre curiosité en compétences tangibles.',
        'coursectalabel' => 'Voir la formations',
        'catalogueempty' => 'Le catalogue sera bientôt disponible.',
    ];
}

function theme_rofeo_setting(string $key) {
    $value = get_config('theme_rofeo', $key);

    if ($value === false || $value === null) {
        $defaults = theme_rofeo_frontpage_defaults();
        return $defaults[$key] ?? '';
    }

    return $value;
}