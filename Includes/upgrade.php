   
<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

 // 8th of March upgrade
 // db_query("ALTER TABLE  `users` ADD  `lastlogin` DATETIME");
 // db_query("ALTER TABLE  `users` ADD  `admin` INT NOT NULL");
  
    require "./db.php";
  	db_connect();
	
	// upgrade dashboard table with primary key, foreign key and name and description fields
  	db_query("ALTER TABLE dashboard ADD COLUMN id int(11) NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id,userid)");
	db_query("ALTER TABLE dashboard ADD FOREIGN KEY (userid) REFERENCES users(id)");
	db_query("ALTER TABLE dashboard ADD `name` VARCHAR(30) DEFAULT 'no name'");
	db_query("ALTER TABLE dashboard ADD `description` VARCHAR(255) DEFAULT 'no description'");
?>
