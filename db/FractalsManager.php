<?php

require_once("Fractal.php");

/**
 * Manage Fractals
 * @package Database
 */
class FractalsManager {

    /**
     * Database
     * @var MongoDB $_db
     */
    private $_db;


    /**
     * Create a FractalsManager
     * @param MongoDB $db
     */
    public function __construct(MongoDB $db) {
        $this->_db = $db;
    }

    /**
     * Add a fractal to the database
     * @param Fractal $fractal
     */
    public function add(Fractal $fractal) {
        $this->_db->fractals->insert($fractal->dehydrate());
    }

    /**
     * Remove a fractal from the database
     *
     * The function will cancel every vote that was applied to it,
     * and remove it's comments.
     *
     * @param Fractal $fractal
     */
    public function remove(Fractal $fractal) {
        $um = new UsersManager($this->$_db);

        foreach ($fractal->upvoters($um) as $user) {
            $user->cancelUpvote($fractal);
            $um->update($user);
        }

        foreach ($fractal->downvoters($um) as $user) {
            $user->cancelDownvote($fractal);
            $um->update($user);
        }

        $cm = new CommentsManager($this->_db);
        foreach ($fractal->comments() as $comment)
            $cm->remove($comment);

        $this->db->fractals->remove(array("_id" => $fractal->id()));
    }

    /**
     * Get one fractal by id
     * @param MongoId $id
     * @return Fractal
     */
    public function get(MongoId $id) {
        return $this->findOne(array("_id" => $id));
    }

    /**
     * Find fractals. You can then hydrate the cursor to work on objects.
     * @param array $query
     * @return MongoCursor
     */
    public function find(array $query = array()) {
        return $this->_db->fractals->find($query);
    }

    /**
     * Find one fractal
     * @param array $query
     * @return User
     */
    public function findOne(array $query = array()) {
        return $this->_db->fractals->findOne($query);
    }

    /**
     * Convert a MongoCursor of fractals to an array of Fractals,
     * indexed by their MongoID
     * @param MongoCursor $cursor
     * @return array
     */
    public function hydrate(MongoCursor $cursor) {
        $a = array();
        foreach ($cursor as $id => $u)
            $a[$id] = new Fractal($u);
        return $a;
    }

    /**
     * Update a fractal
     * @param Fractal $fractal
     */
    public function update(Fractal $fractal) {
        $this->_db->fractals->update(array(
            "_id" => $fractal->id()), array("$set", $fractal->dehydrate()));
    }
}

