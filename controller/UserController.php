<?php

require_once("Controller.php");

/**
 * User Controller
 *
 * @package Controller
 */
class UserController extends Controller {
    public function invoke() {
        $_SESSION["url"] = "/user";

        if (!isset($_GET["id"])) {
            header("Location: /");
            exit;
        }

        $_SESSION["url"] = "/user?id={$_GET["id"]}";

        $u = $this->_um->get(new MongoId($_GET["id"]));

        // Invalid id
        if ($u == NULL) {
            header("Location: /");
            exit;
        }

        // Answer
        $authored = $u->authoredFractals($this->_fm);
        $upvoted  = $u->upvotedFractals($this->_fm);
        $commented = $u->commentedFractals($this->_fm, $this->_cm);
        $fm = $this->_fm;
        $um = $this->_um;
        $cm = $this->_cm;
        require_once("view/pages/user.php");
     }
}

