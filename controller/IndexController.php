<?php

require_once("Controller.php");

/**
 * Index Controller
 *
 * @package Controller
 */
class IndexController extends Controller {

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
        $_SESSION["url"] = "/";

        // AJAX
        if ($this->um()->hasLoggedInUser()) {

            // Votes
            if (isset($_POST["action"]) && isset($_POST["fractal"])) {
                $f = $this->fm()->get(new MongoId($_POST["fractal"]));
                if ($f == NULL) exit;

                if ($_POST["action"] == "upvote") {
                    $u = $this->um()->loggedUser();
                    $u->upvote($f);
                    $this->um()->update($u);
                } elseif ($_POST["action"] == "downvote") {
                    $u = $this->um()->loggedUser();
                    $u->downvote($f);
                    $this->um()->update($u);
                }
                echo $f->votes();
                exit;
            }
        }

        // Answer
        $fractals = $this->fm()->hydrate($this->fm()->find()->sort(array("_id" => -1))->limit(10));
        $fm = $this->fm();
        $um = $this->um();
        $cm = $this->cm();
        require_once("view/pages/index.php");
    }
}

