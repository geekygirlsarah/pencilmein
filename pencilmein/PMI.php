<?php
/**
 * Code for REST queries - root
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */

// JSON objects

// Code objects
require_once("class-MySQL.php");

class PMI {

    static public function root()
    {
        print "Missing version.";
    }
    
    static public function restError()
    {
        print "Unrecognized version '" . $_REQUEST["__route__"] . "'.";
    }

    static public function v1Error()
    {
        print "Unrecognized route/command '" . $_REQUEST["__route__"] . "'.";
    }

}
?>