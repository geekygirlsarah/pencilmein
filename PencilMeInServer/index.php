<?php
/**
 * Main index file
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */


require_once("PMI.php");
require_once("PMIv1Businesses.php");
require_once("PMIv1Owners.php");

// Load Epiphany Framework

include_once('epiphany/Epi.php');
// Turn on errors
Epi::setSetting('exceptions','true');

// Set where the framework files are
Epi::setPath('base', 'epiphany');
// Set the configuration directory as the parent directory's path
Epi::setPath('config', dirname(__FILE__));

// Create database interface
Epi::init('database');
EpiDatabase::employ('mysql', 'pencilmein', 'localhost', 'pencilmein', 'abc123');

// Start in the REST routing mode
Epi::init('route');

// Load the routes from routes.ini
getRoute()->load('routes.ini');
// Call run() to process the REST
getRoute()->run();


?>