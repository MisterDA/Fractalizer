<?php

require_once("User.php");

/**
 * Manage Users
 * @package Database
 */
class UsersManager {

    /**
     * Database
     * @var MongoDB $_db
     */
    private $_db;


    /**
     * Create an UsersManager
     * @param MongoDB $db
     */
    public function __construct(MongoDB $db) {
        $this->_db = $db;
    }

    /**
     * Add an user to the database
     * @param User $user
     */
    public function add(User $user) {
        $this->_db->users->insert($user->dehydrate());
    }

    /**
     * Remove an user from the database
     *
     * The function will remove every fractal created by the user,
     * as well as it's comments, and will also reset it's votes.
     *
     * @param User $user
     */
    public function remove(User $user) {
        $fm = new FractalsManager($this->_db);
        foreach ($user->authoredFractals($fm) as $fractal)
            $fm->remove($fractal);

        foreach ($user->upvotedFractals($fm) as $fractal) {
            $fractal->downvote();
            $fm->update($fractal);
        }

        foreach ($user->downvotedFractals($fm) as $fractal) {
            $fractal->upvote();
            $fn->update($fractal);
        }

        $cm = new CommentsManager($this->_db);
        foreach ($user->comments($cm) as $comment)
            $cm->remove($comment);

        $this->db->users->remove(array("_id" => $user->id()));
    }

    /**
     * Get one user by id
     * @param MongoId $id
     * @return User
     */
    public function get(MongoId $id) {
        return $this->findOne(array("_id" => $id));
    }

    /**
     * Find users. You can then hydrate the cursor to work on objects.
     * @param array $query
     * @return MongoCursor
     */
    public function find(array $query = array()) {
        return $this->_db->users->find($query);
    }

    /**
     * Find one user
     * @param array $query
     * @return User
     */
    public function findOne(array $query = array()) {
        return new User($this->_db->users->findOne($query));
    }

    /**
     * Convert a MongoCursor of users to an array of Users,
     * indexed by their MongoID
     * @param MongoCursor $cursor
     * @return array
     */
    public function hydrate(MongoCursor $cursor) {
        $a = array();
        foreach ($cursor as $id => $u)
            $a[$id] = new User($u);
        return $a;
    }

    /**
     * Update an user,
     * will also update the fractals the user changed its votes on
     * @param User $user
     */
    public function update(User $user) {
        $fm = new FractalsManager($this->_db);
        foreach ($user->changedVotesFractals() as $f)
            $fm->update($f);
        $this->_db->users->update(array(
            "_id" => $user->id()), array('$set', $user->dehydrate()));
    }


}

