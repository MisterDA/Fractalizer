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
    private $_db;

    /**
     * URI (request)
     * @var array URI
     */
    private $_uri;

    /**
     * Fractals manager
     * @var FractalsManager $_fm
     */
    private $_fm;

    /**
     * Users manager
     * @var UsersManager $_um
     */
    private $_um;

    /**
     * Comments manager
     * @var CommentsManager $_cm
     */
    private $_cm;


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

        $this->_um->autoLogin();
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

