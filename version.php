<?php
defined('MOODLE_INTERNAL') || die();

$plugin->component = 'theme_rofeo';
$plugin->version   = 2026082100;
$plugin->requires  = 2026041000;
$plugin->maturity  = MATURITY_ALPHA;
$plugin->release   = '0.1.5';
$plugin->dependencies = [
    'theme_boost' => ANY_VERSION,
    'theme_moove' => ANY_VERSION,
];