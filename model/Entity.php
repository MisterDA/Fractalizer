<?php

/**
 * Entity root class
 *
 * @package Model
 */
abstract class Entity {

    /**
     * Stores the id
     * @var MongoId $_id
     */
    private $_id;


    /**
     * Hydrate an User from MongoDB document
     * @param array $document Document to create the user from
     */
    public function hydrate(array $document) {
        foreach ($document as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Create MongoDB document from User
     * @return array MogoDB document
     */
    public function dehydrate() {
        if ($this->_id != NULL)
            return array("_id" => $this->_id);
        return array();
    }

    /**
     * Get id
     * @return MongoId
     */
    public function id() {
        return $this->_id;
    }

    /**
     * Set id
     * @param MongoId $id
     */
    private function set_id(MongoId $id) {
        $this->_id = $id;
    }
}

