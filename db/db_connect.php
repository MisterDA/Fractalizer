<?php
require_once("db_info.php");

$url = 'mongodb://'.DBUSER.':'.DBPASSWD.'@'.DBSERVER.'/'.DBNAME;
$m = new MongoClient($url);
$db = $m->{DBUSER};

