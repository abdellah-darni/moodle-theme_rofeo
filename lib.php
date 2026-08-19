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
        'coursectalabel' => 'Voir la formation',
        'catalogueempty' => 'Le catalogue sera bientôt disponible.',
        'whyenabled'  => 1,
        'whytitle'    => 'Pourquoi choisir ROFEO ?',
        'whysubtitle' => 'Une approche pédagogique qui combine théorie solide et expérimentation concrète.',
        'why1icon'  => 'fa-wrench',
        'why1title' => 'Apprentissage pratique',
        'why1body'  => 'Des ateliers où l\'on construit, teste et recommence. Chaque notion s\'apprend en manipulant du vrai matériel.',
        'why2icon'  => 'fa-code',
        'why2title' => 'Progression encadrée',
        'why2body'  => 'Un formateur vous accompagne à chaque étape, du premier montage au projet autonome.',
        'why3icon'  => 'fa-users',
        'why3title' => 'Des parcours pour tous les niveaux',
        'why3body'  => 'Du débutant complet au participant confirmé, chaque formation part de là où vous en êtes.',
        'faqenabled'  => 1,
        'faqtitle'    => 'Questions fréquentes',
        'faqsubtitle' => 'Tout ce que vous devez savoir avant de vous lancer.',
        'faq1question' => 'Faut-il des prérequis en programmation ?',
        'faq1answer'   => 'Non. Nos formations débutant partent de zéro et n\'exigent aucune expérience préalable.',
        'faq2question' => 'Le matériel est-il fourni pendant la formation ?',
        'faq2answer'   => 'Oui. Le matériel nécessaire est mis à disposition pendant les séances.',
        'faq3question' => 'À partir de quel âge peut-on s\'inscrire ?',
        'faq3answer'   => 'Chaque formation indique la tranche d\'âge concernée sur sa page de détail.',
        'footertagline' => 'L\'excellence éducative au service du futur de la technologie et du faire.',
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