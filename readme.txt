This exercise was done using the following working environment:

- Ubuntu 14.04.3 LTS
- Postgis 2.1.2 
- PostgreSQL 9.3.9 
- Apache/2.4.7
- PHP 5.5.9
- Qgis 2.8.1

File explanation:

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

[cars.sql]
Database dump.
