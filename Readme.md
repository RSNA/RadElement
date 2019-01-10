Radelement
==========
Source code repository for the radelement web page + database

Local Development
=================
We include a basic docker-compose setup for local development which can be started with:

`docker-compose up`

Create Database
===============
`docker exec -it CONTAINER mysql -u root -p`

Opens a mysql shell on the container

Load Database
=============
`docker exec -i CONTAINER mysql -u root --password=radelement radelement < ./sqlfile.sql`

Edit open_db.php
================
The db configuration file for the php scripts will need to be edited for your purposes.