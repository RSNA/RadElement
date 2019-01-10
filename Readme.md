Readme.md

docker-compose up

Create Database
===============
`docker exec -it CONTAINER mysql -u root -p`

Opens a mysql shell on the container

Load Database
=============
`docker exec -i CONTAINER mysql -u root --password=radelement radelement < ./2018-09-10-radelement.sql`

