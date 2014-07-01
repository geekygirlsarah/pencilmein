<?php

// Include SimpleTest unit testing framework
require_once("simpletest/autorun.php");
require_once("../pencilmein/PMIfunctions.php");

class TestCreateProperJSONStatusObject extends UnitTestCase {
    function testCreatesProperJSONStatusObject() {
        // What it should look like:
        $expected= '{"status":{"message":"Test status message","error":false}}';

        $actual = generateStatusJson("Test status message");
        $this->assertEqual($expected, $actual);
    }
}

class TestCreateProperJSONErrorObject extends UnitTestCase {
    function testCreatesProperJSONErrorObject() {
        // What it should look like:
        $expected= '{"status":{"message":"Test error message","error":true}}';

        $actual = generateErrorJson("Test error message");

        $this->assertEqual($expected, $actual);
    }
}

class TestConversionOfTimestampToYMDFormat extends UnitTestCase {
    function testConvertTimestampToYmd()
    {
        // Dec 1, 2013 8:20:30 am
        $expected1 = array(2013, 12, 1);;
        $actual1 = convertTimestampToYmd(1385882430);

        $this->assertEqual($expected1, $actual1);

        // Jan 1, 2000 3:50:45 pm
        $expected2 = array(2000, 1, 1);
        $actual2 = convertTimestampToYmd(946738245);
        $this->assertEqual($expected2, $actual2);
    }
}

class TestConversionOfTimestampToYMDHMSFormat extends UnitTestCase {
    function testConvertTimestampToYmdhms()
    {
        // Dec 1, 2013 8:20:30 am
        $expected1 = array(2013, 12, 1, 8, 20, 30);
        $actual1 = convertTimestampToYmdhms(1385882430);

        $this->assertEqual($expected1, $actual1);

        // Jan 1, 2000 3:50:45 pm
        $expected2 = array(2000, 1, 1, 3, 50, 45);
        $actual2 = convertTimestampToYmdhms(946738245);
        $this->assertEqual($expected2, $actual2);
    }
}

class TestConversionOfStringsToTimestamp extends UnitTestCase {
    function testConvertStringDateTimeToTimestamp()
    {
        // Dec 1, 2013 8:20:30 am
        $expected1 = 1385882430;
        $actual1 = convertStringDateTimeToTimestamp("2013-12-01", "08:20:30");

        $this->assertEqual($expected1, $actual1);

        // Jan 1, 2000 3:50:45 pm
        $expected2 = 946738245;
        $actual2 = convertStringDateTimeToTimestamp("2000-01-01", "15:50:45");
        $this->assertEqual($expected2, $actual2);
    }

}

