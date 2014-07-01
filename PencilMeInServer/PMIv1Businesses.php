<?php
/**
 * Code for /business/ REST queries
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */

// JSON objects
require_once("json-address.php");
require_once("json-appointment.php");
require_once("json-appointment_list.php");
require_once("json-appointment_server.php");
require_once("json-business.php");
require_once("json-businesses.php");
require_once("json-status.php");
require_once("json-statusmsg.php");

// Code objects

class PMIv1Businesses
{


    /**
     * Root function. Basically just prints an error message.
     * URL (GET): /
     */
    static public function root()
    {
        print "Missing command.";
    }



    /**
     * Retrieves a list of the first 20 businesses from the database.
     * URL (GET): /businesses/
     */
    static public function getBusinesses()
    {
        $query = "SELECT * FROM businesses ORDER BY name";
        //$res = $db->query($query);
        $res = getDatabase()->all($query);

        $num = 0;

        //while ($row = $res->fetch_assoc())
        foreach ($res as $row)
        {
            $json[$num] = new Business();
            $json[$num]->id = (int)$row["id"];
            $json[$num]->name = $row["name"];
            $json[$num]->address = new Address();
            $json[$num]->address->street = $row["street"];
            $json[$num]->address->city = $row["city"];
            $json[$num]->address->state = $row["state"];
            $json[$num]->address->zip = $row["zip"];
            $json[$num]->phone = (int) $row["phone"];

            $num++;
        }

        $bs = new Businesses();
        $bs->businesses = $json;

        print(json_encode($bs));
    }



    /**
     * Retrieves info from one business
     * /businesses/:id/
     * @param $id business ID number
     */
    static public function getBusiness($id)
    {

        $query = "SELECT * FROM businesses WHERE id = '$id' ORDER BY name";
        $res = getDatabase()->all($query);

        $num = 0;

        // Should only have one result, so I'm only going to fetch that one and not loop through
        $row = $res[0];
        {
            $json[$num] = new Business();
            $json[$num]->id = (int) $row["id"];
            $json[$num]->name = $row["name"];
            $json[$num]->address = new Address();
            $json[$num]->address->street = $row["street"];
            $json[$num]->address->city = $row["city"];
            $json[$num]->address->state = $row["state"];
            $json[$num]->address->zip = $row["zip"];
            $json[$num]->phone = (int) $row["phone"];

            $num++;
        }

        $bs = new Businesses();
        $bs->businesses = $json;

        $str = json_encode($bs);

        print($str);
    }



    /**
     * Retrieves appointments for a business
     * URL (GET): /businesses/:id/appointments/
     * @param int $bid business ID
     */
    static public function getBusinessAppointments($bid)
    {
        // Get business start/end dates
        // Run the query
        $query = "SELECT appt_duration, start_time, end_time  "
                . "FROM business_settings "
                        . "WHERE business_id = $bid";
        $res = getDatabase()->one($query);
        // Get the data
        $appt_duration = $res["appt_duration"];
        $biz_start_time = (int) $res["start_time"];
        $biz_end_time = (int) $res["end_time"];

        // Build times that our appt search should start/end at
        // Grab dates from URL, grab times from business start/stop times
        $appt_start_time = 0;
        $appt_end_time = 0;
        if(isset($_REQUEST["date"]) && $_REQUEST["date"] != "")
        {
            $appt_start_time = date("U", mktime(date("H", $biz_start_time),
                    date("i", $biz_start_time),
                    date("s", $biz_start_time),
                    date("m", strtotime($_REQUEST["date"])),
                    date("d", strtotime($_REQUEST["date"])),
                    date("y", strtotime($_REQUEST["date"]))));
            $appt_end_time = date("U", mktime(date("H", $biz_end_time),
                    date("i", $biz_end_time),
                    date("s", $biz_end_time),
                    date("m", strtotime($_REQUEST["date"])),
                    date("d", strtotime($_REQUEST["date"])),
                    date("y", strtotime($_REQUEST["date"]))));
        }
        else
        {
            // default to today
            $appt_start_time = date("U", mktime(date("H", $biz_start_time),
                    date("i", $biz_start_time),
                    date("s", $biz_start_time),
                    date("m", time()),
                    date("d", time()),
                    date("y", time())));
            $appt_end_time = date("U", mktime(date("H", $biz_end_time),
                    date("i", $biz_end_time),
                    date("s", $biz_end_time),
                    date("m", time()),
                    date("d", time()),
                    date("y", time())));
        }

        // Clean up some unused variables
        unset($biz_start_time, $biz_end_time);

        // Find any appointments during provided date (or today)
        $query  = "SELECT start_time, duration "
                . "FROM appointments "
                        . "WHERE business_id = $bid "
                        . "AND start_time >= $appt_start_time "
                        . "AND start_time < $appt_end_time "
                        . "ORDER BY start_time";

        // Run query
        $res = getDatabase()->all($query);

        // Build list of open appointments
        $num = 0;
        $appointments = new Appointment_List;
        while($appt_start_time < $appt_end_time)
        {
            $appointments->appointments[$num] = new Appointment();
            $appointments->appointments[$num]->time = $appt_start_time;
            $appointments->appointments[$num]->duration = $appt_duration;
            $appointments->appointments[$num]->state = "open";
            $num++;
            $appt_start_time += $appt_duration * 60;   // add this many minutes
        }

        // For each appointment, mark it ask taken
        foreach($res as $row)
        {
            // If appointment exists, mark as taken
            // I know this can be optimized with associative array/map using time as the key, but will do that later
            // Functionality first, optimization later...
            for($i = 0; $i < count($appointments->appointments); $i++)
            {
                if($row["start_time"] == $appointments->appointments[$i]->time)
                {
                    $appointments->appointments[$i]->state = "taken";
                    break; // found it, quit looking
                }
            }
        }

        // Print JSON version fo appointment list
        $str = json_encode($appointments);
        print($str);

    }



    /**
     * Creates an appointment based on the JSON object passed in
     * URL (POST): /businesses/:id/appointments/
     * @param unknown $bid
     */
    static public function createBusinessAppointments($bid)
    {
        // Error checking!
        if(!isset($_REQUEST["request"]))
        {
            die(generateErrorJSON("Missing variable 'request'"));
        }
        if($_REQUEST["request"] == "")
        {
            die(generateErrorJSON("Request variable is blank"));
        }

        // It worked, so grab the variable
        $request = json_decode($_REQUEST["request"]);
        if($request == NULL)
        {
            die(generateErrorJSON("Invalid JSON object!"));
        }

        // Make status object
        $appointment = new AppointmentServer();

        // Try to build valid object. If possible, great. If not, give bad status message.
        try{
            $appointment->userid = $request->appointment->userid;
            $appointment->time = $request->appointment->time;
            $appointment->duration = $request->appointment->duration;
            //$appointment->time = $request->
        }
        catch(Exception $e)
        {
            die(generateErrorJSON("Invalid JSON detected: $e"));
        }

        // Check if appt exists first
        // Future to-do probably...

        // Build DB query
        $query = "INSERT INTO appointments (business_id, start_time, duration, user_id, state) VALUES (";

        // Validate data here
        $query .= $bid . ", ";
        $query .= $appointment->time . ", ";
        $query .= $appointment->duration . ", ";
        $query .= $appointment->userid . ", ";
        $query .= "'pending')";

        // Run query
        $res = getDatabase()->execute($query);
        //$res = $db->query($query);

        // Build status object
        if($res)
        {
            print(generateStatusJson("Appointment added"));
        }
        else
        {
            print(generateErrorJson("Appointment not added"));
        }
    }
}
?>