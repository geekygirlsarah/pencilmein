PencilMeIn Server Component
Sarah Withee
sarahwithee@umkc.edu
12/7/2013

---------------------------------------
Live Server Information
---------------------------------------

The server is currently running at http://www.sarahwithee.com/pencilmein/. 
I plan to keep it running until about January when I will take the PencilMeIn
site down. Therefore if you don't have to install a server of your own if you 
don't want. You can skip to the testing section using 
http://www.sarahwithee.com/pencilmein/test.html to test with instead of 
localhost.

Follow the directions below if you wish to install this yourself.


---------------------------------------
Setting up Eclipse
---------------------------------------

I tried a variety of IDEs to work with PHP to try to speed up development. I 
did settle on Eclipse. You do need to install the PHP Development Tools (PDT)
so you can debug code. (I haven't tried it, but I'm sure Eclipse would open 
it anyway.)

However, the program doesn't run IN Eclipse, it runs within Apache as a 
server component. With Apache running in the background, the PencilMeIn
component program runs whenever the site is visited and quits afterward.


---------------------------------------
Installing the Server
---------------------------------------
My project is based on a AMP (Apache, MySQL, PHP) server. In Windows, you can 
download a simple, pre-setup version of XAMPP at 
http://www.apachefriends.org/en/xampp.html. I used XAMPP for testing, and a 
Linux server with Apache, MySQL, and PHP installed individually for 
production. My directions are based on XAMPP.

1) Install XAMPP.
2) In your browser, go to http://localhost/
   If that doesn't work:
2a) In the XAMPP install directory, run xampp-control.exe or find the "XAMPP
    Control Panel" program in your Start menu.
2b) Make sure Apache and MySQL are both not running. If they are, click the 
    stop button next to them.
2c) Click both of the X icons next to them to set them as background services.
2d) Click on the Start buttons next to both. The others do not need to be 
    started.
3) Click on English, then click on phpMyAdmin.
4) Click on Import, then click on Browse to find the pencilmein_structure.sql 
   file, then click on Go at the bottom. It should import the database 
   structure into the database.
5) Click on Import again, then click on Browse to find the
   pencilmein_sampledata.sql and click on Go. This will import some sample 
   businesses and appointments that was used by the class.
6) In XAMPP's install directory, navigate to htdocs, then make a new 
   directory called pencilmein. Copy all of the files to that directory.
7) Go to http://localhost/pencilmein/test.html in your browser and you should 
   get a test site.
8) Click on businesses in the top left frame, then click on the first button 
   in the bottom left frame. It should return a list of all of the businesses
   in the database.


---------------------------------------
Testing the Server
---------------------------------------

1) Go to http://localhost/pencilmein/test.html
2) Choose an API section to test in the top frame.
3) The individual calls are listed below. Click a button to get the server's 
   result in the frame to the right.


---------------------------------------
Running Unit Tests
---------------------------------------

If you visit http://localhost/pencilmeintest/ it should run and pass all 
5 test cases (8 assertions).

---------------------------------------
Removing the Server
---------------------------------------

You may wish to not keep XAMPP after grading. To stop it and uninstall it:
1) In the XAMPP install directory, run xampp-control.exe or find the "XAMPP
   Control Panel" program in your Start menu.
2) Click Stop next to all services.
3) Click on the green check marks next to any installed services to uninstall 
   them as background services.
4) Close XAMPP Control Panel.
5) In the Start menu click the Uninstall application in the XAMPP group. This 
   should remove the XAMPP components except any files you added.
6) Delete the install directory to remove any additional files.

