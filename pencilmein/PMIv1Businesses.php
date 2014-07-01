<?php
/**
 * Code for /business/ REST query
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
require_once("class-MySQL.php");

class PMIv1Businesses {
    // DB connectivity
    //var $database = "pencilmein";//$database;
    //var $username = "pencilmein";//$username;
    //var $password = "abc123";//$password;
    //var $hostname = "localhost";//$hostname;

    var $db;
    

    
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
        $db = new mysqli("localhost", "pencilmein", "abc123", "pencilmein");
        
        $query = "SELECT * FROM businesses ORDER BY name";
        $res = $db->query($query);
        
        $num = 0;
        
        while ($row = $res->fetch_assoc()) 
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
        
        $str = json_encode($bs);
        
        print($str);
        $res->close();
    }
    
    
    
    /**
     * Retrieves info from one business
     * /businesses/:id/
     */
    static public function getBusiness($id)
    {
        $db = new mysqli("localhost", "pencilmein", "abc123", "pencilmein");

        $query = "SELECT * FROM businesses WHERE id = '$id' ORDER BY name";
        $res = $db->query($query);
        
        $num = 0;
        
        while ($row = $res->fetch_assoc()) 
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
        $res->close();
    }
    
    
    
    /**
     * Retrieves appointments for a business
     * URL (GET): /businesses/:id/appointments/
     * @param int $bid business ID
     */
    static public function getBusinessAppointments($bid)
    {
        $db = new mysqli("localhost", "pencilmein", "abc123", "pencilmein");

        // Get business start/end dates
        // Run the query
        $query = "SELECT appt_duration, start_time, end_time FROM business_settings WHERE business_id = $bid";
        //print "$query<br />";
        $res = $db->query($query);
        // Get the data
        $row = $res->fetch_assoc();
        $appt_duration = $row["appt_duration"];
        $biz_start_time = (int) $row["start_time"];
        $biz_end_time = (int) $row["end_time"];
        $res->close();
        
        //print "$biz_start_time and $biz_end_time<br />";
        
        
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
        
        // Clean up some variables
        unset($biz_start_time, $biz_end_time);

        //print("$appt_start_time and $appt_end_time <br />");
        
        // Find any appointments during provided date (or today) and
        $query = "SELECT start_time, duration FROM appointments WHERE business_id = $bid ";
        $query .= " AND start_time >= $appt_start_time AND start_time < $appt_end_time "
            . "ORDER BY start_time";
        
        // Run query
        //print $query;
        $res = $db->query($query);
        
        // DEBUG
        //print_r($res);
        
        
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
        
        //print_r($appointments);
        
        
        // For each appointment, mark it ask taken
        while($row = $res->fetch_assoc())
        {
            // If appointment exists, mark as taken
            // I know this can be optimized with associative array/map using time as the key, but will do that later
            // Functionality first, optimization later...
            for($i = 0; $i < count($appointments->appointments); $i++)
            {
                if($row["start_time"] == $appointments->appointments[$i]->time)
                {
                    //print "found!";
                    $appointments->appointments[$i]->state = "taken";
                    break; // found it, quit looking
                }
            }
        }
        $res->close();

        
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
            die("Missing variable 'request'");
                    }
        if($_REQUEST["request"] == "")
        {
            die("Request variable is blank");
        }
        
        // It worked, so grab the variable
        $request = json_decode($_REQUEST["request"]);
        if($request == NULL)
        {
            die("Invalid JSON object!<br />\nYou sent:<br /><br />\n\n${_REQUEST['request']}");
        }
        
        $appointment = new AppointmentServer();
        $status = new Status();
        $status->status = new StatusMsg();
        
        try{
            $appointment->userid = $request->appointment->userid;
            $appointment->time = $request->appointment->time;
            $appointment->duration = $request->appointment->duration;
            //$appointment->time = $request->
        }
        catch(Exception $e)
        {
            $status = new Status();
            $status->status = new StatusMSG();
            $status->status->error = true;
            $status->status->message = "Invalid JSON detected: $e";      
            $str = json_encode($status);
            print($str);
            die();
        }
        //Huh... PHP 5.4 doesn't support finally...
        //finally
        //{
        //}

        $status = new Status();
        $status->status = new StatusMSG();
        $status->status->error = false;
        $status->status->message = "JSON valid. This is the theoretical part where the appointment is added.";      
        $str = json_encode($status);
        print($str);
    }
}
?>