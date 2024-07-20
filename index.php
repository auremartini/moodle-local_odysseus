<?php
// Standard GPL and phpdocs

require_once(__DIR__ . '/../../config.php');

global $DB, $PAGE, $USER;

//SET RENDERER
$PAGE->set_context(context_system::instance());
$output = $PAGE->get_renderer('local_odysseus');

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/local/odysseus/index.php'));
$PAGE->set_title(get_string('plugin_page_title', 'local_odysseus'));
$PAGE->set_heading(get_string('plugin_page_title', 'local_odysseus'));
$url = new moodle_url('/local/totem/index.php', array());
$node = $PAGE->settingsnav->add(get_string('plugin_navbar_lavel', 'local_odysseus'), $url);
$node->make_active();

// PRINT HEADER
$context = context_system::instance();
echo $output->header();

$uuid = "";

// PRINT CONTENT
/**
 * WALKTROUGHT PER MOSTRARE UNA ATTIVITÀ
 */
$uuid = '197135f9-8a3b-459a-86e1-e76ece52ba65';

if ($uuid != '') {
    //if ($uuid è token di una attività)
    $renderable = new \local_odysseus\data\activity(array(
        'uuid' => '197135f9-8a3b-459a-86e1-e76ece52ba65'
    ));
    echo $output->render_activity_info($renderable);

    //} else ($uuid == "token conferma utente") {

    //}
}

if (has_capability('local/odysseus:fullviewownactivity', $context) || has_capability('local/odysseus:viewownactivity', $context)) {
    echo "<h3>Le mie attività</h3>";
    $renderable = new \local_odysseus\data\activity(array(
        'user_id' => $USER->id
    ));
    echo $output->render_activity_list($renderable);
}

if (has_capability('local/odysseus:fullviewallactivity', $context) || has_capability('local/odysseus:viewallactivity', $context)) {
    echo "<h3>Tutte le attività</h3>";
    $renderable = new \local_odysseus\data\activity(array());
    echo $output->render_activity_list($renderable);
}

// PRINT FOOTER
echo $output->footer();
