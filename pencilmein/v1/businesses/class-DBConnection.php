<?php
/**
 * Database connectivity class to create a persistent object to a MySQL 
 * database.  
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */
 
class DBConnection
{
    private $server = "localhost";
    private $username = "pencilmein";
    private $password = "abc123";
    private $database = "pencilmein";

    private $mysqli;     
    //private $connection; 
     
    function connect()
    {
        $mysqli = new mysqli($server, $username, $password, $database);
        
        if($mysqli->connect_error)
        {
            die('Connect Error (' . $mysqli->connect_errno . ') ' 
            . $mysqli->connect_error);        
        }                    
    }    

    function getConn()
    {
        return $mysqli;  
    }

} 

?>