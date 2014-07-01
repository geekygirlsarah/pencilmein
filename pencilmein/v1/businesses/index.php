<?php
/**
 * Business class to store representation of a business 
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */

require_once("class-address.php");
require_once("class-business.php");
//require_once("class-DBConnection.php");

class Businesses
{
    public $businesses;

}


$server = "localhost";
$username = "pencilmein";
$password = "abc123";
$database = "pencilmein";
$mysqli = new mysqli($server, $username, $password, $database);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

//$m = new DBConnection;
//$conn = $m->getConn();

$query = "SELECT * FROM businesses ORDER BY name LIMIT 0, 20;";

$res = $mysqli->query($query);

//$mysqli->real_query($query);

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

print_r (json_encode($bs));
    //print "\n<br /><br />\n";
    //$j = json_encode($b);



$res->close();


?>