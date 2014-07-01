<?php
/**
 * Code for REST queries - root
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */

// JSON objects
require_once("json-status.php");
require_once("json-statusmsg.php");

// Code objects
require_once("PMIfunctions.php");

class PMI {

    static public function root()
    {
        die(generateErrorJSON("Missing version number"));
    }
    
    static public function restError()
    {
        die(generateErrorJSON("Unrecognized version " . $_REQUEST["__route__"]));
    }

    static public function v1Error()
    {
        die(generateErrorJSON("Unrecognized route/command " . $_REQUEST["__route__"]));
    }
    
    
}
?>