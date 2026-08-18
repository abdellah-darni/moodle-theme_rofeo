<?php
// This file is part of Moodle - http://moodle.org/
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
 * A drawer based layout for the Eskada theme.
 *
 * @package    theme_moove
 * @copyright  2025 Willian Mano - willianmanoaraujo@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));

$addcontentblockbutton = $OUTPUT->addblockbutton('content');
$contentblocks = $OUTPUT->custom_block_region('content');

if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $secondary = $PAGE->secondarynav;

    if ($secondary->get_children_key_list()) {
        $tablistnav = $PAGE->has_tablist_secondary_navigation();
        $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
        $secondarynavigation = $moremenu->export_for_template($OUTPUT);
        $extraclasses[] = 'has-secondarynavigation';
    }

    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => \core\context\course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'addcontentblockbutton' => $addcontentblockbutton,
    'contentblocks' => $contentblocks,
];

$themesettings = new \theme_moove\util\settings();

$templatecontext = array_merge($templatecontext, $themesettings->footer());

$template = 'theme_moove/drawers';

$template = 'theme_moove/frontpage';

foreach (['herotitle', 'heroctalabel', 'heroctaurl'] as $key) {
    $templatecontext[$key] = theme_rofeo_setting($key);
}

$templatecontext['herosubtitle'] = format_text(theme_rofeo_setting('herosubtitle'), FORMAT_HTML);
$templatecontext['cataloguetitle'] = format_string(theme_rofeo_setting('cataloguetitle'));
$templatecontext['cataloguesubtitle'] = format_text(theme_rofeo_setting('cataloguesubtitle'), FORMAT_HTML);
$templatecontext['coursectalabel'] = format_string(theme_rofeo_setting('coursectalabel'));
$templatecontext['catalogueempty'] = format_text(theme_rofeo_setting('catalogueempty'), FORMAT_HTML);

$heroimage = $PAGE->theme->setting_file_url('heroimage', 'heroimage');
if (empty($heroimage)) {
    $heroimage = $OUTPUT->image_url('hero', 'theme_rofeo')->out(false);
}
$templatecontext['heroimageurl'] = $heroimage;

$catalogue = new \theme_rofeo\output\catalogue();
$templatecontext = array_merge($templatecontext, $catalogue->export_for_template($OUTPUT));

$templatecontext['whyenabled'] = (int) theme_rofeo_setting('whyenabled') === 1;
$templatecontext['whytitle'] = format_string(theme_rofeo_setting('whytitle'));
$templatecontext['whysubtitle'] = format_text(theme_rofeo_setting('whysubtitle'), FORMAT_HTML);

$templatecontext['whycards'] = [];
for ($i = 1; $i <= 3; $i++) {
    $templatecontext['whycards'][] = [
        'icon'  => theme_rofeo_setting("why{$i}icon"),
        'title' => format_string(theme_rofeo_setting("why{$i}title")),
        'body'  => format_text(theme_rofeo_setting("why{$i}body"), FORMAT_HTML),
    ];
}

$templatecontext['faqtitle'] = format_string(theme_rofeo_setting('faqtitle'));
$templatecontext['faqsubtitle'] = format_text(theme_rofeo_setting('faqsubtitle'), FORMAT_HTML);

$faqitems = [];
$firstfaqdone = false;
for ($i = 1; $i <= 3; $i++) {
    $question = theme_rofeo_setting("faq{$i}question");
    if (trim($question) === '') {
        continue;
    }

    $faqitems[] = [
        'id' => $i,
        'question' => format_string($question),
        'answer' => format_text(theme_rofeo_setting("faq{$i}answer"), FORMAT_HTML),
        'active' => !$firstfaqdone,
    ];
    $firstfaqdone = true;
}
$templatecontext['faqitems'] = $faqitems;

$templatecontext['faqenabled'] = (int) theme_rofeo_setting('faqenabled') === 1 && !empty($faqitems);

echo $OUTPUT->render_from_template($template, $templatecontext);