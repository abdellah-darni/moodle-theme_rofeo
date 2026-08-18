<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/rofeo/lib.php');

if ($ADMIN->fulltree) {

    $settings = new admin_settingpage('themesettingrofeo', get_string('configtitle', 'theme_rofeo'));
    $defaults = theme_rofeo_frontpage_defaults();

    $addfield = function (string $key, string $type = 'text') use ($settings, $defaults) {
        $name = "theme_rofeo/$key";
        $title = get_string($key, 'theme_rofeo');
        $description = get_string($key . '_desc', 'theme_rofeo');
        $default = $defaults[$key] ?? '';

        $setting = ($type === 'textarea')
            ? new admin_setting_configtextarea($name, $title, $description, $default, PARAM_RAW)
            : new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);

        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);
    };

    // Hero section.
    $settings->add(new admin_setting_heading(
        'theme_rofeo/heroheading',
        get_string('heroheading', 'theme_rofeo'),
        ''
    ));

    $addfield('herotitle');
    $addfield('herosubtitle', 'textarea');
    $addfield('heroctalabel');
    $addfield('heroctaurl');

    $heroimage = new admin_setting_configstoredfile(
        'theme_rofeo/heroimage',
        get_string('heroimage', 'theme_rofeo'),
        get_string('heroimage_desc', 'theme_rofeo'),
        'heroimage',
        0,
        ['maxfiles' => 1, 'accepted_types' => ['.jpg', '.jpeg', '.png', '.webp']]
    );
    $heroimage->set_updatedcallback('theme_reset_all_caches');
    $settings->add($heroimage);

    // Catalogue section.
    $settings->add(new admin_setting_heading(
        'theme_rofeo/catalogueheading',
        get_string('catalogueheading', 'theme_rofeo'),
        ''
    ));

    $addfield('cataloguetitle');
    $addfield('cataloguesubtitle', 'textarea');
    $addfield('coursectalabel');
    $addfield('catalogueempty', 'textarea');
}