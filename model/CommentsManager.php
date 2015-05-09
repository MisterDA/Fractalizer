<?php

require_once("Manager.php");
require_once("Comment.php");

/**
 * Manage Comments
 *
 * @package Model
 */
class CommentsManager extends Manager {

    /**
     * Create a CommentsManager
     * @param MongoDB $db
     */
    public function __construct(MongoDB $db) {
        parent::__construct($db);
    }

    /**
     * Add a comment to the database
     * @param Comment $comment
     */
    public function add(Comment $comment) {
        $doc = $comment->dehydrate();
        $this->db()->comments->insert($doc);
        $comment->hydrate($doc);
    }

    /**
     * Remove a comment from the database
     * @param Comment $comment
     */
    public function remove(Comment $comment) {
        $this->db()->comments->remove(array("_id" => $comment->id()));
    }

    /**
     * Find comments. You can the hydrate the cursor to work on objects.
     * @param array $query
     * @return MongoCursor
     */
    public function find(array $query = array()) {
        return $this->db()->comments->find($query);
    }

    /**
     * Find one comment
     * @param array $query
     * @return Comment
     */
    public function findOne(array $query = array()) {
        $doc = $this->db()->comments->findOne($query);
        return ($doc != NULL) ? new Comment($doc) : NULL;
    }

    /** Convert a MongoCursor of comments to an array of Comment,
     * indexed by their MongoId
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
        $this->db()->comments->update(array("_id" => $comment->id()), array('$set' => $comment->dehydrate()));
    }

    /**
     * Post a comment
     * @param Comment $comment
     * @return boolean true if success
     */
    public function post(Comment $comment) {
        if ($comment->text() != NULL) {
            $this->add($comment);
            return true;
        }
        return false;
    }
}

