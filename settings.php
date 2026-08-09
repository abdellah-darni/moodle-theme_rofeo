<?php
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new admin_settingpage('themesettingrofeo', get_string('configtitle', 'theme_rofeo'));

    $fields = [
        ['herotitle', 'La robotique, pour tous.', 'text'],
        ['herosubtitle', 'Des ateliers pratiques pour apprendre à construire et programmer, dès 8 ans.', 'textarea'],
        ['heroctalabel', 'Voir les formations', 'text'],
        ['heroctaurl', '#catalogue', 'text'],
        ['cataloguetitle', 'Catalogue des formations', 'text'],
        ['coursectalabel', 'Voir la formation', 'text'],
    ];

    foreach ($fields as [$key, $default, $type]) {
        $name = "theme_rofeo/$key";
        $title = get_string($key, 'theme_rofeo');
        $description = get_string($key . '_desc', 'theme_rofeo');
        $setting = ($type === 'textarea')
            ? new admin_setting_configtextarea($name, $title, $description, $default)
            : new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);
    }

    $setting = new admin_setting_configstoredfile(
        'theme_rofeo/heroimage',
        get_string('heroimage', 'theme_rofeo'),
        get_string('heroimage_desc', 'theme_rofeo'),
        'heroimage',
        0,
        ['maxfiles' => 1, 'accepted_types' => ['.jpg', '.jpeg', '.png', '.webp']]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
}