<?php

require_once("Entity.php");

/**
 * Manager root class
 *
 * @package Model
 */
abstract class Manager {

    /**
     * Database
     * @var MongoDB $_db
     */
    private $_db;


    /**
     * Create a Manager
     * @param MongoDB $db
     */
    public function __construct(MongoDB $db) {
        $this->_db = $db;
    }

    /**
     * Add an entity to the database
     * @param Entity $e
     */
    //abstract public function add(Entity $e);

    /**
     * Remove an entity from the database
     * @param Entity $e
     */
    //abstract public function remove(Entity $e);

    /**
     * Find entities
     * @param array $query
     * @return MongoCursor
     */
    //abstract public function find(array $query = array());

    /**
     * Find one entity
     * @param array $query
     * @return Entity
     */
    //abstract public function findOne(array $query = array());

    /**
     * Convert a MongoCursor of entities to an array of Entity,
     * indexed by their MongoId
     * @param MongoCursor $cursor
     * @return array
     */
    //abstract public function hydrate(MongoCursor $cursor);

    /**
     * Update an entity
     * @param Entity $e
     */
    //abstract public function update(Entity $e);

    /**
     * Get one Entity by id
     * @param MongoId $id
     * @return Entity
     */
    public function get(MongoId $id) {
        return $this->findOne(array("_id" => $id));
    }

    /**
     * Get database
     * @return MongoDB
     */
    public function db() {
        return $this->_db;
    }
}

