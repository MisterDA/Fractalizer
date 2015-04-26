<?php

/**
 * Connect to the database
 */

require_once("db_info.php");

$url = 'mongodb://'.DBUSER.':'.DBPASSWD.'@'.DBSERVER.'/'.DBNAME;
$m = new MongoClient($url);

/**
 * @var MongoDB The database object
 */
$db = $m->{DBUSER};

