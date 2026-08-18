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

    // Why section.
    $settings->add(new admin_setting_heading(
        'theme_rofeo/whyheading',
        get_string('whyheading', 'theme_rofeo'),
        ''
    ));

    $whyenabled = new admin_setting_configselect(
        'theme_rofeo/whyenabled',
        get_string('whyenabled', 'theme_rofeo'),
        get_string('whyenabled_desc', 'theme_rofeo'),
        $defaults['whyenabled'] ?? 1,
        [0 => get_string('no'), 1 => get_string('yes')]
    );
    $whyenabled->set_updatedcallback('theme_reset_all_caches');
    $settings->add($whyenabled);

    $addfield('whytitle');
    $addfield('whysubtitle', 'textarea');

    $addfield('why1icon');
    $addfield('why1title');
    $addfield('why1body', 'textarea');

    $addfield('why2icon');
    $addfield('why2title');
    $addfield('why2body', 'textarea');

    $addfield('why3icon');
    $addfield('why3title');
    $addfield('why3body', 'textarea');

    // FAQ section.
    $settings->add(new admin_setting_heading(
        'theme_rofeo/faqheading',
        get_string('faqheading', 'theme_rofeo'),
        ''
    ));

    $faqenabled = new admin_setting_configselect(
        'theme_rofeo/faqenabled',
        get_string('faqenabled', 'theme_rofeo'),
        get_string('faqenabled_desc', 'theme_rofeo'),
        $defaults['faqenabled'] ?? 1,
        [0 => get_string('no'), 1 => get_string('yes')]
    );
    $faqenabled->set_updatedcallback('theme_reset_all_caches');
    $settings->add($faqenabled);

    $addfield('faqtitle');
    $addfield('faqsubtitle', 'textarea');

    $addfield('faq1question');
    $addfield('faq1answer', 'textarea');

    $addfield('faq2question');
    $addfield('faq2answer', 'textarea');

    $addfield('faq3question');
    $addfield('faq3answer', 'textarea');
}