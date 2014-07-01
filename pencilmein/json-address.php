<?php
/**
 * Address class to store representation of an address 
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */
 
class Address
{
    public $street;
    public $city;
    public $state;
    public $zip;
    
    public function toJSON()
    {
        return json_encode($this);    
    }
}
?>
