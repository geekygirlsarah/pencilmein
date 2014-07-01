<?php
/**
 * User class to store info about the user 
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */

require_once("json-business.php");
 
class Userinfo
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $street;
    public $city;
    public $state;
    public $zip;
    public $phone;
}
?>
