   
<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

 db_query(
        "CREATE TABLE users
        (
        id int NOT NULL AUTO_INCREMENT, 
        PRIMARY KEY(id),
        username varchar(30),
        password varchar(64),
        salt varchar(3),
        apikey_write varchar(64),
        apikey_read varchar(64)
      )"); 

  db_query(
  "CREATE TABLE input
  (
    id int NOT NULL AUTO_INCREMENT, 
    PRIMARY KEY(id),
    userid int,
    name text,
    processList text,
    time DATETIME,
    value float
  )");

  db_query(
  "CREATE TABLE feeds
  (
    id int NOT NULL AUTO_INCREMENT, 
    PRIMARY KEY(id),
    name text,
    tag text,
    time DATETIME,
    value FLOAT,
    status int,
    today FLOAT,
    yesterday FLOAT,
    week FLOAT,
    month FLOAT,
    year FLOAT
  )");

  db_query(
  "CREATE TABLE feed_relation
  (
    userid int,
    feedid int
  )");

  db_query(
  "CREATE TABLE dashboard
  (
    userid int,
    content text
  )");


?>
