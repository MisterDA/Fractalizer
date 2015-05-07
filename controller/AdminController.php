<?php

require_once("Controller.php");

/**
 * Admin Controller
 *
 * @package Controller
 */
class AdminController extends Controller {
    public function invoke() {
        $_SESSION["url"] = "/admin";

        $users = $this->_um->hydrate($this->_um->find());
        $fractals = $this->_fm->hydrate($this->_fm->find());

        require_once("view/pages/admin.php");
    }
}

