<?php
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

$THEME->name = 'rofeo';
$THEME->sheets = [];
$THEME->parents = ['moove', 'boost'];
$THEME->enable_dock = false;
$THEME->yuicssmodules = [];
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->usefallback = false;
$THEME->iconsystem = \theme_moove\util\icon_system::class;

$THEME->scss = function($theme) {
    return theme_rofeo_get_main_scss_content($theme);
};
$THEME->prescsscallback  = 'theme_rofeo_get_pre_scss';
$THEME->extrascsscallback = 'theme_rofeo_get_extra_scss';

// Copy the entire $THEME->layouts array verbatim from theme/moove/config.php.
$THEME->layouts = [
    'base' => [
        'file' => 'drawers.php',
        'regions' => [],
    ],
    'frontpage' => [
        'file' => 'frontpage.php',
        'regions' => ['side-pre', 'content'],
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true],
    ],
    'course' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre', 'content'],
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    'login' => [
        'file' => 'login.php',
        'regions' => [],
        'options' => ['langmenu' => true],
    ],
];