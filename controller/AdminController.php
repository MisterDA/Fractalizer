<?php

require_once("Controller.php");

/**
 * Admin Controller
 *
 * @package Controller
 */
class AdminController extends Controller {

    /**
     * Create a Controller
     * @param MongoDB $db Database object
     * @param array $uri URI (request)
     */
    public function __construct($db, $uri) {
        parent::__construct($db, $uri);
    }

    /**
     * Invoke the Controller
     */
    public function invoke() {
        $_SESSION["url"] = "/admin";

        $users = $this->um()->hydrate($this->um()->find());
        $fractals = $this->fm()->hydrate($this->fm()->find());

        require_once("view/pages/admin.php");
    }
}

