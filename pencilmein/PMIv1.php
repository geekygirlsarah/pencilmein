<?php
/**
 * Code for /business/ REST query
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */

// JSON objects
require_once("class-address.php");
require_once("class-appointment.php");
require_once("class-appointment_list.php");
require_once("class-business.php");
require_once("class-businesses.php");

// Code objects
require_once("class-MySQL.php");

class PMIv1 {
    // DB connectivity
    //var $database = "pencilmein";//$database;
    //var $username = "pencilmein";//$username;
    //var $password = "abc123";//$password;
    //var $hostname = "localhost";//$hostname;

    var $db;
    
    // /
    static public function root()
    {
        print "Missing command.";
    }
    
    // /businesses/
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
            
            $num++;    
        }
        
        $bs = new Businesses();
        $bs->businesses = $json;
        
        $str = json_encode($bs);
        
        print($str);
        $res->close();
    }
    
    // /businesses/:id/
    static public function getBusiness($id)
    {
        $db = new mysqli("localhost", "pencilmein", "abc123", "pencilmein");

        $query = "SELECT * FROM businesses WHERE id = '$id' ORDER BY name";
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
            
            $num++;    
        }
        
        $bs = new Businesses();
        $bs->businesses = $json;
        
        $str = json_encode($bs);
        
        print($str);
        $res->close();
    }
    
    // /businesses/:id/appointments/
    static public function getBusinessAppointments($id)
    {
        $db = new mysqli("localhost", "pencilmein", "abc123", "pencilmein");

        $query = "SELECT appt_duration,start_time,end_time FROM business_settings WHERE business_id = '$id'";
        $res = $db->query($query);
        
        $row = $res->fetch_assoc();
        $duration = $row["appt_duration"];
        $start_time = strtotime($row["start_time"]);
        $end_time = strtotime($row["end_time"]);
        $res->close();
        
        $appointments = new Appointment_List;       
        for($i = 0; $i < 5; $i++)
        {
            $start_time = mktime(date("h", $start_time),
                                 date("i", $start_time),
                                 date("s", $start_time),
                                 date("n", time()) + $i,
                                 date("j", time()) + $i,
                                 date("Y", time()) + $i);
            $end_time   = mktime(date("h", $end_time),
                                 date("i", $end_time),
                                 date("s", $end_time),
                                 date("n", time()) + $i,
                                 date("j", time()) + $i,
                                 date("Y", time()) + $i);
            $appointment = new Appointment;
            $appointment->time = $start_time;
            $appointment->duration = $duration;
            $appointment->state = "open";
            $appointments->appointments[$i] = $appointment;
        }
        
        $str = json_encode($appointments);
        
        print($str);
    }

    static public function createBusinessAppointments($bid, $atime)
    {
        $start_time = mktime(date("h", $atime),
                             date("i", $atime),
                             date("s", $atime),
                             date("n", time()),
                             date("j", time()),
                             date("Y", time()));
        
        print "Will provide code to create an appointment for business #"
            . $bid . " on " . date("n/j/Y", $atime) . " at " . date("h:i:s",$start_time)
            . " soon.";
        
        
    }
}
?>