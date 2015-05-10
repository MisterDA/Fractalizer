<?php

/**
 * Populate the database
 */

require_once("db/db_connect.php");

$users = $db->users;
$fractals = $db->fractals;
$comments = $db->comments;

srand((double)microtime()*1000000);


/**
 * Generate a random string
 * @param int $n String length
 * @return string Random string
 */
function randstr($n) {
    $s = "";
    $alphabet = "azertyuiopqsdfghjklmwxcvbn0123456789-_ ";
    $l = strlen($alphabet);
    for ($i = 0; $i < $n; $i++)
        $s .= $alphabet[rand()%$l];
    return $s;
}

/**
 * Populate the database for testing purposes
 * @todo Set up votes and down votes
 * @param MongoCollection $users Users collection
 * @param MongoCollection $fractals Fractals collection
 * @param MongoCollection $comments Comments collection
 */
function populateWithDocuments($users, $fractals, $comments) {
    for ($i = 0, $n = 20; $i < $n; $i++) {
        $pwd = randstr(9);
        $users->insert(array(
            "name"  => randstr(7),
            "email"     => randstr(7)."@".randstr(5),
            "password"  => password_hash($pwd, PASSWORD_DEFAULT),
            //"password"  => crypt($pwd, '$2a$07'.$pwd.'$'),
            "upvoted"   => array(),
            "downvoted" => array()));
    }

    for ($j = 0, $n = 50; $j < $n; $j++) {
        $f = array(
            "title"     => randstr(15),
            "author"  => "",
            "votes"    => 0,
            "date"     => time(),
            "formula"  => '{"axiom":"F","rules":{"F":"F+F-F-F+F"},"angle":90}');

        $user = $users->find()->limit(-1)->skip(rand(0, $users->count() - 1))->getNext();
        $f["author"] = $user["_id"];
        $fractals->insert($f);
    }


    for ($k = 0, $n = 50; $k < $n; $k++) {
        $c = array(
            "text"    => randstr(140),
            "date"    => time(),
            "author"  => "",
            "fractal" => "");

        $user_cursor = $users->find();
        $user = $user_cursor->limit(-1)->skip(rand(0, $users->count() - 1))->getNext();
        $c["author"] = $user["_id"];

        $frac_cursor = $fractals->find();
        $frac = $frac_cursor->limit(-1)->skip(rand(0, $fractals->count() - 1))->getNext();
        $c["fractal"] = $frac["_id"];
        $comments->insert($c);
    }
}

populateWithDocuments($users, $fractals, $comments);

