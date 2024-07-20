<?php
// Standard GPL and phpdocs

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_odysseus';
$plugin->version = 2024060106;

$plugin->requires = 2014051200; // Moodle 2.7.0 is required.
$plugin->supported = [37, 41]; // Moodle 3.7.x, 3.8.x, 3.9.x, 4.0.x and 4.1.x are supported.
//$plugin->incompatible = 36; // Moodle 3.6.x and later are incompatible.

$plugin->maturity = MATURITY_ALPHA; //Supported value is any of the predefined constants MATURITY_ALPHA, MATURITY_BETA, MATURITY_RC or MATURITY_STABLE.
$plugin->release = 'v1.0-a1';

$plugin->dependencies = array();