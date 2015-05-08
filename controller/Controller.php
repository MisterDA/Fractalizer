<?php

require_once("model/FractalsManager.php");

session_start();

/**
 * Controller root class
 *
 * @package Controller
 */
abstract class Controller {
    /**
     * Database object
     * @var MongoDB $_db;
     */
    protected $_db;

    /**
     * URI (request)
     * @var array URI
     */
    protected $_uri;

    /**
     * Fractals manager
     * @var FractalsManager $_fm
     */
    protected $_fm;

    /**
     * Users manager
     * @var UsersManager $_um
     */
    protected $_um;

    /**
     * Comments manager
     * @var CommentsManager $_cm
     */
    protected $_cm;


    /**
     * Create a Controller
     * @param MongoDB $db Database object
     * @param array $uri URI (request)
     */
    public function __construct($db, $uri) {
        $this->_db  = $db;
        $this->_uri = $uri;

        $this->_fm = new FractalsManager($db);
        $this->_um = new UsersManager($db);
        $this->_cm = new CommentsManager($db);
    }

    /**
     * Invoke the Controller
     */
    abstract public function invoke();

    /**
     * Get a fractal manager
     * @return FractalsManager
     */
    public function fm() {
        return $this->_fm;
    }

    /**
     * Get an user manager
     * @return UsersManager
     */
    public function um() {
        return $this->_um;
    }

    /**
     * Get a comment manager
     * @return CommentsManager
     */
    public function cm() {
        return $this->_cm;
    }
}

