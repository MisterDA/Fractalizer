<?php

require_once("Manager.php");
require_once("Fractal.php");

/**
 * Manage Fractals
 *
 * @package Model
 */
class FractalsManager extends Manager {

    /**
     * Create a FractalsManager
     * @param MongoDB $db
     */
    public function __construct(MongoDB $db) {
        parent::__construct($db);
    }

    /**
     * Add a fractal to the database
     * @param Fractal $fractal
     */
    public function add(Fractal $fractal) {
        $doc = $fractal->dehydrate();
        $this->db()->fractals->insert($doc);
        $fractal->hydrate($doc);
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
        $um = new UsersManager($this->db());

        foreach ($fractal->upvoters($um) as $user) {
            $user->cancelUpvote($fractal);
            $um->update($user);
        }

        foreach ($fractal->downvoters($um) as $user) {
            $user->cancelDownvote($fractal);
            $um->update($user);
        }

        $cm = new CommentsManager($this->db());
        foreach ($fractal->comments($cm) as $comment)
            $cm->remove($comment);

        $this->db()->fractals->remove(array("_id" => $fractal->id()));
    }

    /**
     * Find fractals. You can then hydrate the cursor to work on objects.
     * @param array $query
     * @return MongoCursor
     */
    public function find(array $query = array()) {
        return $this->db()->fractals->find($query);
    }

    /**
     * Find one fractal
     * @param array $query
     * @return Fractal
     */
    public function findOne(array $query = array()) {
        $doc = $this->db()->fractals->findOne($query);
        return ($doc != NULL) ? new Fractal($doc) : NULL;
    }

    /**
     * Convert a MongoCursor of fractals to an array of Fractal,
     * indexed by their MongoId
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
        $this->db()->fractals->update(array(
            "_id" => $fractal->id()), array('$set' => $fractal->dehydrate()));
    }

    /**
     * Post a fractal
     * @param Fractal $fractal
     * @return boolean true if success
     */
    public function post(Fractal $fractal) {
        if ($fractal->title() != NULL && $fractal->author() != NULL && $fractal->formula() != NULL) {
            $this->add($fractal);
            return true;
        }
        return false;
    }
}

