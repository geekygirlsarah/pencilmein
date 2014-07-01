<?php
/**
 * Business class to store representation of a business 
 *
 * @package    PencilMeIn
 * @author     Sarah Withee <sarahwithee@mail.umkc.edu>
 * @since      File available since Release 1.0
 */

require_once("class-business.php");
 
class Business
{
    public $id;
    public $name;
    public $address;
    
    public function toJSON()
    {
        return json_encode($this);    
    }
}
?>
