<?php

require_once("Controller.php");

/**
 * Index Controller
 *
 * @package Controller
 */
class IndexController extends Controller {

    /**
     * @var string $_page "": index,  "new": new
     */
    private $_page;

    /**
     * Create a Controller
     * @param MongoDB $db Database object
     * @param array $uri URI (request)
     */
    public function __construct($db, $uri, $page = "") {
        parent::__construct($db, $uri);
        $this->_page = $page;
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
                    if ($u->hasUpvoted($f)) {
                        $u->cancelUpvote($f);
                    } else {
                        $u->upvote($f);
                    }
                    $this->um()->update($u);
                } elseif ($_POST["action"] == "downvote") {
                    $u = $this->um()->loggedUser();
                    if ($u->hasDownvoted($f))
                        $u->cancelDownvote($f);
                    else
                        $u->downvote($f);
                    $this->um()->update($u);
                }
                echo $f->votes();
                exit;
            }
        }
        if (isset($_POST["action"]) && isset($_POST["skip"]) && isset($_POST["sort"])) {
            if ($_POST["sort"] == "votes") {
                $fractals = $this->fm()->hydrate(
                    $this->fm()->find()->sort(array("votes" => -1))->skip($_POST["skip"])->limit(10));
                $fm = $this->fm();
                $um = $this->um();
                $cm = $this->cm();
                foreach ($fractals as $f)
                    require("view/include/fractal.php");
                exit;
            } elseif ($_POST["sort"] == "date") {
                $fractals = $this->fm()->hydrate(
                    $this->fm()->find()->skip($_POST["skip"])->limit(10));
                $fm = $this->fm();
                $um = $this->um();
                $cm = $this->cm();
                foreach ($fractals as $f)
                    require("view/include/fractal.php");
                exit;
            }
        }

        // Answer
        $fractals;
        $title = "";
        if ($this->_page == "") {
            $fractals = $this->fm()->hydrate($this->fm()->find()->sort(array("votes" => -1))->limit(10));
            $title = "Top";
        } elseif ($this->_page == "new") {
            $fractals = $this->fm()->hydrate($this->fm()->find()->limit(10));
            $title = "New";
        }
        $fm = $this->fm();
        $um = $this->um();
        $cm = $this->cm();
        require_once("view/pages/index.php");
    }
}

