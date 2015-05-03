<?php

require_once("Comment.php");

/**
 * Manage Comments
 * @package Database
 */
class CommentsManager {

    /**
     * Database
     * @var MongoDB $_db
     */
    private $_db;

    /**
     * Create a CommentsManager
     * @param MongoDB $db
     */
    public function __construct(MongoDB $db) {
        $this->_db = $db;
    }

    /**
     * Add a comment to the database
     * @param Comment $comment
     */
    public function add(Comment $comment) {
        $this->_db->comments->insert($comment->dehydrate());
    }

    /**
     * Remove a comment from the database
     * @param Comment $comment
     */
    public function remove(Comment $comment) {
        $this->db->comments->remove(array("_id" => $fractal->id()));
    }

    /**
     * Get one comment by id
     * @param MongoId $id
     * @return Comment
     */
    public function get(MongoId $id) {
        return $this->findOne(array("_id" => $id));
    }

    /**
     * Find comments. You can the hydrate the cursor to work on objects.
     * @param array $query
     * @return MongoCursor
     */
    public function find(array $query = array()) {
        return $this->_db->comments->find($query);
    }

    /**
     * Find one comment
     * @param array $query
     * @return Comment
     */
    public function findOne(array $query = array()) {
        $doc = $this->_db->comments->findOne($query);
        return ($doc != NULL) ? new Comment($doc) : NULL;
    }

    /** Convert a MongoCursor of comments to an array of Comments,
     * indexed by their MongoID
     * @param MongoCursor $cursor
     * @return array
     */
    public function hydrate(MongoCursor $cursor) {
        $a = array();
        foreach ($cursor as $id => $u)
            $a[$id] = new Comment($u);
        return $a;
    }

    /**
     * Update a comment
     * @param Comment $comment
     */
    public function update(Comment $comment) {
        $this->_db->comments->update(array("_id" => $comment->id()), array("$set", $comment->dehydrate()));
    }
}

