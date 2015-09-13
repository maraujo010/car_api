
DEV ENVIRONMENT:

Backend:

-Ubuntu 14.04 LTS
-PostgreSQL 9.3.9
-Postgis 2.1.2
-Apache 2.4.7 
-php 5.5.9


Frontend (test):

-OpenLayers3
-OSM
-jquery
-ajax
-javacritpt
-html
-firefox






FILE DESCRIPTION

[importer.php] 
Command line script to import all data from file data.json to the database.

[.htaccess]
Directory level configuration. Routes all API requests to the controller.

[index.php]
Controller where api object is created.

[Restful.class.php]
Abstract class to process restful communications.

[CarsApi.class.php]
Api class that performs the work for the endpoint.

[js/script.js]
This is the javascript file where all the frontend operations are done. 
Initializing map, api service call with ajax, selecting a location on mouse clicking, 
drawing and removing points as layers.

[cars.sql]
"cars" database dump.







API EXPLANATION 

cars endpoint:

GET: /api/cars?location=51.5444204,-0.22707
- Returns the nearest ten cars from the giving location

GET: /api/cars/all
- Returns all cars from database






FRONTEND (testing)

Browsing the url /test.php will show a OSM map with all the cars stored in the database. 
They are represented as small blue circles. Clicking on the map will select a location. 
Then a red circle is drawed at this location and all the nearest cars are selected with a green color.





SETTING UP THE API AND FRONTEND

- Setting up the database:

First i created the database "cars" and used the script "importer.php" to import all the data from the file "data.json". 
Now there is a database dump in the file "cars.sql". 

All you need to do is to create the database "cars" in your posgreSQL database server 
and import the dump file with command: "psql cars < cars.sql"



- Setting up the api and test web server app config:
Copy all the files to the configured web folder of your app. 
*Do not forget .htaccess file which is routing all API requests.

As an example here is my apache configuration:

Alias /api "/path_to_app_web_folder"
<Directory /path_to_app_web_folder>
	Options Indexes FollowSymLinks
	AllowOverride All
	Order allow,deny
	Require all granted
	Allow from all
</Directory>

Alias /testapi "/path_to_app_web_folder"
<Directory /path_to_app_web_folder>
	Options Indexes FollowSymLinks
	AllowOverride All
	Order allow,deny
	Require all granted
        Allow from all
</Directory>


- According to this config:

Endpoint url: http://localhost/api/cars
Test url: http://localhost/testapi/test.html





* There is a "screenshot.jpg" file where you can see an example of the map 
with a selected location (red circle) and nearest cars (green circles). 
It also shows a firebug window where you can see all the requests to the api.







