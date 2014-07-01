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

/**
 * Returns a JSON status object with error message
 * @param string $message message to put in object
 * @return string JSON status object containing error message
*/
function generateErrorJson($message)
{
    $status = new Status();
    $status->status = new StatusMSG();
    $status->status->error = true;
    $status->status->message = $message;
    return json_encode($status);
}

/**
 * Returns a JSON status object with message
 * @param string $message message to put in object
 * @return string JSON status object containing status message
 */
function generateStatusJson($message)
{
    $status = new Status();
    $status->status = new StatusMSG();
    $status->status->error = false;
    $status->status->message = $message;
    return json_encode($status);
}

/**
 *
 * @param unknown $timestamp
 * @return multitype:number an array of date numbers
 */
function convertTimestampToYmd($timestamp)
{
    // Array of year, month, day
    return array((int)date("Y",$timestamp), date("n",$timestamp), date("j",$timestamp));
}

/**
 * Converts a timestamp to individual Y/M/D H:M:S parts
 * @param unknown $timestamp
 * @return multitype:number an array of date numbers
 */
function convertTimestampToYmdhms($timestamp)
{
    // Array of year, month, day, hour, minute, second
    return array((int)date("Y",$timestamp), (int)date("n",$timestamp), (int)date("j",$timestamp), (int)date("g", $timestamp), (int)date("i", $timestamp), (int)date("s", $timestamp));
}

function convertYMDToTimestamp($yr, $mon, $dy)
{
    return date("U", mktime(0, 0, 0, $mon, $dy, $yr));
}

function convertYmdhmsToTimestamp($yr, $mon, $dy, $hr, $min, $sc)
{
    return date("U", mktime($hr, $min, $sc, $mon, $dy, $yr));
}

function convertStringDateTimeToTimestamp($strDate, $strTime)
{
    return date("U", mktime(date("H", strtotime($strTime)),
            date("i", strtotime($strTime)),
            date("s", strtotime($strTime)),
            date("m", strtotime($strDate)),
            date("d", strtotime($strDate)),
            date("y", strtotime($strDate))));
}

function addDayToTimestamp($timestamp)
{
    return $timestamp + (60 * 60 * 24);
}

