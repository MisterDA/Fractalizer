<?php

require_once("Controller.php");

/**
 * Index Controller
 *
 * @package Controller
 */
class IndexController extends Controller {
    public function invoke() {
        $_SESSION["url"] = "/";

        // AJAX
        if ($this->_um->hasLoggedInUser()) {

            // Votes
            if (isset($_POST["action"]) && isset($_POST["fractal"])) {
                $f = $this->_fm->get(new MongoId($_POST["fractal"]));
                if ($f == NULL) exit;

                if ($_POST["action"] == "upvote") {
                    $u = $this->_um->loggedUser();
                    $u->upvote($f);
                    $this->_um->update($u);
                } elseif ($_POST["action"] == "downvote") {
                    $u = $this->_um->loggedUser();
                    $u->downvote($f);
                    $this->_um->update($u);
                }
                echo $f->votes();
                exit;
            }
        }

        // Answer
        $fractals = $this->_fm->hydrate($this->_fm->find()->sort(array("_id" => -1))->limit(10));
        $fm = $this->_fm;
        $um = $this->_um;
        $cm = $this->_cm;
        require_once("view/pages/index.php");
    }
}

