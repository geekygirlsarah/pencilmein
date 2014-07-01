<?php
/**
 * Code for /owners/ REST queries
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */

// JSON objects
require_once("json-appointment.php");
require_once("json-appointment_list.php");
require_once("json-appointment_owner.php");
require_once("json-userinfo.php");

// Code objects

class PMIv1Owners {


    /**
     * Root function. Basically just prints an error message.
     * URL (GET): /
     */
    static public function root()
    {
        die(generateErrorJson("Missing command."));
    }


    /**
     * Get list of appointments for this business owner
     * URL (GET): /owners/:id/list/
     * @param $oid owner ID number
     */
    static public function getAppointmentList($oid)
    {
        // Get business start/end dates
        // Run the query
        $query = "SELECT business_id, appt_duration FROM business_settings WHERE owner_id = $oid";
        $row = getDatabase()->one($query);

        // Get the data
        $bid = $row["business_id"];
        $duration = $row["appt_duration"];

        // Build times that our appt search should start/end at
        // Grab dates from URL, grab times from business start/stop times
        $appt_start_time = 0;
        $appt_end_time = 0;
        if(isset($_REQUEST["date"]) && $_REQUEST["date"] != "")
        {
            $appt_start_time = date("U", mktime(0, 0, 0,
                    date("m", strtotime($_REQUEST["date"])),
                    date("d", strtotime($_REQUEST["date"])),
                    date("Y", strtotime($_REQUEST["date"]))));
            $appt_end_time = date("U", mktime(23, 59, 59,
                    date("m", strtotime($_REQUEST["date"])),
                    date("d", strtotime($_REQUEST["date"])),
                    date("Y", strtotime($_REQUEST["date"]))));
        }
        else
        {
            $appt_start_time = date("U", mktime(0, 0, 0,
                    date("m", time()),
                    date("d", time()),
                    date("Y", time())));
            $appt_end_time = date("U", mktime(23, 59, 59,
                    date("m", time()),
                    date("d", time()),
                    date("Y", time())));
        }

        // Clean up some variables
        unset($biz_start_time, $biz_end_time);

        // Find any appointments during provided date (or today) and
        $query  = "SELECT a.start_time, a.duration, a.duration, a.state AS appt_state, u.user_id, u.first_name, u.last_name, ";
        $query .= "u.street_address, u.city, u.state, u.zip, u.phone ";
        $query .= "FROM appointments AS a ";
        $query .= "INNER JOIN users AS u ON a.user_id = u.user_id ";
        $query .= "WHERE business_id = $bid ";
        $query .= "AND start_time >= $appt_start_time ";
        $query .= "AND start_time < $appt_end_time ";
        $query .= "ORDER BY start_time";

        // Run query
        $res = getDatabase()->all($query);

        // Build list of open appointments
        $num = 0;
        $appointments = new Appointment_List;
        //while($row = $res->fetch_assoc())
        foreach($res as $row)
        {
            $appointments->appointments[$num] = new AppointmentOwner();
            $appointments->appointments[$num]->time = (int) $row["start_time"];
            $appointments->appointments[$num]->duration = (int) $duration;
            $appointments->appointments[$num]->state = $row["appt_state"];

            $appointments->appointments[$num]->user = new Userinfo();
            $appointments->appointments[$num]->user->id = (int) $row["user_id"];
            $appointments->appointments[$num]->user->firstname = $row["first_name"];
            $appointments->appointments[$num]->user->lastname = $row["last_name"];
            $appointments->appointments[$num]->user->street = $row["street_address"];
            $appointments->appointments[$num]->user->city = $row["city"];
            $appointments->appointments[$num]->user->state = $row["state"];
            $appointments->appointments[$num]->user->zip = $row["zip"];

            $num++;
        }

        // Print JSON version fo appointment list
        $str = json_encode($appointments);
        print($str);
    }
}
?>